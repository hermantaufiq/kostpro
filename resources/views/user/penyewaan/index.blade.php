@extends('layouts.app')
@section('title', 'Riwayat Penyewaan - KosPro')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Riwayat Penyewaan</h1>
        <p class="text-slate-500 mt-1">Daftar semua pengajuan dan penyewaan kamar Anda.</p>
    </div>

    @if($penyewaanList->isEmpty())
        <div class="bg-white rounded-2xl p-10 shadow-soft border border-slate-100 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 8h8"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-slate-500 mb-6">Anda belum pernah mengajukan penyewaan kamar.</p>
            <a href="{{ route('kamar.index') }}" class="btn-primary">
                Cari Kamar
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($penyewaanList as $penyewaan)
                @php
                    $statusColor = match($penyewaan->status->value) {
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'approved' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'active' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'rejected', 'cancelled', 'completed' => 'bg-slate-50 text-slate-600 border-slate-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-card-hover transition-shadow">
                    <div class="flex flex-1 items-center gap-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                            @if($penyewaan->kamar->thumbnail)
                                <img src="{{ $penyewaan->kamar->thumbnail->foto_url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 mb-1 block">{{ $penyewaan->kode_penyewaan }}</span>
                            <h3 class="font-bold text-lg text-slate-900 leading-tight mb-1">{{ $penyewaan->kamar->nama }}</h3>
                            <p class="text-sm text-slate-500">
                                Mulai: {{ $penyewaan->tanggal_masuk->format('d M Y') }} &bull; Durasi: {{ $penyewaan->durasi_bulan }} Bln
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col md:items-end justify-center gap-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold border {{ $statusColor }}">
                            {{ ucfirst($penyewaan->status->value) }}
                        </span>
                        <a href="{{ route('user.penyewaan.show', $penyewaan->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
