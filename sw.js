const CACHE_NAME = 'severgoth-cache-v3';
const urlsToCache = [
  '/',
  '/pleer.html',
  '/1.png',
  '/manifest.json',
  '/tracks.txt'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  if (event.request.url.endsWith('.mp3')) {
    event.respondWith(
      fetch(event.request).catch(() => caches.match(event.request))
    );
  } else {
    event.respondWith(
      caches.match(event.request).then(response => {
        if (response) {
          // Проверяем, не устарел ли кэш для tracks.txt
          if (event.request.url.endsWith('tracks.txt')) {
            return fetch(event.request).catch(() => response);
          }
          return response;
        }
        return fetch(event.request).then(networkResponse => {
          if (networkResponse.ok) {
            const cacheResponse = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(event.request, cacheResponse));
          }
          return networkResponse;
        });
      })
    );
  }
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (!cacheWhitelist.includes(cacheName)) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});