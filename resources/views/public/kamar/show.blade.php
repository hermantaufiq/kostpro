@extends('layouts.app')
@section('title', $room->nama . ' - KostPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-4 flex items-center text-sm text-slate-500">
        <a href="{{ route('kamar.index') }}" class="hover:text-indigo-600">Kamar</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-900 font-medium">{{ $room->nama }}</span>
    </div>

    @php
        $isMaintenance = $room->status?->value === 'maintenance';
        $isAvail = !$isMaintenance && $room->sisa_slot > 0;
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

            <!-- Lightbox Gallery Section -->
            @php $fotos = $room->fotoKamar; @endphp
            @if($fotos->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
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
            </div>
            @else
            <div class="bg-slate-100 rounded-2xl aspect-video flex items-center justify-center text-slate-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            @endif

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
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Harga Bulanan</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($room->harga_bulanan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Deposit Awal</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($room->harga_deposit, 0, ',', '.') }}</span>
                    </div>
                </div>

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
                    <button class="w-full bg-slate-100 text-slate-400 rounded-xl px-6 py-3 font-semibold text-sm cursor-not-allowed" disabled>
                        Kamar Penuh
                    </button>
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
@endsection
