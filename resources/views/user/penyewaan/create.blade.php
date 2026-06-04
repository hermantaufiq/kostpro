@extends('layouts.app')
@section('title', 'Ajukan Sewa Kamar - KosPro')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Pengajuan Sewa</h1>
        <p class="text-slate-500 mt-2">Lengkapi formulir di bawah ini untuk mengajukan sewa kamar.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <!-- Room Summary -->
        <div class="bg-slate-50 p-6 border-b border-slate-100 flex gap-4 items-center">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-200 shrink-0">
                @if($kamar->thumbnail)
                    <img src="{{ $kamar->thumbnail->foto_url }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-lg">{{ $kamar->nama }}</h3>
                <p class="text-slate-500 text-sm">Rp {{ number_format($kamar->harga_bulanan, 0, ',', '.') }} / bulan</p>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <form action="{{ route('user.penyewaan.store', $kamar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}" required class="form-input">
                            @error('tanggal_masuk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="durasi_bulan" class="form-label">Durasi Sewa (Bulan)</label>
                            <select id="durasi_bulan" name="durasi_bulan" required class="form-input">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('durasi_bulan') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                                @endfor
                            </select>
                            @error('durasi_bulan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="3" class="form-input" placeholder="Misal: Saya bawa kendaraan mobil, apakah ada slot parkir?">{{ old('catatan') }}</textarea>
                        @error('catatan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if(!Auth::user()->foto_ktp_url)
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <h4 class="font-bold text-amber-900 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                Verifikasi Identitas (Wajib)
                            </h4>
                            <p class="text-sm text-amber-800 mb-3">Sebagai syarat keamanan kos, mohon unggah foto KTP asli Anda. Data ini akan disimpan aman dan hanya digunakan untuk verifikasi penghuni.</p>
                            
                            <label for="foto_ktp" class="block text-xs font-semibold text-slate-700 mb-1">Unggah Foto KTP</label>
                            <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
                            @error('foto_ktp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex gap-3">
                        <svg class="w-6 h-6 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="text-sm text-indigo-900">
                            <strong>Informasi:</strong> Pengajuan sewa akan direview oleh admin. Anda belum akan dikenakan tagihan pada tahap ini. Tagihan deposit dan sewa bulan pertama akan diterbitkan setelah pengajuan disetujui.
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('kamar.show', $kamar->id) }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">Kirim Pengajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
