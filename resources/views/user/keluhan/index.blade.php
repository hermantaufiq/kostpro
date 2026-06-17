@extends('layouts.app')
@section('title', 'Keluhan Saya - KosPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0"
                 style="background: linear-gradient(135deg, #f87171, #dc2626);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Keluhan Saya</h1>
                <p class="text-sm text-slate-500">Riwayat laporan & keluhan Anda kepada manajemen</p>
            </div>
        </div>
        <a href="{{ route('user.keluhan.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:-translate-y-0.5"
           style="background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 4px 12px rgba(99,102,241,0.35);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if($keluhans->isEmpty())
    {{-- Empty State --}}
    <div class="bg-white rounded-3xl p-12 shadow-soft border border-slate-100 text-center">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5"
             style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Keluhan</h3>
        <p class="text-slate-500 max-w-sm mx-auto mb-6">Jika ada masalah pada fasilitas kamar atau pelayanan, jangan ragu untuk melaporkannya.</p>
        <a href="{{ route('user.keluhan.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
           style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Laporkan Sekarang
        </a>
    </div>

    @else
    <div class="space-y-3">
        @foreach($keluhans as $keluhan)
        @php
            $statusVal = $keluhan->status->value;
            if ($statusVal === 'menunggu') {
                $sBg = '#fffbeb'; $sText = '#92400e'; $sBorder = '#fde68a'; $sIcon = '⏳';
            } elseif ($statusVal === 'diproses') {
                $sBg = '#eff6ff'; $sText = '#1e40af'; $sBorder = '#bfdbfe'; $sIcon = '🔄';
            } elseif ($statusVal === 'selesai') {
                $sBg = '#ecfdf5'; $sText = '#065f46'; $sBorder = '#a7f3d0'; $sIcon = '✅';
            } else {
                $sBg = '#fef2f2'; $sText = '#991b1b'; $sBorder = '#fecaca'; $sIcon = '❌';
            }

            $prioritasVal = $keluhan->prioritas->value;
            if ($prioritasVal === 'tinggi') {
                $pBg = '#fef2f2'; $pText = '#b91c1c';
            } elseif ($prioritasVal === 'sedang') {
                $pBg = '#fffbeb'; $pText = '#92400e';
            } else {
                $pBg = '#f8fafc'; $pText = '#64748b';
            }
        @endphp

        <a href="{{ route('user.keluhan.show', $keluhan) }}"
           class="block bg-white rounded-2xl border border-slate-100 shadow-soft hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    {{-- Badges --}}
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border"
                              style="background: {{ $sBg }}; color: {{ $sText }}; border-color: {{ $sBorder }};">
                            {{ $sIcon }} {{ $keluhan->status->label() }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                              style="background: {{ $pBg }}; color: {{ $pText }};">
                            {{ ucfirst($prioritasVal) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-1 truncate">{{ $keluhan->judul }}</h3>
                    <p class="text-slate-400 text-sm line-clamp-2">{{ $keluhan->deskripsi }}</p>

                    @if($keluhan->tanggapan_admin)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-1">💬 Tanggapan Admin:</p>
                        <p class="text-sm text-slate-600 line-clamp-2 italic">"{{ $keluhan->tanggapan_admin }}"</p>
                    </div>
                    @endif
                </div>

                {{-- Date & Room --}}
                <div class="text-right shrink-0">
                    <p class="text-xs font-medium text-slate-400">{{ $keluhan->created_at->format('d M Y') }}</p>
                    @if($keluhan->kamar)
                    <p class="text-xs text-slate-500 mt-1 bg-slate-50 px-2 py-0.5 rounded-lg">{{ $keluhan->kamar->nama }}</p>
                    @endif
                    <svg class="w-4 h-4 text-slate-300 ml-auto mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $keluhans->links() }}
    </div>
    @endif

</div>
@endsection
