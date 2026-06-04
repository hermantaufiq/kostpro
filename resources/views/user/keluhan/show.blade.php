@extends('layouts.app')
@section('title', 'Detail Keluhan - KostPro')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <a href="{{ route('user.keluhan.index') }}" class="text-indigo-600 text-sm font-medium flex items-center gap-1 mb-4 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Keluhan
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Detail Keluhan</h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 mb-6">{{ session('success') }}</div>
    @endif

    @php
        $statusColor = match($keluhan->status->value) {
            'menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
            'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
            'selesai'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'ditolak'  => 'bg-red-100 text-red-700 border-red-200',
            default    => 'bg-slate-100 text-slate-700 border-slate-200',
        };
        $prioritasColor = match($keluhan->prioritas->value) {
            'tinggi' => 'bg-rose-100 text-rose-700',
            'sedang' => 'bg-amber-100 text-amber-700',
            default  => 'bg-slate-100 text-slate-500',
        };
    @endphp

    <div class="space-y-6">
        {{-- Status Card --}}
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <span class="px-3 py-1 text-sm font-bold rounded-full border {{ $statusColor }}">
                    {{ $keluhan->status->label() }}
                </span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $prioritasColor }}">
                    Prioritas: {{ $keluhan->prioritas->label() }}
                </span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $keluhan->judul }}</h2>
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <span>📅 {{ $keluhan->created_at->format('d M Y, H:i') }}</span>
                @if($keluhan->kamar)
                <span>🏠 Kamar {{ $keluhan->kamar->nama }}</span>
                @endif
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <h3 class="font-bold text-slate-700 mb-3">📝 Deskripsi Keluhan</h3>
            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $keluhan->deskripsi }}</p>
        </div>

        {{-- Foto --}}
        @if($keluhan->foto_url)
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <h3 class="font-bold text-slate-700 mb-3">📸 Foto Bukti</h3>
            <img src="{{ Storage::url($keluhan->foto_url) }}" alt="Foto keluhan" class="rounded-xl max-w-full max-h-80 object-contain border border-slate-100">
        </div>
        @endif

        {{-- Chat / Komentar Thread --}}
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">💬 Percakapan dengan Tim KostPro</h3>
            </div>

            <div class="p-4 space-y-4 max-h-96 overflow-y-auto" id="chat-thread">
                {{-- Pesan awal dari penyewa --}}
                <div class="flex gap-3 justify-start">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold shrink-0 mt-1">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-xs">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Anda (laporan awal)</p>
                        <p class="text-sm text-slate-800 whitespace-pre-wrap">{{ $keluhan->deskripsi }}</p>
                        <p class="text-xs text-slate-400 mt-2">{{ $keluhan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($keluhan->komentars->isNotEmpty())
                    @foreach($keluhan->komentars as $komentar)
                        @if($komentar->is_admin)
                        {{-- Komentar dari Admin (kanan) --}}
                        <div class="flex gap-3 justify-end">
                            <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 max-w-xs">
                                <p class="text-xs font-semibold text-indigo-200 mb-1">Tim KostPro</p>
                                <p class="text-sm whitespace-pre-wrap">{{ $komentar->isi }}</p>
                                <p class="text-xs text-indigo-300 mt-2">{{ $komentar->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-1">A</div>
                        </div>
                        @else
                        {{-- Komentar dari Penyewa (kiri) --}}
                        <div class="flex gap-3 justify-start">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold shrink-0 mt-1">
                                {{ substr($komentar->user->name, 0, 1) }}
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-xs">
                                <p class="text-xs font-semibold text-slate-500 mb-1">Anda</p>
                                <p class="text-sm text-slate-800 whitespace-pre-wrap">{{ $komentar->isi }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $komentar->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <p class="text-center text-sm text-slate-400 py-4">Belum ada percakapan. Admin akan segera merespons tiket Anda.</p>
                @endif
            </div>

            {{-- Form Input Komentar --}}
            @if($keluhan->status->value !== 'selesai' && $keluhan->status->value !== 'ditolak')
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                <form action="{{ route('user.keluhan.komentar', $keluhan->id) }}" method="POST" class="flex gap-3 items-end">
                    @csrf
                    <textarea id="isi" name="isi" rows="2" required placeholder="Tulis pesan tambahan ke Tim KostPro..." class="flex-1 px-4 py-2.5 border-2 border-slate-200 rounded-xl text-sm text-slate-800 bg-white outline-none focus:border-indigo-500 resize-none"></textarea>
                    <button type="submit" class="bg-indigo-600 text-white p-2.5 rounded-xl hover:bg-indigo-700 transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto scroll chat thread ke bawah
    const chatThread = document.getElementById('chat-thread');
    if (chatThread) chatThread.scrollTop = chatThread.scrollHeight;
</script>
@endsection
