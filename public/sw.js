// Service Worker pour NiangProgrammeur - PWA Mode Hors Ligne Complet
const CACHE_VERSION = 'v3.0.0';
const STATIC_CACHE = `niangprogrammeur-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `niangprogrammeur-dynamic-${CACHE_VERSION}`;
const CONTENT_CACHE = `niangprogrammeur-content-${CACHE_VERSION}`;

// Assets statiques critiques à mettre en cache immédiatement
const STATIC_ASSETS = [
    '/',
    '/offline.html',
    '/css/app.css',
    '/css/ux-improvements.css',
    '/js/app.js',
    '/js/main.js',
    '/js/pwa.js',
    '/js/ux-improvements.js',
    '/images/logo.png',
    '/manifest.json',
];

// Pages importantes à pré-cacher (formations, exercices, quiz)
const IMPORTANT_PAGES = [
    '/formations',
    '/exercices',
    '/quiz',
    '/about',
    '/contact',
    '/faq',
];

// Routes de formations à pré-cacher
const FORMATION_ROUTES = [
    '/formations/html5',
    '/formations/css3',
    '/formations/javascript',
    '/formations/php',
    '/formations/python',
    '/formations/java',
    '/formations/cpp',
    '/formations/csharp',
    '/formations/dart',
];

// Installer le Service Worker
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installation...', CACHE_VERSION);
    
    event.waitUntil(
        Promise.all([
            // Cache des assets statiques
            caches.open(STATIC_CACHE).then((cache) => {
                console.log('[Service Worker] Mise en cache des assets statiques');
                return cache.addAll(STATIC_ASSETS).catch((err) => {
                    console.warn('[Service Worker] Erreur lors du cache statique:', err);
                });
            }),
            // Pré-cache des pages importantes (en arrière-plan)
            caches.open(CONTENT_CACHE).then((cache) => {
                console.log('[Service Worker] Pré-cache des pages importantes');
                // Ne pas bloquer l'installation si certaines pages échouent
                return Promise.allSettled(
                    IMPORTANT_PAGES.map(url => 
                        fetch(url).then(response => {
                            if (response.ok) {
                                return cache.put(url, response);
                            }
                        }).catch(() => {
                            // Ignorer les erreurs pour ne pas bloquer l'installation
                        })
                    )
                );
            })
        ])
    );
    
    // Forcer l'activation immédiate
    self.skipWaiting();
});

// Activer le Service Worker
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activation...', CACHE_VERSION);
    
    event.waitUntil(
        Promise.all([
            // Nettoyer les anciens caches
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== STATIC_CACHE && 
                            cacheName !== DYNAMIC_CACHE && 
                            cacheName !== CONTENT_CACHE) {
                            console.log('[Service Worker] Suppression de l\'ancien cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            }),
            // Prendre le contrôle de tous les clients
            self.clients.claim()
        ])
    );
});

// Fonction pour vérifier si une URL doit être mise en cache
function shouldCache(url) {
    // Ne pas mettre en cache les requêtes API, les formulaires POST, etc.
    if (url.pathname.startsWith('/admin') || 
        url.pathname.startsWith('/api/') ||
        url.pathname.includes('/run') ||
        url.pathname.includes('/submit')) {
        return false;
    }
    return true;
}

// Fonction pour obtenir une réponse depuis le cache ou le réseau
async function getFromCacheOrNetwork(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
            // Mettre en cache pour la prochaine fois
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        // Si c'est une page HTML et qu'on est hors ligne, retourner offline.html
        if (request.headers.get('accept') && request.headers.get('accept').includes('text/html')) {
            const offlinePage = await cache.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
        }
        throw error;
    }
}

// Intercepter les requêtes réseau
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Ignorer les requêtes non-GET
    if (request.method !== 'GET') {
        return;
    }
    
    // Ignorer les requêtes vers des domaines externes (sauf CDN autorisés)
    if (url.origin !== location.origin && 
        !url.hostname.includes('cdn') && 
        !url.hostname.includes('fonts.googleapis.com') &&
        !url.hostname.includes('fonts.gstatic.com') &&
        !url.hostname.includes('images.unsplash.com')) {
        return;
    }
    
    // Vérifier si on doit mettre en cache
    if (!shouldCache(url)) {
        return;
    }
    
    event.respondWith(handleRequest(request, url));
});

// Fonction principale de gestion des requêtes
async function handleRequest(request, url) {
    // Stratégie: Cache First pour les assets statiques
    if (request.destination === 'image' || 
        request.destination === 'script' || 
        request.destination === 'style' ||
        request.destination === 'font' ||
        url.pathname.match(/\.(jpg|jpeg|png|gif|svg|webp|css|js|woff|woff2|ttf|eot|ico)$/)) {
        
        return caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            
            return fetch(request).then((response) => {
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                }
                return response;
            }).catch(() => {
                // Fallback pour les images
                if (request.destination === 'image') {
                    return new Response(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><rect width="400" height="300" fill="#e2e8f0"/><text x="50%" y="50%" text-anchor="middle" fill="#94a3b8" font-family="Arial" font-size="20">Image non disponible</text></svg>',
                        { headers: { 'Content-Type': 'image/svg+xml' } }
                    );
                }
                return new Response('Ressource non disponible hors ligne', { 
                    status: 503,
                    headers: { 'Content-Type': 'text/plain' }
                });
            });
        });
    }
    
    // Stratégie: Network First avec fallback cache pour les pages HTML
    if (request.headers.get('accept') && request.headers.get('accept').includes('text/html')) {
        try {
            // Essayer d'abord le réseau
            const networkResponse = await fetch(request);
            
            if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                // Mettre en cache pour la prochaine fois
                const responseToCache = networkResponse.clone();
                caches.open(DYNAMIC_CACHE).then((cache) => {
                    cache.put(request, responseToCache);
                });
                return networkResponse;
            }
            
            // Si la réponse n'est pas OK, essayer le cache
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
            
            // Si pas de cache, retourner offline.html
            const offlinePage = await caches.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
            
            return networkResponse;
        } catch (error) {
            // Réseau indisponible, essayer le cache
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
            
            // Si pas de cache, retourner offline.html
            const offlinePage = await caches.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
            
            // Dernier recours: page d'accueil
            const homePage = await caches.match('/');
            if (homePage) {
                return homePage;
            }
            
            // Fallback final
            return new Response('Mode hors ligne - Contenu non disponible', {
                status: 503,
                headers: { 'Content-Type': 'text/html; charset=utf-8' }
            });
        }
    }
    
    // Pour les autres requêtes (API, JSON, etc.): Network First
    return fetch(request)
        .then((response) => {
            if (response && response.status === 200 && response.type === 'basic') {
                const responseToCache = response.clone();
                caches.open(DYNAMIC_CACHE).then((cache) => {
                    cache.put(request, responseToCache);
                });
            }
            return response;
        })
        .catch(() => {
            // Essayer le cache en cas d'échec réseau
            return caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return new Response('Ressource non disponible hors ligne', { 
                    status: 503,
                    headers: { 'Content-Type': 'text/plain' }
                });
            });
        });
}

// Gestion des messages depuis le client
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CLEAN_CACHE') {
        cleanOldCache();
    }
    
    if (event.data && event.data.type === 'CACHE_PAGE') {
        const url = event.data.url;
        if (url) {
            cachePage(url);
        }
    }
});

// Fonction pour nettoyer le cache dynamique (garder les 100 dernières requêtes)
async function cleanOldCache() {
    const cache = await caches.open(DYNAMIC_CACHE);
    const keys = await cache.keys();
    
    if (keys.length > 100) {
        // Supprimer les 20 plus anciennes entrées
        const toDelete = keys.slice(0, 20);
        await Promise.all(toDelete.map(key => cache.delete(key)));
        console.log('[Service Worker] Cache nettoyé:', toDelete.length, 'entrées supprimées');
    }
}

// Fonction pour mettre en cache une page spécifique
async function cachePage(url) {
    try {
        const response = await fetch(url);
        if (response && response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            await cache.put(url, response.clone());
            console.log('[Service Worker] Page mise en cache:', url);
        }
    } catch (error) {
        console.warn('[Service Worker] Erreur lors de la mise en cache de:', url, error);
    }
}

// Nettoyer le cache toutes les heures
setInterval(() => {
    cleanOldCache();
}, 3600000);

// Pré-cache des formations populaires en arrière-plan
self.addEventListener('message', async (event) => {
    if (event.data && event.data.type === 'PRE_CACHE_FORMATIONS') {
        const cache = await caches.open(CONTENT_CACHE);
        const formations = FORMATION_ROUTES;
        
        for (const formation of formations) {
            try {
                const response = await fetch(formation);
                if (response && response.ok) {
                    await cache.put(formation, response.clone());
                    console.log('[Service Worker] Formation pré-cachée:', formation);
                }
            } catch (error) {
                console.warn('[Service Worker] Erreur pré-cache formation:', formation);
            }
        }
    }
});
