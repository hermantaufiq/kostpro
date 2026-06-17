@extends('layouts.app')
@section('title', 'Tagihan Saya - KosPro')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0"
                 style="background: linear-gradient(135deg, #fb923c, #ea580c);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tagihan Saya</h1>
                <p class="text-sm text-slate-500">Riwayat dan status semua tagihan kos Anda</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm border border-emerald-200 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if($semuaTagihan->isEmpty())
    {{-- Empty State --}}
    <div class="bg-white rounded-3xl p-12 shadow-soft border border-slate-100 text-center">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5"
             style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Tagihan</h3>
        <p class="text-slate-500 max-w-sm mx-auto">Tagihan akan muncul di sini setelah penyewaan kamar Anda disetujui oleh admin.</p>
        <a href="{{ url('/kamar') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            Cari Kamar Sekarang →
        </a>
    </div>

    @else
    {{-- Summary Stats --}}
    @php
        $totalUnpaid = $semuaTagihan->whereIn('status', ['unpaid','overdue'])->sum('total_tagihan');
        $countUnpaid = $semuaTagihan->whereIn('status', ['unpaid','overdue'])->count();
        $countPaid   = $semuaTagihan->where('status', 'paid')->count();
    @endphp
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-soft text-center">
            <p class="text-2xl font-black text-slate-900">{{ $semuaTagihan->total() }}</p>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mt-0.5">Total Tagihan</p>
        </div>
        <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 text-center">
            <p class="text-2xl font-black text-amber-600">{{ $countUnpaid }}</p>
            <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide mt-0.5">Belum Lunas</p>
        </div>
        <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200 text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $countPaid }}</p>
            <p class="text-xs text-emerald-500 font-semibold uppercase tracking-wide mt-0.5">Sudah Lunas</p>
        </div>
    </div>

    {{-- Tagihan List --}}
    <div class="space-y-3">
        @foreach($semuaTagihan as $tagihan)
        @php
            $statusVal = $tagihan->status->value;
            if ($statusVal === 'overdue') {
                $dotColor   = '#ef4444';
                $badgeBg    = '#fef2f2';
                $badgeText  = '#b91c1c';
                $badgeBorder= '#fecaca';
                $label      = 'Terlambat';
                $iconStyle  = 'background: #fef2f2; color: #ef4444;';
            } elseif ($statusVal === 'paid') {
                $dotColor   = '#10b981';
                $badgeBg    = '#ecfdf5';
                $badgeText  = '#065f46';
                $badgeBorder= '#a7f3d0';
                $label      = 'Lunas';
                $iconStyle  = 'background: #ecfdf5; color: #10b981;';
            } else {
                $dotColor   = '#f59e0b';
                $badgeBg    = '#fffbeb';
                $badgeText  = '#92400e';
                $badgeBorder= '#fde68a';
                $label      = 'Belum Bayar';
                $iconStyle  = 'background: #fffbeb; color: #f59e0b;';
            }
            $isActionable = in_array($statusVal, ['unpaid','overdue']);
        @endphp

        <a href="{{ route('user.tagihan.show', $tagihan->id) }}"
           class="block bg-white rounded-2xl border border-slate-100 shadow-soft hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-5">
            <div class="flex items-center gap-4">
                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="{{ $iconStyle }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                        <span class="text-sm font-bold text-slate-800">{{ $tagihan->kode_tagihan }}</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full border"
                              style="background: {{ $badgeBg }}; color: {{ $badgeText }}; border-color: {{ $badgeBorder }};">
                            {{ $label }}
                        </span>
                        @if($tagihan->jumlah_denda > 0)
                        <span class="text-xs font-bold bg-red-50 text-red-500 px-2 py-0.5 rounded-full">
                            +Denda
                        </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 truncate">
                        Periode: {{ \Carbon\Carbon::createFromDate($tagihan->periode_tahun, $tagihan->periode_bulan, 1)->translatedFormat('F Y') }}
                        &bull; JT: {{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}
                    </p>
                </div>

                {{-- Jumlah & Aksi --}}
                <div class="text-right shrink-0">
                    <p class="text-base font-extrabold text-slate-900">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                    @if($tagihan->jumlah_denda > 0)
                    <p class="text-xs text-red-500">+Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}</p>
                    @endif
                    <span class="mt-1 inline-block text-xs font-semibold px-2.5 py-1 rounded-lg text-white"
                          style="background: {{ $isActionable ? '#4f46e5' : '#94a3b8' }};">
                        {{ $isActionable ? 'Bayar →' : 'Detail →' }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6 flex justify-center">
        {{ $semuaTagihan->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
