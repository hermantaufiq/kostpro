@extends('layouts.app')
@section('title', 'Keluhan Saya - KosPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Keluhan Saya</h1>
            <p class="text-slate-500 mt-1">Riwayat laporan & keluhan Anda kepada manajemen.</p>
        </div>
        <a href="{{ route('user.keluhan.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Keluhan
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if($keluhans->isEmpty())
    <div class="bg-white rounded-2xl p-10 shadow-soft border border-slate-100 text-center">
        <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Keluhan</h3>
        <p class="text-slate-500 mb-6">Jika ada masalah pada fasilitas kamar atau pelayanan, jangan ragu untuk melaporkannya.</p>
        <a href="{{ route('user.keluhan.create') }}" class="btn-primary inline-flex">Laporkan Sekarang</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($keluhans as $keluhan)
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
        <a href="{{ route('user.keluhan.show', $keluhan) }}" class="block bg-white rounded-2xl shadow-soft border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all duration-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $statusColor }}">
                            {{ $keluhan->status->label() }}
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $prioritasColor }}">
                            {{ $keluhan->prioritas->label() }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $keluhan->judul }}</h3>
                    <p class="text-slate-500 text-sm line-clamp-2">{{ $keluhan->deskripsi }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs text-slate-400">{{ $keluhan->created_at->format('d M Y') }}</p>
                    @if($keluhan->kamar)
                    <p class="text-xs text-slate-500 mt-1">Kamar: {{ $keluhan->kamar->nama }}</p>
                    @endif
                </div>
            </div>
            @if($keluhan->tanggapan_admin)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-500 mb-1">💬 Tanggapan Admin:</p>
                <p class="text-sm text-slate-700 line-clamp-2">{{ $keluhan->tanggapan_admin }}</p>
            </div>
            @endif
        </a>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $keluhans->links() }}
    </div>
    @endif
</div>
@endsection
