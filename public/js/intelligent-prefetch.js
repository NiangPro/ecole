// Préchargement intelligent basé sur l'étude de cas Terra
// Adapté pour NiangProgrammeur

class IntelligentPrefetcher {
    constructor() {
        this.prefetchQueue = new Set();
        this.observer = null;
        this.isInitialized = false;
    }

    // Vérifier si le préchargement est approprié
    shouldPrefetch() {
        // Vérifier la connexion réseau
        if (navigator.connection) {
            const connection = navigator.connection;
            // Exclure les connexions lentes (< 3G)
            if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
                console.log('📡 Connexion trop lente pour le préchargement');
                return false;
            }
            
            // Vérifier si la connexion est limitée (data saver)
            if (connection.saveData) {
                console.log('💾 Mode économie de données activé');
                return false;
            }
        }

        // Vérifier la mémoire de l'appareil
        if (navigator.deviceMemory && navigator.deviceMemory <= 2) {
            console.log('🧠 Mémoire insuffisante pour le préchargement');
            return false;
        }

        // iOS est toujours autorisé (comme dans l'étude Terra)
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        if (isIOS) {
            console.log('🍎 iOS détecté - préchargement autorisé');
            return true;
        }

        return true;
    }

    // Précharger une URL avec priorité basse
    prefetchUrl(url) {
        if (this.prefetchQueue.has(url)) {
            return; // Déjà en file d'attente
        }

        this.prefetchQueue.add(url);
        
        // Utiliser fetch avec priorité basse
        fetch(url, { 
            priority: 'low',
            headers: {
                'X-Prefetch': 'true',
                'Purpose': 'prefetch'
            }
        }).then(response => {
            console.log('✅ Préchargé:', url);
        }).catch(error => {
            console.log('❌ Erreur préchargement:', url, error);
        }).finally(() => {
            this.prefetchQueue.delete(url);
        });
    }

    // Initialiser l'observer pour les liens visibles
    initializeObserver() {
        if (!this.shouldPrefetch()) {
            console.log('⏸️ Préchargement désactivé - conditions non optimales');
            return;
        }

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const link = entry.target;
                    const url = link.href || link.dataset.href;
                    
                    if (url && !this.prefetchQueue.has(url)) {
                        console.log('👁️ Lien visible, préchargement:', url);
                        this.prefetchUrl(url);
                        this.observer.unobserve(link);
                    }
                }
            });
        }, {
            rootMargin: '50px' // Commencer 50px avant la visibilité
        });

        console.log('🚀 Préchargement intelligent activé');
        this.isInitialized = true;
    }

    // Observer les liens spécifiques
    observeLinks(selector) {
        if (!this.isInitialized) {
            this.initializeObserver();
        }

        const links = document.querySelectorAll(selector);
        links.forEach(link => {
            if (this.observer) {
                this.observer.observe(link);
            }
        });

        console.log(`🔍 ${links.length} liens en observation pour préchargement`);
    }

    // Précharger les pages de formations principales
    prefetchMainPages() {
        if (!this.shouldPrefetch()) return;

        const mainPages = [
            '/formations/html5',
            '/formations/css3',
            '/formations/javascript',
            '/formations/php',
            '/formations/laravel'
        ];

        // Précharger les pages les plus importantes
        const idleCallback = window.requestIdleCallback || function(cb) {
            const start = Date.now();
            return setTimeout(() => {
                cb({
                    didTimeout: false,
                    timeRemaining: () => Math.max(0, 50 - (Date.now() - start))
                });
            }, 1);
        };

        idleCallback(() => {
            mainPages.forEach((page, index) => {
                setTimeout(() => {
                    this.prefetchUrl(window.location.origin + page);
                }, index * 200); // Espacer les requêtes de 200ms
            });
        });
    }
}

// Créer et exporter l'instance
const prefetcher = new IntelligentPrefetcher();

// Initialiser quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    // Observer les liens de formations dans le carrousel
    setTimeout(() => {
        prefetcher.observeLinks('.tech-link-carousel');
        prefetcher.observeLinks('.formation-card a');
        prefetcher.observeLinks('.featured-article-link');
        
        // Précharger les pages principales
        prefetcher.prefetchMainPages();
    }, 2000);
});

// Exporter pour utilisation globale
window.IntelligentPrefetcher = IntelligentPrefetcher;
window.prefetcher = prefetcher;
