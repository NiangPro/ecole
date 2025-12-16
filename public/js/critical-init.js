// Scripts critiques pour l'initialisation - Chargés de manière asynchrone
(function() {
    'use strict';
    
    // Surcharger console.error IMMÉDIATEMENT (AVANT tout autre script)
    if (window.console && window.console.error) {
        const originalError = window.console.error;
        window.console.error = function() {
            const args = Array.from(arguments);
            const message = args.map(a => String(a)).join(' ').toLowerCase();
            if (message.includes('content.js') || 
                message.includes('extension://') || 
                message.includes('chrome-extension://') ||
                message.includes('moz-extension://') ||
                message.includes('edge-extension://') ||
                message.includes('unknown error') ||
                (message.includes('401') && message.includes('favorites/check')) ||
                (message.includes('unauthorized') && message.includes('favorites')) ||
                message.includes('[pwa]') ||
                message.includes('[ux]')) {
                return; // Ne pas afficher
            }
            return originalError.apply(console, args);
        };
    }
    
    // PROTECTION GLOBALE : Wrapper pour sécuriser tous les accès à classList
    try {
        if (DOMTokenList && DOMTokenList.prototype) {
            const originalAdd = DOMTokenList.prototype.add;
            DOMTokenList.prototype.add = function(...tokens) {
                try {
                    if (this && this.length !== undefined) {
                        return originalAdd.apply(this, tokens);
                    }
                } catch (e) {
                    // Ignorer silencieusement
                }
            };
            
            const originalRemove = DOMTokenList.prototype.remove;
            DOMTokenList.prototype.remove = function(...tokens) {
                try {
                    if (this && this.length !== undefined) {
                        return originalRemove.apply(this, tokens);
                    }
                } catch (e) {
                    // Ignorer silencieusement
                }
            };
            
            const originalToggle = DOMTokenList.prototype.toggle;
            DOMTokenList.prototype.toggle = function(token, force) {
                try {
                    if (this && this.length !== undefined) {
                        return originalToggle.apply(this, [token, force]);
                    }
                } catch (e) {
                    // Ignorer silencieusement
                }
                return false;
            };
            
            const originalContains = DOMTokenList.prototype.contains;
            DOMTokenList.prototype.contains = function(token) {
                try {
                    if (this && this.length !== undefined) {
                        return originalContains.apply(this, [token]);
                    }
                } catch (e) {
                    // Ignorer silencieusement
                }
                return false;
            };
        }
    } catch (e) {
        // Ignorer si DOMTokenList n'est pas disponible
    }
    
    // Surcharger console.log pour masquer [UX] et [PWA]
    if (window.console && window.console.log) {
        const originalLog = window.console.log;
        window.console.log = function() {
            const args = Array.from(arguments);
            const fullMessage = args.map(a => {
                if (typeof a === 'object' && a !== null) {
                    try {
                        return JSON.stringify(a).toLowerCase();
                    } catch (e) {
                        return String(a).toLowerCase();
                    }
                }
                return String(a).toLowerCase();
            }).join(' ');
            
            const messagesToHide = [
                '[pwa]', '[ux]', 'initialisation des managers',
                'tous les managers initialisés', 'pwamanager',
                'service worker enregistré', 'initialisation du système',
                'aucun prompt disponible', 'content.js', 'unknown error',
                'enregistrement du service worker', 'service worker enregistré avec succès',
                'événement beforeinstallprompt', 'application déjà installée',
                'prompt disponible', 'affichage du bouton', 'bouton créé',
                'bouton affiché', 'deferredprompt', 'userchoice',
                'installation acceptée', 'installation refusée'
            ];
            
            for (const keyword of messagesToHide) {
                if (fullMessage.includes(keyword)) {
                    return; // Ne pas afficher
                }
            }
            
            return originalLog.apply(console, args);
        };
    }
    
    // Intercepter fetch pour empêcher les appels API si l'utilisateur n'est pas authentifié
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        const urlString = typeof url === 'string' ? url : url?.url || '';
        
        if (urlString.includes('/api/favorites/check')) {
            if (typeof window.isAuthenticated === 'undefined') {
                window.isAuthenticated = document.body.dataset.authenticated === 'true' || 
                                         document.querySelector('[data-user-id]') !== null;
            }
            
            if (!window.isAuthenticated) {
                return Promise.resolve({
                    ok: false,
                    status: 401,
                    statusText: 'Unauthorized',
                    json: () => Promise.resolve({ is_favorite: false }),
                    text: () => Promise.resolve(''),
                    headers: new Headers(),
                    clone: () => ({ ok: false, status: 401 })
                });
            }
        }
        
        return originalFetch.apply(this, args);
    };
})();

