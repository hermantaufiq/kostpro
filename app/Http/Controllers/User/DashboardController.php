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

        // Cari penyewaan yang sedang aktif atau disetujui
        $activePenyewaan = Penyewaan::with('kamar')
            ->where('user_id', $userId)
            ->whereIn('status', [StatusPenyewaan::Active, StatusPenyewaan::Approved, StatusPenyewaan::Pending])
            ->first();

        $sisaHariSewa = null;
        if ($activePenyewaan && $activePenyewaan->tanggal_keluar) {
            // diffInDays with false returns negative if date has passed
            $sisaHariSewa = ceil(now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($activePenyewaan->tanggal_keluar)->startOfDay(), false));
        }

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
            'activePenyewaan',
            'sisaHariSewa',
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
                ->perpanjangKontrak($id, (int) $request->durasi_bulan, Auth::id());
                
            return redirect()->back()->with('success', "Berhasil memperpanjang kontrak selama {$request->durasi_bulan} bulan. Tagihan baru telah dibuat.");
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
