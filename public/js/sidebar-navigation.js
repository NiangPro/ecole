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
        const offset = 80; // Offset réduit pour une meilleure détection
        
        // Fonction pour surligner la section active
        function highlightActiveSection() {
            let current = '';
            const scrollPosition = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
            const viewportTop = scrollPosition + navbarHeight;
            const viewportMiddle = scrollPosition + (window.innerHeight / 2);
            
            // Parcourir toutes les sections de haut en bas pour trouver la section active
            for (let i = 0; i < sections.length; i++) {
                const section = sections[i];
                const rect = section.getBoundingClientRect();
                const sectionTop = rect.top + scrollPosition;
                const sectionBottom = sectionTop + rect.height;
                
                // Vérifier si la section est dans la zone visible
                if (viewportTop + offset >= sectionTop) {
                    // Si c'est la dernière section ou si la prochaine section n'est pas encore visible
                    if (i === sections.length - 1) {
                        current = section.getAttribute('id');
                        break;
                    } else {
                        // Vérifier la prochaine section
                        const nextSection = sections[i + 1];
                        const nextRect = nextSection.getBoundingClientRect();
                        const nextSectionTop = nextRect.top + scrollPosition;
                        
                        // Si la prochaine section n'est pas encore visible, cette section est active
                        if (viewportTop < nextSectionTop - offset) {
                            current = section.getAttribute('id');
                            break;
                        } else {
                            // Si les deux sections sont visibles, choisir celle qui est la plus proche du milieu
                            const currentDistance = Math.abs(viewportMiddle - (sectionTop + rect.height / 2));
                            const nextDistance = Math.abs(viewportMiddle - (nextSectionTop + nextRect.height / 2));
                            
                            if (currentDistance < nextDistance) {
                                current = section.getAttribute('id');
                            } else {
                                current = nextSection.getAttribute('id');
                            }
                            break;
                        }
                    }
                }
            }
            
            // Si aucune section trouvée et qu'on est en haut, prendre la première
            if (!current && sections.length > 0) {
                const firstSection = sections[0];
                const firstRect = firstSection.getBoundingClientRect();
                if (scrollPosition < firstRect.top - offset) {
                    current = '';
                } else {
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
                    const targetPosition = rect.top + scrollTop - navbarHeight - 10;
                    
                    // Mettre à jour l'active immédiatement
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Scroll vers la position avec smooth
                    window.scrollTo({
                        top: Math.max(0, targetPosition),
                        behavior: 'smooth'
                    });
                    
                    // Mettre à jour pendant et après le scroll
                    let scrollCheckInterval = setInterval(() => {
                        highlightActiveSection();
                    }, 50);
                    
                    // Arrêter la vérification après le scroll
                    setTimeout(() => {
                        clearInterval(scrollCheckInterval);
                        highlightActiveSection();
                    }, 1000);
                    
                    // Vérification finale
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
