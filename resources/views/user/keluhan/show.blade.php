@extends('layouts.app')
@section('title', 'Detail Keluhan - KosPro')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <a href="{{ route('user.keluhan.index') }}" class="text-indigo-600 text-sm font-medium flex items-center gap-1 mb-4 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Keluhan
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detail Keluhan</h1>
    </div>

    @php
        $statusColor = match($keluhan->status->value) {
            'menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
            'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
            'selesai'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'ditolak'  => 'bg-red-100 text-red-700 border-red-200',
            default    => 'bg-slate-100 text-slate-700 border-slate-200',
        };
        $prioritasColor = match($keluhan->prioritas->value) {
            'tinggi' => 'bg-rose-100 text-rose-700',
            'sedang' => 'bg-amber-100 text-amber-700',
            default  => 'bg-slate-100 text-slate-500',
        };
    @endphp

    <div class="space-y-6">
        {{-- Status Card --}}
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <span class="px-3 py-1 text-sm font-bold rounded-full border {{ $statusColor }}">
                    {{ $keluhan->status->label() }}
                </span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $prioritasColor }}">
                    Prioritas: {{ $keluhan->prioritas->label() }}
                </span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $keluhan->judul }}</h2>
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <span>📅 {{ $keluhan->created_at->format('d M Y, H:i') }}</span>
                @if($keluhan->kamar)
                <span>🏠 Kamar {{ $keluhan->kamar->nama }}</span>
                @endif
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <h3 class="font-bold text-slate-700 mb-3">📝 Deskripsi Keluhan</h3>
            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $keluhan->deskripsi }}</p>
        </div>

        {{-- Foto --}}
        @if($keluhan->foto_url)
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <h3 class="font-bold text-slate-700 mb-3">📸 Foto Bukti</h3>
            <img src="{{ Storage::url($keluhan->foto_url) }}" alt="Foto keluhan" class="rounded-xl max-w-full max-h-80 object-contain border border-slate-100">
        </div>
        @endif

        {{-- Tanggapan Admin --}}
        <div class="rounded-2xl p-6 {{ $keluhan->tanggapan_admin ? 'bg-indigo-50 border border-indigo-100' : 'bg-white shadow-soft border border-slate-100' }}">
            <h3 class="font-bold mb-3 {{ $keluhan->tanggapan_admin ? 'text-indigo-700' : 'text-slate-700' }}">
                💬 Tanggapan Tim KosPro
            </h3>
            @if($keluhan->tanggapan_admin)
                <p class="text-indigo-800 leading-relaxed whitespace-pre-wrap">{{ $keluhan->tanggapan_admin }}</p>
                @if($keluhan->resolved_at)
                <p class="text-xs text-indigo-500 mt-3">✅ Diselesaikan pada {{ $keluhan->resolved_at->format('d M Y, H:i') }}</p>
                @endif
            @else
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-slate-500 text-sm">Keluhan Anda sedang dalam antrian. Tim kami akan segera merespons.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
