@extends('layouts.app')
@section('title', 'Masuk - KosPro')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-soft border border-slate-100">
        <div class="text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-brand mx-auto mb-4">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="white" d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/><path fill="rgba(255,255,255,0.6)" d="M9 22V12h6v10"/></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900">Selamat Datang Kembali</h2>
            <p class="mt-2 text-sm text-slate-500">Masuk ke akun KosPro Anda untuk mengelola kos</p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
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

            <div class="space-y-4">
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" name="email" type="email" required class="form-input" placeholder="contoh@email.com" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required class="form-input" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-slate-700">Ingat Saya</label>
                </div>
                <div class="text-sm">
                    <!-- <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Lupa password?</a> -->
                </div>
            </div>

            <div>
                <button type="submit" class="w-full btn-primary justify-center">
                    Masuk
                </button>
            </div>
            
            <div class="text-center text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar sekarang</a>
            </div>
        </form>
    </div>
</div>
@endsection
