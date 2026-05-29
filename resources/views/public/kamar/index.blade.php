@extends('layouts.app')
@section('title', 'Cari Kamar Kos - KosPro')

@section('content')
<div class="bg-indigo-600 pb-24 pt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Temukan Kamar Impianmu</h1>
        <p class="text-indigo-100 max-w-2xl mx-auto">Gunakan filter pencarian untuk menemukan kamar yang sesuai dengan preferensi dan budget Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-4 mb-8">
        <form action="{{ route('kamar.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tipe Kamar</label>
                <select name="tipe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 outline-none focus:border-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="pria" {{ request('tipe') == 'pria' ? 'selected' : '' }}>Pria</option>
                    <option value="wanita" {{ request('tipe') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    <option value="campur" {{ request('tipe') == 'campur' ? 'selected' : '' }}>Campur</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Minimum</label>
                <input type="number" name="min_harga" value="{{ request('min_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 outline-none focus:border-indigo-500" placeholder="Rp 0">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Maksimum</label>
                <input type="number" name="max_harga" value="{{ request('max_harga') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 outline-none focus:border-indigo-500" placeholder="Rp Tak Terhingga">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full md:w-auto">Terapkan Filter</button>
            </div>
        </form>
    </div>

    @if($rooms->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Kamar Tidak Ditemukan</h3>
            <p class="text-slate-500 mt-1">Coba ubah filter pencarian Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($rooms as $room)
                <div class="bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover border border-slate-100 transition-all group flex flex-col">
                    <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                        @if($room->thumbnail)
                            <img src="{{ $room->thumbnail->foto_url }}" alt="{{ $room->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">No Image</div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white/90 backdrop-blur text-slate-700 shadow-sm">
                                {{ ucfirst($room->tipe->value) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $room->nama }}</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $room->deskripsi ?? 'Kamar kos nyaman dan strategis.' }}</p>
                        
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
        
        <div class="flex justify-center">
            {{ $rooms->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
