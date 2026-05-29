@extends('layouts.app')
@section('title', 'Detail Tagihan - KosPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center text-sm text-slate-500">
        <a href="{{ route('user.tagihan.index') }}" class="hover:text-indigo-600">Tagihan</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900">{{ $tagihan->kode_tagihan }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Invoice Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 md:p-8 text-white">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-indigo-200 text-sm font-medium mb-1">KosPro Invoice</p>
                        <h2 class="text-2xl font-extrabold">{{ $tagihan->kode_tagihan }}</h2>
                    </div>
                    @php
                        $statusLabel = match($tagihan->status->value) {
                            'unpaid'  => 'Belum Bayar',
                            'paid'    => 'Lunas',
                            'overdue' => 'Terlambat',
                            default   => ucfirst($tagihan->status->value),
                        };
                        $statusStyle = match($tagihan->status->value) {
                            'paid'    => 'bg-emerald-400/20 text-emerald-200',
                            'overdue' => 'bg-red-400/20 text-red-200',
                            default   => 'bg-white/20 text-white',
                        };
                    @endphp
                    <span class="px-3 py-1.5 rounded-xl text-sm font-bold {{ $statusStyle }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-indigo-200 text-xs mb-1">Kamar</p>
                        <p class="font-semibold text-sm">{{ $tagihan->penyewaan->kamar->nama }}</p>
                    </div>
                    <div>
                        <p class="text-indigo-200 text-xs mb-1">Periode</p>
                        <p class="font-semibold text-sm">
                            {{ \Carbon\Carbon::createFromDate($tagihan->periode_tahun, $tagihan->periode_bulan, 1)->format('F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-indigo-200 text-xs mb-1">Tanggal Tagihan</p>
                        <p class="font-semibold text-sm">{{ $tagihan->tanggal_tagihan->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-indigo-200 text-xs mb-1">Jatuh Tempo</p>
                        <p class="font-semibold text-sm {{ $tagihan->status->value === 'overdue' ? 'text-red-200' : '' }}">
                            {{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                            <th class="pb-3">Deskripsi</th>
                            <th class="pb-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="py-4">
                            <td class="py-4 text-slate-700 font-medium">Biaya Sewa Bulanan</td>
                            <td class="py-4 text-slate-900 font-semibold text-right">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        @if($tagihan->jumlah_denda > 0)
                        <tr>
                            <td class="py-4 text-red-600 font-medium">Denda Keterlambatan</td>
                            <td class="py-4 text-red-600 font-semibold text-right">Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <td class="pt-4 text-base font-bold text-slate-900">Total Pembayaran</td>
                            <td class="pt-4 text-xl font-extrabold text-indigo-600 text-right">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Action Panel -->
        <div class="space-y-4">
            @if(in_array($tagihan->status->value, ['unpaid', 'overdue']))
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Bayar Tagihan</h3>
                    <p class="text-sm text-slate-500 mb-6">Pilih metode pembayaran yang Anda inginkan. Transaksi Anda aman & terenkripsi.</p>

                    <form action="{{ route('payment.create', $tagihan->id) }}" method="POST">
                        @csrf
                        <div class="space-y-3 mb-6">
                            <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-indigo-400 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="metode" value="virtual_account" class="text-indigo-600" checked>
                                <div>
                                    <p class="font-semibold text-sm text-slate-800">Virtual Account</p>
                                    <p class="text-xs text-slate-500">BCA, BNI, BRI, Mandiri</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-indigo-400 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="metode" value="qris" class="text-indigo-600">
                                <div>
                                    <p class="font-semibold text-sm text-slate-800">QRIS</p>
                                    <p class="text-xs text-slate-500">Semua dompet digital</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-indigo-400 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="metode" value="ewallet" class="text-indigo-600">
                                <div>
                                    <p class="font-semibold text-sm text-slate-800">E-Wallet</p>
                                    <p class="text-xs text-slate-500">OVO, DANA, GoPay, ShopeePay</p>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Lanjutkan Pembayaran
                        </button>
                    </form>

                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400 justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Powered by Xendit — Aman & Terpercaya
                    </div>
                </div>
            @elseif($tagihan->status->value === 'paid')
                <div class="bg-white rounded-2xl shadow-soft border border-emerald-100 p-6 text-center">
                    <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-bold text-emerald-700 mb-1">Tagihan Lunas</h3>
                    <p class="text-sm text-slate-500">Dibayar pada: {{ optional($tagihan->tanggal_bayar)->format('d M Y H:i') ?? '-' }}</p>
                </div>
            @endif

            @if($tagihan->pembayaran)
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-5">
                    <h4 class="font-bold text-slate-900 text-sm mb-3">Info Pembayaran</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kode Bayar</span>
                            <span class="font-mono font-bold text-slate-900">{{ $tagihan->pembayaran->kode_pembayaran }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode</span>
                            <span class="text-slate-900">{{ ucwords(str_replace('_', ' ', $tagihan->pembayaran->metode?->value ?? '-')) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status</span>
                            <span class="text-slate-900 font-semibold">{{ ucfirst($tagihan->pembayaran->status->value) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
