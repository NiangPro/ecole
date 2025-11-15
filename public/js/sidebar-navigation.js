// Script pour la navigation du sidebar sur les pages de formations - Version robuste et définitive
(function() {
    'use strict';
    
    let isScrolling = false;
    let scrollTimeout = null;
    
    function initSidebarNavigation() {
        // Récupérer toutes les sections avec un ID
        const sections = Array.from(document.querySelectorAll('h1[id], h2[id]')).filter(section => {
            const id = section.getAttribute('id');
            return id && id.trim() !== '';
        });
        
        // Récupérer tous les liens du sidebar
        const navLinks = Array.from(document.querySelectorAll('.sidebar a[href^="#"]')).filter(link => {
            const href = link.getAttribute('href');
            return href && href !== '#' && href.trim() !== '';
        });
        
        // Vérifier que les sections et les liens existent
        if (sections.length === 0 || navLinks.length === 0) {
            console.warn('Sidebar navigation: Sections or links not found');
            return;
        }
        
        // Récupérer la hauteur réelle de la navbar
        const navbar = document.querySelector('.navbar-modern') || document.querySelector('nav') || document.querySelector('.navbar');
        const navbarHeight = navbar ? navbar.offsetHeight : 70; // Par défaut 70px
        
        // Récupérer le padding-top du body (qui compense la navbar fixe)
        const bodyStyle = window.getComputedStyle(document.body);
        const bodyPaddingTop = parseInt(bodyStyle.paddingTop) || 70;
        
        // Offset pour la visibilité (espace supplémentaire au-dessus de la section)
        const scrollOffset = 10;
        let currentActiveId = '';
        
        // Fonction pour mettre à jour le lien actif
        function setActiveLink(sectionId) {
            if (sectionId === currentActiveId) return;
            
            currentActiveId = sectionId;
            navLinks.forEach(link => {
                link.classList.remove('active');
                const linkHref = link.getAttribute('href');
                if (linkHref === '#' + sectionId) {
                    link.classList.add('active');
                    // Scroll du sidebar pour garder le lien visible (optionnel)
                    try {
                        link.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } catch (e) {
                        // Ignorer les erreurs si scrollIntoView n'est pas supporté
                    }
                }
            });
        }
        
        // Utiliser Intersection Observer pour une détection précise
        const observerOptions = {
            root: null,
            rootMargin: `-${bodyPaddingTop + scrollOffset}px 0px -50% 0px`,
            threshold: [0, 0.1, 0.25, 0.5, 0.75, 1]
        };
        
        const observer = new IntersectionObserver((entries) => {
            // Ignorer pendant le scroll programmé
            if (isScrolling) return;
            
            // Trouver la section la plus proche du haut de la viewport
            let activeSection = null;
            let minDistance = Infinity;
            const viewportTop = bodyPaddingTop + scrollOffset;
            
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const rect = entry.boundingClientRect;
                    const distance = Math.abs(rect.top - viewportTop);
                    
                    // Prioriser les sections qui sont dans la zone active
                    // La section doit être visible dans la partie supérieure de la viewport
                    if (rect.top <= viewportTop + 150 && rect.bottom >= viewportTop - 50) {
                        if (distance < minDistance) {
                            minDistance = distance;
                            activeSection = entry.target;
                        }
                    }
                }
            });
            
            if (activeSection) {
                const sectionId = activeSection.getAttribute('id');
                if (sectionId && sectionId !== currentActiveId) {
                    setActiveLink(sectionId);
                }
            }
        }, observerOptions);
        
        // Observer toutes les sections
        sections.forEach(section => {
            observer.observe(section);
        });
        
        // Fallback avec scroll event pour les cas limites
        let scrollTicking = false;
        function handleScroll() {
            if (isScrolling || scrollTicking) return;
            
            scrollTicking = true;
            window.requestAnimationFrame(() => {
                const scrollPosition = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                const viewportTop = scrollPosition + bodyPaddingTop + scrollOffset;
                
                // Trouver la section la plus proche du haut de la viewport
                let activeSection = null;
                let minDistance = Infinity;
                
                sections.forEach(section => {
                    const rect = section.getBoundingClientRect();
                    const sectionTop = rect.top + scrollPosition;
                    const sectionBottom = sectionTop + rect.height;
                    const distance = Math.abs(sectionTop - viewportTop);
                    
                    // Si la section est dans la zone visible
                    if (sectionTop <= viewportTop + 200 && sectionBottom >= bodyPaddingTop) {
                        if (distance < minDistance) {
                            minDistance = distance;
                            activeSection = section;
                        }
                    }
                });
                
                if (activeSection) {
                    const sectionId = activeSection.getAttribute('id');
                    if (sectionId && sectionId !== currentActiveId) {
                        setActiveLink(sectionId);
                    }
                }
                
                scrollTicking = false;
            });
        }
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        
        // Initialiser l'état actif au chargement
        setTimeout(() => {
            handleScroll();
        }, 300);
        
        // Gestion du clic sur les liens du sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetHref = this.getAttribute('href');
                if (!targetHref || targetHref === '#') return;
                
                const targetId = targetHref.substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (!targetSection) {
                    console.warn('Sidebar navigation: Section not found:', targetId);
                    return;
                }
                
                // Marquer qu'on est en train de scroller
                isScrolling = true;
                
                // Mettre à jour l'active immédiatement
                setActiveLink(targetId);
                
                // Calculer la position exacte de la section
                // Méthode : Calcul manuel pour un contrôle précis
                const rect = targetSection.getBoundingClientRect();
                const scrollTop = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                
                // Position absolue de l'élément = position relative + scroll actuel
                const elementAbsoluteTop = rect.top + scrollTop;
                
                // Offset = padding-top du body (qui compense la navbar) + petit offset pour visibilité
                const offset = bodyPaddingTop + scrollOffset;
                const targetPosition = elementAbsoluteTop - offset;
                
                // Scroll vers la position calculée
                window.scrollTo({
                    top: Math.max(0, targetPosition),
                    behavior: 'smooth'
                });
                
                // Vérification et ajustement après le scroll
                setTimeout(() => {
                    // Vérifier que la section est visible après le scroll
                    const newRect = targetSection.getBoundingClientRect();
                    const newScroll = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                    const elementNewAbsoluteTop = newRect.top + newScroll;
                    const expectedPosition = elementNewAbsoluteTop - offset;
                    const currentPosition = newScroll;
                    const difference = Math.abs(currentPosition - expectedPosition);
                    
                    // Si la différence est significative (plus de 10px), ajuster
                    if (difference > 10) {
                        window.scrollTo({
                            top: Math.max(0, expectedPosition),
                            behavior: 'smooth'
                        });
                    }
                    
                    // Marquer que le scroll est terminé après un délai
                    setTimeout(() => {
                        isScrolling = false;
                        // Vérifier une dernière fois la section active
                        handleScroll();
                    }, 300);
                }, 400);
            });
        });
        
        // Réinitialiser au resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                handleScroll();
            }, 250);
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
        setTimeout(initSidebarNavigation, 500);
    });
})();
