<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Enums\StatusPenyewaan;
use App\Enums\StatusTagihan;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Cari semua penyewaan yang sedang aktif atau disetujui (dukung multi-kamar)
        $activePenyewaans = Penyewaan::with('kamar')
            ->where('user_id', $userId)
            ->whereIn('status', [StatusPenyewaan::Active, StatusPenyewaan::Approved, StatusPenyewaan::Pending])
            ->orderBy('created_at', 'desc')
            ->get();


        // Cari semua tagihan yang belum lunas
        $tagihanAktif = Tagihan::with('penyewaan.kamar')
            ->where('user_id', $userId)
            ->whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Ambil notifikasi terbaru
        $notifikasi = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        $unreadNotifCount = $notifikasi->whereNull('read_at')->count();

        // Hitung keluhan aktif
        $keluhanAktif = Keluhan::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->count();

        // Ambil pengumuman terbaru
        $pengumuman = \App\Models\Pengumuman::orderBy('created_at', 'desc')->take(3)->get();

        return view('user.dashboard', compact(
            'activePenyewaans',
            'tagihanAktif',
            'unreadNotifCount',

            'notifikasi',
            'keluhanAktif',
            'pengumuman'
        ));
    }

    public function perpanjangSewa(Request $request, $id)
    {
        $request->validate([
            'durasi_bulan' => 'required|integer|in:1,3,6,12',
        ]);

        try {
            app(\App\Contracts\Services\PenyewaanServiceInterface::class)
                ->terbitkanTagihanBerikutnya($id, (int) $request->durasi_bulan);
                
            return redirect()->route('user.tagihan.index')
                ->with('success', "Berhasil menerbitkan tagihan perpanjangan. Silakan lakukan pembayaran agar masa sewa Anda bertambah.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function readAllNotifikasi()
    {
        Notifikasi::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
