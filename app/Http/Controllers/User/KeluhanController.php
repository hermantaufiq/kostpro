<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Kamar;
use App\Models\Penyewaan;
use App\Enums\StatusKeluhan;
use App\Enums\PrioritasKeluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhans = Keluhan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.keluhan.index', compact('keluhans'));
    }

    public function create()
    {
        // Cari kamar aktif milik user
        $penyewaan = Penyewaan::with('kamar')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        return view('user.keluhan.create', compact('penyewaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $foto_url = null;
        if ($request->hasFile('foto')) {
            $foto_url = $request->file('foto')->store('keluhan-foto', 'public');
        }

        // Cari kamar aktif
        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        Keluhan::create([
            'user_id'   => Auth::id(),
            'kamar_id'  => $penyewaan?->kamar_id,
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'prioritas' => $validated['prioritas'],
            'foto_url'  => $foto_url,
            'status'    => StatusKeluhan::Menunggu,
        ]);

        return redirect()->route('user.keluhan.index')
            ->with('success', 'Keluhan Anda berhasil dikirim. Tim kami akan segera merespons.');
    }

    public function show(Keluhan $keluhan)
    {
        if ($keluhan->user_id !== Auth::id()) {
            abort(403);
        }

        $keluhan->load('komentars.user');

        return view('user.keluhan.show', compact('keluhan'));
    }

    public function addKomentar(Request $request, Keluhan $keluhan)
    {
        if ($keluhan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['isi' => 'required|string|max:1000']);

        \App\Models\KeluhanKomentar::create([
            'keluhan_id' => $keluhan->id,
            'user_id'    => Auth::id(),
            'isi'        => $request->isi,
            'is_admin'   => false,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
