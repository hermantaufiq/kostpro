<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tidak Ada Koneksi — KosPro</title>
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --indigo: #4f46e5;
            --indigo-dark: #3730a3;
            --violet: #7c3aed;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-700: #334155;
            --slate-900: #0f172a;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--slate-50);
            color: var(--slate-900);
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            pointer-events: none;
        }
        .blob-1 {
            width: 400px; height: 400px;
            background: var(--indigo);
            top: -100px; left: -100px;
        }
        .blob-2 {
            width: 300px; height: 300px;
            background: var(--violet);
            bottom: -80px; right: -80px;
        }

        .card {
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 2rem;
            padding: 3rem 2.5rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
        }

        /* Animated wifi-off icon */
        .icon-wrap {
            width: 96px;
            height: 96px;
            background: linear-gradient(135deg, #eef2ff, #ede9fe);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.75rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .icon-wrap svg {
            width: 52px;
            height: 52px;
            color: var(--indigo);
        }

        /* Pulse dots */
        .pulse-dots {
            display: flex;
            gap: 6px;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .pulse-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--indigo);
            opacity: 0.3;
            animation: pulse-dot 1.4s ease-in-out infinite;
        }
        .pulse-dot:nth-child(2) { animation-delay: 0.2s; }
        .pulse-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes pulse-dot {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.3; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.75rem;
        }

        .subtitle {
            font-size: 0.9375rem;
            color: var(--slate-500);
            line-height: 1.65;
            margin-bottom: 2rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            color: white;
            font-weight: 700;
            font-size: 0.9375rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.45);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary svg {
            width: 18px; height: 18px;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--slate-100);
            margin: 2rem 0 1.5rem;
        }

        .offline-features h3 {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 1rem;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.875rem;
            color: var(--slate-700);
            font-weight: 500;
        }

        .feature-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon svg { width: 16px; height: 16px; }

        /* Logo at top */
        .logo {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        .logo-text span { color: var(--indigo); }

        /* Status indicator */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #ef4444;
            background: #fef2f2;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            margin-bottom: 1.25rem;
            border: 1px solid #fee2e2;
        }

        .status-dot {
            width: 7px; height: 7px;
            background: #ef4444;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Background blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <!-- Logo -->
        <a href="/" class="logo">
            <div class="logo-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path fill="white" d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
                    <path fill="rgba(255,255,255,0.6)" d="M9 22V12h6v10"/>
                </svg>
            </div>
            <span class="logo-text">Kos<span>Pro</span></span>
        </a>

        <!-- Card -->
        <div class="card">
            <!-- Floating wifi-off icon -->
            <div class="icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/>
                    <line x1="2" y1="2" x2="22" y2="22" stroke-linecap="round" stroke-width="1.5"/>
                </svg>
            </div>

            <!-- Status -->
            <div class="status-badge">
                <div class="status-dot"></div>
                Tidak Ada Koneksi
            </div>

            <h1>Ups, Kamu Offline!</h1>
            <p class="subtitle">
                KosPro membutuhkan koneksi internet untuk menampilkan data terbaru. 
                Pastikan WiFi atau data selulermu aktif, lalu coba lagi.
            </p>

            <!-- Pulse dots while "checking" -->
            <div class="pulse-dots" id="pulseDots" style="display:none;">
                <div class="pulse-dot"></div>
                <div class="pulse-dot"></div>
                <div class="pulse-dot"></div>
            </div>

            <button class="btn-primary" onclick="tryReconnect()" id="retryBtn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Coba Lagi
            </button>

            <hr class="divider">

            <div class="offline-features">
                <h3>Yang bisa dilihat saat offline</h3>
                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-icon" style="background:#eef2ff; color:#4f46e5;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        Halaman yang sudah dibuka sebelumnya
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon" style="background:#f0fdf4; color:#16a34a;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        Info tagihan yang di-cache
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon" style="background:#fff7ed; color:#ea580c;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        Voucher aktif Anda
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function tryReconnect() {
            const btn = document.getElementById('retryBtn');
            const dots = document.getElementById('pulseDots');

            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `
                <svg class="spin" style="animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Mengecek koneksi...
            `;
            dots.style.display = 'flex';

            // Coba fetch ke server
            fetch('/manifest.json', { cache: 'no-store' })
                .then(() => {
                    // Berhasil! Reload halaman
                    window.location.reload();
                })
                .catch(() => {
                    // Masih offline
                    setTimeout(() => {
                        btn.style.opacity = '1';
                        btn.style.pointerEvents = 'auto';
                        btn.innerHTML = `
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Coba Lagi
                        `;
                        dots.style.display = 'none';
                    }, 2000);
                });
        }

        // Otomatis reload saat koneksi kembali
        window.addEventListener('online', () => {
            window.location.reload();
        });

        // Inject keyframe animation
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>
</html>
