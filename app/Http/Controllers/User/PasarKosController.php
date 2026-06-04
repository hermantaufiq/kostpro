<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PasarKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasarKosController extends Controller
{
    public function index()
    {
        $items = PasarKos::with('user')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        $myItems = PasarKos::where('user_id', Auth::id())->latest()->get();

        return view('user.pasar-kos.index', compact('items', 'myItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'harga'       => 'required|numeric|min:0',
            'kontak'      => 'required|string|max:20',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $foto_url = null;
        if ($request->hasFile('foto')) {
            $foto_url = $request->file('foto')->store('pasar-kos', 'public');
        }

        PasarKos::create([
            'user_id'     => Auth::id(),
            'nama_barang' => $validated['nama_barang'],
            'deskripsi'   => $validated['deskripsi'],
            'harga'       => $validated['harga'],
            'kontak'      => $validated['kontak'],
            'foto_url'    => $foto_url,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan ke Pasar Kos!');
    }

    public function destroy(PasarKos $pasarKo)
    {
        if ($pasarKo->user_id !== Auth::id()) {
            abort(403);
        }

        $pasarKo->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }
}
