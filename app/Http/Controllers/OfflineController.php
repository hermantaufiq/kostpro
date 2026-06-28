<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfflineController extends Controller
{
    /**
     * Tampilkan halaman offline untuk PWA fallback.
     * Service Worker akan serve halaman ini saat pengguna tidak ada koneksi.
     */
    public function index()
    {
        return response()
            ->view('pwa.offline')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Content-Type', 'text/html; charset=utf-8');
    }
}
