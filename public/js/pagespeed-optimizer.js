// Script d'optimisation avancée pour PageSpeed
class PageSpeedOptimizer {
    constructor() {
        this.init();
    }

    init() {
        // Optimiser le chargement des polices
        this.optimizeFonts();
        
        // Précharger les ressources critiques
        this.preloadCriticalResources();
        
        // Optimiser les images restantes
        this.optimizeImages();
        
        // Réduire le JavaScript non critique
        this.deferNonCriticalJS();
        
        // Optimiser le temps d'exécution
        this.optimizeExecution();
    }

    // Optimiser les polices pour éviter le FOUT/FOIT
    optimizeFonts() {
        // Ajouter font-display: swap pour les polices externes
        const style = document.createElement('style');
        style.textContent = `
            @font-face {
                font-family: 'Inter';
                font-display: swap;
                src: url('https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyfAZ9hiA.woff2') format('woff2');
            }
        `;
        document.head.appendChild(style);
    }

    // Précharger les ressources critiques
    preloadCriticalResources() {
        // Précharger le CSS critique
        const criticalCSS = document.createElement('link');
        criticalCSS.rel = 'preload';
        criticalCSS.href = '/css/critical.css';
        criticalCSS.as = 'style';
        document.head.appendChild(criticalCSS);

        // Précharger les images hero
        const heroImages = document.querySelectorAll('.hero-section img');
        heroImages.forEach(img => {
            if (img.src) {
                const preload = document.createElement('link');
                preload.rel = 'preload';
                preload.href = img.src;
                preload.as = 'image';
                document.head.appendChild(preload);
            }
        });
    }

    // Optimiser les images avec WebP et lazy loading
    optimizeImages() {
        const images = document.querySelectorAll('img:not([loading])');
        
        images.forEach(img => {
            // Ajouter lazy loading
            img.loading = 'lazy';
            
            // Optimiser les dimensions
            if (!img.width || !img.height) {
                img.width = img.naturalWidth || 300;
                img.height = img.naturalHeight || 200;
            }
            
            // Ajouter WebP si disponible
            const webpSrc = img.src.replace(/\.(jpg|jpeg|png)$/i, '.webp');
            if (img.src !== webpSrc) {
                const picture = document.createElement('picture');
                const source = document.createElement('source');
                source.srcset = webpSrc;
                source.type = 'image/webp';
                
                picture.appendChild(source);
                picture.appendChild(img.cloneNode());
                img.parentNode.replaceChild(picture, img);
            }
        });
    }

    // Différer le JavaScript non critique
    deferNonCriticalJS() {
        const scripts = document.querySelectorAll('script:not([defer]):not([async])');
        
        scripts.forEach(script => {
            if (!script.src.includes('critical') && !script.src.includes('main')) {
                script.defer = true;
                script.async = true;
            }
        });
    }

    // Optimiser le temps d'exécution
    optimizeExecution() {
        // Utiliser requestIdleCallback pour les tâches non critiques
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                // Charger les analytics après le chargement initial
                this.loadAnalytics();
                
                // Initialiser les widgets tiers
                this.initializeThirdParty();
            }, { timeout: 2000 });
        }
    }

    // Charger les analytics de manière optimisée
    loadAnalytics() {
        // Google Analytics optimisé
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID', {
            page_title: document.title,
            page_location: window.location.href,
            send_page_view: false
        });

        // Charger le script analytics de manière asynchrone
        const script = document.createElement('script');
        script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID';
        script.async = true;
        document.head.appendChild(script);
    }

    // Initialiser les widgets tiers de manière optimisée
    initializeThirdParty() {
        // Charger les widgets sociaux après le chargement principal
        const socialWidgets = document.querySelectorAll('.social-widget');
        socialWidgets.forEach(widget => {
            if (widget.dataset.src) {
                widget.src = widget.dataset.src;
            }
        });
    }

    // Réduire le Cumulative Layout Shift (CLS)
    reduceCLS() {
        // Ajouter des dimensions explicites aux images
        const images = document.querySelectorAll('img:not([width]):not([height])');
        images.forEach(img => {
            if (img.naturalWidth && img.naturalHeight) {
                img.width = img.naturalWidth;
                img.height = img.naturalHeight;
            }
        });

        // Réserver l'espace pour les publicités
        const ads = document.querySelectorAll('.ad-container');
        ads.forEach(ad => {
            ad.style.minHeight = '250px';
            ad.style.width = '100%';
        });
    }

    // Optimiser le Largest Contentful Paint (LCP)
    optimizeLCP() {
        // Précharger l'image la plus grande
        const lcpElement = document.querySelector('.hero-section img, .hero-section picture');
        if (lcpElement) {
            const preload = document.createElement('link');
            preload.rel = 'preload';
            preload.as = 'image';
            preload.href = lcpElement.querySelector('img')?.src || lcpElement.src;
            document.head.appendChild(preload);
        }
    }

    // Optimiser le Time to First Byte (TTFB)
    optimizeTTFB() {
        // Activer le cache HTTP
        if ('caches' in window) {
            caches.open('pagespeed-cache').then(cache => {
                cache.add(window.location.href);
            });
        }
    }
}

// Initialiser l'optimiseur
const pageSpeedOptimizer = new PageSpeedOptimizer();

// Exporter pour utilisation globale
window.PageSpeedOptimizer = PageSpeedOptimizer;
