@extends('layouts.app')
@section('title', 'Buat Keluhan - KosPro')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <a href="{{ route('user.keluhan.index') }}" class="text-indigo-600 text-sm font-medium flex items-center gap-1 mb-4 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Keluhan
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Buat Laporan Keluhan</h1>
        <p class="text-slate-500 mt-1">Ceritakan masalah yang Anda alami agar segera kami tindak lanjuti.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-8">
        @if($penyewaan)
        <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-indigo-700 text-sm font-medium">Keluhan akan otomatis dikaitkan dengan kamar <strong>{{ $penyewaan->kamar->nama }}</strong> yang Anda sewa saat ini.</p>
        </div>
        @else
        <div class="mb-6 p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p class="text-amber-700 text-sm font-medium">Anda tidak memiliki penyewaan aktif saat ini. Keluhan tetap bisa dikirim untuk keluhan umum.</p>
        </div>
        @endif

        <form action="{{ route('user.keluhan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Keluhan <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('judul') border-red-400 @enderror"
                    placeholder="Contoh: AC kamar tidak dingin, Kran air bocor...">
                @error('judul')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tingkat Prioritas <span class="text-rose-500">*</span></label>
                <select name="prioritas" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('prioritas') border-red-400 @enderror">
                    <option value="rendah" {{ old('prioritas')=='rendah'?'selected':'' }}>Rendah — Tidak terlalu mendesak</option>
                    <option value="sedang" {{ old('prioritas','sedang')=='sedang'?'selected':'' }}>Sedang — Perlu ditangani segera</option>
                    <option value="tinggi" {{ old('prioritas')=='tinggi'?'selected':'' }}>Tinggi — Sangat mendesak!</option>
                </select>
                @error('prioritas')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" rows="5"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('deskripsi') border-red-400 @enderror"
                    placeholder="Jelaskan masalah secara detail: kapan terjadi, apa yang rusak/bermasalah, dampaknya bagi Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Bukti <span class="text-slate-400 font-normal">(Opsional, maks. 2MB)</span></label>
                <input type="file" name="foto" accept="image/*"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('foto') border-red-400 @enderror">
                @error('foto')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2 flex gap-4">
                <button type="submit" class="btn-primary flex-1 text-center">
                    Kirim Keluhan
                </button>
                <a href="{{ route('user.keluhan.index') }}" class="flex-1 text-center px-6 py-3 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
