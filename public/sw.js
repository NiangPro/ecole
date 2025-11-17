// Service Worker pour NiangProgrammeur - PWA
const CACHE_NAME = 'niangprogrammeur-v1';
const STATIC_CACHE = 'niangprogrammeur-static-v1';
const DYNAMIC_CACHE = 'niangprogrammeur-dynamic-v1';

// Assets statiques à mettre en cache immédiatement
const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/images/logo.png',
    // Ajouter d'autres assets statiques importants
];

// Installer le Service Worker
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            console.log('[Service Worker] Caching static assets');
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.log('[Service Worker] Error caching static assets:', err);
            });
        })
    );
    self.skipWaiting();
});

// Activer le Service Worker
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Intercepter les requêtes réseau
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET
    if (request.method !== 'GET') {
        return;
    }

    // Ignorer les requêtes vers des domaines externes (sauf pour les images CDN)
    if (url.origin !== location.origin && !url.hostname.includes('cdn') && !url.hostname.includes('images.unsplash.com')) {
        return;
    }

    // Stratégie: Cache First pour les assets statiques
    if (request.destination === 'image' || 
        request.destination === 'script' || 
        request.destination === 'style' ||
        url.pathname.match(/\.(jpg|jpeg|png|gif|svg|webp|css|js|woff|woff2|ttf|eot)$/)) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then((response) => {
                    // Vérifier si la réponse est valide
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    // Cloner la réponse pour la mettre en cache
                    const responseToCache = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                }).catch(() => {
                    // Fallback pour les images
                    if (request.destination === 'image') {
                        return new Response(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><rect width="400" height="300" fill="#e2e8f0"/><text x="50%" y="50%" text-anchor="middle" fill="#94a3b8" font-family="Arial" font-size="20">Image non disponible</text></svg>',
                            { headers: { 'Content-Type': 'image/svg+xml' } }
                        );
                    }
                });
            })
        );
        return;
    }

    // Stratégie: Network First pour les pages HTML
    if (request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Vérifier si la réponse est valide
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    // Cloner la réponse pour la mettre en cache
                    const responseToCache = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                })
                .catch(() => {
                    // Fallback vers le cache si le réseau échoue
                    return caches.match(request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // Fallback vers la page d'accueil
                        return caches.match('/');
                    });
                })
        );
        return;
    }

    // Pour les autres requêtes, utiliser Network First
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }
                const responseToCache = response.clone();
                caches.open(DYNAMIC_CACHE).then((cache) => {
                    cache.put(request, responseToCache);
                });
                return response;
            })
            .catch(() => {
                return caches.match(request);
            })
    );
});

// Nettoyer le cache dynamique (garder seulement les 50 dernières requêtes)
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'CLEAN_CACHE') {
        caches.open(DYNAMIC_CACHE).then((cache) => {
            cache.keys().then((keys) => {
                if (keys.length > 50) {
                    cache.delete(keys[0]);
                }
            });
        });
    }
});

