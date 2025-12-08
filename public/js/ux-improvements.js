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
                if (pageLoader) {
                    pageLoader.setAttribute('data-anchor-active', 'true');
                    pageLoader.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important; pointer-events: none !important; z-index: -1 !important; position: fixed !important;';
                    pageLoader.classList.add('hidden');
                }
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
                if (pageLoader && window.location.hash && !pageLoader.classList.contains('hidden')) {
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
                        if (loader) {
                            loader.style.transition = 'opacity 0.5s ease-out, visibility 0.5s ease-out';
                            loader.classList.add('hidden');
                            setTimeout(() => {
                                if (loader) {
                                    loader.style.display = 'none';
                                }
                            }, 500);
                        }
                    }, 300);
                });
                
                // Empêcher le loader de s'afficher pour les liens d'ancrage
                // En surveillant les changements de hash dans l'URL
                let lastHash = window.location.hash;
                const hideLoaderForAnchor = () => {
                    if (loader) {
                        try {
                            if (!loader.classList.contains('hidden')) {
                                loader.classList.add('hidden');
                                loader.style.display = 'none';
                                loader.style.opacity = '0';
                                loader.style.visibility = 'hidden';
                                loader.style.pointerEvents = 'none';
                            }
                        } catch (error) {
                            // Erreur silencieuse
                        }
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
            // Désactivé : pas de chargement sur les boutons
            // document.addEventListener('submit', (e) => {
            //     const form = e.target;
            //     if (form.tagName === 'FORM' && !form.dataset.noLoader) {
            //         const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            //         if (submitBtn) {
            //             submitBtn.disabled = true;
            //             const loaderId = this.showLoader(submitBtn, 'Envoi en cours...');
            //             
            //             // Si le formulaire échoue, réactiver le bouton
            //             form.addEventListener('submit', () => {
            //                 setTimeout(() => {
            //                     if (submitBtn.disabled) {
            //                         this.hideLoader(loaderId);
            //                         submitBtn.disabled = false;
            //                     }
            //                 }, 5000);
            //             }, { once: true });
            //     }
            // });
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
                    if (pageLoader && window.location.hash && !pageLoader.classList.contains('hidden')) {
                        hidePageLoaderFn();
                    }
                });
                
                loaderObserver.observe(pageLoader, {
                    attributes: true,
                    attributeFilter: ['class', 'style']
                });
                
                // Vérification périodique pour s'assurer que le loader reste caché pour les ancres
                setInterval(() => {
                    if (pageLoader && window.location.hash && !pageLoader.classList.contains('hidden')) {
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
            if (notification) {
                setTimeout(() => {
                    if (notification) {
                        notification.classList.add('show');
                    }
                }, 10);
            }
            
            // Fermeture
            const closeBtn = notification?.querySelector('.custom-notification-close');
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
            if (!notification) return;
            
            try {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification && notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            } catch (error) {
                // Erreur silencieuse
            }
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
                try {
                    const registration = await navigator.serviceWorker.register('/sw.js');

                    // Vérifier les mises à jour
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                this.showUpdateNotification();
                            }
                        });
                    });
                } catch (error) {
                    // Erreur silencieuse
                }
            }
        }

        addInstallPrompt() {
            // Vérifier si l'app est déjà installée
            if (window.matchMedia('(display-mode: standalone)').matches) {
                return;
            }

            // Intercepter l'événement beforeinstallprompt
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.showInstallButton();
            });

            // Attacher l'événement click de manière globale (délégation d'événements) - backup
            document.addEventListener('click', (e) => {
                const target = e.target.closest('#pwa-install-btn') || e.target.closest('.pwa-install-button');
                if (target) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.installPWA();
                }
            }, true); // Capture phase pour priorité
        }

        showInstallButton() {
            let installBtn = document.getElementById('pwa-install-btn');
            if (!installBtn) {
                installBtn = document.createElement('button');
                installBtn.id = 'pwa-install-btn';
                installBtn.className = 'pwa-install-button';
                installBtn.innerHTML = '<i class="fas fa-download"></i> Installer l\'app';
                installBtn.setAttribute('aria-label', 'Installer l\'application');
                installBtn.setAttribute('title', 'Installer l\'application sur votre appareil');
                installBtn.type = 'button';
                
                const self = this;
                
                const handleClick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    self.installPWA();
                    return false;
                };
                
                installBtn.addEventListener('click', handleClick, true);
                installBtn.addEventListener('click', handleClick, false);
                installBtn.onclick = handleClick;
                
                installBtn.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    handleClick(e);
                });
                
                installBtn.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    handleClick(e);
                });
                
                document.body.appendChild(installBtn);
            } else {
                const self = this;
                const handleClick = function(e) {
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
        }

        async installPWA() {
            if (!this.deferredPrompt) {
                if (typeof window.feedbackManager !== 'undefined') {
                    window.feedbackManager.showInfo('L\'installation n\'est pas disponible pour le moment. Vérifiez que vous êtes en HTTPS et que le Service Worker est actif.');
                }
                return;
            }

            try {
                if (typeof this.deferredPrompt.prompt !== 'function') {
                    return;
                }
                
                const promptResult = this.deferredPrompt.prompt();
                
                if (promptResult && typeof promptResult.then === 'function') {
                    await promptResult;
                }
                
                if (this.deferredPrompt.userChoice && typeof this.deferredPrompt.userChoice.then === 'function') {
                    const userChoice = await this.deferredPrompt.userChoice;
                    const outcome = userChoice.outcome;
                    
                    if (outcome === 'accepted') {
                        const installBtn = document.getElementById('pwa-install-btn');
                        if (installBtn) {
                            installBtn.style.display = 'none';
                        }
                    }
                }
                
                this.deferredPrompt = null;
            } catch (error) {
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
        // Initialiser les managers
        window.loadingManager = new LoadingManager();
        window.feedbackManager = new FeedbackManager();
        window.accessibilityManager = new AccessibilityManager();
        window.pwaManager = new PWAManager();

        // Ajouter l'ID main-content si absent
        const mainContent = document.querySelector('main, .main-content, #content');
        if (mainContent && !mainContent.id) {
            mainContent.id = 'main-content';
        }
    });

})();

