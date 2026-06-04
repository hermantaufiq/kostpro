@extends('layouts.app')
@section('title', 'Dashboard Penyewa - KosPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p class="text-slate-500 mt-2">Selamat datang di dashboard penyewa KostPro.</p>
        </div>
        <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-5 py-3 rounded-2xl shadow-lg shadow-amber-500/30 flex items-center gap-3 w-fit">
            <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-amber-50 uppercase tracking-wider">Poin KostPro</p>
                <p class="text-xl font-bold">{{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Status Sewa</p>
                <p class="text-2xl font-bold text-slate-900">
                    @if($activePenyewaan)
                        Aktif
                    @else
                        Belum Ada
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Tagihan Aktif</p>
                <p class="text-2xl font-bold text-slate-900">{{ $tagihanAktif->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        
        <div onclick="openNotifModal()"
             class="group bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between cursor-pointer hover:border-indigo-300 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Notifikasi</p>
                <div class="flex items-center gap-2">
                    <p class="text-2xl font-bold text-slate-900">{{ $unreadNotifCount }}</p>
                    @if($unreadNotifCount > 0)
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                        </span>
                    @endif
                </div>
                <p class="text-xs text-indigo-500 mt-1 font-medium group-hover:underline">Klik untuk lihat →</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Keluhan Aktif</p>
                <p class="text-2xl font-bold text-slate-900">{{ $keluhanAktif }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Papan Pengumuman -->
    @if($pengumuman->isNotEmpty())
    <div class="mb-8 space-y-4">
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

    @if(!$activePenyewaan)
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
        <!-- Detail Kamar Tersedia -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 p-6 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-900">Kamar Aktif</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Berjalan</span>
                </div>
                <div class="p-6 grid sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Nama Kamar</p>
                        <p class="font-bold text-slate-900 text-lg">{{ $activePenyewaan->kamar->nama }}</p>
                        <p class="text-sm text-indigo-600 font-medium">{{ ucfirst($activePenyewaan->kamar->tipe->value ?? 'Campur') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Kode Booking</p>
                        <p class="font-mono text-slate-900 font-semibold">{{ $activePenyewaan->kode_penyewaan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Tanggal Masuk</p>
                        <p class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($activePenyewaan->tanggal_masuk)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Harga Bulanan</p>
                        <p class="font-bold text-slate-900">Rp {{ number_format($activePenyewaan->harga_bulanan_snapshot, 0, ',', '.') }}</p>
                    </div>
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

<!-- Modal Notifikasi Modern -->
<div id="modal-notifikasi"
     class="fixed inset-0 z-[200] hidden items-center justify-center p-4"
     style="display:none!important">
</div>


{{-- ============================================================
     NOTIFIKASI DRAWER — Slide dari kanan (seperti app HP native)
     ============================================================ --}}
<div id="modal-notifikasi" style="display:none!important" aria-hidden="true"></div>

<div id="notif-overlay" class="fixed inset-0 z-[300]" style="display:none; visibility:hidden;">
    {{-- Backdrop blur --}}
    <div id="notif-backdrop"
         onclick="closeNotifModal()"
         class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         style="opacity:0; transition: opacity 0.35s ease;"></div>

    {{-- Drawer panel slide dari kanan --}}
    <div id="notif-drawer"
         class="absolute right-0 top-0 bottom-0 w-full max-w-sm bg-white flex flex-col shadow-[−20px_0_60px_rgba(0,0,0,0.2)]"
         style="transform: translateX(100%); transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);">

        {{-- ── HEADER ── --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 px-5 pt-6 pb-5 flex-shrink-0">
            {{-- Decorasi --}}
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-6 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/15 border border-white/25 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-white text-lg leading-none">Notifikasi</h2>
                        <p class="text-indigo-200 text-xs mt-1">Pusat pesan & informasi Anda</p>
                    </div>
                </div>
                <button onclick="closeNotifModal()"
                        id="notif-close-btn"
                        class="w-9 h-9 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl flex items-center justify-center text-white/70 hover:text-white transition-all duration-200"
                        style="transition: transform 0.2s ease;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Stats bar --}}
            <div class="relative flex gap-3">
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl px-3 py-2.5 text-center backdrop-blur-sm">
                    <p class="text-white font-bold text-xl leading-none">{{ isset($notifikasi) ? $notifikasi->count() : 0 }}</p>
                    <p class="text-indigo-200 text-[10px] mt-1 font-medium uppercase tracking-wider">Total</p>
                </div>
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl px-3 py-2.5 text-center backdrop-blur-sm">
                    <p class="font-bold text-xl leading-none {{ $unreadNotifCount > 0 ? 'text-amber-300' : 'text-white' }}">{{ $unreadNotifCount }}</p>
                    <p class="text-indigo-200 text-[10px] mt-1 font-medium uppercase tracking-wider">Belum Dibaca</p>
                </div>
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl px-3 py-2.5 text-center backdrop-blur-sm">
                    <p class="text-emerald-300 font-bold text-xl leading-none">{{ isset($notifikasi) ? $notifikasi->whereNotNull('read_at')->count() : 0 }}</p>
                    <p class="text-indigo-200 text-[10px] mt-1 font-medium uppercase tracking-wider">Dibaca</p>
                </div>
            </div>
        </div>

        {{-- ── FILTER TABS (opsional, future-proof) ── --}}
        <div class="flex-shrink-0 border-b border-slate-100 px-4 py-2 flex items-center justify-between bg-slate-50/70">
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 text-xs font-semibold bg-indigo-600 text-white rounded-lg">Semua</button>
                @if($unreadNotifCount > 0)
                <button class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                    Belum Dibaca
                    <span class="ml-1 px-1.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold">{{ $unreadNotifCount }}</span>
                </button>
                @endif
            </div>
            @if($unreadNotifCount > 0)
            <form action="{{ route('user.notifikasi.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 px-2 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai semua
                </button>
            </form>
            @endif
        </div>

        {{-- ── NOTIFICATION LIST ── --}}
        <div class="flex-1 overflow-y-auto overscroll-contain" id="notif-list-scroll">
            @if(isset($notifikasi) && $notifikasi->count() > 0)
                <div class="p-3 space-y-2">
                    @foreach($notifikasi as $index => $notif)
                        @php
                            $isUnread = is_null($notif->read_at);
                            // Determine icon & color based on keywords
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

                        <div class="relative group rounded-2xl {{ $isUnread ? 'bg-indigo-50 border border-indigo-100' : 'bg-white border border-slate-100 hover:border-slate-200' }} transition-all duration-200 overflow-hidden"
                             style="animation: slideInRight 0.3s ease {{ $index * 0.05 }}s both;">

                            {{-- Garis kiri indikator --}}
                            @if($isUnread)
                            <div class="absolute left-0 top-3 bottom-3 w-1 bg-gradient-to-b from-indigo-500 to-violet-500 rounded-r-full"></div>
                            @endif

                            <div class="flex gap-3 p-3.5 {{ $isUnread ? 'pl-4' : '' }}">
                                {{-- Icon --}}
                                <div class="shrink-0">
                                    <div class="w-10 h-10 rounded-xl {{ $iconColor }} flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <p class="text-sm font-semibold {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }} leading-snug line-clamp-1">
                                            {{ $notif->judul }}
                                        </p>
                                        @if($isUnread)
                                        <span class="shrink-0 w-2 h-2 mt-1 rounded-full bg-indigo-500"
                                              style="box-shadow: 0 0 0 3px rgba(99,102,241,0.2);"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-2">{{ $notif->pesan }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notif->created_at->diffForHumans() }}
                                        </span>
                                        @if($isUnread)
                                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full">Baru</span>
                                        @else
                                        <span class="text-[10px] text-slate-400">Dibaca</span>
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
                    <div class="relative mb-6">
                        {{-- Outer ring --}}
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-white shadow-inner flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                        </div>
                        {{-- Badge ceklis --}}
                        <div class="absolute bottom-0 right-0 w-9 h-9 bg-emerald-500 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Semua Bersih! ✨</h3>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                        Tidak ada notifikasi baru. Anda akan diberitahu saat ada informasi penting.
                    </p>
                </div>
            @endif
        </div>

        {{-- ── FOOTER ── --}}
        <div class="flex-shrink-0 border-t border-slate-100 bg-white px-4 py-3 flex items-center justify-between gap-3">
            <p class="text-xs text-slate-400">
                Hanya 20 notifikasi terbaru ditampilkan
            </p>
            <button onclick="closeNotifModal()"
                    class="flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(16px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    #notif-list-scroll::-webkit-scrollbar { width: 4px; }
    #notif-list-scroll::-webkit-scrollbar-track { background: transparent; }
    #notif-list-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
    #notif-list-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<script>
    function openNotifModal() {
        const overlay  = document.getElementById('notif-overlay');
        const backdrop = document.getElementById('notif-backdrop');
        const drawer   = document.getElementById('notif-drawer');

        // Tampilkan overlay
        overlay.style.display  = 'block';
        overlay.style.visibility = 'visible';
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            // Fade backdrop
            backdrop.style.opacity = '1';
            // Slide drawer masuk dari kanan
            drawer.style.transform = 'translateX(0)';
        });
    }

    function closeNotifModal() {
        const overlay  = document.getElementById('notif-overlay');
        const backdrop = document.getElementById('notif-backdrop');
        const drawer   = document.getElementById('notif-drawer');

        // Slide balik ke kanan
        backdrop.style.opacity  = '0';
        drawer.style.transform  = 'translateX(100%)';
        document.body.style.overflow = '';

        setTimeout(() => {
            overlay.style.display    = 'none';
            overlay.style.visibility = 'hidden';
        }, 420);
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNotifModal();
    });

    // Backward compat
    window.openModal  = id => { if (id === 'modal-notifikasi') openNotifModal(); };
    window.closeModal = id => { if (id === 'modal-notifikasi') closeNotifModal(); };
</script>
@endsection
