@props(['fotos'])

<div x-data="{ lightboxOpen: false, activeIndex: 0, 
                openLightbox(index) { this.activeIndex = index; this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
                closeLightbox() { this.lightboxOpen = false; document.body.style.overflow = ''; }
              }">
    
    <!-- Swiper Container -->
    <div class="swiper main-gallery rounded-2xl overflow-hidden mb-4 aspect-[16/9] shadow-card bg-slate-100">
        <div class="swiper-wrapper">
            @forelse($fotos as $index => $foto)
                <div class="swiper-slide cursor-pointer" @click="openLightbox({{ $index }})">
                    <img src="{{ $foto->foto_url }}" alt="Foto Kamar" class="w-full h-full object-cover">
                </div>
            @empty
                <div class="swiper-slide flex items-center justify-center text-slate-400">
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endforelse
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev !text-white !w-10 !h-10 !bg-black/20 hover:!bg-black/40 rounded-full backdrop-blur transition-all"></div>
        <div class="swiper-button-next !text-white !w-10 !h-10 !bg-black/20 hover:!bg-black/40 rounded-full backdrop-blur transition-all"></div>
    </div>

    <!-- Thumbnail Strip -->
    @if($fotos->count() > 1)
    <div class="swiper thumb-gallery">
        <div class="swiper-wrapper">
            @foreach($fotos as $index => $foto)
                <div class="swiper-slide cursor-pointer aspect-square rounded-xl overflow-hidden border-2 border-transparent [&.swiper-slide-thumb-active]:border-indigo-600 transition-all opacity-60 [&.swiper-slide-thumb-active]:opacity-100">
                    <img src="{{ $foto->foto_url }}" alt="Thumb" class="w-full h-full object-cover">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Alpine.js Lightbox -->
    <div x-show="lightboxOpen" 
         style="display: none;"
         x-transition.opacity.duration.300ms
         class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-sm flex items-center justify-center p-4">
        
        <!-- Close Button -->
        <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/70 hover:text-white z-50 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Lightbox Content -->
        <div class="w-full max-w-5xl h-full flex items-center justify-center relative" @click.away="closeLightbox()">
            @foreach($fotos as $index => $foto)
                <img src="{{ $foto->foto_url }}" 
                     x-show="activeIndex === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="max-h-full max-w-full object-contain rounded-xl shadow-2xl">
            @endforeach

            <!-- Lightbox Nav -->
            @if($fotos->count() > 1)
                <button @click.stop="activeIndex = activeIndex > 0 ? activeIndex - 1 : {{ $fotos->count() - 1 }}" class="absolute left-0 text-white/50 hover:text-white p-4 transition-colors">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click.stop="activeIndex = activeIndex < {{ $fotos->count() - 1 }} ? activeIndex + 1 : 0" class="absolute right-0 text-white/50 hover:text-white p-4 transition-colors">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if(typeof Swiper !== 'undefined') {
            const thumbGallery = new Swiper('.thumb-gallery', {
                spaceBetween: 12,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    640: { slidesPerView: 5 },
                    1024: { slidesPerView: 6 },
                }
            });

            const mainGallery = new Swiper('.main-gallery', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                thumbs: {
                    swiper: thumbGallery
                }
            });
        }
    });
</script>
@endpush
