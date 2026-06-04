@extends('layouts.app')
@section('title', 'Pasar Kos - KostPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Pasar Kos 🏪</h1>
            <p class="text-slate-500 mt-1">Tempat jual beli barang & jasa antar sesama penghuni kos.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="btn-primary">
            + Jual Sesuatu
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6">{{ session('success') }}</div>
    @endif

    {{-- Tabs (All vs My Items) --}}
    <div class="mb-6 border-b border-slate-200">
        <div class="flex gap-6">
            <button onclick="switchTab('all')" id="tab-all" class="pb-3 text-sm font-bold text-indigo-600 border-b-2 border-indigo-600">Semua Iklan</button>
            <button onclick="switchTab('my')" id="tab-my" class="pb-3 text-sm font-semibold text-slate-500 hover:text-slate-700 border-b-2 border-transparent">Iklan Saya</button>
        </div>
    </div>

    {{-- Tab Content: All --}}
    <div id="content-all">
        @if($items->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-soft">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Barang yang Dijual</h3>
                <p class="text-slate-500 mt-1 text-sm">Jadilah yang pertama menawarkan barang atau jasamu di sini!</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden flex flex-col hover:-translate-y-1 transition-transform">
                        <div class="aspect-square bg-slate-100 relative">
                            @if($item->foto_url)
                                <img src="{{ Storage::url($item->foto_url) }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg shadow-sm text-xs font-bold text-slate-700">
                                {{ $item->user->name }}
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-slate-900 mb-1 line-clamp-1">{{ $item->nama_barang }}</h3>
                            <p class="text-xs text-slate-500 mb-3 line-clamp-2">{{ $item->deskripsi }}</p>
                            <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="font-bold text-indigo-600 text-sm">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-xs font-bold hover:bg-green-100 transition-colors">
                                    Hubungi
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                {{ $items->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- Tab Content: My Items --}}
    <div id="content-my" class="hidden">
        @if($myItems->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-soft">
                <p class="text-slate-500 text-sm">Anda belum memposting iklan apapun.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myItems as $item)
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-4 flex gap-4">
                        <div class="w-24 h-24 bg-slate-100 rounded-xl shrink-0 overflow-hidden">
                            @if($item->foto_url)
                                <img src="{{ Storage::url($item->foto_url) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-900 line-clamp-1">{{ $item->nama_barang }}</h3>
                            <p class="font-bold text-indigo-600 text-sm mt-1 mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <form action="{{ route('user.pasar_kos.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus iklan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Hapus Iklan</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modal-tambah" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-900">Pasang Iklan Jualan</h3>
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('user.pasar_kos.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Barang / Jasa</label>
                    <input type="text" name="nama_barang" required class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" required rows="3" class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" required min="0" class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. WA / Kontak</label>
                    <input type="text" name="kontak" required placeholder="08123456789" class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Barang (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn-primary w-full justify-center">Posting Iklan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        if(tab === 'all') {
            document.getElementById('content-all').classList.remove('hidden');
            document.getElementById('content-my').classList.add('hidden');
            
            document.getElementById('tab-all').className = 'pb-3 text-sm font-bold text-indigo-600 border-b-2 border-indigo-600';
            document.getElementById('tab-my').className = 'pb-3 text-sm font-semibold text-slate-500 hover:text-slate-700 border-b-2 border-transparent';
        } else {
            document.getElementById('content-all').classList.add('hidden');
            document.getElementById('content-my').classList.remove('hidden');
            
            document.getElementById('tab-my').className = 'pb-3 text-sm font-bold text-indigo-600 border-b-2 border-indigo-600';
            document.getElementById('tab-all').className = 'pb-3 text-sm font-semibold text-slate-500 hover:text-slate-700 border-b-2 border-transparent';
        }
    }
</script>
@endsection
