@extends('layouts.app')
@section('title', 'Layanan Tambahan - KostPro')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Layanan Tambahan</h1>
            <p class="text-slate-500 mt-2">Pesan ekstra layanan untuk menambah kenyamanan ngekos Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Katalog Layanan -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-slate-800 border-b border-slate-200 pb-2">Katalog Layanan</h2>
            
            @if(!$penyewaanAktif)
                <div class="bg-amber-50 border border-amber-200 p-6 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3 text-amber-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-amber-800 font-bold mb-1">Anda belum bisa memesan layanan</h3>
                    <p class="text-sm text-amber-700">Pemesanan layanan tambahan hanya tersedia bagi penyewa dengan status Kos Aktif.</p>
                </div>
            @elseif($layananTersedia->isEmpty())
                <div class="text-center py-12 bg-white rounded-2xl border border-slate-100">
                    <p class="text-slate-500">Belum ada katalog layanan tambahan saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($layananTersedia as $layanan)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-indigo-300 hover:shadow-soft transition group">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-lg text-slate-800 group-hover:text-indigo-700 transition">{{ $layanan->nama }}</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                                {{ ucfirst($layanan->tipe_siklus) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mb-4 min-h-[40px]">{{ $layanan->deskripsi ?? 'Tanpa deskripsi' }}</p>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                            <p class="font-bold text-indigo-600">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                            
                            <button type="button" 
                                onclick="openModal('modal-pesan-{{ $layanan->id }}')" 
                                class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                Pesan
                            </button>
                        </div>
                    </div>

                    <!-- Modal Pesan -->
                    <div id="modal-pesan-{{ $layanan->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                        <div class="bg-white rounded-2xl max-w-md w-full shadow-xl overflow-hidden transform scale-95 opacity-0 transition-all duration-200" id="modal-content-{{ $layanan->id }}">
                            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                <h3 class="font-bold text-lg text-slate-900">Pesan Layanan Tambahan</h3>
                                <button type="button" onclick="closeModal('modal-pesan-{{ $layanan->id }}')" class="text-slate-400 hover:text-rose-500 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="{{ route('user.layanan.store') }}" method="POST" class="p-6">
                                @csrf
                                <input type="hidden" name="layanan_tambahan_id" value="{{ $layanan->id }}">
                                
                                <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl mb-4">
                                    <p class="text-sm text-indigo-900 font-bold mb-1">{{ $layanan->nama }}</p>
                                    <p class="text-xs text-indigo-700 mb-2">{{ ucfirst($layanan->tipe_siklus) }}</p>
                                    <p class="text-lg font-extrabold text-indigo-700">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan (Opsional)</label>
                                    <textarea name="catatan_user" rows="3" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: Mulai besok ya pak, atau Tolong cuci bersih..."></textarea>
                                </div>
                                
                                <div class="bg-amber-50 p-3 rounded-lg text-xs text-amber-800 mb-5 border border-amber-100">
                                    <span class="font-semibold">Info:</span> Tagihan untuk layanan ini akan dimasukkan ke invoice bulan aktif atau bulan berikutnya setelah disetujui Admin.
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" onclick="closeModal('modal-pesan-{{ $layanan->id }}')" class="flex-1 px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Batal</button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200 text-sm">Kirim Permintaan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Riwayat Permintaan -->
        <div class="lg:col-span-1">
            <h2 class="text-xl font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6">Status Permintaan</h2>
            
            @if($riwayatRequest->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 p-6 text-center shadow-soft">
                    <p class="text-sm text-slate-500">Belum ada riwayat pesanan.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($riwayatRequest as $req)
                    <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-soft">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-sm text-slate-800">{{ $req->layananTambahan->nama }}</h4>
                            @php
                                $statusBadge = match($req->status) {
                                    'menunggu' => 'bg-amber-100 text-amber-700',
                                    'disetujui' => 'bg-emerald-100 text-emerald-700',
                                    'ditolak' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-slate-100 text-slate-700'
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $statusBadge }}">
                                {{ $req->status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-2">Tanggal: {{ $req->created_at->format('d M Y') }}</p>
                        
                        @if($req->catatan_admin)
                        <div class="bg-slate-50 p-2 rounded text-xs border border-slate-100 mt-2">
                            <span class="font-semibold text-slate-600 block mb-1">Catatan Admin:</span>
                            <span class="text-slate-600">{{ $req->catatan_admin }}</span>
                        </div>
                        @endif
                        
                        @if($req->status === 'disetujui')
                        <div class="mt-2 text-[10px] text-emerald-600 font-semibold">
                            ✓ Telah ditambahkan ke tagihan
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('modal-content-' + id.split('-')[2]);
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('modal-content-' + id.split('-')[2]);
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endsection
