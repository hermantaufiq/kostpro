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

<!-- Map & Location Section -->
@if(isset($settings['map_iframe']) && !empty($settings['map_iframe']))
<div class="py-16 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Lokasi Kami</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $settings['alamat_kos'] ?? 'Lokasi strategis, dekat dengan berbagai fasilitas umum.' }}
            </p>
        </div>
        
        <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-200">
            <div class="aspect-[16/9] md:aspect-[21/9] w-full rounded-2xl overflow-hidden relative">
                <!-- Inject the raw iframe string -->
                {!! $settings['map_iframe'] !!}
            </div>
            
            @if(isset($settings['kontak_admin']))
            <div class="mt-6 flex justify-center">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['kontak_admin']) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-full font-semibold transition-colors shadow-sm shadow-green-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    Hubungi Admin
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@endsection
