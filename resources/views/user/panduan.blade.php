@extends('layouts.app')
@section('title', 'Panduan & Kontak Darurat - KostPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-4 flex items-center text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900 font-medium">Buku Panduan</span>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Panduan & Tata Tertib Kos</h1>
        <p class="text-slate-500 mt-2">Mohon baca dan patuhi tata tertib berikut demi kenyamanan bersama.</p>
    </div>

    <!-- Kontak Darurat -->
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-bold text-rose-900 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            Kontak Darurat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm text-slate-500 font-semibold mb-1">Pengurus Kos (24 Jam)</p>
                <p class="text-lg font-bold text-slate-900">0812-3456-7890</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm text-slate-500 font-semibold mb-1">Polsek Terdekat</p>
                <p class="text-lg font-bold text-slate-900">(021) 110</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-sm text-slate-500 font-semibold mb-1">Rumah Sakit / Ambulans</p>
                <p class="text-lg font-bold text-slate-900">118 / 119</p>
            </div>
        </div>
    </div>

    <!-- Tata Tertib -->
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="p-6 md:p-8 space-y-6 text-slate-700">
            <section>
                <h3 class="text-lg font-bold text-slate-900 mb-2">1. Jam Malam & Tamu</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Gerbang utama dikunci pada pukul 23.00 WIB.</li>
                    <li>Tamu dilarang menginap tanpa melapor ke pengurus kos terlebih dahulu.</li>
                    <li>Tamu lawan jenis dilarang masuk ke dalam kamar (hanya di ruang tamu/lobi).</li>
                </ul>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-slate-900 mb-2">2. Kebersihan & Kenyamanan</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Dilarang membuang pembalut atau tisu tebal ke dalam kloset.</li>
                    <li>Jaga ketenangan pada malam hari (setelah jam 22.00) agar tidak mengganggu tetangga kamar.</li>
                    <li>Sampah kamar harap diletakkan di tempat sampah umum di depan lorong.</li>
                </ul>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-slate-900 mb-2">3. Keamanan Barang</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Pastikan pintu kamar dan lemari selalu terkunci jika Anda bepergian.</li>
                    <li>Pengelola kos tidak bertanggung jawab atas kehilangan barang berharga di dalam kamar.</li>
                </ul>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-slate-900 mb-2">4. Pembayaran Sewa</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Tagihan akan muncul pada tanggal 1 setiap bulannya.</li>
                    <li>Jatuh tempo pembayaran adalah tanggal 10. Jika terlambat, akan dikenakan denda sesuai kontrak.</li>
                    <li>Pembayaran tepat waktu akan memberikan Anda <strong class="text-amber-600">Poin KostPro</strong> yang bisa ditukar dengan diskon.</li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection
