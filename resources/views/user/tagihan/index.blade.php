@extends('layouts.app')
@section('title', 'Tagihan Saya - KosPro')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Tagihan Saya</h1>
            <p class="text-slate-500 mt-1">Riwayat dan status semua tagihan kos Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($semuaTagihan->isEmpty())
        <div class="bg-white rounded-2xl p-10 shadow-soft border border-slate-100 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Tagihan</h3>
            <p class="text-slate-500">Tagihan akan muncul di sini setelah penyewaan kamar Anda disetujui.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($semuaTagihan as $tagihan)
                @php
                    $statusStyle = match($tagihan->status->value) {
                        'unpaid'  => 'bg-amber-50 text-amber-700 border-amber-200',
                        'paid'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'overdue' => 'bg-red-50 text-red-700 border-red-200',
                        default   => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                    $statusLabel = match($tagihan->status->value) {
                        'unpaid'  => 'Belum Bayar',
                        'paid'    => 'Lunas',
                        'overdue' => 'Terlambat',
                        default   => ucfirst($tagihan->status->value),
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-card-hover transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $tagihan->status->value === 'overdue' ? 'bg-red-50 text-red-600' : ($tagihan->status->value === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600') }} flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $tagihan->kode_tagihan }}</h3>
                            <p class="text-sm text-slate-500">
                                Periode: {{ \Carbon\Carbon::createFromDate($tagihan->periode_tahun, $tagihan->periode_bulan, 1)->format('F Y') }}
                                &bull; Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Total Tagihan</p>
                            <p class="text-lg font-extrabold text-slate-900">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                            @if($tagihan->jumlah_denda > 0)
                                <p class="text-xs text-red-500">+Denda Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}</p>
                            @endif
                        </div>

                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold border {{ $statusStyle }} text-center w-28">
                            {{ $statusLabel }}
                        </span>

                        <a href="{{ route('user.tagihan.show', $tagihan->id) }}" class="btn-primary text-sm py-2 px-4 whitespace-nowrap">
                            {{ in_array($tagihan->status->value, ['unpaid','overdue']) ? 'Bayar Sekarang' : 'Lihat Detail' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            {{ $semuaTagihan->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
