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
        
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Notifikasi</p>
                <p class="text-2xl font-bold text-slate-900">{{ $unreadNotifCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
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
    <!-- Empty State for Kamar -->
    <div class="bg-white rounded-2xl p-8 shadow-soft border border-slate-100 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 8h8"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Anda Belum Menyewa Kamar</h3>
        <p class="text-slate-500 mb-6 max-w-md mx-auto">Mulai cari kamar yang sesuai dengan kebutuhan Anda dan ajukan penyewaan sekarang juga.</p>
        <a href="{{ route('kamar.index') }}" class="btn-primary inline-flex">
            Cari Kamar Sekarang
        </a>
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
                <div class="space-y-3">
                    <a href="{{ route('user.tagihan.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
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
                            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Keluhan & Laporan</p>
                                <p class="text-xs text-slate-500">Laporkan masalah fasilitas</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    <a href="{{ route('user.panduan') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Buku Panduan & Aturan</p>
                                <p class="text-xs text-slate-500">Tata tertib dan kontak darurat</p>
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
@endsection
