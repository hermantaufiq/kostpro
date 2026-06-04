<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LayananTambahan;
use App\Models\RequestLayanan;
use Illuminate\Support\Facades\Auth;

class LayananTambahanController extends Controller
{
    public function index()
    {
        // Hanya tampilkan jika user punya penyewaan aktif
        $user = Auth::user();
        $penyewaanAktif = $user->penyewaan()->whereIn('status', ['active', 'approved'])->first();
        
        $layananTersedia = LayananTambahan::where('is_active', true)->get();
        $riwayatRequest = RequestLayanan::where('user_id', $user->id)
                            ->with('layananTambahan')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('user.layanan.index', compact('layananTersedia', 'riwayatRequest', 'penyewaanAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_tambahan_id' => 'required|exists:layanan_tambahans,id',
            'catatan_user' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $penyewaanAktif = $user->penyewaan()->whereIn('status', ['active', 'approved'])->first();

        if (!$penyewaanAktif) {
            return redirect()->back()->with('error', 'Anda harus memiliki penyewaan aktif untuk memesan layanan tambahan.');
        }

        // Cek apakah sudah ada request serupa yang masih menunggu
        $existing = RequestLayanan::where('user_id', $user->id)
                        ->where('layanan_tambahan_id', $request->layanan_tambahan_id)
                        ->where('penyewaan_id', $penyewaanAktif->id)
                        ->where('status', 'menunggu')
                        ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan permintaan untuk layanan ini yang masih berstatus menunggu konfirmasi.');
        }

        RequestLayanan::create([
            'user_id' => $user->id,
            'layanan_tambahan_id' => $request->layanan_tambahan_id,
            'penyewaan_id' => $penyewaanAktif->id,
            'catatan_user' => $request->catatan_user,
            'status' => 'menunggu'
        ]);

        return redirect()->route('user.layanan.index')->with('success', 'Permintaan layanan tambahan berhasil dikirim. Admin akan segera memproses tagihan Anda.');
    }
}
