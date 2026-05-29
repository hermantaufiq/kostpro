@extends('layouts.app')
@section('title', $room->nama . ' - KosPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-4 flex items-center text-sm text-slate-500">
        <a href="{{ route('kamar.index') }}" class="hover:text-indigo-600">Kamar</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900 font-medium">{{ $room->nama }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Gallery Section -->
            <x-gallery-swiper :fotos="$room->fotoKamar" />

            <!-- Description & Facilities -->
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Deskripsi Kamar</h2>
                <div class="prose prose-slate max-w-none mb-8">
                    <p>{{ $room->deskripsi ?? 'Belum ada deskripsi.' }}</p>
                </div>

                <h2 class="text-xl font-bold text-slate-900 mb-4">Fasilitas</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($room->fasilitasMaster as $fasilitas)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-indigo-600">
                                <!-- Placeholder Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $fasilitas->nama }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar / Booking Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600">
                        Tipe {{ ucfirst($room->tipe->value) }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $room->status->isAvailable() ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                        {{ $room->status->isAvailable() ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $room->nama }}</h1>
                <p class="text-slate-500 text-sm mb-6">Lantai {{ $room->lantai ?? '-' }} &bull; Luas {{ $room->luas ?? '-' }} m&sup2;</p>
                
                <div class="py-4 border-y border-slate-100 mb-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Harga Bulanan</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($room->harga_bulanan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Deposit Awal</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($room->harga_deposit, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($room->status->isAvailable())
                    <a href="{{ route('user.penyewaan.create', $room->id) }}" class="btn-primary w-full justify-center text-center">
                        Ajukan Sewa
                    </a>
                    <p class="text-center text-xs text-slate-400 mt-4">Anda belum ditagih saat pengajuan.</p>
                @else
                    <button class="w-full bg-slate-100 text-slate-400 rounded-xl px-6 py-3 font-semibold text-sm cursor-not-allowed" disabled>
                        Kamar Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
