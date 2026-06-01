<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Cari penyewaan yang sedang aktif
        $activePenyewaan = Penyewaan::with('kamar')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        // Cari semua tagihan yang belum lunas
        $tagihanAktif = Tagihan::with('penyewaan.kamar')
            ->where('user_id', $userId)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Hitung notifikasi belum dibaca
        $unreadNotifCount = Notifikasi::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return view('user.dashboard', compact(
            'activePenyewaan', 
            'tagihanAktif', 
            'unreadNotifCount'
        ));
    }
}
