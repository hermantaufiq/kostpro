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

        $voucherAktif = app(\App\Services\Voucher\VoucherService::class)
            ->getActiveVouchersForUser($userId);

        // Siapkan data Kalender & Jadwal Pembayaran
        $paymentSchedules = collect();
        
        // 1. Masukkan Tagihan Belum Lunas (Jatuh Tempo)
        foreach ($tagihanAktif as $tagihan) {
            $paymentSchedules->push([
                'id' => 'tagihan_' . $tagihan->id,
                'type' => 'tagihan',
                'title' => 'Jatuh Tempo: Tagihan ' . $tagihan->kamar->nama,
                'date' => \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo),
                'amount' => $tagihan->total_tagihan,
                'status' => $tagihan->status->value,
                'is_overdue' => $tagihan->status->value === 'overdue' || now()->startOfDay()->gt(\Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->startOfDay()),
            ]);
        }

        // 2. Masukkan Batas Sewa dari Penyewaan (Pengingat Perpanjangan)
        foreach ($activePenyewaans as $sewa) {
            if ($sewa->tanggal_keluar) {
                // Cek apakah sudah ada tagihan untuk penyewaan ini
                $hasTagihan = $tagihanAktif->where('penyewaan_id', $sewa->id)->isNotEmpty();
                if (!$hasTagihan) {
                    $paymentSchedules->push([
                        'id' => 'sewa_' . $sewa->id,
                        'type' => 'perpanjang',
                        'title' => 'Batas Masa Sewa: ' . $sewa->kamar->nama,
                        'date' => \Carbon\Carbon::parse($sewa->tanggal_keluar),
                        'amount' => null,
                        'status' => 'active',
                        'is_overdue' => now()->startOfDay()->gt(\Carbon\Carbon::parse($sewa->tanggal_keluar)->startOfDay()),
                        'penyewaan_id' => $sewa->id
                    ]);
                }
            }
        }

        // Urutkan jadwal berdasarkan tanggal paling dekat
        $paymentSchedules = $paymentSchedules->sortBy(function($item) {
            return $item['date']->timestamp;
        })->values();

        return view('user.dashboard', compact(
            'activePenyewaans',
            'tagihanAktif',
            'unreadNotifCount',
            'notifikasi',
            'keluhanAktif',
            'pengumuman',
            'voucherAktif',
            'paymentSchedules'
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
