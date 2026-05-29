<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosPro — Sistem Manajemen Kos Modern')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-jakarta">

    <!-- NAVIGATION BAR -->
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

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/kamar') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Cari Kamar</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Dashboard</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Tagihan</a>
                    @endauth
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @guest
                        <div class="hidden md:flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">Daftar Gratis</a>
                        </div>
                    @endguest

                    @auth
                        <!-- Auth User Avatar -->
                        <div class="hidden md:flex items-center gap-2 relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 hover:bg-slate-100 rounded-xl px-3 py-1.5 transition-all">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </button>
                            
                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 top-12 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-slate-50">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-16 min-h-screen">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center text-slate-400 text-sm">
            &copy; {{ date('Y') }} KosPro. Sistem Manajemen Kos Modern.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
