// Script pour la navigation du sidebar sur les pages de formations
(function() {
    'use strict';
    
    function initSidebarNavigation() {
        const sections = document.querySelectorAll('h1[id], h2[id]');
        const navLinks = document.querySelectorAll('.sidebar a[href^="#"]');
        
        if (sections.length === 0 || navLinks.length === 0) {
            console.log('Sidebar navigation: No sections or links found');
            return;
        }
        
        console.log('Sidebar navigation initialized:', sections.length, 'sections,', navLinks.length, 'links');
        
        const navbarHeight = 60;
        const offset = 100;
        
        // Fonction pour surligner la section active
        function highlightActiveSection() {
            let current = '';
            const scrollPosition = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
            const viewportTop = scrollPosition + navbarHeight;
            
            // Parcourir toutes les sections de bas en haut pour trouver la première qui est visible
            for (let i = sections.length - 1; i >= 0; i--) {
                const section = sections[i];
                const rect = section.getBoundingClientRect();
                const sectionTop = rect.top + scrollPosition;
                
                // Si le haut de la section est au-dessus ou proche du haut de la viewport
                if (sectionTop <= viewportTop + offset) {
                    current = section.getAttribute('id');
                    break;
                }
            }
            
            // Si aucune section trouvée et qu'on est en haut, prendre la première
            if (!current && sections.length > 0) {
                const firstSection = sections[0];
                const firstRect = firstSection.getBoundingClientRect();
                if (firstRect.top <= viewportTop + offset) {
                    current = firstSection.getAttribute('id');
                }
            }
            
            // Mettre à jour les liens actifs
            navLinks.forEach(link => {
                link.classList.remove('active');
                const linkHref = link.getAttribute('href');
                if (linkHref === '#' + current) {
                    link.classList.add('active');
                }
            });
        }
        
        // Écouter le scroll avec throttling
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    highlightActiveSection();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', onScroll, { passive: true });
        
        // Initialiser immédiatement
        highlightActiveSection();
        
        // Réessayer après un court délai
        setTimeout(highlightActiveSection, 100);
        setTimeout(highlightActiveSection, 500);
        
        // Smooth scroll pour les liens du sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (!targetId || targetId === '#') return;
                
                const targetIdClean = targetId.substring(1);
                const targetSection = document.getElementById(targetIdClean);
                
                if (targetSection) {
                    // Calculer la position exacte
                    const rect = targetSection.getBoundingClientRect();
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const targetPosition = rect.top + scrollTop - navbarHeight - 20;
                    
                    // Mettre à jour l'active immédiatement
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Scroll vers la position
                    window.scrollTo({
                        top: Math.max(0, targetPosition),
                        behavior: 'smooth'
                    });
                    
                    // Mettre à jour après le scroll
                    setTimeout(() => {
                        highlightActiveSection();
                    }, 100);
                    
                    setTimeout(() => {
                        highlightActiveSection();
                    }, 600);
                    
                    setTimeout(() => {
                        highlightActiveSection();
                    }, 1200);
                } else {
                    console.warn('Section not found:', targetIdClean);
                }
            });
        });
    }
    
    // Initialiser quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarNavigation);
    } else {
        initSidebarNavigation();
    }
    
    // Réessayer après le chargement complet
    window.addEventListener('load', function() {
        setTimeout(initSidebarNavigation, 100);
    });
})();
