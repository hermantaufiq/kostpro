@extends('layouts.app')
@section('title', $room->nama . ' - KostPro')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-4 flex items-center text-sm text-slate-500">
        <a href="{{ route('kamar.index') }}" class="hover:text-indigo-600">Kamar</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900 font-medium">{{ $room->nama }}</span>
    </div>

    @php
        $isMaintenance = $room->status?->value === 'maintenance';
        $isFullStatus  = in_array($room->status?->value, ['terisi', 'reserved']);
        $isAvail = !$isMaintenance && !$isFullStatus && $room->sisa_slot > 0;
    @endphp

    @if($isMaintenance)
    <div class="mb-6 flex items-start gap-4 bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="font-bold text-amber-800 text-sm">Kamar Sedang Dalam Perbaikan (Maintenance)</p>
            <p class="text-amber-700 text-sm mt-0.5">Kamar ini sementara tidak dapat disewa. Tim kami sedang melakukan perawatan agar kamar kembali dalam kondisi terbaik untuk Anda. Silakan pantau terus atau hubungi kami untuk info lebih lanjut.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Gallery + 3D Tour Tabbed Section -->
            @php
                $fotos = $room->fotoKamar;
                $hasPhotos = $fotos->isNotEmpty();
                $has360 = !empty($room->foto_360);
                
                // Prioritas Tab Default: 360 -> Foto -> 3D
                $defaultTab = $has360 ? '360' : ($hasPhotos ? 'gallery' : '3d');
            @endphp
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden"
                 x-data="room3DTab('{{ $defaultTab }}', {{ $hasPhotos ? 'true' : 'false' }}, {{ $has360 ? 'true' : 'false' }})"
                 x-init="init()">

                <!-- Tab Header -->
                <div class="flex border-b border-slate-100">
                    {{-- Tab Foto hanya tampil jika ada foto --}}
                    @if($hasPhotos)
                    <button @click="activeTab = 'gallery'"
                        :class="activeTab === 'gallery' ? 'border-b-2 border-indigo-500 text-indigo-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-3.5 text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Foto Kamar
                        <span class="text-slate-400 text-xs">({{ $fotos->count() }})</span>
                    </button>
                    @endif
                    {{-- Tab 360 Panorama (opsional) --}}
                    @if($has360)
                    <button @click="switchTo360()"
                        :class="activeTab === '360' ? 'border-b-2 border-indigo-500 text-indigo-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-3.5 text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Tur 360°
                        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-1.5 py-0.5 rounded-full">REAL</span>
                    </button>
                    @endif
                    <button @click="switchTo3D()"
                        :class="activeTab === '3d' ? 'border-b-2 border-indigo-500 text-indigo-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="{{ (!$hasPhotos && !$has360) ? 'w-full' : 'flex-1' }} py-3.5 text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                        <span>Layout 3D</span>
                        @if(!$hasPhotos)
                        <span class="bg-indigo-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">Utama</span>
                        @else
                        <span class="bg-indigo-100 text-indigo-600 text-xs font-bold px-1.5 py-0.5 rounded-full">BARU</span>
                        @endif
                    </button>
                </div>

                <!-- GALLERY TAB (hanya render jika ada foto) -->
                @if($hasPhotos)
                <div x-show="activeTab === 'gallery'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <!-- Main Photo -->
                    <div class="relative aspect-video overflow-hidden cursor-pointer" onclick="openLightbox(0)">
                        <img id="main-photo" src="{{ $fotos->first()->foto_url }}" alt="{{ $room->nama }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 bg-black/50 text-white text-xs font-semibold px-3 py-1.5 rounded-full backdrop-blur-sm">
                            🔍 Klik untuk perbesar
                        </div>
                    </div>
                    <!-- Thumbnail Strip -->
                    @if($fotos->count() > 1)
                    <div class="p-4 flex gap-3 overflow-x-auto">
                        @foreach($fotos as $i => $foto)
                        <button onclick="openLightbox({{ $i }})" class="shrink-0 w-20 h-16 rounded-xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-all focus:outline-none focus:border-indigo-500">
                            <img src="{{ $foto->foto_url }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif

                    {{-- Banner ajakan mencoba Tur 3D (hanya di bawah galeri foto) --}}
                    <div class="mx-4 mb-4 mt-1 flex items-center justify-between gap-3 bg-gradient-to-r from-indigo-50 to-slate-50 border border-indigo-100 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">Ingin lihat tata letak ruangan?</p>
                                <p class="text-xs text-slate-500 mt-0.5">Jelajahi dimensi & furnitur kamar secara virtual 360°</p>
                            </div>
                        </div>
                        <button @click="switchTo3D()" class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors whitespace-nowrap">
                            Coba Tur 3D →
                        </button>
                    </div>
                </div>
                @endif

                <!-- 360 PANORAMA TAB -->
                @if($has360)
                <div x-show="activeTab === '360'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div id="panorama-container" style="width:100%; height:420px; background:#e2e8f0;"></div>
                </div>
                @endif

                <!-- 3D TOUR TAB -->
                <div x-show="activeTab === '3d'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <!-- Day / Night Controls -->
                    <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-slate-100 bg-slate-50">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Drag putar · Scroll zoom · Klik objek untuk info
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium" :class="isNight ? 'text-slate-500' : 'text-amber-600'">☀ Siang</span>
                            <button @click="toggleDayNight()" class="relative w-10 h-5 rounded-full transition-colors duration-300 focus:outline-none" :class="isNight ? 'bg-indigo-900' : 'bg-amber-200'">
                                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full transition-transform duration-300 shadow" :class="isNight ? 'translate-x-5 bg-indigo-300' : 'translate-x-0 bg-amber-400'"></span>
                            </button>
                            <span class="text-xs font-medium" :class="isNight ? 'text-indigo-400' : 'text-slate-400'">🌙 Malam</span>
                        </div>
                    </div>

                    <!-- Canvas Container -->
                    <div id="room3d-canvas" style="width:100%; height:420px; position:relative; background:#1a1f2e; cursor:grab;">
                        <!-- Loading State -->
                        <div x-show="isLoading" class="absolute inset-0 flex flex-col items-center justify-center gap-3 z-10" style="background:#1a1f2e;">
                            <div class="w-10 h-10 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-slate-400 text-sm">Memuat model 3D...</p>
                        </div>
                    </div>

                    <!-- Quick Object Legend -->
                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
                        <p class="text-xs text-slate-500 mb-2 font-medium">Objek interaktif dalam kamar (klik untuk info):</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">🛏 Kasur</span>
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">🪟 Jendela</span>
                            @if($room->fasilitasMaster->where('nama', 'LIKE', '%meja%')->first() || $room->fasilitasMaster->where('nama', 'LIKE', '%Meja%')->first())
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">🪑 Meja Belajar</span>
                            @endif
                            @if($room->fasilitasMaster->where('nama', 'LIKE', '%lemari%')->first() || $room->fasilitasMaster->where('nama', 'LIKE', '%Lemari%')->first())
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">🚪 Lemari</span>
                            @endif
                            @if($room->fasilitasMaster->where('nama', 'LIKE', '%AC%')->first() || $room->fasilitasMaster->where('nama', 'LIKE', '%ac%')->first())
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">❄ AC</span>
                            @endif
                            @if($room->fasilitasMaster->where('nama', 'LIKE', '%TV%')->first() || $room->fasilitasMaster->where('nama', 'LIKE', '%tv%')->first())
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">📺 TV</span>
                            @endif
                            @if($room->fasilitasMaster->where('nama', 'LIKE', '%kamar mandi dalam%')->first() || $room->fasilitasMaster->where('nama', 'LIKE', '%Kamar Mandi%')->first())
                            <span class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">🚿 KM Dalam</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div><!-- END tabbed card -->

            <!-- Description & Facilities -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Deskripsi Kamar</h2>
                <div class="prose prose-slate max-w-none mb-8">
                    <p>{{ $room->deskripsi ?? 'Belum ada deskripsi.' }}</p>
                </div>

                <h2 class="text-xl font-bold text-slate-900 mb-4">Fasilitas</h2>
                @if($room->fasilitasMaster->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($room->fasilitasMaster as $fasilitas)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-indigo-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $fasilitas->nama }}</span>
                        </div>
                    @endforeach
                </div>
                @else
                <p class="text-slate-400 text-sm">Informasi fasilitas belum tersedia.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar / Booking Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 sticky top-24">

                @if(session('waiting_list_success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold text-emerald-800 text-sm">Berhasil Masuk Daftar Tunggu! 🎉</p>
                        <p class="text-emerald-700 text-xs mt-0.5">Halo <strong>{{ session('waiting_list_nama') }}</strong>! Kami akan menghubungi Anda melalui WhatsApp segera setelah kamar ini tersedia.</p>
                    </div>
                </div>
                @endif
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600 uppercase tracking-wider">
                        {{ $room->gender?->label() ?? 'Campur' }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                        Tipe {{ $room->tipe?->label() ?? 'Standar' }}
                    </span>
                    @if($isMaintenance)
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Maintenance
                    </span>
                    @else
                    <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $isAvail ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} uppercase tracking-wider">
                        {{ $isAvail ? 'Sisa ' . $room->sisa_slot . ' Slot' : 'Kamar Penuh' }}
                    </span>
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $room->nama }}</h1>
                <p class="text-slate-500 text-sm mb-6">Lantai {{ $room->lantai ?? '-' }} &bull; Luas {{ $room->luas ?? '-' }} m&sup2;</p>
                
                <div class="py-4 border-y border-slate-100 mb-6 space-y-3">
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-sm text-slate-500 shrink-0">Harga Bulanan</span>
                        <div class="text-right">
                            <x-harga-kamar :kamar="$room" size="lg" class="items-end" />
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Deposit Awal</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($room->harga_deposit, 0, ',', '.') }}</span>
                    </div>
                </div>

                @php
                    $activeSewaForCurrentUser = null;
                    if (Auth::check()) {
                        $activeSewaForCurrentUser = $room->penyewaan()
                            ->where('user_id', Auth::id())
                            ->whereIn('status', ['active', 'approved'])
                            ->first();
                    }
                @endphp

                @if($isMaintenance)
                    <div class="w-full bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <p class="text-sm font-bold text-amber-800">Sedang Maintenance</p>
                            <p class="text-xs text-amber-600 mt-0.5">Kamar tidak dapat dipesan saat ini</p>
                        </div>
                    </div>
                @elseif($isAvail)
                    <a href="{{ route('user.penyewaan.create', $room->id) }}" class="btn-primary w-full justify-center text-center">
                        Ajukan Sewa
                    </a>
                    <p class="text-center text-xs text-slate-400 mt-4">Anda belum ditagih saat pengajuan.</p>
                @else
                    @if($activeSewaForCurrentUser)
                        <a href="{{ route('dashboard', ['trigger_perpanjang_id' => $activeSewaForCurrentUser->id]) }}" class="btn-primary w-full justify-center text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Perpanjang Sewa Anda
                        </a>
                        <p class="text-center text-xs text-slate-500 mt-3">Masa sewa Anda sedang aktif untuk kamar ini.</p>
                    @elseif($room->tanggal_tersedia_kembali)
                        <a href="{{ route('user.penyewaan.create', $room->id) }}" class="btn-primary w-full justify-center text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Booking Inden (Mulai {{ $room->tanggal_tersedia_kembali->format('d M Y') }})
                        </a>
                        <p class="text-center text-xs text-slate-500 mt-3">Kamar sedang disewa. Anda dapat memesan untuk periode berikutnya mulai tanggal di atas.</p>
                    @else
                        <!-- 🔔 WAITING LIST MODAL TRIGGER -->
                        <div x-data="{ openWL: {{ session('waiting_list_success') ? 'false' : 'false' }} }">
                            <button @click="openWL = true"
                                class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-amber-200/50 transition-all duration-200 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                🔔 Beritahu Saya Jika Kosong
                            </button>
                            <p class="text-center text-xs text-slate-400 mt-3">Kamar sedang penuh. Daftarkan diri Anda agar diinfokan ketika kamar tersedia.</p>

                            <!-- MODAL OVERLAY -->
                            <div x-show="openWL" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
                                <!-- Backdrop -->
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openWL = false"></div>

                                <!-- Modal Card -->
                                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto z-10 overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5">
                                        <h3 class="text-white text-xl font-bold">🔔 Daftar Tunggu Kamar</h3>
                                        <p class="text-amber-100 text-sm mt-1">{{ $room->nama }} — Admin akan menghubungi Anda via WhatsApp jika kamar kosong.</p>
                                    </div>

                                    <!-- Form -->
                                    <form method="POST" action="{{ route('kamar.waiting_list.store', $room->id) }}" class="px-6 py-5 space-y-4">
                                        @csrf
                                        <!-- Nama -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama" required placeholder="Nama Anda"
                                                value="{{ Auth::check() ? Auth::user()->name : old('nama') }}"
                                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                                        </div>
                                        <!-- No WA -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">No WhatsApp Aktif <span class="text-red-500">*</span></label>
                                            <input type="text" name="no_wa" required placeholder="Contoh: 08123456789"
                                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                                        </div>
                                        <!-- 2-col row -->
                                        <div class="grid grid-cols-2 gap-3">
                                            <!-- Jenis Kelamin -->
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <!-- Pekerjaan -->
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-1">Pekerjaan</label>
                                                <select name="pekerjaan" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Mahasiswa">Mahasiswa</option>
                                                    <option value="Karyawan Swasta">Karyawan Swasta</option>
                                                    <option value="PNS/ASN">PNS/ASN</option>
                                                    <option value="Wiraswasta">Wiraswasta</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Estimasi Masuk -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estimasi Ingin Masuk</label>
                                            <select name="estimasi_masuk" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition">
                                                <option value="">-- Kapan perkiraan masuk? --</option>
                                                <option value="Bulan ini">Bulan ini</option>
                                                <option value="1-2 bulan ke depan">1-2 bulan ke depan</option>
                                                <option value="3-6 bulan ke depan">3-6 bulan ke depan</option>
                                                <option value="Lebih dari 6 bulan">Lebih dari 6 bulan</option>
                                                <option value="Belum pasti">Belum pasti</option>
                                            </select>
                                        </div>
                                        <!-- Catatan -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan / Pertanyaan Khusus</label>
                                            <textarea name="catatan_khusus" rows="2" placeholder="Contoh: Apakah bisa parkir motor? Boleh masak?"
                                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition resize-none"></textarea>
                                        </div>
                                        <!-- Actions -->
                                        <div class="flex gap-3 pt-1">
                                            <button type="button" @click="openWL = false"
                                                class="flex-1 border border-slate-200 text-slate-600 rounded-xl py-2.5 text-sm font-semibold hover:bg-slate-50 transition">Batal</button>
                                            <button type="submit"
                                                class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl py-2.5 text-sm font-bold hover:from-amber-600 hover:to-orange-600 transition shadow-md">Daftarkan Saya</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
@if($fotos->isNotEmpty())
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeLightbox(event)">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white z-10 bg-white/10 rounded-full p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    <button onclick="prevPhoto()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 rounded-full p-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" onclick="event.stopPropagation()">
    <button onclick="nextPhoto()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 rounded-full p-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
    <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/70 text-sm font-medium bg-black/40 px-4 py-1.5 rounded-full backdrop-blur-sm"></div>
</div>

<script>
const photos = @json($fotos->pluck('foto_url'));
let currentIndex = 0;

function openLightbox(index) {
    currentIndex = index;
    document.getElementById('lightbox-img').src = photos[currentIndex];
    document.getElementById('lightbox-counter').textContent = (currentIndex + 1) + ' / ' + photos.length;
    const lb = document.getElementById('lightbox');
    lb.classList.remove('hidden');
    lb.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(event) {
    if (!event || event.target === document.getElementById('lightbox')) {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function prevPhoto() {
    currentIndex = (currentIndex - 1 + photos.length) % photos.length;
    document.getElementById('lightbox-img').src = photos[currentIndex];
    document.getElementById('lightbox-counter').textContent = (currentIndex + 1) + ' / ' + photos.length;
}

function nextPhoto() {
    currentIndex = (currentIndex + 1) % photos.length;
    document.getElementById('lightbox-img').src = photos[currentIndex];
    document.getElementById('lightbox-counter').textContent = (currentIndex + 1) + ' / ' + photos.length;
}

document.addEventListener('keydown', (e) => {
    if (document.getElementById('lightbox').classList.contains('flex')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevPhoto();
        if (e.key === 'ArrowRight') nextPhoto();
    }
});
</script>
@endif

{{-- ========================================
     3D ROOM VIEWER – Room Data & Init Script
     ======================================== --}}
@php
    $roomJsonData = [
        'luas'       => $room->luas,
        'tipe'       => $room->tipe?->value ?? '',
        'facilities' => collect($room->fasilitas)->merge($room->fasilitasMaster->pluck('nama'))->unique()->toArray(),
        'warna_dinding' => $room->warna_dinding,
        'warna_lantai'  => $room->warna_lantai,
        'warna_kasur'   => $room->warna_kasur,
    ];
@endphp
@push('scripts')
<script>
const roomData = @json($roomJsonData);

let viewer3D = null;
let viewer360 = null;
const foto360Url = '{{ $has360 ? Storage::url($room->foto_360) : "" }}';

function room3DTab(defaultTab, hasPhotos, has360) {
    return {
        activeTab: defaultTab || 'gallery',
        isNight: false,
        isLoading: false,

        init() {
            if (this.activeTab === '360') {
                this.switchTo360();
            } else if (this.activeTab === '3d') {
                this.switchTo3D();
            }
        },

        switchTo360() {
            this.activeTab = '360';
            if (!viewer360 && has360) {
                this.$nextTick(() => {
                    const container = document.getElementById('panorama-container');
                    if (container) {
                        viewer360 = pannellum.viewer('panorama-container', {
                            "type": "equirectangular",
                            "panorama": foto360Url,
                            "autoLoad": true,
                            "compass": false,
                            "showZoomCtrl": true,
                            "mouseZoom": true,
                        });
                    }
                });
            }
        },

        switchTo3D() {
            this.activeTab = '3d';
            if (!viewer3D) {
                this.isLoading = true;
                this.$nextTick(async () => {
                    const container = document.getElementById('room3d-canvas');
                    if (container) {
                        try {
                            // Lazy load module hanya ketika tab 3D dibuka
                            const module = await import('/js/room-3d-renderer.js');
                            viewer3D = module.initRoom3DViewer(container, roomData);
                        } catch (error) {
                            console.error("Gagal memuat modul 3D:", error);
                        } finally {
                            this.isLoading = false;
                        }
                    }
                });
            }
        },

        toggleDayNight() {
            this.isNight = !this.isNight;
            if (viewer3D && viewer3D.setDayNightMode) {
                viewer3D.setDayNightMode(this.isNight);
            }
        },
    };
}
</script>
@endpush
@endsection
