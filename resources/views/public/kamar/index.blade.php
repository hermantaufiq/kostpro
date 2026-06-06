@extends('layouts.app')
@section('title', 'Cari Kamar Kos - KosPro')

@section('content')
{{-- HERO SECTION --}}
<div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 pt-16 pb-20 relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-slate-50 to-transparent pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white relative z-10">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 tracking-tight">Temukan Kamar Kos Impianmu</h1>
        <p class="text-indigo-100 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
            Gunakan filter pencarian cerdas kami untuk menemukan hunian yang paling sesuai dengan preferensi, fasilitas, dan budget Anda.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 pb-24">
    
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- ==========================================
             SIDEBAR FILTER (DESKTOP)
             ========================================== --}}
        <aside class="hidden lg:block w-72 shrink-0">
            <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-3xl p-6 shadow-xl shadow-slate-200/40 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Filter Pencarian
                    </h2>
                                    @if(request()->hasAny(['gender', 'tipe', 'min_harga', 'max_harga', 'fasilitas']))
                    <a href="{{ route('kamar.index') }}" class="text-xs font-semibold text-rose-500 hover:text-rose-700 transition-colors">Reset</a>
                    @endif
                </div>

                <form action="{{ route('kamar.index') }}" method="GET" id="filter-form-desktop">
                    
                    {{-- Gender / Target Penghuni --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Target Penghuni</label>
                        <div class="flex flex-wrap gap-2">
                            @php $reqGender = request('gender', ''); @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="" class="peer hidden" onchange="this.form.submit()" {{ $reqGender == '' ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-xl text-sm font-semibold transition-all border {{ $reqGender == '' ? 'bg-slate-800 text-white border-slate-800 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">Semua</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="putra" class="peer hidden" onchange="this.form.submit()" {{ $reqGender == 'putra' ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-xl text-sm font-semibold transition-all border {{ $reqGender == 'putra' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">Putra</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="putri" class="peer hidden" onchange="this.form.submit()" {{ $reqGender == 'putri' ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-xl text-sm font-semibold transition-all border {{ $reqGender == 'putri' ? 'bg-rose-500 text-white border-rose-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">Putri</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="campur" class="peer hidden" onchange="this.form.submit()" {{ $reqGender == 'campur' ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-xl text-sm font-semibold transition-all border {{ $reqGender == 'campur' ? 'bg-emerald-500 text-white border-emerald-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">Campur</div>
                            </label>
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Rentang Harga</label>
                        <div class="flex items-center gap-2">
                            <div class="relative w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Rp</span>
                                <input type="number" name="min_harga" value="{{ request('min_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-slate-300 font-medium" placeholder="Min">
                            </div>
                            <span class="text-slate-300 font-bold">-</span>
                            <div class="relative w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Rp</span>
                                <input type="number" name="max_harga" value="{{ request('max_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-slate-300 font-medium" placeholder="Max">
                            </div>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    @if($allFasilitas->isNotEmpty())
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Fasilitas Tersedia</label>
                        <div class="space-y-2.5 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($allFasilitas as $f)
                            @php $isChecked = in_array($f->id, (array) request('fasilitas', [])); @endphp
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center w-5 h-5 rounded-[6px] border transition-all {{ $isChecked ? 'bg-indigo-600 border-indigo-600' : 'bg-slate-50 border-slate-300 group-hover:border-indigo-400' }}">
                                    <input type="checkbox" name="fasilitas[]" value="{{ $f->id }}" {{ $isChecked ? 'checked' : '' }} class="peer hidden" onchange="this.form.submit()">
                                    <svg class="w-3.5 h-3.5 text-white transition-transform scale-0 {{ $isChecked ? 'scale-100' : '' }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-medium {{ $isChecked ? 'text-indigo-900 font-bold' : 'text-slate-600 group-hover:text-slate-900' }}">{{ $f->nama }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>


        {{-- ==========================================
             MAIN CONTENT (ROOMS GRID)
             ========================================== --}}
        <div class="flex-1 min-w-0">
            
            {{-- Header info --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Rekomendasi Kamar</h2>
                    <p class="text-sm text-slate-500 font-medium">Menampilkan {{ $rooms->total() }} kamar sesuai pencarian.</p>
                </div>
            </div>

            @if($rooms->isEmpty())
                <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Yah, Kamar Tidak Ditemukan</h3>
                    <p class="text-slate-500 mb-6 max-w-md mx-auto">Kami tidak dapat menemukan kamar yang cocok dengan kriteria filter Anda. Coba perlebar rentang harga atau kurangi fasilitas yang dicari.</p>
                    <a href="{{ route('kamar.index') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
                    @foreach($rooms as $room)
                    @php
                        $genderColor = match($room->gender?->value ?? 'campur') {
                            'putra'  => 'bg-indigo-500 text-white',
                            'putri'  => 'bg-rose-500 text-white',
                            'campur' => 'bg-emerald-500 text-white',
                            default  => 'bg-slate-800 text-white'
                        };
                        $tipeColor = match($room->tipe?->value ?? 'standar') {
                            'standar' => 'bg-slate-700 text-white',
                            'deluxe'  => 'bg-indigo-400 text-white',
                            'vip'     => 'bg-amber-500 text-white',
                            'suite'   => 'bg-purple-600 text-white',
                            default   => 'bg-slate-700 text-white'
                        };
                        $isAvail = $room->sisa_slot > 0;
                    @endphp
                        
                        <div class="bg-white rounded-3xl overflow-hidden shadow-card hover:shadow-card-hover border border-slate-100 transition-all duration-400 group flex flex-col hover:-translate-y-1.5 relative">
                            {{-- Cover Image --}}
                            <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                                @if($room->thumbnail)
                                    <img src="{{ $room->thumbnail->foto_url }}" alt="{{ $room->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                {{-- Overlay gradient --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    <span class="px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider shadow-md backdrop-blur-sm {{ $genderColor }}">
                                        {{ $room->gender?->label() ?? 'Campur' }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-sm {{ $tipeColor }}">
                                        {{ $room->tipe?->label() ?? 'Standar' }}
                                    </span>
                                </div>
                                
                                <div class="absolute top-3 right-3 flex flex-col gap-2">
                                    @if($isAvail)
                                        <span class="px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider shadow-md backdrop-blur-sm bg-emerald-500/90 text-white border border-white/20">
                                            Sisa {{ $room->sisa_slot }} Slot
                                        </span>
                                    @endif
                                </div>
                                
                                @if(!$isAvail)
                                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center z-10">
                                    <span class="bg-rose-600 text-white text-xs font-black px-4 py-2.5 rounded-xl shadow-[0_4px_15px_rgba(225,29,72,0.4)] border border-rose-400">KAMAR PENUH</span>
                                </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $room->nama }}</h3>
                                </div>
                                <p class="text-sm text-slate-500 mb-4 line-clamp-2 leading-relaxed">{{ $room->deskripsi ?? 'Kamar kos nyaman, aman, dan berfasilitas lengkap.' }}</p>
                                
                                {{-- Fasilitas Icons --}}
                                @if($room->fasilitasMaster->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @foreach($room->fasilitasMaster->take(3) as $fas)
                                    <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg">
                                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                        <span class="text-[11px] font-bold text-slate-600">{{ $fas->nama }}</span>
                                    </div>
                                    @endforeach
                                    @if($room->fasilitasMaster->count() > 3)
                                    <div class="flex items-center px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        <span class="text-[11px] font-bold">+{{ $room->fasilitasMaster->count() - 3 }} lagi</span>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="h-8 mb-5"></div> {{-- Spacer --}}
                                @endif

                                {{-- Price & Action --}}
                                <div class="mt-auto flex items-end justify-between pt-4 border-t border-slate-100 border-dashed">
                                    <div>
                                        <span class="text-xs text-slate-400 block mb-0.5 font-medium">Harga Bulanan</span>
                                        <span class="text-xl font-black text-slate-800">
                                            Rp{{ number_format($room->harga_bulanan, 0, ',', '.') }}<span class="text-xs text-slate-400 font-bold ml-1">/bln</span>
                                        </span>
                                    </div>
                                    <a href="{{ route('kamar.show', $room->id) }}" class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm group-hover:shadow-brand group-hover:rotate-12">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="flex justify-center">
                    {{ $rooms->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ==========================================
     MOBILE FILTER BUTTON & DRAWER
     ========================================== --}}
<button onclick="toggleMobileFilter()" class="lg:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] bg-slate-900 text-white px-6 py-3.5 rounded-full font-bold shadow-[0_10px_40px_rgba(0,0,0,0.3)] flex items-center gap-2 border border-slate-700 active:scale-95 transition-transform">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
    Filter Kamar
        @if(request()->hasAny(['gender', 'tipe', 'min_harga', 'max_harga', 'fasilitas']))
    <span class="w-2.5 h-2.5 bg-rose-500 rounded-full absolute top-0 right-1 border-2 border-slate-900"></span>
    @endif
</button>

{{-- Mobile Drawer Overlay --}}
<div id="mobile-filter-drawer" class="fixed inset-0 z-[400] hidden">
    <div id="mobile-filter-backdrop" onclick="toggleMobileFilter()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    
    <div id="mobile-filter-panel" class="absolute bottom-0 left-0 right-0 h-[85vh] bg-white rounded-t-3xl shadow-2xl flex flex-col translate-y-full transition-transform duration-400 ease-[cubic-bezier(0.32,0.72,0,1)]">
        
        <div class="flex justify-center pt-4 pb-2 shrink-0">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
        </div>
        
        <div class="px-6 pb-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <h3 class="text-xl font-black text-slate-800">Filter Kamar</h3>
            <button onclick="toggleMobileFilter()" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6" id="mobile-filter-scroll">
            <form action="{{ route('kamar.index') }}" method="GET" id="filter-form-mobile">
                {{-- Duplicate form fields for mobile (Simplified for demonstration, in a real app, bind these together or submit the desktop form via JS) --}}
                
                {{-- Target Penghuni (Gender) --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Target Penghuni</label>
                    <div class="grid grid-cols-2 gap-2">
                        @php $reqGender = request('gender', ''); @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="" class="peer hidden" {{ $reqGender == '' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center rounded-xl text-sm font-bold transition-all border peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 bg-white text-slate-500 border-slate-200">Semua</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="putra" class="peer hidden" {{ $reqGender == 'putra' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center rounded-xl text-sm font-bold transition-all border peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 bg-white text-slate-500 border-slate-200">Putra</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="putri" class="peer hidden" {{ $reqGender == 'putri' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center rounded-xl text-sm font-bold transition-all border peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 bg-white text-slate-500 border-slate-200">Putri</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="campur" class="peer hidden" {{ $reqGender == 'campur' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center rounded-xl text-sm font-bold transition-all border peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 bg-white text-slate-500 border-slate-200">Campur</div>
                        </label>
                    </div>
                </div>

                {{-- Harga --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Rentang Harga</label>
                    <div class="space-y-3">
                        <div class="relative w-full">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                            <input type="number" name="min_harga" value="{{ request('min_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-base text-slate-800 outline-none focus:border-indigo-500 font-bold" placeholder="Minimum">
                        </div>
                        <div class="relative w-full">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                            <input type="number" name="max_harga" value="{{ request('max_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-base text-slate-800 outline-none focus:border-indigo-500 font-bold" placeholder="Maksimum">
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                @if($allFasilitas->isNotEmpty())
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Fasilitas</label>
                    <div class="space-y-3">
                        @foreach($allFasilitas as $f)
                        @php $isChecked = in_array($f->id, (array) request('fasilitas', [])); @endphp
                        <label class="flex items-center justify-between p-3 rounded-xl border {{ $isChecked ? 'border-indigo-600 bg-indigo-50/50' : 'border-slate-200 bg-white' }} cursor-pointer">
                            <span class="font-bold {{ $isChecked ? 'text-indigo-900' : 'text-slate-600' }}">{{ $f->nama }}</span>
                            <div class="relative flex items-center justify-center w-6 h-6 rounded-lg border-2 {{ $isChecked ? 'bg-indigo-600 border-indigo-600' : 'bg-slate-50 border-slate-300' }}">
                                <input type="checkbox" name="fasilitas[]" value="{{ $f->id }}" {{ $isChecked ? 'checked' : '' }} class="hidden">
                                <svg class="w-4 h-4 text-white transition-transform scale-0 {{ $isChecked ? 'scale-100' : '' }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Submit Button (Inside mobile drawer) --}}
                <div class="sticky bottom-0 bg-white pt-4 pb-2 border-t border-slate-100 flex gap-3">
                    @if(request()->hasAny(['tipe', 'min_harga', 'max_harga', 'fasilitas']))
                    <a href="{{ route('kamar.index') }}" class="w-1/3 bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-xl text-center">Reset</a>
                    @endif
                    <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-brand">
                        Lihat Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
    function toggleMobileFilter() {
        const drawer = document.getElementById('mobile-filter-drawer');
        const panel = document.getElementById('mobile-filter-panel');
        const backdrop = document.getElementById('mobile-filter-backdrop');
        
        if (drawer.classList.contains('hidden')) {
            // Open
            drawer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Trigger reflow
            void drawer.offsetWidth;
            
            backdrop.classList.add('opacity-100');
            panel.classList.remove('translate-y-full');
        } else {
            // Close
            backdrop.classList.remove('opacity-100');
            panel.classList.add('translate-y-full');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 400);
        }
    }
</script>
@endsection
