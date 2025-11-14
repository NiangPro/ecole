// Script pour forcer le sticky du sidebar sur les pages de formations
(function() {
    'use strict';
    
    function initSidebarSticky() {
        const sidebar = document.querySelector('.sidebar');
        const contentWrapper = document.querySelector('.content-wrapper');
        
        if (!sidebar || !contentWrapper) return;
        
        // S'assurer que le parent permet le sticky
        const tutorialContent = document.querySelector('.tutorial-content');
        if (tutorialContent) {
            tutorialContent.style.position = 'relative';
        }
        
        // Forcer les styles nécessaires
        sidebar.style.position = 'sticky';
        sidebar.style.top = '60px';
        sidebar.style.alignSelf = 'flex-start';
        sidebar.style.paddingTop = '15px';
        
        // Fonction pour vérifier et corriger la position
        function enforceSticky() {
            const sidebarRect = sidebar.getBoundingClientRect();
            const wrapperRect = contentWrapper.getBoundingClientRect();
            const navbarHeight = 60;
            
            // S'assurer que le sidebar reste sticky
            if (sidebar.style.position !== 'sticky') {
                sidebar.style.position = 'sticky';
            }
            
            // Ajuster la hauteur si nécessaire
            const viewportHeight = window.innerHeight;
            const maxHeight = viewportHeight - navbarHeight - 20;
            sidebar.style.maxHeight = maxHeight + 'px';
            sidebar.style.height = maxHeight + 'px';
        }
        
        // Utiliser IntersectionObserver pour un meilleur contrôle
        if (window.IntersectionObserver) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    enforceSticky();
                });
            }, {
                root: null,
                rootMargin: '0px',
                threshold: [0, 0.1, 0.5, 1]
            });
            
            observer.observe(contentWrapper);
        }
        
        // Écouter le scroll avec requestAnimationFrame pour performance
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    enforceSticky();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', onScroll, { passive: true });
        
        // Initialiser immédiatement
        enforceSticky();
        
        // Réinitialiser au resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                enforceSticky();
            }, 100);
        });
        
        // Forcer une mise à jour après un court délai pour s'assurer que tout est chargé
        setTimeout(enforceSticky, 100);
        setTimeout(enforceSticky, 500);
    }
    
    // Initialiser quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarSticky);
    } else {
        // Si déjà chargé, initialiser immédiatement
        initSidebarSticky();
    }
    
    // Réessayer après le chargement complet de la page
    window.addEventListener('load', initSidebarSticky);
})();

