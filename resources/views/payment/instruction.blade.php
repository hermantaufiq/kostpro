@extends('layouts.app')
@section('title', 'Instruksi Pembayaran - KosPro')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm text-slate-500">
        <a href="{{ route('user.tagihan.index') }}" class="hover:text-indigo-600">Tagihan</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900">Instruksi Pembayaran</span>
    </div>

    {{-- Alert: Waktu Kadaluarsa --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 mb-6">
        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-semibold text-amber-800">Selesaikan pembayaran sebelum:</p>
            <p class="text-sm text-amber-700 font-mono font-bold">{{ $expiredAt->format('d M Y, H:i') }} WIB</p>
        </div>
        <div class="ml-auto text-right">
            <p class="text-xs text-amber-600">Sisa waktu</p>
            <p class="text-sm font-bold text-amber-800" id="countdown">23:59:59</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Panel Utama: Instruksi --}}
        <div class="lg:col-span-3 space-y-5">

            @if($metode === 'virtual_account')
            {{-- ===== VIRTUAL ACCOUNT ===== --}}
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <p class="text-indigo-200 text-xs font-medium">Metode Pembayaran</p>
                    <h2 class="text-white font-bold text-lg">Transfer Virtual Account</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-5">Pilih bank dan transfer ke nomor Virtual Account berikut. Nomor ini unik untuk tagihan Anda dan hanya berlaku sekali.</p>

                    {{-- Tabs Bank --}}
                    <div class="flex gap-2 mb-5 flex-wrap" id="bank-tabs">
                        @foreach($bankOptions as $i => $bank)
                        <button onclick="switchBank({{ $i }})"
                            id="tab-{{ $i }}"
                            class="bank-tab px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all
                                {{ $i === 0 ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-200 text-slate-500 hover:border-indigo-300' }}">
                            {{ $bank['kode'] }}
                        </button>
                        @endforeach
                    </div>

                    {{-- VA Info per Bank --}}
                    @foreach($bankOptions as $i => $bank)
                    <div id="bank-content-{{ $i }}" class="bank-content {{ $i !== 0 ? 'hidden' : '' }}">
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-xs"
                                        style="background-color: {{ $bank['logo_color'] }}">
                                        {{ $bank['kode'] }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Bank {{ $bank['kode'] }}</p>
                                        <p class="text-xs font-semibold text-slate-700">Virtual Account</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 mb-1">Nomor Virtual Account</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-2xl font-black tracking-widest text-slate-900 font-mono" id="va-number-{{ $i }}">
                                        {{ chunk_split($bank['va'], 4, ' ') }}
                                    </p>
                                    <button onclick="copyToClipboard('{{ $bank['va'] }}', this)"
                                        class="flex items-center gap-1 text-xs text-indigo-600 font-semibold hover:text-indigo-700 border border-indigo-200 rounded-lg px-2 py-1 transition-all hover:bg-indigo-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Salin
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Jumlah Transfer (harus tepat)</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-xl font-extrabold text-indigo-600" id="jumlah-transfer">
                                        Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                    </p>
                                    <button onclick="copyToClipboard('{{ $tagihan->total_tagihan }}', this)"
                                        class="flex items-center gap-1 text-xs text-indigo-600 font-semibold hover:text-indigo-700 border border-indigo-200 rounded-lg px-2 py-1 transition-all hover:bg-indigo-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Cara Pembayaran ATM --}}
                        <div class="mt-4">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Cara Bayar via ATM {{ $bank['kode'] }}</p>
                            <ol class="space-y-2">
                                @if($bank['kode'] === 'BCA')
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Masukkan kartu ATM dan PIN Anda</li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih menu <strong>Transfer → Virtual Account</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Masukkan nomor VA: <strong class="font-mono">{{ $bank['va'] }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Pastikan nominal transfer <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">5</span>Konfirmasi dan selesaikan transaksi</li>
                                @elseif($bank['kode'] === 'BNI')
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Masukkan kartu ATM dan PIN Anda</li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih menu <strong>Transfer → Rekening Tabungan</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Masukkan nomor VA: <strong class="font-mono">{{ $bank['va'] }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Transfer sejumlah <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">5</span>Konfirmasi dan selesaikan transaksi</li>
                                @elseif($bank['kode'] === 'BRI')
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Masukkan kartu ATM dan PIN Anda</li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih menu <strong>Transaksi Lain → Pembayaran → BRIVA</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Masukkan nomor BRIVA: <strong class="font-mono">{{ $bank['va'] }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Konfirmasi nominal <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">5</span>Konfirmasi dan selesaikan transaksi</li>
                                @else
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Masukkan kartu ATM dan PIN Anda</li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih <strong>Bayar/Beli → Multi Payment → Kode Perusahaan: 88608</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Masukkan nomor VA: <strong class="font-mono">{{ $bank['va'] }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Konfirmasi nominal <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong></li>
                                <li class="flex gap-3 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">5</span>Konfirmasi dan selesaikan transaksi</li>
                                @endif
                            </ol>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @elseif($metode === 'qris')
            {{-- ===== QRIS ===== --}}
            @php
                $qrisData = 'KOSPRO|' . $pembayaran->kode_pembayaran . '|IDR|' . $tagihan->total_tagihan . '|KosPro Kost Payment';
                $qrisUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&ecc=M&data=' . urlencode($qrisData);
            @endphp
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <p class="text-indigo-200 text-xs font-medium">Metode Pembayaran</p>
                    <h2 class="text-white font-bold text-lg">QRIS — Scan & Pay</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-5 text-center">Scan QR Code ini menggunakan aplikasi GoPay, OVO, DANA, ShopeePay, atau BCA Mobile.</p>

                    {{-- Logo QRIS + QR nyata --}}
                    <div class="flex justify-center mb-4">
                        <div class="relative">
                            {{-- Border QRIS bergaya --}}
                            <div class="bg-white p-3 rounded-2xl border-2 border-slate-900 shadow-lg inline-block">
                                {{-- Header QRIS --}}
                                <div class="flex items-center justify-between mb-2 px-1">
                                    <span class="text-xs font-black text-slate-900 tracking-tight">QRIS</span>
                                    <span class="text-xs text-slate-500 font-mono">{{ substr($pembayaran->kode_pembayaran, -6) }}</span>
                                </div>
                                {{-- Real QR Code dari API --}}
                                <img src="{{ $qrisUrl }}"
                                     alt="QRIS KosPro {{ $pembayaran->kode_pembayaran }}"
                                     class="w-52 h-52 rounded-xl"
                                     onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=KOSPRO-PAYMENT'">
                                {{-- Footer --}}
                                <div class="flex items-center justify-center gap-2 mt-2">
                                    <div class="w-4 h-4 rounded bg-red-500"></div>
                                    <div class="w-4 h-4 rounded bg-white border border-slate-300"></div>
                                    <span class="text-xs font-bold text-slate-700">Powered by Xendit</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Logo E-Wallet yang didukung --}}
                    <div class="flex items-center justify-center gap-2 mb-5">
                        @foreach([['GoPay','#00AED6'],['OVO','#4C3494'],['DANA','#118EEA'],['ShopeePay','#EE4D2D'],['LinkAja','#E82529']] as $w)
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-black" style="background-color:{{ $w[1] }}" title="{{ $w[0] }}">
                            {{ substr($w[0],0,1) }}
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-indigo-50 rounded-xl p-4 flex items-center justify-between mb-5">
                        <div>
                            <p class="text-xs text-slate-500">Total yang dibayar</p>
                            <p class="text-2xl font-extrabold text-indigo-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                        </div>
                        <button onclick="copyToClipboard('{{ $tagihan->total_tagihan }}', this)"
                            class="flex items-center gap-1 text-xs text-indigo-600 font-semibold border border-indigo-200 rounded-lg px-2 py-1.5 hover:bg-indigo-50 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Salin
                        </button>
                    </div>

                    <div class="space-y-2">
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Cara Pembayaran</p>
                        <p class="flex gap-2 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Buka aplikasi GoPay, OVO, DANA, atau dompet digital lainnya</p>
                        <p class="flex gap-2 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih menu <strong>Scan QR / Pay QR</strong></p>
                        <p class="flex gap-2 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Arahkan kamera ke QR Code di atas hingga terbaca</p>
                        <p class="flex gap-2 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Pastikan nominal <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong> sudah benar</p>
                        <p class="flex gap-2 text-sm text-slate-600"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">5</span>Konfirmasi dengan PIN / biometrik dan transaksi selesai</p>
                    </div>
                </div>
            </div>

            @else
            {{-- ===== E-WALLET ===== --}}
            @php
                $wallets = [
                    ['nama' => 'GoPay',     'warna' => '#00AED6', 'teks' => '#fff', 'singkat' => 'GO'],
                    ['nama' => 'OVO',       'warna' => '#4C3494', 'teks' => '#fff', 'singkat' => 'OV'],
                    ['nama' => 'DANA',      'warna' => '#118EEA', 'teks' => '#fff', 'singkat' => 'DA'],
                    ['nama' => 'ShopeePay','warna' => '#EE4D2D', 'teks' => '#fff', 'singkat' => 'SP'],
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <p class="text-green-100 text-xs font-medium">Metode Pembayaran</p>
                    <h2 class="text-white font-bold text-lg">E-Wallet</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-4">Pilih dompet digital Anda, lalu scan QR code yang muncul atau salin kode pembayaran.</p>

                    {{-- Tabs E-Wallet --}}
                    <div class="flex gap-2 mb-5 flex-wrap">
                        @foreach($wallets as $wi => $w)
                        <button onclick="switchWallet({{ $wi }})"
                            id="wtab-{{ $wi }}"
                            class="wallet-tab flex items-center gap-2 px-3 py-2 rounded-xl border-2 text-sm font-bold transition-all
                                {{ $wi === 0 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-500 hover:border-emerald-300' }}">
                            <span class="w-5 h-5 rounded flex items-center justify-center text-white font-black text-xs" style="background-color:{{ $w['warna'] }}">{{ $w['singkat'] }}</span>
                            {{ $w['nama'] }}
                        </button>
                        @endforeach
                    </div>

                    {{-- Content per Wallet --}}
                    @foreach($wallets as $wi => $w)
                    @php
                        $ewData = $w['nama'] . '|KOSPRO|' . $pembayaran->kode_pembayaran . '|IDR' . $tagihan->total_tagihan;
                        $ewQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&ecc=M&data=' . urlencode($ewData);
                    @endphp
                    <div id="wcontent-{{ $wi }}" class="wallet-content {{ $wi !== 0 ? 'hidden' : '' }}">
                        <div class="flex flex-col items-center mb-4">
                            <div class="bg-white p-3 rounded-2xl border-2 shadow-md mb-3" style="border-color: {{ $w['warna'] }}">
                                <div class="flex items-center justify-between mb-2 px-1">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white font-black text-xs" style="background-color:{{ $w['warna'] }}">{{ $w['singkat'] }}</div>
                                    <span class="text-xs font-bold" style="color:{{ $w['warna'] }}">{{ $w['nama'] }}</span>
                                </div>
                                <img src="{{ $ewQrUrl }}"
                                     alt="QR {{ $w['nama'] }} {{ $pembayaran->kode_pembayaran }}"
                                     class="w-48 h-48 rounded-xl">
                            </div>
                            <p class="text-xs text-slate-500">Scan dengan aplikasi <strong>{{ $w['nama'] }}</strong></p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs text-slate-500">Total Pembayaran</p>
                                <p class="text-xl font-extrabold text-indigo-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                            </div>
                            <button onclick="copyToClipboard('{{ $tagihan->total_tagihan }}', this)"
                                class="flex items-center gap-1 text-xs text-indigo-600 font-semibold border border-indigo-200 rounded-lg px-2 py-1.5 hover:bg-indigo-50">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Salin
                            </button>
                        </div>

                        <div class="space-y-2 text-sm">
                            <p class="flex gap-2 text-slate-600"><span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">1</span>Buka aplikasi <strong>{{ $w['nama'] }}</strong> di HP Anda</p>
                            <p class="flex gap-2 text-slate-600"><span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">2</span>Pilih <strong>Scan QR / Pay</strong> di halaman utama</p>
                            <p class="flex gap-2 text-slate-600"><span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">3</span>Arahkan kamera ke QR di atas</p>
                            <p class="flex gap-2 text-slate-600"><span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">4</span>Konfirmasi nominal <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong> lalu bayar</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Panel Kanan: Ringkasan --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Ringkasan Tagihan --}}
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Kode Tagihan</span>
                        <span class="font-mono font-bold text-slate-700 text-xs">{{ $tagihan->kode_tagihan }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Kamar</span>
                        <span class="font-semibold text-slate-800 text-xs text-right max-w-[120px]">{{ $tagihan->penyewaan->kamar->nama }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Periode</span>
                        <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::createFromDate($tagihan->periode_tahun, $tagihan->periode_bulan, 1)->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Sewa Bulanan</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</span>
                    </div>
                    @if($tagihan->jumlah_denda > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-500">Denda</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="border-t border-slate-100 pt-3 flex justify-between">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="font-extrabold text-indigo-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Kode Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Info Transaksi</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Kode Pembayaran</p>
                        <p class="font-mono font-bold text-slate-900">{{ $pembayaran->kode_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Status</p>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Menunggu Pembayaran</span>
                    </div>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <a href="{{ route('user.tagihan.show', $tagihan->id) }}"
               class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold text-sm hover:border-indigo-300 hover:text-indigo-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Tagihan
            </a>

            <p class="text-xs text-center text-slate-400">Status akan otomatis diperbarui setelah pembayaran berhasil.</p>
        </div>
    </div>
</div>

{{-- Countdown Timer Script --}}
<script>
function switchBank(index) {
    document.querySelectorAll('.bank-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.bank-tab').forEach(el => {
        el.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
        el.classList.add('border-slate-200', 'text-slate-500');
    });
    document.getElementById('bank-content-' + index).classList.remove('hidden');
    const activeTab = document.getElementById('tab-' + index);
    activeTab.classList.remove('border-slate-200', 'text-slate-500');
    activeTab.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
}

function switchWallet(index) {
    document.querySelectorAll('.wallet-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.wallet-tab').forEach(el => {
        el.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
        el.classList.add('border-slate-200', 'text-slate-500');
    });
    document.getElementById('wcontent-' + index).classList.remove('hidden');
    const activeTab = document.getElementById('wtab-' + index);
    activeTab.classList.remove('border-slate-200', 'text-slate-500');
    activeTab.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
}

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '✓ Disalin!';
        btn.classList.add('text-emerald-600', 'border-emerald-300', 'bg-emerald-50');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('text-emerald-600', 'border-emerald-300', 'bg-emerald-50');
        }, 2000);
    });
}

// Countdown Timer
const expiredAt = new Date('{{ $expiredAt->toIso8601String() }}');
function updateCountdown() {
    const now = new Date();
    const diff = expiredAt - now;
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'Kedaluwarsa';
        return;
    }
    const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
    const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
    const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
    document.getElementById('countdown').textContent = `${h}:${m}:${s}`;
}
updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endsection
