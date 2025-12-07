/**
 * Améliorations UX/UI - NiangProgrammeur
 * - Animations de chargement
 * - Feedback visuel amélioré
 * - Accessibilité WCAG
 * - PWA améliorée
 */

(function() {
    'use strict';

    // ============================================
    // 1. SYSTÈME DE LOADING AMÉLIORÉ
    // ============================================
    
    class LoadingManager {
        constructor() {
            this.loaders = new Map();
            this.anchorLinkClicked = false;
            this.init();
        }

        init() {
            // Améliorer le loader global
            this.enhanceGlobalLoader();
            
            // Intercepter les formulaires pour afficher un loader
            this.interceptForms();
            
            // Intercepter les liens pour afficher un loader
            this.interceptLinks();
            
            // Protection supplémentaire pour les liens d'ancrage
            this.protectAnchorLinks();
        }
        
        protectAnchorLinks() {
            // Empêcher complètement l'affichage du loader pour les liens d'ancrage
            const pageLoader = document.getElementById('page-loader');
            if (!pageLoader) return;
            
            // Fonction pour forcer le loader à rester caché
            const forceHide = () => {
                pageLoader.setAttribute('data-anchor-active', 'true');
                pageLoader.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important; pointer-events: none !important; z-index: -1 !important; position: fixed !important;';
                pageLoader.classList.add('hidden');
            };
            
            // Intercepter TOUS les clics sur les liens d'ancrage AVANT tout autre script
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link) {
                    const href = link.getAttribute('href');
                    if (href && (href.startsWith('#') || link.dataset.noLoader === 'true')) {
                        this.anchorLinkClicked = true;
                        forceHide();
                        // Réinitialiser après un court délai
                        setTimeout(() => {
                            this.anchorLinkClicked = false;
                        }, 2000);
                    }
                }
            }, true); // Phase de capture - PRIORITÉ MAXIMALE
            
            // Surveiller les changements de hash
            let lastHash = window.location.hash;
            const checkHash = () => {
                const currentHash = window.location.hash;
                if (currentHash !== lastHash && currentHash) {
                    forceHide();
                }
                lastHash = currentHash;
            };
            
            window.addEventListener('hashchange', () => {
                forceHide();
                setTimeout(checkHash, 10);
            });
            
            // Vérification continue toutes les 25ms pour être ultra-réactif
            setInterval(() => {
                if (window.location.hash) {
                    forceHide();
                }
            }, 25);
            
            // Observer les changements du loader pour empêcher son affichage
            const observer = new MutationObserver(() => {
                if (window.location.hash && !pageLoader.classList.contains('hidden')) {
                    forceHide();
                }
            });
            
            observer.observe(pageLoader, {
                attributes: true,
                attributeFilter: ['class', 'style'],
                childList: false,
                subtree: false
            });
        }

        enhanceGlobalLoader() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                // Ajouter un texte de chargement
                const text = document.createElement('div');
                text.className = 'loader-text';
                text.textContent = 'Chargement...';
                text.style.cssText = 'color: #06b6d4; margin-top: 20px; font-size: 0.9rem; font-weight: 500;';
                loader.appendChild(text);

                // Animation de fade out améliorée
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        loader.style.transition = 'opacity 0.5s ease-out, visibility 0.5s ease-out';
                        loader.classList.add('hidden');
                        setTimeout(() => {
                            loader.style.display = 'none';
                        }, 500);
                    }, 300);
                });
                
                // Empêcher le loader de s'afficher pour les liens d'ancrage
                // En surveillant les changements de hash dans l'URL
                let lastHash = window.location.hash;
                const hideLoaderForAnchor = () => {
                    if (loader && !loader.classList.contains('hidden')) {
                        loader.classList.add('hidden');
                        loader.style.display = 'none';
                        loader.style.opacity = '0';
                        loader.style.visibility = 'hidden';
                        loader.style.pointerEvents = 'none';
                    }
                };
                
                const checkHashChange = () => {
                    const currentHash = window.location.hash;
                    if (currentHash !== lastHash) {
                        // Si le hash change, c'est probablement un lien d'ancrage
                        hideLoaderForAnchor();
                    }
                    lastHash = currentHash;
                };
                
                // Vérifier périodiquement les changements de hash
                setInterval(checkHashChange, 50);
                
                // Écouter les événements de hashchange
                window.addEventListener('hashchange', hideLoaderForAnchor);
                
                // Surveiller les tentatives d'affichage du loader
                const observer = new MutationObserver(() => {
                    if (loader.dataset.anchorLink === 'true') {
                        hideLoaderForAnchor();
                    }
                });
                
                observer.observe(loader, {
                    attributes: true,
                    attributeFilter: ['class', 'style'],
                    childList: false,
                    subtree: false
                });
            }
        }

        showLoader(element, message = 'Chargement...') {
            const loaderId = 'loader-' + Date.now();
            const loader = this.createLoader(message);
            element.style.position = 'relative';
            element.appendChild(loader);
            this.loaders.set(loaderId, { element, loader });
            return loaderId;
        }

        hideLoader(loaderId) {
            const loaderData = this.loaders.get(loaderId);
            if (loaderData) {
                loaderData.loader.style.opacity = '0';
                setTimeout(() => {
                    loaderData.loader.remove();
                    this.loaders.delete(loaderId);
                }, 300);
            }
        }

        // Méthode pour cacher le loader de page global
        hidePageLoader() {
            const pageLoader = document.getElementById('page-loader');
            if (pageLoader) {
                pageLoader.classList.add('hidden');
                pageLoader.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important; pointer-events: none !important; z-index: -1 !important; position: fixed !important;';
            }
        }

        createLoader(message) {
            const loader = document.createElement('div');
            loader.className = 'action-loader';
            loader.innerHTML = `
                <div class="action-loader-spinner"></div>
                <div class="action-loader-text">${message}</div>
            `;
            return loader;
        }

        interceptForms() {
            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (form.tagName === 'FORM' && !form.dataset.noLoader) {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        const loaderId = this.showLoader(submitBtn, 'Envoi en cours...');
                        
                        // Si le formulaire échoue, réactiver le bouton
                        form.addEventListener('submit', () => {
                            setTimeout(() => {
                                if (submitBtn.disabled) {
                                    this.hideLoader(loaderId);
                                    submitBtn.disabled = false;
                                }
                            }, 5000);
                        }, { once: true });
                    }
                }
            });
        }

        interceptLinks() {
            // Intercepter les clics sur les liens
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link || !link.href) {
                    return;
                }
                
                const hrefAttr = link.getAttribute('href');
                
                // SOLUTION SIMPLE : Si le lien a data-no-loader OU commence par #, NE RIEN FAIRE
                // Laisser le comportement par défaut du navigateur (scroll vers l'ancre)
                if (link.dataset.noLoader === 'true' || (hrefAttr && hrefAttr.trim().startsWith('#'))) {
                    // Cacher tous les loaders qui pourraient être affichés
                    this.loaders.forEach((loaderData, loaderId) => {
                        this.hideLoader(loaderId);
                    });
                    document.querySelectorAll('.action-loader').forEach(loader => {
                        loader.remove();
                    });
                    // Ne rien faire d'autre - laisser le comportement par défaut
                    return;
                }
                
                // Ignorer les liens javascript:
                if (hrefAttr && hrefAttr.startsWith('javascript:')) {
                    return;
                }
                
                // Ignorer les liens avec target="_blank" (nouvelle fenêtre)
                if (link.target === '_blank' || link.getAttribute('target') === '_blank') {
                    return;
                }
                
                // Pour les autres liens internes, afficher le loader
                const isInternalLink = !link.hostname || link.hostname === window.location.hostname;
                if (isInternalLink) {
                    const loaderId = this.showLoader(document.body, 'Chargement de la page...');
                    // Le loader sera caché quand la nouvelle page se charge
                }
            });
            
            // Surveiller et empêcher l'affichage du loader pour les liens d'ancrage
            const pageLoader = document.getElementById('page-loader');
            if (pageLoader) {
                // Utiliser la méthode de classe pour cacher le loader
                const hidePageLoaderFn = () => {
                    this.hidePageLoader();
                };
                
                // Ajouter des écouteurs sur tous les liens d'ancrage existants et futurs
                const setupAnchorLinks = () => {
                    const anchorLinks = document.querySelectorAll('a[href^="#"]');
                    anchorLinks.forEach(anchorLink => {
                        // Vérifier si l'écouteur n'existe pas déjà
                        if (!anchorLink.dataset.loaderListenerAdded) {
                            anchorLink.addEventListener('click', () => {
                                hidePageLoaderFn();
                            }, true); // Phase de capture
                            anchorLink.dataset.loaderListenerAdded = 'true';
                        }
                    });
                };
                
                // Initialiser pour les liens existants
                setupAnchorLinks();
                
                // Observer les changements pour les nouveaux liens
                const observer = new MutationObserver(() => {
                    setupAnchorLinks();
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
                
                // Écouter les événements hashchange pour cacher le loader
                window.addEventListener('hashchange', () => {
                    hidePageLoaderFn();
                });
                
                // Surveiller les changements de style du loader
                const loaderObserver = new MutationObserver(() => {
                    // Si le loader devient visible et qu'on a un hash dans l'URL, le cacher
                    if (window.location.hash && !pageLoader.classList.contains('hidden')) {
                        hidePageLoaderFn();
                    }
                });
                
                loaderObserver.observe(pageLoader, {
                    attributes: true,
                    attributeFilter: ['class', 'style']
                });
                
                // Vérification périodique pour s'assurer que le loader reste caché pour les ancres
                setInterval(() => {
                    if (window.location.hash && !pageLoader.classList.contains('hidden')) {
                        hidePageLoaderFn();
                    }
                }, 50); // Vérification toutes les 50ms pour être plus réactif
            }
        }
    }

    // ============================================
    // 2. FEEDBACK VISUEL AMÉLIORÉ
    // ============================================
    
    class FeedbackManager {
        constructor() {
            this.init();
        }

        init() {
            this.enhanceToastr();
            this.addSuccessFeedback();
            this.addErrorFeedback();
            this.addInfoFeedback();
        }

        enhanceToastr() {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "tapToDismiss": true
                };
            }
        }

        showSuccess(message, title = 'Succès') {
            if (typeof toastr !== 'undefined') {
                toastr.success(message, title);
            } else {
                this.showCustomNotification(message, 'success');
            }
        }

        showError(message, title = 'Erreur') {
            if (typeof toastr !== 'undefined') {
                toastr.error(message, title);
            } else {
                this.showCustomNotification(message, 'error');
            }
        }

        showInfo(message, title = 'Information') {
            if (typeof toastr !== 'undefined') {
                toastr.info(message, title);
            } else {
                this.showCustomNotification(message, 'info');
            }
        }

        showWarning(message, title = 'Attention') {
            if (typeof toastr !== 'undefined') {
                toastr.warning(message, title);
            } else {
                this.showCustomNotification(message, 'warning');
            }
        }

        showCustomNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `custom-notification custom-notification-${type}`;
            notification.innerHTML = `
                <div class="custom-notification-content">
                    <i class="fas ${this.getIcon(type)}"></i>
                    <span>${message}</span>
                    <button class="custom-notification-close" aria-label="Fermer">&times;</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animation d'entrée
            setTimeout(() => notification.classList.add('show'), 10);
            
            // Fermeture
            const closeBtn = notification.querySelector('.custom-notification-close');
            closeBtn.addEventListener('click', () => this.closeNotification(notification));
            
            // Auto-fermeture après 5 secondes
            setTimeout(() => this.closeNotification(notification), 5000);
        }

        getIcon(type) {
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle'
            };
            return icons[type] || icons.info;
        }

        closeNotification(notification) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }

        addSuccessFeedback() {
            // Feedback visuel pour les actions réussies
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-success], a[data-success]');
                if (btn) {
                    const message = btn.dataset.success || 'Action réussie !';
                    setTimeout(() => this.showSuccess(message), 300);
                }
            });
        }

        addErrorFeedback() {
            // Feedback visuel pour les erreurs
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-error], a[data-error]');
                if (btn) {
                    const message = btn.dataset.error || 'Une erreur est survenue';
                    setTimeout(() => this.showError(message), 300);
                }
            });
        }

        addInfoFeedback() {
            // Feedback visuel pour les informations
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-info], a[data-info]');
                if (btn) {
                    const message = btn.dataset.info || 'Information';
                    setTimeout(() => this.showInfo(message), 300);
                }
            });
        }
    }

    // ============================================
    // 3. ACCESSIBILITÉ WCAG
    // ============================================
    
    class AccessibilityManager {
        constructor() {
            this.init();
        }

        init() {
            this.addAriaLabels();
            this.addKeyboardNavigation();
            this.addFocusManagement();
            this.addSkipLinks();
            this.addScreenReaderSupport();
        }

        addAriaLabels() {
            // Ajouter des labels ARIA aux éléments interactifs sans texte
            document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])').forEach(btn => {
                if (!btn.textContent.trim() && !btn.querySelector('span, .sr-only')) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        btn.setAttribute('aria-label', this.getIconLabel(icon.className));
                    }
                }
            });

            // Ajouter des labels aux liens d'image
            document.querySelectorAll('a img').forEach(img => {
                const link = img.closest('a');
                if (link && !link.getAttribute('aria-label')) {
                    link.setAttribute('aria-label', img.alt || 'Lien vers ' + (img.title || 'page'));
                }
            });
        }

        getIconLabel(iconClass) {
            const iconLabels = {
                'fa-search': 'Rechercher',
                'fa-user': 'Profil',
                'fa-bars': 'Menu',
                'fa-times': 'Fermer',
                'fa-edit': 'Modifier',
                'fa-trash': 'Supprimer',
                'fa-save': 'Enregistrer',
                'fa-download': 'Télécharger',
                'fa-upload': 'Téléverser',
                'fa-plus': 'Ajouter',
                'fa-minus': 'Retirer',
                'fa-check': 'Valider',
                'fa-arrow-left': 'Retour',
                'fa-arrow-right': 'Suivant',
                'fa-home': 'Accueil',
                'fa-cog': 'Paramètres'
            };
            
            for (const [icon, label] of Object.entries(iconLabels)) {
                if (iconClass.includes(icon)) {
                    return label;
                }
            }
            return 'Action';
        }

        addKeyboardNavigation() {
            // Navigation au clavier pour les modals
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('.modal.show, .modal.active');
                    modals.forEach(modal => {
                        const closeBtn = modal.querySelector('.modal-close, [data-dismiss="modal"]');
                        if (closeBtn) {
                            closeBtn.click();
                        }
                    });
                }

                // Tab trap dans les modals
                if (e.key === 'Tab') {
                    const modal = document.querySelector('.modal.show, .modal.active');
                    if (modal) {
                        const focusableElements = modal.querySelectorAll(
                            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                        );
                        const firstElement = focusableElements[0];
                        const lastElement = focusableElements[focusableElements.length - 1];

                        if (e.shiftKey && document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        } else if (!e.shiftKey && document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }

        addFocusManagement() {
            // Gérer le focus lors de l'ouverture/fermeture de modals
            document.addEventListener('click', (e) => {
                const modalTrigger = e.target.closest('[data-toggle="modal"], [data-bs-toggle="modal"]');
                if (modalTrigger) {
                    const modalId = modalTrigger.getAttribute('data-target') || 
                                   modalTrigger.getAttribute('data-bs-target') ||
                                   modalTrigger.getAttribute('href');
                    if (modalId) {
                        setTimeout(() => {
                            const modal = document.querySelector(modalId);
                            if (modal) {
                                const firstInput = modal.querySelector('input, textarea, select, button');
                                if (firstInput) {
                                    firstInput.focus();
                                }
                            }
                        }, 300);
                    }
                }
            });
        }

        addSkipLinks() {
            // Ajouter des liens de saut pour l'accessibilité
            const skipLinks = document.createElement('div');
            skipLinks.className = 'skip-links';
            skipLinks.innerHTML = `
                <a href="#main-content" class="skip-link">Aller au contenu principal</a>
                <a href="#navigation" class="skip-link">Aller à la navigation</a>
                <a href="#footer" class="skip-link">Aller au pied de page</a>
            `;
            document.body.insertBefore(skipLinks, document.body.firstChild);
        }

        addScreenReaderSupport() {
            // Ajouter des annonces pour les lecteurs d'écran
            const announcer = document.createElement('div');
            announcer.id = 'screen-reader-announcer';
            announcer.className = 'sr-only';
            announcer.setAttribute('aria-live', 'polite');
            announcer.setAttribute('aria-atomic', 'true');
            document.body.appendChild(announcer);

            // Fonction pour annoncer aux lecteurs d'écran
            window.announceToScreenReader = (message) => {
                announcer.textContent = message;
                setTimeout(() => {
                    announcer.textContent = '';
                }, 1000);
            };
        }
    }

    // ============================================
    // 4. PWA AMÉLIORÉE
    // ============================================
    
    class PWAManager {
        constructor() {
            this.deferredPrompt = null;
            this.init();
        }

        init() {
            this.registerServiceWorker();
            this.addInstallPrompt();
            this.addOfflineSupport();
        }

        async registerServiceWorker() {
            if ('serviceWorker' in navigator) {
                console.log('[PWA] Enregistrement du Service Worker...');
                try {
                    const registration = await navigator.serviceWorker.register('/sw.js');
                    console.log('[PWA] ✅ Service Worker enregistré avec succès:', registration);

                    // Vérifier les mises à jour
                    registration.addEventListener('updatefound', () => {
                        console.log('[PWA] Nouvelle version du Service Worker détectée');
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('[PWA] Nouveau Service Worker installé');
                                this.showUpdateNotification();
                            }
                        });
                    });
                } catch (error) {
                    console.error('[PWA] ❌ Erreur lors de l\'enregistrement du Service Worker:', error);
                    console.error('[PWA] Détails:', error.message);
                }
            } else {
                console.warn('[PWA] ⚠️ Service Worker non supporté par ce navigateur');
            }
        }

        addInstallPrompt() {
            console.log('[PWA] Initialisation du système d\'installation...');
            
            // Vérifier si l'app est déjà installée
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('[PWA] Application déjà installée');
                return;
            }

            // Intercepter l'événement beforeinstallprompt
            window.addEventListener('beforeinstallprompt', (e) => {
                console.log('[PWA] Événement beforeinstallprompt détecté');
                e.preventDefault();
                this.deferredPrompt = e;
                this.showInstallButton();
            });

            // Attacher l'événement click de manière globale (délégation d'événements) - backup
            document.addEventListener('click', (e) => {
                const target = e.target.closest('#pwa-install-btn') || e.target.closest('.pwa-install-button');
                if (target) {
                    console.log('[PWA] Clic sur le bouton détecté via délégation');
                    e.preventDefault();
                    e.stopPropagation();
                    this.installPWA();
                }
            }, true); // Capture phase pour priorité

            // Vérifier périodiquement si le prompt est disponible (pour le débogage)
            setTimeout(() => {
                if (this.deferredPrompt) {
                    console.log('[PWA] Prompt disponible, bouton devrait être visible');
                } else {
                    console.log('[PWA] Aucun prompt disponible. Vérifiez : HTTPS, manifest.json, Service Worker');
                }
            }, 2000);
        }

        showInstallButton() {
            console.log('[PWA] Affichage du bouton d\'installation...');
            let installBtn = document.getElementById('pwa-install-btn');
            if (!installBtn) {
                console.log('[PWA] Création du bouton d\'installation');
                installBtn = document.createElement('button');
                installBtn.id = 'pwa-install-btn';
                installBtn.className = 'pwa-install-button';
                installBtn.innerHTML = '<i class="fas fa-download"></i> Installer l\'app';
                installBtn.setAttribute('aria-label', 'Installer l\'application');
                installBtn.setAttribute('title', 'Installer l\'application sur votre appareil');
                installBtn.type = 'button'; // Important pour éviter la soumission de formulaire
                
                // Créer une référence stable à this pour l'événement
                const self = this;
                
                // Fonction de gestion du clic
                const handleClick = function(e) {
                    console.log('[PWA] ✅✅✅ CLIC DÉTECTÉ SUR LE BOUTON ✅✅✅');
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Appeler directement installPWA
                    console.log('[PWA] Appel de installPWA()...');
                    self.installPWA();
                    
                    return false;
                };
                
                // Ajouter l'événement click DIRECT sur le bouton (priorité maximale)
                installBtn.addEventListener('click', handleClick, true); // Capture phase
                installBtn.addEventListener('click', handleClick, false); // Bubble phase aussi
                
                // Ajouter aussi onclick pour compatibilité maximale
                installBtn.onclick = handleClick;
                
                // Ajouter mousedown aussi (au cas où)
                installBtn.addEventListener('mousedown', function(e) {
                    console.log('[PWA] mousedown détecté');
                    e.preventDefault();
                    handleClick(e);
                });
                
                // Ajouter touchstart pour mobile
                installBtn.addEventListener('touchstart', function(e) {
                    console.log('[PWA] touchstart détecté');
                    e.preventDefault();
                    handleClick(e);
                });
                
                document.body.appendChild(installBtn);
                console.log('[PWA] Bouton créé et ajouté au DOM');
            } else {
                console.log('[PWA] Bouton existe déjà, réattacher les événements');
                // Réattacher les événements au cas où
                const self = this;
                const handleClick = function(e) {
                    console.log('[PWA] ✅ Clic sur bouton existant');
                    e.preventDefault();
                    e.stopPropagation();
                    self.installPWA();
                    return false;
                };
                installBtn.onclick = handleClick;
                installBtn.addEventListener('click', handleClick, true);
            }
            installBtn.style.display = 'flex';
            installBtn.style.pointerEvents = 'auto';
            installBtn.style.cursor = 'pointer';
            console.log('[PWA] Bouton affiché et prêt');
        }

        async installPWA() {
            console.log('[PWA] ========== installPWA() appelée ==========');
            console.log('[PWA] deferredPrompt:', this.deferredPrompt);
            console.log('[PWA] Type:', typeof this.deferredPrompt);
            
            if (!this.deferredPrompt) {
                console.warn('[PWA] ❌ Aucune invite d\'installation disponible');
                console.warn('[PWA] Vérifiez que :');
                console.warn('  - Le site est en HTTPS (ou localhost)');
                console.warn('  - Le manifest.json est valide');
                console.warn('  - Le Service Worker est enregistré');
                console.warn('  - L\'app n\'est pas déjà installée');
                
                // Afficher un message à l'utilisateur
                if (typeof window.feedbackManager !== 'undefined') {
                    window.feedbackManager.showInfo('L\'installation n\'est pas disponible pour le moment. Vérifiez que vous êtes en HTTPS et que le Service Worker est actif.');
                }
                return;
            }

            try {
                console.log('[PWA] ✅ deferredPrompt disponible, affichage du prompt...');
                console.log('[PWA] Méthodes disponibles:', Object.keys(this.deferredPrompt));
                
                // Vérifier que prompt() existe
                if (typeof this.deferredPrompt.prompt !== 'function') {
                    console.error('[PWA] ❌ deferredPrompt.prompt n\'est pas une fonction');
                    console.error('[PWA] Type de prompt:', typeof this.deferredPrompt.prompt);
                    console.error('[PWA] deferredPrompt complet:', this.deferredPrompt);
                    
                    // Essayer d'appeler prompt() quand même (certains navigateurs)
                    try {
                        console.log('[PWA] Tentative d\'appel direct de prompt()...');
                        const result = this.deferredPrompt.prompt();
                        console.log('[PWA] Résultat prompt():', result);
                    } catch (promptError) {
                        console.error('[PWA] Erreur lors de l\'appel de prompt():', promptError);
                    }
                    return;
                }
                
                // Afficher l'invite d'installation
                console.log('[PWA] Appel de deferredPrompt.prompt()...');
                const promptResult = this.deferredPrompt.prompt();
                console.log('[PWA] prompt() appelé, résultat:', promptResult);
                
                // Si prompt() retourne une Promise, l'attendre
                if (promptResult && typeof promptResult.then === 'function') {
                    console.log('[PWA] prompt() retourne une Promise, attente...');
                    await promptResult;
                }
                
                console.log('[PWA] Prompt affiché, attente de la réponse utilisateur...');
                
                // Attendre la réponse de l'utilisateur
                if (this.deferredPrompt.userChoice && typeof this.deferredPrompt.userChoice.then === 'function') {
                    const userChoice = await this.deferredPrompt.userChoice;
                    console.log('[PWA] userChoice reçu:', userChoice);
                    
                    const outcome = userChoice.outcome;
                    console.log('[PWA] Résultat installation:', outcome);
                    
                    if (outcome === 'accepted') {
                        console.log('[PWA] ✅ Installation acceptée par l\'utilisateur');
                        // Masquer le bouton après installation
                        const installBtn = document.getElementById('pwa-install-btn');
                        if (installBtn) {
                            installBtn.style.display = 'none';
                        }
                    } else {
                        console.log('[PWA] ❌ Installation refusée par l\'utilisateur');
                    }
                } else {
                    console.log('[PWA] ⚠️ userChoice n\'est pas une Promise, prompt() devrait avoir fonctionné');
                }
                
                // Réinitialiser la variable
                this.deferredPrompt = null;
                console.log('[PWA] deferredPrompt réinitialisé');
            } catch (error) {
                console.error('[PWA] ❌ Erreur lors de l\'installation:', error);
                console.error('[PWA] Type d\'erreur:', error.name);
                console.error('[PWA] Message:', error.message);
                console.error('[PWA] Stack:', error.stack);
                
                // Afficher un message à l'utilisateur
                if (typeof window.feedbackManager !== 'undefined') {
                    window.feedbackManager.showError('Erreur lors de l\'installation: ' + error.message);
                } else {
                    alert('Erreur lors de l\'installation: ' + error.message);
                }
            }
        }

        showUpdateNotification() {
            const notification = document.createElement('div');
            notification.className = 'pwa-update-notification';
            notification.innerHTML = `
                <div class="pwa-update-content">
                    <i class="fas fa-sync-alt"></i>
                    <span>Une nouvelle version est disponible !</span>
                    <button class="pwa-update-btn" onclick="window.location.reload()">Mettre à jour</button>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.classList.add('show'), 100);
        }

        addOfflineSupport() {
            // Afficher un indicateur de statut hors ligne
            window.addEventListener('online', () => {
                this.showOnlineNotification();
            });

            window.addEventListener('offline', () => {
                this.showOfflineNotification();
            });
        }

        showOnlineNotification() {
            if (typeof window.feedbackManager !== 'undefined') {
                window.feedbackManager.showSuccess('Vous êtes de nouveau en ligne', 'Connexion rétablie');
            }
        }

        showOfflineNotification() {
            if (typeof window.feedbackManager !== 'undefined') {
                window.feedbackManager.showWarning('Vous êtes hors ligne. Certaines fonctionnalités peuvent être limitées.', 'Mode hors ligne');
            }
        }
    }

    // ============================================
    // INITIALISATION
    // ============================================
    
    document.addEventListener('DOMContentLoaded', () => {
        console.log('[UX] Initialisation des managers...');
        // Initialiser les managers
        window.loadingManager = new LoadingManager();
        window.feedbackManager = new FeedbackManager();
        window.accessibilityManager = new AccessibilityManager();
        window.pwaManager = new PWAManager();
        console.log('[UX] ✅ Tous les managers initialisés');
        console.log('[UX] PWAManager:', window.pwaManager);

        // Ajouter l'ID main-content si absent
        const mainContent = document.querySelector('main, .main-content, #content');
        if (mainContent && !mainContent.id) {
            mainContent.id = 'main-content';
        }
    });

})();

