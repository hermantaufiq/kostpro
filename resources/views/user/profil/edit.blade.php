@extends('layouts.app')
@section('title', 'Edit Profil - KosPro')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Pengaturan Profil</h1>
        <p class="text-slate-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Informasi Profil -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden mb-8">
                <div class="p-6 md:p-8 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Informasi Pribadi</h2>
                    <p class="text-sm text-slate-500">Perbarui data diri Anda yang terdaftar di KosPro.</p>
                </div>
                
                @if(session('success'))
                    <div class="m-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.profil.update') }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required class="form-input" maxlength="16">
                                @error('nik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="form-label">Nomor WhatsApp</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="form-input">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}" class="form-input">
                                @error('pekerjaan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="institusi" class="form-label">Institusi / Kampus / Perusahaan</label>
                                <input type="text" id="institusi" name="institusi" value="{{ old('institusi', $user->institusi) }}" class="form-input">
                                @error('institusi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="kontak_darurat" class="form-label">Kontak Darurat (Nomor HP)</label>
                            <input type="text" id="kontak_darurat" name="kontak_darurat" value="{{ old('kontak_darurat', $user->kontak_darurat) }}" required class="form-input">
                            @error('kontak_darurat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden sticky top-24">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Ubah Password</h2>
                    <p class="text-sm text-slate-500">Pastikan akun Anda aman dengan password yang kuat.</p>
                </div>
                
                @if(session('success_password'))
                    <div class="m-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100">
                        {{ session('success_password') }}
                    </div>
                @endif

                <form action="{{ route('user.profil.password') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" required class="form-input">
                            @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" id="password" name="password" required class="form-input">
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-6 py-3 font-semibold text-sm transition-colors">
                                Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
