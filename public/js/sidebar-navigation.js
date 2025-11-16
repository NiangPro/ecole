// Script pour la navigation du sidebar sur les pages de formations - Version simplifiée et fiable
(function() {
    'use strict';
    
    let isScrolling = false;
    
    function initSidebarNavigation() {
        console.log('Sidebar navigation: Initializing...');
        
        // Récupérer tous les liens du sidebar
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) {
            console.warn('Sidebar navigation: .sidebar not found');
            return;
        }
        
        const navLinks = Array.from(sidebar.querySelectorAll('a[href^="#"]')).filter(link => {
            const href = link.getAttribute('href');
            return href && href !== '#' && href.trim() !== '';
        });
        
        if (navLinks.length === 0) {
            console.warn('Sidebar navigation: No navigation links found');
            return;
        }
        
        console.log('Sidebar navigation: Found', navLinks.length, 'links');
        
        // Récupérer toutes les sections avec un ID
        const sections = Array.from(document.querySelectorAll('[id]')).filter(section => {
            const id = section.getAttribute('id');
            return id && id.trim() !== '' && !id.startsWith('__');
        });
        
        console.log('Sidebar navigation: Found', sections.length, 'sections');
        
        // Récupérer la hauteur de la navbar
        const navbar = document.querySelector('.navbar-modern') || document.querySelector('.navbar') || document.querySelector('nav');
        const navbarHeight = navbar ? navbar.offsetHeight : 70;
        
        // Fonction pour mettre à jour le lien actif
        let currentActiveId = null;
        function setActiveLink(sectionId) {
            if (sectionId === currentActiveId) return;
            currentActiveId = sectionId;
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const linkHref = link.getAttribute('href');
                if (linkHref === '#' + sectionId) {
                    link.classList.add('active');
                }
            });
        }
        
        // Intersection Observer pour détecter la section active
        const observerOptions = {
            root: null,
            rootMargin: `-${navbarHeight + 20}px 0px -60% 0px`,
            threshold: [0, 0.1, 0.5, 1]
        };
        
        const observer = new IntersectionObserver((entries) => {
            if (isScrolling) return;
            
            let activeSection = null;
            let maxRatio = 0;
            
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.intersectionRatio > maxRatio) {
                    maxRatio = entry.intersectionRatio;
                    activeSection = entry.target;
                }
            });
            
            if (activeSection) {
                const sectionId = activeSection.getAttribute('id');
                if (sectionId) {
                    setActiveLink(sectionId);
                }
            }
        }, observerOptions);
        
        // Observer toutes les sections
        sections.forEach(section => {
            observer.observe(section);
        });
        
        // Gestion du clic sur les liens du sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetHref = this.getAttribute('href');
                const targetId = targetHref.substring(1).trim();
                
                console.log('Sidebar navigation: Clicked on', targetId);
                
                if (!targetId) {
                    console.warn('Sidebar navigation: Empty target ID');
                    return;
                }
                
                // Chercher la section cible
                let targetSection = document.getElementById(targetId);
                
                if (!targetSection) {
                    // Essayer de chercher par attribut
                    const allElements = document.querySelectorAll('[id]');
                    for (let el of allElements) {
                        if (el.id === targetId || el.getAttribute('id') === targetId) {
                            targetSection = el;
                            break;
                        }
                    }
                }
                
                if (!targetSection) {
                    console.error('Sidebar navigation: Section not found for ID:', targetId);
                    // Fallback: navigation native
                    window.location.hash = targetId;
                    return;
                }
                
                console.log('Sidebar navigation: Found section:', targetSection);
                console.log('Sidebar navigation: Section element:', targetSection);
                
                // Marquer qu'on est en train de scroller
                isScrolling = true;
                
                // Mettre à jour le lien actif immédiatement
                setActiveLink(targetId);
                
                // Calculer l'offset dynamiquement
                const navbarEl = document.querySelector('.navbar-modern') || document.querySelector('.navbar') || document.querySelector('nav');
                const actualNavbarHeight = navbarEl ? navbarEl.offsetHeight : 70;
                const offset = actualNavbarHeight + 20;
                
                console.log('Sidebar navigation: Navbar height:', actualNavbarHeight, 'Offset:', offset);
                
                // Fonction de scroll avec plusieurs méthodes de fallback
                function scrollToSection() {
                    // Obtenir les positions
                    const rect = targetSection.getBoundingClientRect();
                    const bodyRect = document.body.getBoundingClientRect();
                    const htmlRect = document.documentElement.getBoundingClientRect();
                    
                    // Obtenir la position actuelle de scroll
                    const scrollTop = window.pageYOffset || window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
                    
                    // Calculer la position absolue de l'élément
                    const elementTop = rect.top + scrollTop;
                    const targetPosition = Math.max(0, elementTop - offset);
                    
                    console.log('Sidebar navigation: Current scroll:', scrollTop);
                    console.log('Sidebar navigation: Element rect.top:', rect.top);
                    console.log('Sidebar navigation: Element absolute top:', elementTop);
                    console.log('Sidebar navigation: Target position:', targetPosition);
                    
                    // Méthode 1: window.scrollTo (la plus directe)
                    try {
                        window.scrollTo({
                            top: targetPosition,
                            left: 0,
                            behavior: 'smooth'
                        });
                        
                        // Vérifier que le scroll s'est bien fait après un court délai
                        setTimeout(() => {
                            const newScroll = window.pageYOffset || window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
                            const newRect = targetSection.getBoundingClientRect();
                            
                            console.log('Sidebar navigation: After scroll - Position:', newScroll);
                            console.log('Sidebar navigation: After scroll - Element distance from top:', newRect.top);
                            
                            // Si le scroll ne s'est pas fait, essayer une autre méthode
                            if (Math.abs(newScroll - scrollTop) < 10) {
                                console.warn('Sidebar navigation: window.scrollTo did not work, trying scrollIntoView');
                                
                                // Méthode 2: scrollIntoView
                                targetSection.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start',
                                    inline: 'nearest'
                                });
                                
                                // Ajuster avec l'offset après un délai
                                setTimeout(() => {
                                    window.scrollBy({
                                        top: -offset,
                                        left: 0,
                                        behavior: 'smooth'
                                    });
                                }, 300);
                            } else {
                                // Ajuster la position si nécessaire
                                const distanceFromTop = newRect.top;
                                if (Math.abs(distanceFromTop - offset) > 30) {
                                    console.log('Sidebar navigation: Adjusting position by', (distanceFromTop - offset));
                                    window.scrollBy({
                                        top: -(distanceFromTop - offset),
                                        left: 0,
                                        behavior: 'smooth'
                                    });
                                }
                            }
                        }, 200);
                    } catch (error) {
                        console.error('Sidebar navigation: Error with window.scrollTo:', error);
                        
                        // Méthode 3: Fallback avec scrollIntoView direct
                        try {
                            targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            setTimeout(() => {
                                window.scrollBy(0, -offset);
                            }, 300);
                        } catch (e2) {
                            console.error('Sidebar navigation: Error with scrollIntoView:', e2);
                            
                            // Méthode 4: Fallback ultime - scroll instantané
                            document.documentElement.scrollTop = targetPosition;
                            document.body.scrollTop = targetPosition;
                        }
                    }
                }
                
                // Exécuter le scroll
                scrollToSection();
                
                // Mettre à jour l'URL
                try {
                    if (history.pushState) {
                        history.pushState(null, null, targetHref);
                    } else {
                        window.location.hash = targetId;
                    }
                } catch (e) {
                    window.location.hash = targetId;
                }
                
                // Réinitialiser le flag après le scroll
                setTimeout(() => {
                    isScrolling = false;
                    console.log('Sidebar navigation: Scroll completed');
                }, 800);
            });
        });
        
        // Initialiser l'état actif au chargement
        function updateActiveOnLoad() {
            if (isScrolling) return;
            
            const scrollPosition = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
            const viewportTop = scrollPosition + navbarHeight + 20;
            
            let activeSection = null;
            let minDistance = Infinity;
            
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                const sectionTop = rect.top + scrollPosition;
                const distance = Math.abs(sectionTop - viewportTop);
                
                if (sectionTop <= viewportTop + 100 && rect.bottom >= navbarHeight) {
                    if (distance < minDistance) {
                        minDistance = distance;
                        activeSection = section;
                    }
                }
            });
            
            if (activeSection) {
                const sectionId = activeSection.getAttribute('id');
                if (sectionId) {
                    setActiveLink(sectionId);
                }
            }
        }
        
        // Événement de scroll pour mettre à jour l'active
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (isScrolling) return;
            
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                updateActiveOnLoad();
            }, 100);
        }, { passive: true });
        
        // Initialiser au chargement
        setTimeout(updateActiveOnLoad, 300);
        
        // Réinitialiser au resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateActiveOnLoad, 250);
        });
        
        console.log('Sidebar navigation: Initialized successfully');
    }
    
    // Initialiser quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarNavigation);
    } else {
        initSidebarNavigation();
    }
    
    // Réessayer après le chargement complet (au cas où)
    window.addEventListener('load', function() {
        setTimeout(initSidebarNavigation, 500);
    });
})();
