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
                e.stopPropagation();
                
                const targetHref = this.getAttribute('href');
                if (!targetHref || targetHref === '#') return;
                
                const targetId = targetHref.substring(1);
                
                // Chercher la section cible
                let targetSection = document.getElementById(targetId);
                
                // Si pas trouvé, chercher dans h1 et h2
                if (!targetSection) {
                    const allHeadings = document.querySelectorAll('h1[id], h2[id], h3[id]');
                    allHeadings.forEach(heading => {
                        if (heading.id === targetId) {
                            targetSection = heading;
                        }
                    });
                }
                
                // Si toujours pas trouvé, log pour debug
                if (!targetSection) {
                    console.warn('Sidebar navigation: Section not found:', targetId);
                    const allIds = Array.from(document.querySelectorAll('[id]')).map(el => el.id).filter(id => id);
                    console.log('Available IDs:', allIds);
                    // Permettre la navigation normale du navigateur comme fallback
                    window.location.href = targetHref;
                    return;
                }
                
                // Marquer qu'on est en train de scroller
                isScrolling = true;
                
                // Mettre à jour l'active immédiatement
                setActiveLink(targetId);
                
                // Calculer les offsets dynamiquement
                const actualBodyPaddingTop = parseInt(window.getComputedStyle(document.body).paddingTop) || 0;
                const navbarElement = document.querySelector('.navbar-modern') || document.querySelector('nav');
                const actualNavbarHeight = navbarElement ? navbarElement.offsetHeight : 70;
                const offset = Math.max(actualBodyPaddingTop, actualNavbarHeight, 70) + scrollOffset + 10;
                
                // Calculer la position de scroll cible
                const rect = targetSection.getBoundingClientRect();
                const currentScroll = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                const elementTop = rect.top + currentScroll;
                const targetPosition = elementTop - offset;
                
                // Scroll vers la position avec animation smooth
                targetSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
                
                // Ajuster la position après le scroll initial pour tenir compte de la navbar
                setTimeout(() => {
                    const newRect = targetSection.getBoundingClientRect();
                    const newScroll = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
                    const elementNewTop = newRect.top + newScroll;
                    const expectedTop = elementNewTop - offset;
                    
                    // Si la position n'est pas correcte, ajuster
                    if (Math.abs(newScroll - expectedTop) > 5) {
                        window.scrollTo({
                            top: Math.max(0, expectedTop),
                            behavior: 'smooth'
                        });
                    }
                    
                    // Marquer la fin du scroll
                    setTimeout(() => {
                        isScrolling = false;
                        // Vérifier la section active après le scroll
                        handleScroll();
                    }, 400);
                }, 200);
                
                // Mettre à jour l'URL sans recharger la page
                if (history.pushState) {
                    history.pushState(null, null, targetHref);
                }
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
