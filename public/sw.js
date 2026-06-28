// ============================================================
// KosPro — Service Worker v3
// Strategi: Cache-first untuk aset statis, Network-first untuk halaman
// ============================================================

const CACHE_VERSION = 'v3';
const STATIC_CACHE  = `kospro-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `kospro-dynamic-${CACHE_VERSION}`;

// Aset statis yang selalu di-cache saat install
const STATIC_ASSETS = [
  '/offline',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/manifest.json',
];

// Prefix URL yang dianggap "halaman dinamis" (selalu network-first)
const DYNAMIC_ROUTES = [
  '/dashboard',
  '/user/',
  '/kamar',
  '/payment',
  '/logout',
];

// ── INSTALL ──────────────────────────────────────────────────
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .then(() => self.skipWaiting()) // Langsung aktif tanpa nunggu tab ditutup
  );
});

// ── ACTIVATE ─────────────────────────────────────────────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== STATIC_CACHE && name !== DYNAMIC_CACHE)
          .map(name => caches.delete(name))
      );
    }).then(() => self.clients.claim()) // Ambil kontrol semua tab langsung
  );
});

// ── FETCH ────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Abaikan request non-GET, extension, dan cross-origin
  if (request.method !== 'GET') return;
  if (!url.origin.startsWith(self.location.origin)) return;
  if (url.pathname.startsWith('/admin')) return; // Jangan cache Filament admin
  if (url.pathname.startsWith('/_debugbar')) return;

  // ── Strategi untuk aset statis (CSS, JS, font, gambar) ──
  if (isStaticAsset(url.pathname)) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // ── Strategi untuk halaman dinamis ──
  event.respondWith(networkFirstWithOfflineFallback(request));
});

// ── HELPER: Deteksi aset statis ──────────────────────────────
function isStaticAsset(pathname) {
  return (
    pathname.startsWith('/build/') ||
    pathname.startsWith('/css/') ||
    pathname.startsWith('/js/') ||
    pathname.startsWith('/fonts/') ||
    pathname.startsWith('/images/') ||
    pathname.startsWith('/icons/') ||
    pathname.endsWith('.ico') ||
    pathname.endsWith('.woff2') ||
    pathname.endsWith('.woff') ||
    pathname.endsWith('.ttf')
  );
}

// ── Strategi: Cache-First (untuk aset statis) ────────────────
async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(STATIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch {
    return new Response('Asset not available offline', { status: 503 });
  }
}

// ── Strategi: Network-First + Offline Fallback (untuk halaman) ──
async function networkFirstWithOfflineFallback(request) {
  try {
    const networkResponse = await fetch(request);

    // Cache respons yang berhasil di dynamic cache
    if (networkResponse.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, networkResponse.clone());
    }

    return networkResponse;
  } catch {
    // Coba ambil dari cache dulu
    const cached = await caches.match(request);
    if (cached) return cached;

    // Kalau tidak ada di cache, tampilkan halaman offline
    const offlinePage = await caches.match('/offline');
    if (offlinePage) return offlinePage;

    // Last resort
    return new Response(
      '<html><body><h1>Offline</h1><p>Tidak ada koneksi internet.</p></body></html>',
      { headers: { 'Content-Type': 'text/html' }, status: 503 }
    );
  }
}

// ── MESSAGE: Skip waiting (dari install banner) ──────────────
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
