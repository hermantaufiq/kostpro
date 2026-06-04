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

        // Cari penyewaan yang sedang aktif
        $activePenyewaan = Penyewaan::with('kamar')
            ->where('user_id', $userId)
            ->where('status', StatusPenyewaan::Active)
            ->first();

        // Cari semua tagihan yang belum lunas
        $tagihanAktif = Tagihan::with('penyewaan.kamar')
            ->where('user_id', $userId)
            ->whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Hitung notifikasi belum dibaca
        $unreadNotifCount = Notifikasi::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        // Hitung keluhan aktif
        $keluhanAktif = Keluhan::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->count();

        // Ambil pengumuman terbaru
        $pengumuman = \App\Models\Pengumuman::orderBy('created_at', 'desc')->take(3)->get();

        return view('user.dashboard', compact(
            'activePenyewaan',
            'tagihanAktif',
            'unreadNotifCount',
            'keluhanAktif',
            'pengumuman'
        ));
    }
}
