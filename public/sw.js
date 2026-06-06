const CACHE_NAME = 'kospro-v2'; // Cache dinaikkan versinya
const urlsToCache = [
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Hapus cache versi lama saat aktivasi
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Network-first strategy: Ambil dari internet dulu, kalau gagal (offline) baru ambil dari cache
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request)
      .then(networkResponse => {
        // Jangan cache HTML, biar UI selalu update (opsional, tapi aman)
        // Kita hanya cache aset statis jika diperlukan
        return networkResponse;
      })
      .catch(() => {
        // Kalau offline, coba ambil dari cache
        return caches.match(event.request);
      })
  );
});
