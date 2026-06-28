@extends('layouts.app')
@section('title', 'Dashboard Penyewa - KosPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p class="text-slate-500 mt-2">Selamat datang di dashboard penyewa KostPro.</p>
        </div>
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-3 rounded-2xl shadow-lg shadow-emerald-500/30 flex items-center gap-3 w-fit">
            <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-emerald-50 uppercase tracking-wider">Voucher Aktif</p>
                <p class="text-xl font-bold">{{ $voucherAktif->count() }} <span class="text-sm font-medium">({{ $voucherAktif->count() > 0 ? 'Rp ' . number_format($voucherAktif->sum('nominal_diskon'), 0, ',', '.') : 'Belum ada' }})</span></p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SMART ALERT BANNERS — Muncul otomatis jika ada peringatan
         ============================================================ --}}
    @foreach($activePenyewaans as $ap)
        @php
            $sisaHariSewa = null;
            if ($ap->tanggal_keluar) {
                $sisaHariSewa = ceil(now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($ap->tanggal_keluar)->startOfDay(), false));
            }
        @endphp
        @if($sisaHariSewa !== null && $sisaHariSewa <= 14 && $sisaHariSewa >= 0)
        <div id="alert-sewa-{{ $ap->id }}" class="mb-6 flex items-start gap-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-5 shadow-sm animate-[slideDown_0.4s_ease-out]">
            <div class="shrink-0 w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-amber-900 text-sm">
                    ⚠️ Masa Sewa Hampir Berakhir! ({{ $ap->kamar->nama }})
                </h4>
                <p class="text-amber-700 text-sm mt-0.5">
                    Sewa kamar Anda akan berakhir dalam <strong>{{ $sisaHariSewa }} hari</strong>
                    ({{ \Carbon\Carbon::parse($ap->tanggal_keluar)->format('d M Y') }}).
                    Segera perpanjang agar tidak kehilangan kamar!
                </p>
            </div>
            <div class="flex gap-2 shrink-0">
                <button onclick="openPerpanjangModal({{ $ap->id }})" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm hover:-translate-y-0.5">
                    Perpanjang
                </button>
                <button onclick="document.getElementById('alert-sewa-{{ $ap->id }}').remove()" class="text-amber-500 hover:text-amber-700 p-2 rounded-lg hover:bg-amber-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif
    @endforeach


    @php $tagihanTerdekat = $tagihanAktif->first(); @endphp
    @if($tagihanTerdekat)
    @php $sisaHariTagihan = ceil(now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($tagihanTerdekat->tanggal_jatuh_tempo)->startOfDay(), false)); @endphp
    @if($sisaHariTagihan <= 3)
    <div id="alert-tagihan" class="mb-6 flex items-start gap-4 bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 rounded-2xl p-5 shadow-sm animate-[slideDown_0.4s_ease-out_0.1s_both]">
        <div class="shrink-0 w-11 h-11 bg-rose-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="font-bold text-rose-900 text-sm">
                🔴 Tagihan Segera Jatuh Tempo!
            </h4>
            <p class="text-rose-700 text-sm mt-0.5">
                @if($sisaHariTagihan <= 0)
                    Tagihan <strong>Rp {{ number_format($tagihanTerdekat->total_tagihan, 0, ',', '.') }}</strong> sudah <strong>melewati jatuh tempo!</strong> Segera bayar sekarang.
                @else
                    Tagihan <strong>Rp {{ number_format($tagihanTerdekat->total_tagihan, 0, ',', '.') }}</strong> jatuh tempo dalam <strong>{{ $sisaHariTagihan }} hari</strong>. Bayar tepat waktu untuk mendapat voucher diskon!
                @endif
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            <form action="{{ route('payment.create', $tagihanTerdekat->id) }}" method="POST">
                @csrf
                <input type="hidden" name="metode" value="virtual_account">
                <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm hover:-translate-y-0.5">
                    Bayar Kini
                </button>
            </form>
            <button onclick="document.getElementById('alert-tagihan').remove()" class="text-rose-500 hover:text-rose-700 p-2 rounded-lg hover:bg-rose-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif
    @endif

    <!-- Quick Stats — 4 kolom konsisten -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Kartu 1: Status Sewa --}}
        <div class="bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex items-center gap-4 hover:-translate-y-0.5 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Status Sewa</p>
                <p class="text-xl font-bold text-slate-900 mt-0.5 truncate">
                    @if($activePenyewaans->isNotEmpty())
                        {{ $activePenyewaans->count() }} Aktif
                    @else
                        <span class="text-slate-400 text-base">Belum Ada</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Kartu 2: Tagihan --}}
        <a href="{{ route('user.tagihan.index') }}"
           class="bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex items-center gap-4 hover:-translate-y-0.5 transition-all duration-200 group" style="border-color: #e2e8f0;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #fb923c, #ea580c); box-shadow: 0 4px 12px rgba(234,88,12,0.3);">
                <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tagihan Aktif</p>
                <p class="text-xl font-bold mt-0.5" style="color: {{ $tagihanAktif->count() > 0 ? '#ea580c' : '#0f172a' }};">
                    {{ $tagihanAktif->count() }}
                </p>
                @if($tagihanAktif->count() > 0)
                <p class="text-[10px] font-medium" style="color: #fb923c;">Lihat →</p>
                @endif
            </div>
        </a>

        {{-- Kartu 3: Notifikasi --}}
        <div onclick="openNotifModal()"
             class="group bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex items-center gap-4 cursor-pointer hover:-translate-y-0.5 hover:border-indigo-300 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-violet-200 relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if($unreadNotifCount > 0)
                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadNotifCount }}</span>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Notifikasi</p>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $unreadNotifCount }}</p>
                <p class="text-[10px] text-indigo-400 font-medium group-hover:underline">Klik lihat →</p>
            </div>
        </div>

        {{-- Kartu 4: Keluhan --}}
        <a href="{{ route('user.keluhan.index') }}"
           class="bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex items-center gap-4 hover:-translate-y-0.5 transition-all duration-200 group" style="border-color: #e2e8f0;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #fb7185, #e11d48); box-shadow: 0 4px 12px rgba(225,29,72,0.3);">
                <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Keluhan Aktif</p>
                <p class="text-xl font-bold mt-0.5" style="color: {{ $keluhanAktif > 0 ? '#e11d48' : '#0f172a' }};">{{ $keluhanAktif }}</p>
                @if($keluhanAktif > 0)
                <p class="text-[10px] font-medium" style="color: #fb7185;">Lihat →</p>
                @endif
            </div>
        </a>

    </div>

    @if($voucherAktif->isNotEmpty())

    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Voucher Diskon Saya
            </h2>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">{{ $voucherAktif->count() }} aktif</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($voucherAktif as $voucher)
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-5 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-200/40 rounded-full"></div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Voucher Diskon</p>
                <p class="text-2xl font-extrabold text-emerald-700 mb-1">Rp {{ number_format($voucher->nominal_diskon, 0, ',', '.') }}</p>
                <p class="text-xs font-mono text-emerald-800 bg-white/60 px-2 py-1 rounded-lg inline-block mb-3">{{ $voucher->kode_voucher }}</p>
                <p class="text-xs text-emerald-600">Berlaku hingga {{ $voucher->berlaku_sampai->format('d M Y') }}</p>
                @if($tagihanAktif->isNotEmpty())
                <a href="{{ route('user.tagihan.show', $tagihanAktif->first()->id) }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                    Gunakan saat bayar tagihan →
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Papan Pengumuman -->
    @if($pengumuman->isNotEmpty())
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Papan Pengumuman
            </h2>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ $pengumuman->count() }} info</span>
        </div>
        <div class="space-y-4">
        @foreach($pengumuman as $p)
        <div class="rounded-2xl p-4 sm:p-5 flex gap-4 {{ $p->is_penting ? 'bg-amber-50 border border-amber-200' : 'bg-white border border-slate-100 shadow-soft' }}">
            <div class="shrink-0 mt-1">
                @if($p->is_penting)
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                @else
                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-slate-900 {{ $p->is_penting ? 'text-amber-900' : '' }}">{{ $p->judul }}</h3>
                <p class="text-sm text-slate-600 mt-1 whitespace-pre-line">{{ $p->konten }}</p>
                <p class="text-xs text-slate-400 mt-3">{{ $p->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($activePenyewaans->isEmpty())
    <!-- Empty State for Kamar dengan Animasi -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-violet-900 rounded-3xl p-8 sm:p-12 shadow-2xl border border-indigo-700/50 text-center">

        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl animate-pulse-soft"></div>
            <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-violet-500/20 rounded-full blur-3xl animate-pulse-soft" style="animation-delay: 1s;"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center">
            <!-- Icon/Ilustrasi melayang -->
            <div class="relative mb-8 animate-float">
                <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-[0_0_40px_rgba(99,102,241,0.3)] border border-white/20 relative z-10 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <!-- Ornamen melayang -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-amber-400/20 backdrop-blur-md rounded-full flex items-center justify-center border border-amber-300/30 animate-float-delayed">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-white mb-3">Mulai Petualangan Kos Anda!</h3>
            <p class="text-indigo-200 mb-8 max-w-lg mx-auto text-base">Temukan kamar impian yang sesuai dengan gaya hidup dan kebutuhan Anda. Booking cepat, tanpa ribet.</p>
            
            <a href="{{ route('kamar.index') }}" class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-indigo-900 bg-white rounded-full overflow-hidden transition-all hover:scale-105 shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                <!-- Efek cahaya kilat (shine) saat dihover -->
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_ease-in-out_infinite]"></div>
                <span class="relative flex items-center gap-2">
                    Cari Kamar Sekarang
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </span>
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ============================================================
             JADWAL & SIKLUS PEMBAYARAN — Modern Countdown Design
             ============================================================ --}}
        <div class="lg:col-span-3">
            <h3 class="font-bold text-xl text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Jadwal Pembayaran
            </h3>

            @php
                $nearestSchedule = $paymentSchedules->first();
                $sisaHariNearest = $nearestSchedule
                    ? (int) ceil(now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($nearestSchedule['date'])->startOfDay(), false))
                    : null;
            @endphp

            @if($nearestSchedule)
            {{-- Layout: Countdown Card (kiri) + Daftar Jadwal (kanan) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- KARTU COUNTDOWN UTAMA --}}
                @php
                    $isLate = $nearestSchedule['is_overdue'];
                    $isClose = !$isLate && $sisaHariNearest !== null && $sisaHariNearest <= 7;

                    if ($isLate) {
                        $gradFrom = 'from-rose-500'; $gradTo = 'to-red-600';
                        $badgeBg = 'bg-white/20'; $badgeText = 'text-white';
                        $labelText = 'Sudah Melewati Jatuh Tempo!';
                        $icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
                    } elseif ($isClose) {
                        $gradFrom = 'from-amber-400'; $gradTo = 'to-orange-500';
                        $badgeBg = 'bg-white/20'; $badgeText = 'text-white';
                        $labelText = 'Segera Bayar!';
                        $icon = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
                    } else {
                        $gradFrom = 'from-indigo-500'; $gradTo = 'to-violet-600';
                        $badgeBg = 'bg-white/20'; $badgeText = 'text-white';
                        $labelText = 'Tagihan Mendatang';
                        $icon = 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z';
                    }
                @endphp

                <div class="md:col-span-1 bg-gradient-to-br {{ $gradFrom }} {{ $gradTo }} rounded-3xl p-6 text-white relative overflow-hidden shadow-lg">
                    {{-- Background dekorasi --}}
                    <div class="absolute -bottom-8 -right-8 w-36 h-36 bg-white/10 rounded-full"></div>
                    <div class="absolute -top-8 -left-8 w-24 h-24 bg-white/5 rounded-full"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="{{ $badgeBg }} backdrop-blur-sm rounded-lg p-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest text-white/80">{{ $labelText }}</span>
                        </div>

                        {{-- Angka hitung mundur besar --}}
                        <div class="mb-4">
                            @if($isLate)
                                <p class="text-7xl font-black leading-none tracking-tighter">{{ abs($sisaHariNearest) }}</p>
                                <p class="text-white/80 text-sm font-semibold mt-1">hari terlambat</p>
                            @elseif($sisaHariNearest === 0)
                                <p class="text-5xl font-black leading-none tracking-tighter">Hari Ini!</p>
                            @else
                                <p class="text-7xl font-black leading-none tracking-tighter">{{ $sisaHariNearest }}</p>
                                <p class="text-white/80 text-sm font-semibold mt-1">hari lagi</p>
                            @endif
                        </div>

                        {{-- Info Tagihan --}}
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-3 mt-2">
                            <p class="text-xs text-white/70 mb-0.5">{{ $nearestSchedule['type'] === 'tagihan' ? 'Tagihan Jatuh Tempo' : 'Batas Masa Sewa' }}</p>
                            <p class="font-bold text-sm leading-tight">{{ $nearestSchedule['title'] }}</p>
                            @if($nearestSchedule['amount'])
                            <p class="text-lg font-black mt-1">Rp {{ number_format($nearestSchedule['amount'], 0, ',', '.') }}</p>
                            @endif
                            <p class="text-xs text-white/70 mt-1">
                                📅 {{ \Carbon\Carbon::parse($nearestSchedule['date'])->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR JADWAL BERIKUTNYA --}}
                <div class="md:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-soft p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="font-bold text-slate-900">Semua Jadwal Terdekat</h4>
                        <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">{{ $paymentSchedules->count() }} jadwal</span>
                    </div>

                    <div class="space-y-3 flex-1">
                        @foreach($paymentSchedules->take(4) as $i => $ps)
                            @php
                                $psDate = \Carbon\Carbon::parse($ps['date']);
                                $psSisaHari = (int) ceil(now()->startOfDay()->diffInDays($psDate->copy()->startOfDay(), false));
                                $psIsOverdue = $ps['is_overdue'];
                                $psIsUrgent = !$psIsOverdue && $psSisaHari <= 7;
                                $psIsFirst = $i === 0;

                                if ($psIsOverdue) {
                                    $dotColor = 'bg-rose-500';
                                    $cardBg = 'bg-rose-50 border-rose-200';
                                    $titleColor = 'text-rose-800';
                                    $badge = '<span class="text-[10px] font-bold bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full">Terlambat</span>';
                                } elseif ($psIsUrgent) {
                                    $dotColor = 'bg-amber-400';
                                    $cardBg = 'bg-amber-50 border-amber-200';
                                    $titleColor = 'text-amber-900';
                                    $badge = '<span class="text-[10px] font-bold bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full">Segera</span>';
                                } else {
                                    $dotColor = 'bg-indigo-400';
                                    $cardBg = 'bg-slate-50 border-slate-100';
                                    $titleColor = 'text-slate-800';
                                    $badge = '';
                                }
                            @endphp

                            <div class="flex items-center gap-4 p-3.5 rounded-2xl border {{ $cardBg }} transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                {{-- Dot indikator --}}
                                <div class="shrink-0 flex flex-col items-center gap-1">
                                    <div class="w-3 h-3 rounded-full {{ $dotColor }} {{ $psIsFirst ? 'ring-4 ring-offset-1 ring-current opacity-60' : '' }}"></div>
                                </div>

                                {{-- Info Tanggal --}}
                                <div class="shrink-0 text-center w-12">
                                    <p class="text-lg font-black text-slate-800 leading-none">{{ $psDate->format('d') }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $psDate->format('M') }}</p>
                                </div>

                                <div class="w-px h-8 bg-slate-200 shrink-0"></div>

                                {{-- Deskripsi --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm {{ $titleColor }} truncate">{{ $ps['title'] }}</p>
                                    @if($ps['amount'])
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Rp {{ number_format($ps['amount'], 0, ',', '.') }}</p>
                                    @else
                                    <p class="text-xs text-slate-400 mt-0.5">Batas masa sewa</p>
                                    @endif
                                </div>

                                {{-- Sisa Hari / Badge --}}
                                <div class="shrink-0 text-right">
                                    {!! $badge !!}
                                    @if(!$psIsOverdue)
                                    <p class="text-xs text-slate-400 mt-1 font-medium">
                                        {{ $psSisaHari === 0 ? 'Hari ini' : $psSisaHari . ' hari lagi' }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($paymentSchedules->count() === 0)
                    <div class="flex-1 flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-bold text-slate-800">Semua Beres! 🎉</p>
                        <p class="text-sm text-slate-400 mt-1">Tidak ada tagihan atau jadwal mendatang.</p>
                    </div>
                    @endif
                </div>
            </div>

            @else
            {{-- Empty state jika tidak ada jadwal sama sekali --}}
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-3xl p-8 flex items-center gap-6">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="font-bold text-emerald-900 text-lg">Semua Tagihan Lunas! 🎉</p>
                    <p class="text-emerald-700 text-sm mt-1">Tidak ada tagihan mendatang. Nikmati masa kos Anda dengan tenang!</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Detail Kamar Tersedia -->
        <div class="lg:col-span-2 space-y-8 min-w-0">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-xl text-slate-900">Kamar Aktif Anda</h3>
                    <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">{{ $activePenyewaans->count() }} kamar</span>
                </div>

                <!-- Horizontal Scrollable Container (Carousel) -->
                <div class="flex overflow-x-auto pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 gap-5 snap-x snap-mandatory" style="scrollbar-width: none;">
                    <style>
                        /* Sembunyikan scrollbar untuk browser webkit */
                        .flex.overflow-x-auto::-webkit-scrollbar { display: none; }
                    </style>

                    @foreach($activePenyewaans as $ap)
                    @php
                        $sisaHariSewa = null;
                        if ($ap->tanggal_keluar) {
                            $sisaHariSewa = ceil(now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($ap->tanggal_keluar)->startOfDay(), false));
                        }
                    @endphp
                    <div class="shrink-0 w-[85%] md:w-[360px] snap-center bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden flex flex-col">
                        <div class="border-b border-slate-100 p-5 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-900 truncate pr-2">Kamar {{ $ap->kamar->nama }}</h3>
                            <div class="shrink-0">
                                @if($ap->status->value === 'active')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Aktif</span>
                                @elseif($ap->status->value === 'approved')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Approved</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col gap-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-400 font-medium mb-0.5">Tipe Kamar</p>
                                    <p class="text-sm font-semibold text-indigo-600">{{ ucfirst($ap->kamar->tipe->value ?? 'Campur') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 font-medium mb-0.5">Kode Booking</p>
                                    <p class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ $ap->kode_penyewaan }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                <div>
                                    <p class="text-xs text-slate-400 font-medium mb-0.5">Tanggal Masuk</p>
                                    <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($ap->tanggal_masuk)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium mb-0.5">Tanggal Keluar</p>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $ap->tanggal_keluar ? \Carbon\Carbon::parse($ap->tanggal_keluar)->format('d M Y') : '-' }}
                                    </p>
                                    @if($sisaHariSewa !== null && $sisaHariSewa >= 0)
                                    <p class="text-[10px] font-bold mt-1 inline-block px-1.5 py-0.5 rounded {{ $sisaHariSewa <= 7 ? 'bg-rose-100 text-rose-600' : ($sisaHariSewa <= 14 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600') }}">
                                        Sisa {{ $sisaHariSewa }} hari
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                                <div>
                                    <p class="text-xs text-slate-400 font-medium mb-0.5">Harga Bulanan</p>
                                    <p class="font-black text-slate-900">Rp {{ number_format($ap->harga_bulanan_snapshot, 0, ',', '.') }}</p>
                                </div>
                                @if($ap->status->value === 'active')
                                <button onclick="openPerpanjangModal({{ $ap->id }})" class="btn-primary py-1.5 px-3 text-xs flex items-center gap-1 shadow-md shadow-indigo-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Perpanjang
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>


            <!-- Tagihan Section -->
            <div>
                <h3 class="font-bold text-xl text-slate-900 mb-4">Tagihan Belum Dibayar</h3>
                @if($tagihanAktif->isEmpty())
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 text-center">
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="font-medium text-slate-900">Hore! Tidak ada tagihan tertunggak.</p>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($tagihanAktif as $t)
                    <div class="bg-white rounded-2xl p-6 shadow-soft border {{ $t->status->value === 'overdue' ? 'border-red-200' : 'border-slate-100' }} flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-900">{{ $t->kode_tagihan }}</span>
                                @if($t->status->value === 'overdue')
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-md">Terlambat</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500">Periode: {{ $t->periode_bulan }}/{{ $t->periode_tahun }} &bull; Jatuh Tempo: <span class="{{ $t->status->value === 'overdue' ? 'text-red-600 font-semibold' : '' }}">{{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d M Y') }}</span></p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-6 sm:w-1/3">
                            <div class="text-right">
                                <p class="text-xs text-slate-500 mb-0.5">Total</p>
                                <p class="font-bold text-lg text-slate-900">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</p>
                            </div>
                            <form action="{{ route('payment.create', $t->id) }}" method="POST">
                                @csrf
                                <!-- Simulasi VA default -->
                                <input type="hidden" name="metode" value="virtual_account">
                                <button type="submit" class="btn-primary py-2 px-4 whitespace-nowrap">
                                    Bayar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <h3 class="font-bold text-slate-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3                    <a href="{{ route('user.tagihan.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Riwayat Tagihan</p>
                                <p class="text-xs text-slate-500">Lihat semua tagihan</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="{{ route('user.keluhan.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Lapor Masalah</p>
                                <p class="text-xs text-slate-500">Keluhan fasilitas</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('user.pasar_kos.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Pasar Kos</p>
                                <p class="text-xs text-slate-500">Jual beli antar penghuni</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('user.panduan') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Buku Panduan</p>
                                <p class="text-xs text-slate-500">Tata tertib & aturan</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ============================================================
     MODAL PERPANJANG SEWA (Multi-Kamar)
     ============================================================ --}}
@foreach($activePenyewaans->filter(fn($p) => $p->status->value === 'active') as $ap)
@php $harga = $ap->harga_bulanan_snapshot; @endphp
<div id="modal-perpanjang-{{ $ap->id }}" class="fixed inset-0 z-[500] hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div onclick="closePerpanjangModal({{ $ap->id }})" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" id="modal-perpanjang-backdrop-{{ $ap->id }}"></div>

    {{-- Panel --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="modal-perpanjang-panel-{{ $ap->id }}" class="bg-white rounded-3xl w-full max-w-md shadow-2xl scale-95 opacity-0 transition-all duration-300 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-lg leading-tight">Perpanjang Sewa</h3>
                            <p class="text-indigo-200 text-xs">{{ $ap->kamar->nama }}</p>
                        </div>
                    </div>
                    <button onclick="closePerpanjangModal({{ $ap->id }})" class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <form action="{{ route('user.sewa.perpanjang', $ap->id) }}" method="POST">
                @csrf
                <div class="p-6">
                    <p class="text-slate-600 text-sm mb-5">Pilih berapa bulan Anda ingin memperpanjang sewa. Tagihan akan dibuat otomatis dan masa sewa akan diperbarui setelah pembayaran dikonfirmasi.</p>

                    <label class="block text-sm font-bold text-slate-700 mb-3">Durasi Perpanjangan</label>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @foreach([1, 3, 6, 12] as $bln)
                        @php
                            $diskon = $bln >= 12 ? 10 : ($bln >= 6 ? 5 : 0);
                            $totalNormal = $harga * $bln;
                            $totalDiskon = (int) round($totalNormal * (1 - $diskon / 100));
                            $hemat = $totalNormal - $totalDiskon;
                        @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="durasi_bulan" value="{{ $bln }}" class="peer hidden" {{ $bln === 1 ? 'checked' : '' }}>
                            <div class="relative p-4 rounded-2xl border-2 transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-50 border-slate-200 hover:border-slate-300">
                                @if($diskon > 0)
                                <span class="absolute -top-2.5 -right-2.5 bg-emerald-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">HEMAT {{ $diskon }}%</span>
                                @endif
                                <p class="font-black text-slate-900 text-xl">{{ $bln }} Bulan</p>
                                @if($diskon > 0)
                                    <p class="text-xs text-slate-400 line-through mt-0.5">Rp {{ number_format($totalNormal, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold text-indigo-600">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</p>
                                    <p class="text-[11px] font-semibold text-emerald-600 mt-1">Hemat Rp {{ number_format($hemat, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm font-bold text-indigo-600 mt-1">Rp {{ number_format($totalNormal, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex gap-3 mb-6">
                        <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-slate-500 leading-relaxed">Tagihan perpanjangan akan muncul di daftar tagihan Anda. Masa sewa akan otomatis diperpanjang setelah pembayaran lunas dikonfirmasi.</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button" onclick="closePerpanjangModal({{ $ap->id }})" class="flex-1 bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-2xl hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                            Ajukan Perpanjangan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function openPerpanjangModal(id) {
    const modal = document.getElementById('modal-perpanjang-' + id);
    const backdrop = document.getElementById('modal-perpanjang-backdrop-' + id);
    const panel = document.getElementById('modal-perpanjang-panel-' + id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    void modal.offsetWidth;
    backdrop.style.opacity = '1';
    panel.style.opacity = '1';
    panel.style.transform = 'scale(1)';
}
function closePerpanjangModal(id) {
    const modal = document.getElementById('modal-perpanjang-' + id);
    const backdrop = document.getElementById('modal-perpanjang-backdrop-' + id);
    const panel = document.getElementById('modal-perpanjang-panel-' + id);
    if (!modal) return;
    backdrop.style.opacity = '0';
    panel.style.opacity = '0';
    panel.style.transform = 'scale(0.95)';
    document.body.style.overflow = '';
    setTimeout(() => modal.classList.add('hidden'), 300);
}

@if(request('trigger_perpanjang_id'))
    setTimeout(function() {
        openPerpanjangModal({{ (int) request('trigger_perpanjang_id') }});
    }, 500);
@endif
</script>


{{-- ============================================================
     NOTIFIKASI DRAWER — Bottom Sheet (Mobile) / Side Drawer (Desktop)
     ============================================================ --}}
<div id="notif-overlay" class="fixed inset-0 z-[300] hidden">
    {{-- Backdrop blur --}}
    <div id="notif-backdrop"
         onclick="closeNotifModal()"
         class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-out"></div>

    {{-- Drawer Panel --}}
    <div id="notif-drawer"
         class="absolute bg-white flex flex-col shadow-2xl transition-transform duration-400 ease-[cubic-bezier(0.32,0.72,0,1)]
                /* Mobile: Bottom Sheet */
                bottom-0 left-0 right-0 h-[85vh] rounded-t-3xl translate-y-full
                /* Desktop: Side Drawer */
                md:top-0 md:bottom-0 md:left-auto md:right-0 md:h-full md:w-[400px] md:rounded-none md:translate-y-0 md:translate-x-full">
        
        {{-- Mobile Drag Indicator --}}
        <div class="md:hidden flex justify-center pt-3 pb-1 bg-white rounded-t-3xl" onclick="closeNotifModal()">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
        </div>

        {{-- ── HEADER ── --}}
        <div class="relative px-6 py-5 border-b border-slate-100 flex-shrink-0 bg-white md:pt-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-xl leading-none">Notifikasi</h2>
                        <p class="text-slate-500 text-xs mt-1">Pusat pesan & informasi Anda</p>
                    </div>
                </div>
                <button onclick="closeNotifModal()" class="w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Stats bar --}}
            <div class="flex gap-2">
                <div class="flex-1 bg-slate-50 rounded-xl px-3 py-2 text-center border border-slate-100">
                    <p class="text-slate-700 font-bold text-lg leading-none">{{ isset($notifikasi) ? $notifikasi->count() : 0 }}</p>
                    <p class="text-slate-400 text-[10px] mt-1 font-semibold uppercase">Total</p>
                </div>
                <div class="flex-1 bg-indigo-50 rounded-xl px-3 py-2 text-center border border-indigo-100">
                    <p class="font-bold text-lg leading-none text-indigo-700">{{ $unreadNotifCount }}</p>
                    <p class="text-indigo-400 text-[10px] mt-1 font-semibold uppercase">Baru</p>
                </div>
                <div class="flex-1 bg-slate-50 rounded-xl px-3 py-2 text-center border border-slate-100">
                    <p class="text-emerald-600 font-bold text-lg leading-none">{{ isset($notifikasi) ? $notifikasi->whereNotNull('read_at')->count() : 0 }}</p>
                    <p class="text-slate-400 text-[10px] mt-1 font-semibold uppercase">Dibaca</p>
                </div>
            </div>
        </div>

        {{-- ── FILTER TABS ── --}}
        <div class="flex-shrink-0 px-4 py-3 flex items-center justify-between bg-white border-b border-slate-50 shadow-sm z-10 relative">
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 text-xs font-bold bg-slate-800 text-white rounded-full shadow-sm">Semua</button>
                @if($unreadNotifCount > 0)
                <button class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100 rounded-full transition-colors flex items-center gap-1.5">
                    Belum Dibaca
                    <span class="px-1.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold">{{ $unreadNotifCount }}</span>
                </button>
                @endif
            </div>
            @if($unreadNotifCount > 0)
            <form action="{{ route('user.notifikasi.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 px-2 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai dibaca
                </button>
            </form>
            @endif
        </div>

        {{-- ── NOTIFICATION LIST ── --}}
        <div class="flex-1 overflow-y-auto overscroll-contain bg-slate-50/50" id="notif-list-scroll">
            @if(isset($notifikasi) && $notifikasi->count() > 0)
                <div class="p-3 space-y-2">
                    @foreach($notifikasi as $index => $notif)
                        @php
                            $isUnread = is_null($notif->read_at);
                            $judul = strtolower($notif->judul ?? '');
                            if (str_contains($judul, 'tagih') || str_contains($judul, 'bayar')) {
                                $iconColor = 'bg-orange-100 text-orange-600';
                                $iconPath = 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z';
                            } elseif (str_contains($judul, 'keluh') || str_contains($judul, 'masalah')) {
                                $iconColor = 'bg-rose-100 text-rose-600';
                                $iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
                            } elseif (str_contains($judul, 'sewa') || str_contains($judul, 'kamar') || str_contains($judul, 'booking')) {
                                $iconColor = 'bg-emerald-100 text-emerald-600';
                                $iconPath = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
                            } elseif (str_contains($judul, 'selamat') || str_contains($judul, 'promo') || str_contains($judul, 'bonus')) {
                                $iconColor = 'bg-amber-100 text-amber-600';
                                $iconPath = 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z';
                            } else {
                                $iconColor = 'bg-indigo-100 text-indigo-600';
                                $iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                            }
                        @endphp

                        <div class="relative group rounded-2xl bg-white border border-slate-100 hover:border-slate-200 transition-all duration-200 overflow-hidden shadow-sm hover:shadow-md"
                             style="animation: slideInUp 0.3s ease {{ $index * 0.05 }}s both;">

                            {{-- Indikator Belum Dibaca --}}
                            @if($isUnread)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full"></div>
                            @endif

                            <div class="flex gap-3.5 p-4 {{ $isUnread ? 'bg-indigo-50/30 pl-5' : '' }}">
                                {{-- Icon --}}
                                <div class="shrink-0 mt-0.5">
                                    <div class="w-10 h-10 rounded-full {{ $iconColor }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <p class="text-sm font-bold {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }} leading-snug">
                                            {{ $notif->judul }}
                                        </p>
                                        @if($isUnread)
                                        <span class="shrink-0 w-2 h-2 mt-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-2">{{ $notif->pesan }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notif->created_at->diffForHumans() }}
                                        </span>
                                        @if($isUnread)
                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-md">BARU</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- ── EMPTY STATE ── --}}
                <div class="flex flex-col items-center justify-center h-full px-8 py-16 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Semua Bersih! ✨</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Tidak ada notifikasi baru saat ini.
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #notif-list-scroll::-webkit-scrollbar { width: 4px; }
    #notif-list-scroll::-webkit-scrollbar-track { background: transparent; }
    #notif-list-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    #notif-list-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
    function openNotifModal() {
        const overlay  = document.getElementById('notif-overlay');
        const backdrop = document.getElementById('notif-backdrop');
        const drawer   = document.getElementById('notif-drawer');

        // Tampilkan overlay container
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Trigger reflow agar transisi CSS berjalan
        void overlay.offsetHeight;

        // Animasikan backdrop
        backdrop.classList.add('opacity-100');
        
        // Animasikan drawer masuk
        drawer.classList.remove('translate-y-full', 'md:translate-x-full');
    }

    function closeNotifModal() {
        const overlay  = document.getElementById('notif-overlay');
        const backdrop = document.getElementById('notif-backdrop');
        const drawer   = document.getElementById('notif-drawer');

        // Animasikan backdrop keluar
        backdrop.classList.remove('opacity-100');
        
        // Animasikan drawer keluar
        drawer.classList.add('translate-y-full', 'md:translate-x-full');
        document.body.style.overflow = '';

        // Sembunyikan container setelah transisi selesai
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 400); // 400ms sesuai durasi transisi Tailwind
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('notif-overlay').classList.contains('hidden')) {
            closeNotifModal();
        }
    });

    // Backward compatibilitas
    window.openModal  = id => { if (id === 'modal-notifikasi') openNotifModal(); };
    window.closeModal = id => { if (id === 'modal-notifikasi') closeNotifModal(); };
</script>
@endsection
