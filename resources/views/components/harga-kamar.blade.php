@props(['kamar', 'size' => 'md'])

@php
    $promo = $kamar->isPromoAktif();
    $sizeClasses = match($size) {
        'sm' => ['normal' => 'text-sm line-through text-slate-400', 'promo' => 'text-lg font-black text-rose-600', 'single' => 'text-lg font-black text-slate-800', 'suffix' => 'text-xs'],
        'lg' => ['normal' => 'text-base line-through text-slate-400', 'promo' => 'text-2xl font-black text-rose-600', 'single' => 'text-2xl font-black text-slate-900', 'suffix' => 'text-sm'],
        default => ['normal' => 'text-sm line-through text-slate-400', 'promo' => 'text-xl font-black text-rose-600', 'single' => 'text-xl font-black text-slate-800', 'suffix' => 'text-xs'],
    };
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    @if($promo)
        @if($kamar->label_promo || $kamar->persen_diskon_promo)
        <div class="flex items-center gap-2 mb-1">
            @if($kamar->label_promo)
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700">
                {{ $kamar->label_promo }}
            </span>
            @endif
            @if($kamar->persen_diskon_promo)
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700">
                Hemat {{ $kamar->persen_diskon_promo }}%
            </span>
            @endif
        </div>
        @endif
        <div class="flex flex-col">
            <span class="{{ $sizeClasses['normal'] }}">
                Rp{{ number_format($kamar->harga_bulanan, 0, ',', '.') }}
            </span>
            <span class="{{ $sizeClasses['promo'] }}">
                Rp{{ number_format($kamar->harga_efektif, 0, ',', '.') }}<span class="{{ $sizeClasses['suffix'] }} text-slate-400 font-bold ml-1">/bln</span>
            </span>
        </div>
    @else
        <span class="{{ $sizeClasses['single'] }}">
            Rp{{ number_format($kamar->harga_bulanan, 0, ',', '.') }}<span class="{{ $sizeClasses['suffix'] }} text-slate-400 font-bold ml-1">/bln</span>
        </span>
    @endif
</div>
