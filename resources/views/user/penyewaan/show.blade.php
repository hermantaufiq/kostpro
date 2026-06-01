@extends('layouts.app')
@section('title', 'Detail Penyewaan - KosPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-4 flex items-center text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900 font-medium">Detail Penyewaan</span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-sm text-slate-500 mb-1">Kode Penyewaan</p>
                <h1 class="text-xl font-bold text-slate-900">{{ $penyewaan->kode_penyewaan }}</h1>
            </div>
            <div>
                @php
                    $statusColor = match($penyewaan->status->value) {
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'approved' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'active' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'rejected' => 'bg-red-50 text-red-600 border-red-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                @endphp
                <span class="px-4 py-2 rounded-xl text-sm font-bold border {{ $statusColor }}">
                    Status: {{ ucfirst($penyewaan->status->value) }}
                </span>
            </div>
        </div>

        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Room Info -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Informasi Kamar</h3>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex gap-4">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-200 shrink-0">
                        @if($penyewaan->kamar->thumbnail)
                            <img src="{{ $penyewaan->kamar->thumbnail->foto_url }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $penyewaan->kamar->nama }}</h4>
                        <p class="text-sm text-slate-500">Tipe {{ ucfirst($penyewaan->kamar->tipe->value) }}</p>
                        <a href="{{ route('kamar.show', $penyewaan->kamar_id) }}" class="text-xs text-indigo-600 hover:underline mt-1 inline-block">Lihat Kamar</a>
                    </div>
                </div>

                @if($penyewaan->status->value == 'rejected' && $penyewaan->catatan_admin)
                    <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-100 text-sm text-red-700">
                        <strong>Alasan Penolakan:</strong><br>
                        {{ $penyewaan->catatan_admin }}
                    </div>
                @endif
            </div>

            <!-- Booking Info -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Detail Pengajuan</h3>
                
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">Tanggal Masuk</span>
                    <span class="text-slate-900 font-medium">{{ $penyewaan->tanggal_masuk->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">Durasi</span>
                    <span class="text-slate-900 font-medium">{{ $penyewaan->durasi_bulan }} Bulan</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">Harga Sewa / Bulan</span>
                    <span class="text-slate-900 font-medium">Rp {{ number_format($penyewaan->harga_bulanan_snapshot, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">Deposit (Sekali Bayar)</span>
                    <span class="text-slate-900 font-medium">Rp {{ number_format($penyewaan->deposit_amount, 0, ',', '.') }}</span>
                </div>
                
                @if($penyewaan->catatan_penyewa)
                <div class="pt-2">
                    <span class="text-slate-500 text-sm block mb-1">Catatan Anda:</span>
                    <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        {{ $penyewaan->catatan_penyewa }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        @if($penyewaan->status->value == 'approved' && !$penyewaan->deposit_paid)
        <div class="bg-indigo-50 p-6 border-t border-indigo-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-indigo-900 text-lg">Penyewaan Disetujui!</h4>
                <p class="text-sm text-indigo-700 mt-1">Silakan lakukan pembayaran tagihan pertama dan deposit untuk mengaktifkan sewa Anda.</p>
            </div>
            <a href="{{ route('user.tagihan.index') }}" class="btn-primary whitespace-nowrap">
                Lihat Tagihan
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
