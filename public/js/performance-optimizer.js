/**
 * Performance Optimizer
 * Script pour optimiser les performances du site
 */

(function() {
    'use strict';
    
    // Lazy load images avec Intersection Observer
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        // Observer toutes les images avec data-src
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            imageObserver.observe(img);
        });
    }
    
    // Déferrer les scripts non critiques
    function deferScripts() {
        // Déferrer Google Analytics après interaction utilisateur
        if (window.gtag) {
            // Google Analytics est déjà chargé, on peut le laisser
        }
        
        // Déferrer AdSense après scroll
        let adsenseLoaded = false;
        window.addEventListener('scroll', function() {
            if (!adsenseLoaded && window.scrollY > 500) {
                // Charger AdSense après scroll
                adsenseLoaded = true;
            }
        }, { once: true, passive: true });
    }
    
    // Optimiser les animations pour les performances
    function optimizeAnimations() {
        // Réduire les animations sur mobile
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        }
    }
    
    // Initialiser après DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            deferScripts();
            optimizeAnimations();
        });
    } else {
        deferScripts();
        optimizeAnimations();
    }
})();

