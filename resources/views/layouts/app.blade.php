<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosPro — Sistem Manajemen Kos Modern')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ══ PWA / Web App Manifest ══ -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- Apple / iOS PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KosPro">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('icons/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('icons/icon-128x128.png') }}">

    <!-- Microsoft / Windows Meta -->
    <meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="msapplication-tap-highlight" content="no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    @php
        $currentRoute = request()->route()?->getName() ?? '';
        $currentUrl   = request()->url();

        // Helper: apakah route ini aktif?
        function isActive(string $routeName): bool {
            try { return request()->routeIs($routeName . '*'); } catch (\Throwable $e) { return false; }
        }
    @endphp
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-jakarta">

    <!-- ═══════════════════════════════════════════════════════
         NAVIGATION BAR — Desktop & Mobile Top Bar
         ═══════════════════════════════════════════════════════ -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-slate-200 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-brand">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path fill="white" d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/><path fill="rgba(255,255,255,0.6)" d="M9 22V12h6v10"/></svg>
                    </div>
                    <span class="font-bold text-slate-900 text-lg">Kos<span class="text-indigo-600">Pro</span></span>
                </a>

                <!-- Desktop Nav Links (hidden on mobile) -->
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $navLinks = [
                            ['href' => url('/kamar'),                        'label' => 'Cari Kamar',       'route' => 'kamar.*'],
                        ];
                        if (Auth::check()) {
                            $navLinks[] = ['href' => route('dashboard'),             'label' => 'Dashboard',        'route' => 'dashboard'];
                            $navLinks[] = ['href' => route('user.tagihan.index'),    'label' => 'Tagihan',          'route' => 'user.tagihan.*'];
                            $navLinks[] = ['href' => route('user.layanan.index'),    'label' => 'Layanan',          'route' => 'user.layanan.*'];
                            $navLinks[] = ['href' => route('user.keluhan.index'),    'label' => 'Keluhan',          'route' => 'user.keluhan.*'];
                            $navLinks[] = ['href' => route('user.pasar_kos.index'), 'label' => '🏪 Pasar Kos',    'route' => 'user.pasar_kos.*'];
                        }
                    @endphp

                    @foreach($navLinks as $link)
                        @php $active = request()->routeIs($link['route']); @endphp
                        <a href="{{ $link['href'] }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-all
                                  {{ $active
                                     ? 'bg-indigo-50 text-indigo-700 font-semibold'
                                     : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                            {{ $link['label'] }}
                            @if($active)
                                <span class="sr-only">(halaman aktif)</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Right: Auth Actions -->
                <div class="flex items-center gap-3">
                    @guest
                        <div class="hidden md:flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">Daftar Gratis</a>
                        </div>
                        <!-- Mobile guest: tampilkan tombol masuk -->
                        <div class="md:hidden flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm font-semibold text-indigo-600 border border-indigo-200 rounded-lg">Masuk</a>
                        </div>
                    @endguest

                    @auth
                        <!-- Avatar Dropdown (Desktop) -->
                        <div class="hidden md:flex items-center gap-2 relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 hover:bg-slate-100 rounded-xl px-3 py-1.5 transition-all">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 @click.away="open = false"
                                 style="display: none;"
                                 class="absolute right-0 top-12 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">

                                <!-- Profil Header -->
                                <div class="px-4 py-3 border-b border-slate-50">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <div class="flex items-center gap-1 mt-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        @php $voucherAktifCount = \App\Models\Voucher::aktif()->where('user_id', Auth::id())->count(); @endphp
                                        <span class="text-xs font-bold">{{ $voucherAktifCount }} Voucher Aktif</span>
                                    </div>
                                </div>

                                <div class="py-2">
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }} transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        Dashboard Utama
                                    </a>
                                    <a href="{{ route('user.profil.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('user.profil.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }} transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil & Pengaturan
                                    </a>
                                    <a href="{{ route('user.penyewaan.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('user.penyewaan.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }} transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        Kamar & Sewa Saya
                                    </a>
                                </div>

                                <div class="h-px bg-slate-100 my-1"></div>

                                <div class="py-1">
                                    <a href="{{ route('user.tagihan.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('user.tagihan.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }} transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        Riwayat Tagihan
                                    </a>
                                    <a href="{{ route('user.panduan') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        Panduan Penghuni
                                    </a>
                                </div>

                                <div class="h-px bg-slate-100 my-1"></div>

                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar Akun
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile: Avatar initials only (top right) -->
                        <div class="md:hidden">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT — pb-20 on mobile to avoid bottom nav overlap -->
    <main class="pt-16 min-h-screen pb-20 md:pb-0">
        @yield('content')
    </main>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER — Multi-column, modern
         ═══════════════════════════════════════════════════════ -->
    <footer class="bg-slate-900 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">

                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path fill="white" d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/><path fill="rgba(255,255,255,0.6)" d="M9 22V12h6v10"/></svg>
                        </div>
                        <span class="font-bold text-white text-lg">Kos<span class="text-indigo-400">Pro</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">Sistem manajemen kos modern untuk penghuni dan pengelola yang lebih cerdas.</p>
                    <!-- Social Links -->
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-indigo-600 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-slate-400 hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-indigo-600 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Navigasi -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ url('/kamar') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Cari Kamar</a></li>
                        @auth
                        <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Dashboard</a></li>
                        <li><a href="{{ route('user.tagihan.index') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Tagihan Saya</a></li>
                        <li><a href="{{ route('user.keluhan.index') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Lapor Keluhan</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Layanan -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Layanan</h4>
                    <ul class="space-y-2.5">
                        @auth
                        <li><a href="{{ route('user.layanan.index') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Layanan Tambahan</a></li>
                        <li><a href="{{ route('user.pasar_kos.index') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Pasar Kos</a></li>
                        <li><a href="{{ route('user.panduan') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Panduan Penghuni</a></li>
                        @endauth
                        <li><a href="{{ url('/kamar') }}" class="text-slate-400 hover:text-indigo-400 text-sm transition-colors">Lihat Kamar Tersedia</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-slate-400 text-sm">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            admin@kospro.id
                        </li>
                        <li class="flex items-center gap-2 text-slate-400 text-sm">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +62 812-3456-7890
                        </li>
                        <li class="flex items-start gap-2 text-slate-400 text-sm">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Jl. Kos Indah No. 1, Kota
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} KosPro. Sistem Manajemen Kos Modern.</p>
                <div class="flex items-center gap-4 text-xs text-slate-600">
                    <a href="#" class="hover:text-slate-400 transition-colors">Kebijakan Privasi</a>
                    <span>·</span>
                    <a href="#" class="hover:text-slate-400 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════════════════
         MOBILE BOTTOM NAVIGATION BAR
         Hanya tampil di mobile (md:hidden), mirip Tokopedia/Gojek
         ═══════════════════════════════════════════════════════ -->
    @auth
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        <div class="grid grid-cols-5 h-16">

            <!-- Beranda -->
            @php $isHome = request()->routeIs('dashboard'); @endphp
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 transition-colors {{ $isHome ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                <svg class="w-5 h-5" fill="{{ $isHome ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] font-{{ $isHome ? 'bold' : 'medium' }}">Beranda</span>
                @if($isHome)<div class="absolute bottom-1 w-1 h-1 rounded-full bg-indigo-500"></div>@endif
            </a>

            <!-- Kamar -->
            @php $isKamar = request()->is('kamar*'); @endphp
            <a href="{{ url('/kamar') }}" class="flex flex-col items-center justify-center gap-1 transition-colors {{ $isKamar ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                <svg class="w-5 h-5" fill="{{ $isKamar ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="text-[10px] font-{{ $isKamar ? 'bold' : 'medium' }}">Cari</span>
            </a>

            <!-- Tagihan (tengah — highlighted) -->
            @php $isTagihan = request()->routeIs('user.tagihan.*'); @endphp
            <a href="{{ route('user.tagihan.index') }}" class="flex flex-col items-center justify-center relative">
                <div class="absolute -top-5 w-12 h-12 rounded-full shadow-lg flex items-center justify-center
                             {{ $isTagihan ? 'bg-indigo-700' : 'bg-indigo-600' }} hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-[10px] font-medium text-indigo-600 mt-6">Tagihan</span>
            </a>

            <!-- Keluhan -->
            @php $isKeluhan = request()->routeIs('user.keluhan.*'); @endphp
            <a href="{{ route('user.keluhan.index') }}" class="flex flex-col items-center justify-center gap-1 transition-colors {{ $isKeluhan ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                <svg class="w-5 h-5" fill="{{ $isKeluhan ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-[10px] font-{{ $isKeluhan ? 'bold' : 'medium' }}">Keluhan</span>
            </a>

            <!-- Profil -->
            @php $isProfil = request()->routeIs('user.profil.*'); @endphp
            <a href="{{ route('user.profil.edit') }}" class="flex flex-col items-center justify-center gap-1 transition-colors {{ $isProfil ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                <div class="w-5 h-5 rounded-full {{ $isProfil ? 'bg-indigo-600' : 'bg-slate-300' }} flex items-center justify-center text-white text-[10px] font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="text-[10px] font-{{ $isProfil ? 'bold' : 'medium' }}">Profil</span>
            </a>

        </div>
    </nav>
    @endauth

    @stack('scripts')

    {{-- ═══════════════════════════════════════════════════════
         PWA INSTALL BANNER
         Muncul otomatis saat browser siap untuk diinstall
         ═══════════════════════════════════════════════════════ --}}
    <div id="pwa-install-banner"
         style="display:none; position:fixed; bottom:5rem; left:1rem; right:1rem; z-index:9999;
                max-width:420px; margin:0 auto;"
         class="md:bottom-6">
        <div style="background:white; border-radius:1.25rem; padding:1rem 1.25rem;
                    box-shadow:0 20px 60px rgba(0,0,0,0.15), 0 0 0 1px rgba(79,70,229,0.1);
                    display:flex; align-items:center; gap:0.875rem;">
            {{-- App Icon --}}
            <img src="{{ asset('icons/icon-96x96.png') }}" alt="KosPro"
                 style="width:52px; height:52px; border-radius:14px; flex-shrink:0; box-shadow:0 4px 12px rgba(79,70,229,0.25);">

            {{-- Text --}}
            <div style="flex:1; min-width:0;">
                <p style="font-weight:800; font-size:0.9rem; color:#0f172a; margin-bottom:2px;">Pasang KosPro di HP</p>
                <p style="font-size:0.75rem; color:#64748b;">Akses lebih cepat dari home screen!</p>
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:0.5rem; flex-shrink:0;">
                <button id="pwa-install-btn"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white;
                               border:none; border-radius:50px; padding:0.5rem 1rem;
                               font-weight:700; font-size:0.8rem; cursor:pointer;
                               box-shadow:0 4px 12px rgba(79,70,229,0.3);">
                    Pasang
                </button>
                <button id="pwa-install-close"
                        style="background:#f1f5f9; border:none; border-radius:50px;
                               width:36px; height:36px; cursor:pointer; display:flex;
                               align-items:center; justify-content:center; color:#94a3b8;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         PWA UPDATE BANNER
         Muncul saat ada versi baru service worker tersedia
         ═══════════════════════════════════════════════════════ --}}
    <div id="pwa-update-banner"
         style="display:none; position:fixed; top:4.5rem; left:1rem; right:1rem; z-index:9999;
                max-width:420px; margin:0 auto;">
        <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed); border-radius:1rem;
                    padding:0.875rem 1.25rem; box-shadow:0 8px 30px rgba(79,70,229,0.4);
                    display:flex; align-items:center; gap:0.75rem; color:white;">
            <svg style="width:20px; height:20px; flex-shrink:0; animation:spin 2s linear infinite;"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <p style="flex:1; font-size:0.8125rem; font-weight:600;">Update tersedia! Muat ulang untuk versi terbaru.</p>
            <button id="pwa-update-btn"
                    style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);
                           color:white; border-radius:50px; padding:0.35rem 0.875rem;
                           font-weight:700; font-size:0.75rem; cursor:pointer; white-space:nowrap;">
                Muat Ulang
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         PWA JAVASCRIPT
         Service Worker registration + Install Prompt handling
         ═══════════════════════════════════════════════════════ --}}
    <script>
    (function() {
        'use strict';

        let deferredPrompt = null;
        const installBanner  = document.getElementById('pwa-install-banner');
        const installBtn     = document.getElementById('pwa-install-btn');
        const installClose   = document.getElementById('pwa-install-close');
        const updateBanner   = document.getElementById('pwa-update-banner');
        const updateBtn      = document.getElementById('pwa-update-btn');

        // ── Service Worker Registration ──────────────────────
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        // Cek update saat SW baru ditemukan
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // Ada versi baru! Tampilkan banner update
                                    showUpdateBanner(newWorker);
                                }
                            });
                        });
                    })
                    .catch(err => console.warn('[SW] Registration failed:', err));

                // Reload saat SW baru aktif
                let refreshing = false;
                navigator.serviceWorker.addEventListener('controllerchange', () => {
                    if (!refreshing) {
                        refreshing = true;
                        window.location.reload();
                    }
                });
            });
        }

        // ── Install Prompt ───────────────────────────────────
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            deferredPrompt = e;

            // Jangan tampilkan jika sudah pernah dismiss
            if (!sessionStorage.getItem('pwa-install-dismissed')) {
                setTimeout(() => {
                    if (installBanner) installBanner.style.display = 'block';
                }, 3000); // Delay 3 detik setelah halaman load
            }
        });

        // Tombol Pasang
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                installBanner.style.display = 'none';
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                console.log('[PWA] Install outcome:', outcome);
            });
        }

        // Tombol Close
        if (installClose) {
            installClose.addEventListener('click', () => {
                installBanner.style.display = 'none';
                sessionStorage.setItem('pwa-install-dismissed', '1');
            });
        }

        // Saat berhasil diinstall
        window.addEventListener('appinstalled', () => {
            installBanner.style.display = 'none';
            deferredPrompt = null;
            console.log('[PWA] App installed successfully!');
        });

        // ── Update Banner ────────────────────────────────────
        function showUpdateBanner(newWorker) {
            if (!updateBanner) return;
            updateBanner.style.display = 'block';

            if (updateBtn) {
                updateBtn.addEventListener('click', () => {
                    newWorker.postMessage({ type: 'SKIP_WAITING' });
                    updateBanner.style.display = 'none';
                });
            }
        }

        // Inject CSS keyframes untuk animasi
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            #pwa-install-banner { animation: slideUpIn 0.4s cubic-bezier(0.34,1.56,0.64,1); }
            @keyframes slideUpIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        `;
        document.head.appendChild(style);
    })();
    </script>
</body>
</html>
