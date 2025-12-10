// Fonction pour le défilement fluide vers les ancres
function initSmoothScroll() {
    // Fonction pour gérer le scroll
    function handleAnchorClick(e) {
        const link = e.target.closest('a');
        if (!link) return;
        
        const href = link.getAttribute('href');
        if (!href || !href.startsWith('#') || href === '#') return;
        
        try {
            e.preventDefault();
            
            // Fonction pour faire le scroll
            const scrollToElement = (targetElement, href) => {
                const navbar = document.querySelector('.navbar-modern') || document.querySelector('.navbar') || document.querySelector('nav');
                const navbarHeight = navbar ? navbar.offsetHeight : 80;
                
                const targetPosition = targetElement.offsetTop - navbarHeight;
                
                window.scrollTo({
                    top: Math.max(0, targetPosition),
                    behavior: 'smooth'
                });
                
                // Mettre à jour l'URL
                if (history.pushState) {
                    history.pushState(null, null, href);
                } else {
                    window.location.hash = href;
                }
                
                // Fermer le menu mobile si ouvert
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu) {
                    try {
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                        }
                    } catch (error) {
                        // Erreur silencieuse
                    }
                }
            };
            
            // Chercher l'élément cible
            let targetElement = document.querySelector(href);
            
            // Si non trouvé, réessayer après un court délai (au cas où le DOM n'est pas complètement chargé)
            if (!targetElement) {
                setTimeout(() => {
                    targetElement = document.querySelector(href);
                    if (targetElement) {
                        scrollToElement(targetElement, href);
                    }
                }, 50);
                return;
            }
            
            scrollToElement(targetElement, href);
        } catch (error) {
            // Erreur silencieuse
        }
    }
    
    // Attacher les écouteurs aux liens existants
    function attachListeners() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            // Vérifier si l'écouteur n'existe pas déjà
            if (!anchor.dataset.smoothScrollAdded) {
                anchor.addEventListener('click', handleAnchorClick);
                anchor.dataset.smoothScrollAdded = 'true';
            }
        });
    }
    
    // Initialiser
    attachListeners();
    
    // Utiliser la délégation d'événements pour TOUS les liens d'ancrage (phase de capture - PRIORITÉ MAXIMALE)
    // Cette approche garantit que TOUS les clics sur les liens d'ancrage sont interceptés, même ceux ajoutés dynamiquement
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="#"]');
        if (link) {
            const href = link.getAttribute('href');
            if (href && href !== '#') {
                // Gérer directement le scroll (pas besoin d'attacher un écouteur séparé)
                handleAnchorClick(e);
            }
        }
    }, true); // Phase de capture - PRIORITÉ MAXIMALE pour intercepter avant ux-improvements.js
}

// Initialiser immédiatement (pas besoin d'attendre DOMContentLoaded)
initSmoothScroll();

// Réessayer après un court délai pour les éléments chargés après
setTimeout(initSmoothScroll, 100);
setTimeout(initSmoothScroll, 500);

// Bouton de retour en haut
const backToTopButton = document.getElementById('backToTop');

if (backToTopButton) {
    window.addEventListener('scroll', () => {
        try {
            // Afficher/masquer le bouton de retour en haut
            if (backToTopButton) {
                try {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.add('visible');
                        backToTopButton.style.display = 'block';
                    } else {
                        backToTopButton.classList.remove('visible');
                        backToTopButton.style.display = 'none';
                    }
                } catch (error) {
                    // Erreur silencieuse
                }
            }
            
            // Animation d'apparition des éléments au défilement
            animateOnScroll();
        } catch (error) {
            // Erreur silencieuse
        }
    });

    // Gestion du clic sur le bouton de retour en haut
    backToTopButton.addEventListener('click', () => {
        try {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        } catch (error) {
            // Erreur silencieuse
        }
    });
}

// Animation d'apparition des éléments au défilement
function animateOnScroll() {
    const elements = document.querySelectorAll('.fade-in, .feature-item');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        
        if (element && elementTop < windowHeight - 100) {
            try {
                element.classList.add('visible');
            } catch (error) {
                // Erreur silencieuse
            }
        }
    });
}

// Initialisation des animations au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Démarrer les animations
        animateOnScroll();
        
        // Menu mobile
        const mobileMenuButton = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                try {
                    mobileMenu.classList.toggle('hidden');
                } catch (error) {
                    // Erreur silencieuse
                }
            });
            
            // Gestion du bouton formations mobile
            const mobileFormationsBtn = document.getElementById('mobile-formations-btn');
            const mobileFormationsMenu = document.getElementById('mobile-formations-menu');
            
            if (mobileFormationsBtn && mobileFormationsMenu) {
                mobileFormationsBtn.addEventListener('click', () => {
                    try {
                        mobileFormationsMenu.classList.toggle('hidden');
                    } catch (error) {
                        // Erreur silencieuse
                    }
                });
            }
        }
        
        // Animation des cartes de compétences
        const skillCards = document.querySelectorAll('.skill-card');
        skillCards.forEach((card, index) => {
            // Délai d'animation progressif pour chaque carte
            card.style.transitionDelay = `${index * 0.1}s`;
        });
    } catch (error) {
        // Erreur silencieuse
    }
});

// Gestion de la soumission du formulaire de contact
const contactForm = document.querySelector('#contact form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        try {
            e.preventDefault();
            
            // Récupération des données du formulaire
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            
            // Afficher un message de succès
            alert('Merci pour votre message ! Nous vous recontacterons bientôt.');
            
            // Réinitialiser le formulaire
            this.reset();
        } catch (error) {
            alert('Une erreur s\'est produite. Veuillez réessayer.');
        }
    });
}

// Fonction pour ajouter une classe au header lors du défilement
const header = document.querySelector('header');
if (header) {
    window.addEventListener('scroll', () => {
        try {
            const headerElement = document.querySelector('header');
            if (headerElement && headerElement.classList) {
                if (window.scrollY > 100) {
                    headerElement.classList.add('scrolled');
                } else {
                    headerElement.classList.remove('scrolled');
                }
            }
        } catch (error) {
            // Erreur silencieuse
        }
    }, { passive: true });
}

// Animation des compteurs de statistiques
function animateCounters() {
    try {
        const counters = document.querySelectorAll('.counter');
        const speed = 200; // La vitesse de l'animation
        
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(animateCounters, 1);
            } else {
                counter.innerText = target;
            }
        });
    } catch (error) {
        // Erreur silencieuse
    }
}

// Observer pour déclencher l'animation des compteurs lorsqu'ils sont visibles
if ('IntersectionObserver' in window) {
    try {
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.counter').forEach(counter => {
            observer.observe(counter);
        });
    } catch (error) {
        // Erreur silencieuse
    }
}

// Gestion du mode sombre (optionnel)
const darkModeToggle = document.getElementById('darkModeToggle');
if (darkModeToggle) {
    try {
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        });
        
        // Vérifier les préférences utilisateur
        if (localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    } catch (error) {
        // Erreur silencieuse
    }
}
