@extends('layouts.app')
@section('title', 'Daftar - KosPro')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-soft border border-slate-100">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-slate-900">Daftar Akun Baru</h2>
            <p class="mt-2 text-sm text-slate-500">Mulai pengalaman sewa kos modern dengan KosPro</p>
        </div>
        
        <form class="mt-8 space-y-5" action="{{ route('register') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" name="name" type="text" required class="form-input" placeholder="Sesuai KTP" value="{{ old('name') }}">
            </div>
            
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input id="email" name="email" type="email" required class="form-input" placeholder="contoh@email.com" value="{{ old('email') }}">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="form-label">No. WhatsApp</label>
                    <input id="phone" name="phone" type="text" required class="form-input" placeholder="08..." value="{{ old('phone') }}">
                </div>
                <div>
                    <label for="nik" class="form-label">NIK KTP</label>
                    <input id="nik" name="nik" type="text" required class="form-input" placeholder="16 Digit" value="{{ old('nik') }}" maxlength="16">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required class="form-input" placeholder="Min 8 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input" placeholder="Ulangi password">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary justify-center">
                    Daftar Sekarang
                </button>
            </div>
            
            <div class="text-center text-sm text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
