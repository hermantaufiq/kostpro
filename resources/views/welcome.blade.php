@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold mb-6 border border-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    Sistem Manajemen Kos Modern
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Solusi Cerdas <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400">Kelola Kos-Kosan</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 max-w-lg">
                    KosPro membantu Anda mengelola penyewaan, penagihan otomatis, dan fasilitas kos dengan mudah, aman, dan efisien.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
                    <a href="#fitur" class="btn-secondary">Pelajari Fitur</a>
                </div>
            </div>
            
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-100 to-white rounded-3xl transform rotate-3 scale-105"></div>
                <div class="relative bg-white p-2 rounded-3xl shadow-card-hover border border-slate-100">
                    <div class="aspect-[4/3] rounded-2xl bg-slate-100 overflow-hidden relative group flex items-center justify-center">
                        <img src="{{ asset('images/kost-preview.png') }}" alt="KosPro Interior Kamar" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
