// Service Worker amélioré pour NiangProgrammeur
// Version: 2.0.0
const CACHE_NAME = 'niangprogrammeur-v2.0.0';
const RUNTIME_CACHE = 'niangprogrammeur-runtime-v2.0.0';
const IMAGE_CACHE = 'niangprogrammeur-images-v2.0.0';
const API_CACHE = 'niangprogrammeur-api-v2.0.0';

// Ressources critiques à mettre en cache immédiatement
const PRECACHE_RESOURCES = [
    '/',
    '/css/critical.css',
    '/js/critical-init.js',
    '/images/logo.png',
    '/manifest.json',
    '/offline.html'
];

// Stratégies de cache
const CACHE_STRATEGIES = {
    // Cache First: Pour les ressources statiques (CSS, JS, images)
    CACHE_FIRST: 'cache-first',
    // Network First: Pour les pages HTML et API
    NETWORK_FIRST: 'network-first',
    // Stale While Revalidate: Pour les ressources qui changent peu
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
    // Network Only: Pour les requêtes critiques
    NETWORK_ONLY: 'network-only'
};

// Installation du Service Worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installation du service worker...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Mise en cache des ressources critiques');
                return cache.addAll(PRECACHE_RESOURCES.map(url => new Request(url, { cache: 'reload' })))
                    .catch((error) => {
                        console.warn('[SW] Erreur lors de la mise en cache:', error);
                        // Continuer même si certaines ressources échouent
                    });
            })
            .then(() => {
                console.log('[SW] Service worker installé');
                return self.skipWaiting(); // Activer immédiatement
            })
    );
});

// Activation du Service Worker
self.addEventListener('activate', (event) => {
    console.log('[SW] Activation du service worker...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        // Supprimer les anciens caches
                        if (cacheName !== CACHE_NAME && 
                            cacheName !== RUNTIME_CACHE && 
                            cacheName !== IMAGE_CACHE && 
                            cacheName !== API_CACHE) {
                            console.log('[SW] Suppression de l\'ancien cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('[SW] Service worker activé');
                return self.clients.claim(); // Prendre le contrôle immédiatement
            })
    );
});

// Gestion des requêtes fetch
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET
    if (request.method !== 'GET') {
        return;
    }

    // Ignorer les requêtes vers des domaines externes (sauf API)
    if (url.origin !== self.location.origin && !url.pathname.startsWith('/api/')) {
        return;
    }

    // Stratégie selon le type de ressource
    if (isImageRequest(request)) {
        event.respondWith(cacheFirstStrategy(request, IMAGE_CACHE));
    } else if (isAPIRequest(request)) {
        event.respondWith(networkFirstStrategy(request, API_CACHE));
    } else if (isStaticAsset(request)) {
        event.respondWith(cacheFirstStrategy(request, RUNTIME_CACHE));
    } else if (isHTMLRequest(request)) {
        event.respondWith(networkFirstStrategy(request, RUNTIME_CACHE));
    } else {
        event.respondWith(staleWhileRevalidateStrategy(request, RUNTIME_CACHE));
    }
});

// Vérifier si c'est une requête d'image
function isImageRequest(request) {
    return request.destination === 'image' || 
           /\.(jpg|jpeg|png|gif|webp|svg|ico)$/i.test(request.url);
}

// Vérifier si c'est une requête API
function isAPIRequest(request) {
    return request.url.includes('/api/') || 
           request.url.includes('/ajax/') ||
           request.headers.get('X-Requested-With') === 'XMLHttpRequest';
}

// Vérifier si c'est une ressource statique
function isStaticAsset(request) {
    return /\.(css|js|woff|woff2|ttf|eot)$/i.test(request.url) ||
           request.url.includes('/css/') ||
           request.url.includes('/js/') ||
           request.url.includes('/fonts/');
}

// Vérifier si c'est une requête HTML
function isHTMLRequest(request) {
    return request.destination === 'document' || 
           request.headers.get('accept')?.includes('text/html');
}

// Stratégie Cache First
async function cacheFirstStrategy(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.warn('[SW] Erreur réseau pour:', request.url, error);
        // Retourner une réponse offline si disponible
        if (isHTMLRequest(request)) {
            const offlinePage = await cache.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
        }
        throw error;
    }
}

// Stratégie Network First
async function networkFirstStrategy(request, cacheName) {
    const cache = await caches.open(cacheName);
    
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.warn('[SW] Réseau indisponible, utilisation du cache pour:', request.url);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Page offline pour les requêtes HTML
        if (isHTMLRequest(request)) {
            const offlinePage = await cache.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
        }
        
        throw error;
    }
}

// Stratégie Stale While Revalidate
async function staleWhileRevalidateStrategy(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    // Lancer la requête réseau en arrière-plan
    const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    }).catch(() => {
        // Ignorer les erreurs réseau
    });
    
    // Retourner le cache immédiatement s'il existe
    if (cachedResponse) {
        return cachedResponse;
    }
    
    // Sinon, attendre la réponse réseau
    return fetchPromise;
}

// Gestion des messages depuis le client
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CACHE_URLS') {
        event.waitUntil(
            caches.open(RUNTIME_CACHE).then((cache) => {
                return cache.addAll(event.data.urls);
            })
        );
    }
    
    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => caches.delete(cacheName))
                );
            })
        );
    }
});

// Gestion des notifications push
self.addEventListener('push', (event) => {
    console.log('[SW] Notification push reçue');
    
    let notificationData = {
        title: 'NiangProgrammeur',
        body: 'Vous avez une nouvelle notification',
        icon: '/images/logo.png',
        badge: '/images/logo.png',
        tag: 'notification',
        requireInteraction: false,
        data: {}
    };
    
    if (event.data) {
        try {
            const data = event.data.json();
            notificationData = {
                ...notificationData,
                ...data
            };
        } catch (e) {
            notificationData.body = event.data.text();
        }
    }
    
    event.waitUntil(
        self.registration.showNotification(notificationData.title, {
            body: notificationData.body,
            icon: notificationData.icon,
            badge: notificationData.badge,
            tag: notificationData.tag,
            requireInteraction: notificationData.requireInteraction,
            data: notificationData.data,
            actions: notificationData.actions || [],
            vibrate: [200, 100, 200],
            timestamp: Date.now()
        })
    );
});

// Gestion du clic sur les notifications
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Clic sur notification:', event.notification.tag);
    
    event.notification.close();
    
    const notificationData = event.notification.data;
    const urlToOpen = notificationData?.url || '/';
    
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((clientList) => {
            // Ouvrir ou focus une fenêtre existante
            for (let client of clientList) {
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // Ouvrir une nouvelle fenêtre
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Gestion de la fermeture des notifications
self.addEventListener('notificationclose', (event) => {
    console.log('[SW] Notification fermée:', event.notification.tag);
});

// Synchronisation en arrière-plan
self.addEventListener('sync', (event) => {
    console.log('[SW] Synchronisation en arrière-plan:', event.tag);
    
    if (event.tag === 'sync-data') {
        event.waitUntil(syncData());
    }
});

async function syncData() {
    // Synchroniser les données en arrière-plan
    try {
        // Ici, vous pouvez ajouter la logique de synchronisation
        console.log('[SW] Synchronisation des données...');
    } catch (error) {
        console.error('[SW] Erreur lors de la synchronisation:', error);
    }
}
