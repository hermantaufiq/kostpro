@extends('layouts.app')
@section('title', 'Dashboard Penyewa - KosPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="text-slate-500 mt-2">Selamat datang di dashboard penyewa KosPro.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Status Sewa</p>
                <p class="text-2xl font-bold text-slate-900">Belum Ada</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Tagihan Aktif</p>
                <p class="text-2xl font-bold text-slate-900">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Notifikasi</p>
                <p class="text-2xl font-bold text-slate-900">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>
    </div>

    <!-- Empty State for Kamar -->
    <div class="bg-white rounded-2xl p-8 shadow-soft border border-slate-100 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 8h8"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Anda Belum Menyewa Kamar</h3>
        <p class="text-slate-500 mb-6 max-w-md mx-auto">Mulai cari kamar yang sesuai dengan kebutuhan Anda dan ajukan penyewaan sekarang juga.</p>
        <a href="{{ route('kamar.index') }}" class="btn-primary">
            Cari Kamar Sekarang
        </a>
    </div>
</div>
@endsection
