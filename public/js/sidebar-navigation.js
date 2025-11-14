// Script pour la navigation du sidebar sur les pages de formations - Version robuste et définitive
(function() {
    'use strict';
    
    let isScrolling = false;
    let scrollTimeout = null;
    
    function initSidebarNavigation() {
        const sections = Array.from(document.querySelectorAll('h1[id], h2[id]')).filter(section => {
            const id = section.getAttribute('id');
            return id && id.trim() !== '';
        });
        const navLinks = Array.from(document.querySelectorAll('.sidebar a[href^="#"]')).filter(link => {
            const href = link.getAttribute('href');
            return href && href !== '#' && href.trim() !== '';
        });
        
        if (sections.length === 0 || navLinks.length === 0) {
            return;
        }
        
        const navbarHeight = 60;
        const scrollOffset = 100;
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
                    // Scroll du sidebar pour garder le lien visible
                    link.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }
        
        // Utiliser Intersection Observer pour une détection précise
        const observerOptions = {
            root: null,
            rootMargin: `-${navbarHeight + scrollOffset}px 0px -${window.innerHeight - navbarHeight - scrollOffset - 200}px 0px`,
            threshold: [0, 0.1, 0.25, 0.5, 0.75, 1]
        };
        
        const observer = new IntersectionObserver((entries) => {
            if (isScrolling) return; // Ignorer pendant le scroll programmé
            
            // Filtrer les sections visibles et les trier par position
            const visibleSections = entries
                .filter(entry => entry.isIntersecting && entry.intersectionRatio > 0.1)
                .map(entry => ({
                    element: entry.target,
                    id: entry.target.getAttribute('id'),
                    top: entry.boundingClientRect.top,
                    ratio: entry.intersectionRatio
                }))
                .sort((a, b) => a.top - b.top);
            
            if (visibleSections.length > 0) {
                // Prendre la première section visible (la plus haute dans la viewport)
                const activeSection = visibleSections[0];
                if (activeSection.id && activeSection.id !== currentActiveId) {
                    setActiveLink(activeSection.id);
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
                const viewportTop = scrollPosition + navbarHeight + scrollOffset;
                
                // Trouver la section la plus proche du haut de la viewport
                let activeSection = null;
                let minDistance = Infinity;
                
                sections.forEach(section => {
                    const rect = section.getBoundingClientRect();
                    const sectionTop = rect.top + scrollPosition;
                    const sectionBottom = sectionTop + rect.height;
                    const distance = Math.abs(sectionTop - viewportTop);
                    
                    // Si la section est dans la zone visible
                    if (sectionTop <= viewportTop + 150 && sectionBottom >= navbarHeight) {
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
        }, 200);
        
        // Gestion du clic sur les liens du sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetHref = this.getAttribute('href');
                if (!targetHref || targetHref === '#') return;
                
                const targetId = targetHref.substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (!targetSection) {
                    return;
                }
                
                // Marquer qu'on est en train de scroller
                isScrolling = true;
                
                // Mettre à jour l'active immédiatement
                setActiveLink(targetId);
                
                // Calculer la position exacte en tenant compte de la navbar
                const rect = targetSection.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const elementTop = rect.top + scrollTop;
                const offset = navbarHeight + 30; // Navbar + espace supplémentaire
                const targetPosition = elementTop - offset;
                
                // Scroll vers la position calculée
                window.scrollTo({
                    top: Math.max(0, targetPosition),
                    behavior: 'smooth'
                });
                
                // Vérification supplémentaire après le scroll pour garantir la visibilité
                setTimeout(() => {
                    const finalRect = targetSection.getBoundingClientRect();
                    const finalScrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const finalElementTop = finalRect.top + finalScrollTop;
                    const finalTargetPosition = finalElementTop - offset;
                    
                    // Si la section n'est pas correctement positionnée, ajuster
                    const currentScrollPosition = finalScrollTop;
                    const expectedPosition = finalTargetPosition;
                    const difference = Math.abs(currentScrollPosition - expectedPosition);
                    
                    if (difference > 10) {
                        window.scrollTo({
                            top: expectedPosition,
                            behavior: 'smooth'
                        });
                    }
                }, 300);
                
                // Vérifier que le scroll est terminé
                let lastScrollTop = scrollTop;
                let stableCount = 0;
                
                const checkScrollComplete = setInterval(() => {
                    const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollDelta = Math.abs(currentScrollTop - lastScrollTop);
                    
                    if (scrollDelta < 1) {
                        stableCount++;
                        if (stableCount >= 5) {
                            clearInterval(checkScrollComplete);
                            isScrolling = false;
                            // Vérifier une dernière fois la section active
                            setTimeout(() => {
                                handleScroll();
                            }, 100);
                        }
                    } else {
                        stableCount = 0;
                        lastScrollTop = currentScrollTop;
                    }
                }, 50);
                
                // Timeout de sécurité
                setTimeout(() => {
                    clearInterval(checkScrollComplete);
                    isScrolling = false;
                    handleScroll();
                }, 2000);
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
        setTimeout(initSidebarNavigation, 300);
    });
})();
