// Service Worker básico: cachea el "cascarón" visual (CSS/JS/íconos) para que
// la app cargue instantáneo y se pueda abrir aunque la señal falle un momento.
// A propósito NO cachea las páginas dinámicas (dashboard, formularios) ni
// hace envíos offline: esos siempre van directo al servidor para respetar
// las reglas de negocio (turno abierto, horario permitido, etc.).

const CACHE_NAME = 'canopy-shell-v1';
const SHELL_ASSETS = [
  '/manifest.json',
  '/icons/icon-192.png',
  '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(SHELL_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Solo intervenimos peticiones GET de archivos estáticos (css/js/imágenes).
  // Todo lo demás (POST de formularios, páginas con datos) va siempre a la red.
  if (request.method !== 'GET') return;

  const url = new URL(request.url);
  const esArchivoEstatico = /\.(css|js|png|jpg|jpeg|svg|woff2?)$/.test(url.pathname);

  if (!esArchivoEstatico) return;

  event.respondWith(
    caches.match(request).then((cached) => {
      return (
        cached ||
        fetch(request).then((response) => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
          return response;
        })
      );
    })
  );
});
