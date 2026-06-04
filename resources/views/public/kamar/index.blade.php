@extends('layouts.app')
@section('title', 'Cari Kamar Kos - KostPro')

@section('content')
<div class="bg-gradient-to-br from-indigo-600 to-indigo-800 pb-28 pt-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Temukan Kamar Impianmu</h1>
        <p class="text-indigo-100 max-w-2xl mx-auto">Gunakan filter pencarian untuk menemukan kamar yang sesuai dengan preferensi dan budget Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
    <!-- Advanced Filter Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 mb-8">
        <form action="{{ route('kamar.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tipe Kamar</label>
                    <select name="tipe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="pria" {{ request('tipe') == 'pria' ? 'selected' : '' }}>Pria</option>
                        <option value="wanita" {{ request('tipe') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="campur" {{ request('tipe') == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Minimum</label>
                    <input type="number" name="min_harga" value="{{ request('min_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500" placeholder="Rp 0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Maksimum</label>
                    <input type="number" name="max_harga" value="{{ request('max_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500" placeholder="Rp Tak Terhingga">
                </div>
            </div>

            @if($allFasilitas->isNotEmpty())
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-500 mb-2">Filter Fasilitas</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($allFasilitas as $f)
                    <label class="flex items-center gap-2 px-3 py-1.5 rounded-full border cursor-pointer transition-all
                        {{ in_array($f->id, (array) request('fasilitas', [])) ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-slate-50 border-slate-200 text-slate-600 hover:border-indigo-400' }}">
                        <input type="checkbox" name="fasilitas[]" value="{{ $f->id }}"
                            {{ in_array($f->id, (array) request('fasilitas', [])) ? 'checked' : '' }}
                            class="hidden"
                            onchange="this.closest('form').submit()">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-xs font-semibold">{{ $f->nama }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="btn-primary py-2.5">Terapkan Filter</button>
                @if(request()->hasAny(['tipe', 'min_harga', 'max_harga', 'fasilitas']))
                <a href="{{ route('kamar.index') }}" class="btn-secondary py-2.5">Reset Filter</a>
                @endif
            </div>
        </form>
    </div>

    @if($rooms->isEmpty())
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Kamar Tidak Ditemukan</h3>
            <p class="text-slate-500 mt-1">Coba ubah filter pencarian Anda.</p>
            <a href="{{ route('kamar.index') }}" class="btn-primary inline-flex mt-4">Hapus Filter</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($rooms as $room)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl border border-slate-100 transition-all duration-300 group flex flex-col hover:-translate-y-1">
                    <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                        @if($room->thumbnail)
                            <img src="{{ $room->thumbnail->foto_url }}" alt="{{ $room->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white/90 backdrop-blur text-slate-700 shadow-sm">
                                {{ ucfirst($room->tipe->value) }}
                            </span>
                        </div>
                        @if(!$room->status->isAvailable())
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">Tidak Tersedia</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $room->nama }}</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $room->deskripsi ?? 'Kamar kos nyaman dan strategis.' }}</p>
                        
                        @if($room->fasilitasMaster->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($room->fasilitasMaster->take(3) as $fas)
                            <span class="px-2 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-600 rounded-full">{{ $fas->nama }}</span>
                            @endforeach
                            @if($room->fasilitasMaster->count() > 3)
                            <span class="px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-500 rounded-full">+{{ $room->fasilitasMaster->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif

                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block mb-0.5">Mulai dari</span>
                                <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($room->harga_bulanan, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/bln</span></span>
                            </div>
                            <a href="{{ route('kamar.show', $room->id) }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="flex justify-center pb-8">
            {{ $rooms->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
