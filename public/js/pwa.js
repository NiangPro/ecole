// PWA - Service Worker Registration et Gestion Mode Hors Ligne
(function() {
    'use strict';
    
    let serviceWorkerRegistration = null;
    let deferredPrompt = null;
    let isOnline = navigator.onLine;
    
    // Élément d'indicateur hors ligne
    let offlineIndicator = null;
    let offlineTimeout = null; // Pour gérer le timeout de masquage
    let hasShownOfflineMessage = false; // Pour éviter de réafficher si déjà affiché
    
    // Initialiser la PWA
    function initPWA() {
        registerServiceWorker();
        setupOfflineIndicator();
        setupOnlineStatusListener();
        setupInstallPrompt();
        preCacheImportantPages();
    }
    
    // Enregistrer le Service Worker
    function registerServiceWorker() {
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                        serviceWorkerRegistration = registration;
                        console.log('[PWA] Service Worker enregistré:', registration.scope);
                
                        // Vérifier les mises à jour
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                            
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    console.log('[PWA] Nouveau Service Worker disponible');
                                    showUpdateNotification();
                                } else if (newWorker.state === 'activated') {
                                    console.log('[PWA] Nouveau Service Worker activé');
                                    // Recharger la page pour utiliser le nouveau SW
                                    window.location.reload();
                        }
                    });
                });
                        
                        // Vérifier les mises à jour toutes les heures
                        setInterval(() => {
                            registration.update();
                        }, 3600000);
            })
            .catch((error) => {
                        console.error('[PWA] Erreur lors de l\'enregistrement du Service Worker:', error);
                    });
            });
        }
    }
    
    // Créer l'indicateur hors ligne
    function setupOfflineIndicator() {
        // Créer l'élément d'indicateur
        offlineIndicator = document.createElement('div');
        offlineIndicator.id = 'offline-indicator';
        offlineIndicator.className = 'offline-indicator';
        
        // FORCER la position en bas avec des styles inline
        offlineIndicator.style.cssText = 'position: fixed !important; bottom: 0 !important; top: auto !important; left: 0 !important; right: 0 !important; z-index: 9998 !important; transform: translateY(100%); transition: transform 0.3s ease;';
        
        offlineIndicator.innerHTML = `
            <div class="offline-indicator-content">
                <i class="fas fa-wifi"></i>
                <span class="offline-indicator-text">Mode hors ligne</span>
                <button class="offline-indicator-close" onclick="window.PWA.hideOfflineIndicator()" aria-label="Fermer" title="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Styles inline pour l'indicateur
        const style = document.createElement('style');
        style.textContent = `
            #offline-indicator {
                position: fixed !important;
                bottom: 0 !important;
                top: auto !important;
                left: 0 !important;
                right: 0 !important;
                background: linear-gradient(135deg, #ef4444, #dc2626) !important;
                color: white !important;
                padding: 12px 20px !important;
                text-align: center !important;
                z-index: 9998 !important;
                transform: translateY(100%) !important;
                transition: transform 0.3s ease !important;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.3) !important;
                margin: 0 !important;
            }
            
            #offline-indicator.show {
                transform: translateY(0) !important;
            }
            
            .offline-indicator.online {
                background: linear-gradient(135deg, #22c55e, #16a34a);
            }
            
            .offline-indicator-content {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                font-weight: 600;
                font-size: 0.9rem;
                position: relative;
            }
            
            .offline-indicator-content i {
                font-size: 1.1rem;
            }
            
            .offline-indicator-close {
                position: absolute;
                right: 10px;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                padding: 0;
                font-size: 0.85rem;
            }
            
            .offline-indicator-close:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: scale(1.1);
            }
            
            .offline-indicator-close:active {
                transform: scale(0.95);
            }
            
            .offline-indicator-close i {
                font-size: 0.85rem;
            }
            
            body.dark-mode .offline-indicator {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }
            
            body.dark-mode .offline-indicator.online {
                background: linear-gradient(135deg, #22c55e, #16a34a);
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(offlineIndicator);
        
        // Ne pas afficher au chargement initial
        // La barre s'affichera seulement lors d'un changement de statut (online/offline)
    }
    
    // Mettre à jour l'indicateur hors ligne
    function updateOfflineIndicator() {
        if (!offlineIndicator) return;
        
        // Annuler le timeout précédent s'il existe
        if (offlineTimeout) {
            clearTimeout(offlineTimeout);
            offlineTimeout = null;
        }
        
        if (!isOnline) {
            // Ne réafficher que si on vient de passer en mode hors ligne
            if (!hasShownOfflineMessage) {
                // FORCER la position en bas avec JavaScript
                offlineIndicator.style.setProperty('position', 'fixed', 'important');
                offlineIndicator.style.setProperty('bottom', '0', 'important');
                offlineIndicator.style.setProperty('top', 'auto', 'important');
                offlineIndicator.style.setProperty('left', '0', 'important');
                offlineIndicator.style.setProperty('right', '0', 'important');
                offlineIndicator.style.setProperty('z-index', '9998', 'important');
                offlineIndicator.style.setProperty('transform', 'translateY(100%)', 'important');
                
                offlineIndicator.classList.add('show');
                offlineIndicator.classList.remove('online');
                offlineIndicator.querySelector('.offline-indicator-text').textContent = 'Mode hors ligne - Contenu en cache disponible';
                hasShownOfflineMessage = true;
                
                // Forcer le transform après l'ajout de la classe show
                setTimeout(function() {
                    if (offlineIndicator && offlineIndicator.classList.contains('show')) {
                        offlineIndicator.style.setProperty('transform', 'translateY(0)', 'important');
                    }
                }, 10);
                
                console.log('[PWA] Affichage indicateur hors ligne en bas, masquage dans 5 secondes...');
                
                // Masquer automatiquement après 5 secondes
                offlineTimeout = setTimeout(function() {
                    console.log('[PWA] Masquage automatique...');
                    if (offlineIndicator) {
                        offlineIndicator.style.setProperty('transform', 'translateY(100%)', 'important');
                        setTimeout(function() {
                            if (offlineIndicator) {
                                offlineIndicator.classList.remove('show');
                            }
                        }, 300);
                        console.log('[PWA] Indicateur masqué');
                    }
                    offlineTimeout = null;
                }, 5000);
            }
        } else {
            // Réinitialiser le flag quand on revient en ligne
            hasShownOfflineMessage = false;
            
            // Masquer d'abord si elle est affichée
            if (offlineIndicator.classList.contains('show')) {
                offlineIndicator.classList.remove('show');
            }
            
            // Vérifier si l'utilisateur est connecté avant d'afficher le message
            // Ne pas afficher "Connexion rétablie" si l'utilisateur est authentifié
            var isUserAuthenticated = false;
            if (typeof window.userIsAuthenticated !== 'undefined') {
                // Vérifier toutes les formes possibles (booléen, chaîne, nombre)
                isUserAuthenticated = (window.userIsAuthenticated === true || 
                                      window.userIsAuthenticated === 'true' || 
                                      window.userIsAuthenticated === 1 ||
                                      String(window.userIsAuthenticated).toLowerCase() === 'true');
            }
            
            // Ne pas afficher le message si l'utilisateur est connecté
            if (isUserAuthenticated) {
                // Utilisateur connecté : ne rien afficher
                return;
            }
            
            // Afficher brièvement le message "en ligne" puis masquer (seulement si utilisateur non connecté)
            setTimeout(function() {
                if (offlineIndicator) {
                    offlineIndicator.classList.add('show', 'online');
                    offlineIndicator.querySelector('.offline-indicator-text').textContent = 'Connexion rétablie';
                    
                    setTimeout(function() {
                        if (offlineIndicator) {
                            offlineIndicator.classList.remove('show');
                        }
                    }, 3000);
                }
            }, 100);
        }
    }
    
    // Écouter les changements de statut réseau
    function setupOnlineStatusListener() {
        window.addEventListener('online', () => {
            isOnline = true;
            updateOfflineIndicator();
            console.log('[PWA] Connexion rétablie');
            
            // Synchroniser les données si nécessaire
            syncData();
        });
        
        window.addEventListener('offline', function() {
            console.log('[PWA] Événement offline détecté');
            isOnline = false;
            hasShownOfflineMessage = false; // Réinitialiser pour permettre l'affichage
            updateOfflineIndicator();
        });
        
        // Vérifier périodiquement le statut (mais ne pas réafficher si déjà affiché)
        setInterval(function() {
            const currentStatus = navigator.onLine;
            if (currentStatus !== isOnline) {
                isOnline = currentStatus;
                // Réinitialiser le flag si on change de statut
                if (currentStatus) {
                    hasShownOfflineMessage = false;
                } else {
                    // Réinitialiser le flag quand on passe hors ligne pour permettre l'affichage
                    hasShownOfflineMessage = false;
                }
                updateOfflineIndicator();
            }
        }, 5000);
    }
    
    // Synchroniser les données quand on revient en ligne
    function syncData() {
        // Ici vous pouvez ajouter la logique de synchronisation
        // Par exemple, envoyer des formulaires en attente, etc.
        console.log('[PWA] Synchronisation des données...');
    }
    
    // Gérer l'invite d'installation
    function setupInstallPrompt() {
        // Écouter l'événement beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Afficher un bouton d'installation personnalisé
            showInstallButton();
        });
        
        // Détecter si l'app est déjà installée
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('[PWA] Application installée');
        }
    }
    
    // Afficher le bouton d'installation
    function showInstallButton() {
        // Vérifier si le bouton existe déjà
        if (document.getElementById('pwa-install-button')) {
            return;
        }
        
        const installButton = document.createElement('button');
        installButton.id = 'pwa-install-button';
        installButton.className = 'pwa-install-button';
        installButton.innerHTML = '<i class="fas fa-download"></i> Installer l\'application';
        installButton.title = 'Installer l\'application sur votre appareil';
        
        // Styles pour le bouton
        const style = document.createElement('style');
        style.textContent = `
            .pwa-install-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: linear-gradient(135deg, #06b6d4, #14b8a6);
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }
            
            .pwa-install-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(6, 182, 212, 0.5);
            }
            
            .pwa-install-button:active {
                transform: translateY(0);
            }
            
            @media (max-width: 640px) {
                .pwa-install-button {
                    bottom: 10px;
                    right: 10px;
                    padding: 10px 16px;
                    font-size: 0.85rem;
                }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(installButton);
        
        // Gérer le clic
        installButton.addEventListener('click', async () => {
            if (!deferredPrompt) {
                return;
            }
            
            // Afficher l'invite d'installation
            deferredPrompt.prompt();
            
            // Attendre la réponse de l'utilisateur
            const { outcome } = await deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('[PWA] Installation acceptée');
                installButton.remove();
            } else {
                console.log('[PWA] Installation refusée');
            }
            
            deferredPrompt = null;
        });
    }
    
    // Pré-cacher les pages importantes
    function preCacheImportantPages() {
        if (!serviceWorkerRegistration || !serviceWorkerRegistration.active) {
            return;
        }
        
        // Attendre que le Service Worker soit prêt
        if (navigator.serviceWorker.controller) {
            // Pré-cacher les formations populaires
            const formations = [
                '/formations/html5',
                '/formations/css3',
                '/formations/javascript',
                '/formations/php',
                '/formations/python'
            ];
            
            formations.forEach(url => {
                navigator.serviceWorker.controller.postMessage({
                    type: 'CACHE_PAGE',
                    url: url
                });
            });
        }
    }

// Fonction pour nettoyer le cache
function cleanCache() {
        if (navigator.serviceWorker && navigator.serviceWorker.controller) {
        navigator.serviceWorker.controller.postMessage({
            type: 'CLEAN_CACHE'
        });
    }
}

// Nettoyer le cache toutes les heures
setInterval(cleanCache, 3600000);

    // Fonction pour forcer la mise à jour du Service Worker
    function forceUpdate() {
        if (serviceWorkerRegistration) {
            serviceWorkerRegistration.update();
        }
    }
    
    // Afficher une notification de mise à jour
    function showUpdateNotification() {
        // Vous pouvez implémenter une notification toast ici
        console.log('[PWA] Nouvelle version disponible');
    }
    
    // Fonction pour masquer manuellement l'indicateur
    function hideOfflineIndicator() {
        if (!offlineIndicator) return;
        
        console.log('[PWA] Masquage manuel de l\'indicateur');
        
        // Annuler le timeout s'il existe
        if (offlineTimeout) {
            clearTimeout(offlineTimeout);
            offlineTimeout = null;
        }
        
        // Masquer la barre avec animation
        offlineIndicator.style.setProperty('transform', 'translateY(100%)', 'important');
        setTimeout(function() {
            if (offlineIndicator) {
                offlineIndicator.classList.remove('show');
            }
        }, 300);
        hasShownOfflineMessage = true; // Empêcher la réaffichage immédiat
    }
    
    // Exposer les fonctions globalement
    window.PWA = {
        cleanCache: cleanCache,
        forceUpdate: forceUpdate,
        isOnline: () => isOnline,
        install: async () => {
            if (deferredPrompt) {
                await deferredPrompt.prompt();
            }
        },
        hideOfflineIndicator: hideOfflineIndicator
    };
    
    // Initialiser quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPWA);
    } else {
        initPWA();
    }
})();
