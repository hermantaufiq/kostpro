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

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold text-red-800 text-sm mb-2">Mohon periksa kembali data Anda:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-red-700 text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Info Wajib Identitas --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex gap-3">
        <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <div>
            <p class="font-bold text-amber-900 text-sm">Data identitas wajib dilengkapi</p>
            <p class="text-xs text-amber-700 mt-0.5">Seluruh data identitas di bawah ini <strong>wajib diisi</strong> dan akan diverifikasi oleh admin sebelum pengajuan Anda diproses. Pastikan data sesuai dengan KTP asli Anda.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        {{-- Room Summary --}}
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

                <div class="space-y-8">

                    {{-- ============================================ --}}
                    {{-- BAGIAN 1: DATA SEWA --}}
                    {{-- ============================================ --}}
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-black">1</span>
                            Data Sewa
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}"
                                    required class="form-input">
                                @error('tanggal_masuk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="durasi_bulan" class="form-label">Durasi Sewa <span class="text-red-500">*</span></label>
                                <select id="durasi_bulan" name="durasi_bulan" required class="form-input">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('durasi_bulan') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                                    @endfor
                                </select>
                                @error('durasi_bulan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="catatan" class="form-label">Catatan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="2" class="form-input"
                                placeholder="Misal: Saya bawa kendaraan motor, apakah ada slot parkir?">{{ old('catatan') }}</textarea>
                            @error('catatan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- BAGIAN 2: DATA IDENTITAS DIRI --}}
                    {{-- ============================================ --}}
                    <div class="border-t border-slate-100 pt-8">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-black">2</span>
                            Identitas Diri (Sesuai KTP)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NIK --}}
                            <div class="md:col-span-2">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                                <input type="text" id="nik" name="nik"
                                    value="{{ old('nik', Auth::user()->nik) }}"
                                    maxlength="16" placeholder="16 digit angka sesuai KTP"
                                    class="form-input font-mono tracking-widest">
                                @error('nik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir?->format('Y-m-d')) }}"
                                    class="form-input">
                                @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-input">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', Auth::user()->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Pekerjaan --}}
                            <div>
                                <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-red-500">*</span></label>
                                <input type="text" id="pekerjaan" name="pekerjaan"
                                    value="{{ old('pekerjaan', Auth::user()->pekerjaan) }}"
                                    placeholder="Mahasiswa / Karyawan / Wiraswasta"
                                    class="form-input">
                                @error('pekerjaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kota Asal --}}
                            <div>
                                <label for="asal_kota" class="form-label">Kota Asal <span class="text-red-500">*</span></label>
                                <input type="text" id="asal_kota" name="asal_kota"
                                    value="{{ old('asal_kota', Auth::user()->asal_kota) }}"
                                    placeholder="Contoh: Surabaya, Medan, Makassar"
                                    class="form-input">
                                @error('asal_kota') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Alamat Lengkap --}}
                            <div class="md:col-span-2">
                                <label for="alamat" class="form-label">Alamat Asal Lengkap <span class="text-red-500">*</span></label>
                                <textarea id="alamat" name="alamat" rows="3" class="form-input"
                                    placeholder="Contoh: Jl. Merdeka No. 12, RT 01/RW 02, Kel. Sukamaju, Kec. Bogor Utara, Kota Bogor">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                @error('alamat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- BAGIAN 3: KONTAK DARURAT --}}
                    {{-- ============================================ --}}
                    <div class="border-t border-slate-100 pt-8">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-black">3</span>
                            Kontak Darurat
                        </h3>
                        <p class="text-xs text-slate-500 mb-4 ml-8">Pihak yang bisa dihubungi jika terjadi situasi darurat (bukan nomor Anda sendiri).</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kontak_darurat_nama" class="form-label">Nama Kontak Darurat <span class="text-red-500">*</span></label>
                                <input type="text" id="kontak_darurat_nama" name="kontak_darurat_nama"
                                    value="{{ old('kontak_darurat_nama', Auth::user()->kontak_darurat_nama) }}"
                                    placeholder="Nama orang tua / keluarga / kerabat"
                                    class="form-input">
                                @error('kontak_darurat_nama') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="kontak_darurat_hp" class="form-label">Nomor HP Kontak Darurat <span class="text-red-500">*</span></label>
                                <input type="text" id="kontak_darurat_hp" name="kontak_darurat_hp"
                                    value="{{ old('kontak_darurat_hp', Auth::user()->kontak_darurat_hp) }}"
                                    placeholder="08xx-xxxx-xxxx"
                                    class="form-input">
                                @error('kontak_darurat_hp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- BAGIAN 4: UPLOAD KTP --}}
                    {{-- ============================================ --}}
                    <div class="border-t border-slate-100 pt-8">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-black">4</span>
                            Upload Foto KTP
                        </h3>
                        <p class="text-xs text-slate-500 mb-4 ml-8">Unggah foto KTP Anda yang jelas dan terbaca. Format: JPG, PNG, JPEG. Maks. 2MB.</p>

                        @if(Auth::user()->foto_ktp_url)
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-green-800">Foto KTP sudah tersimpan</p>
                                    <p class="text-xs text-green-700">Anda dapat mengunggah foto baru jika ingin memperbarui.</p>
                                </div>
                                <a href="{{ Storage::url(Auth::user()->foto_ktp_url) }}" target="_blank"
                                    class="text-xs text-green-700 underline hover:text-green-900">Lihat KTP</a>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-400 transition-colors" id="ktp-drop-zone">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            <p class="text-sm text-slate-500 mb-1">Klik atau seret file KTP ke sini</p>
                            <p class="text-xs text-slate-400">JPG, PNG atau JPEG — Maks. 2MB</p>
                            <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*"
                                {{ !Auth::user()->foto_ktp_url ? 'required' : '' }}
                                class="mt-3 block mx-auto text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
                            <div id="ktp-preview" class="hidden mt-4">
                                <img id="ktp-img-preview" src="" class="max-h-40 mx-auto rounded-lg shadow border">
                            </div>
                            @error('foto_ktp') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex gap-3">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-indigo-900"><strong>Informasi:</strong> Pengajuan sewa akan direview oleh admin. Anda belum akan dikenakan tagihan pada tahap ini. Tagihan deposit dan sewa bulan pertama akan diterbitkan setelah pengajuan disetujui.</p>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('kamar.show', $kamar->id) }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview foto KTP setelah dipilih
document.getElementById('foto_ktp').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('ktp-img-preview').src = ev.target.result;
            document.getElementById('ktp-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Validasi NIK: hanya angka, maks 16
document.getElementById('nik').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 16);
});
</script>
@endsection
