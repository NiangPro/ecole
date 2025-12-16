// Gestionnaire d'erreurs - Chargé de manière asynchrone
(function() {
    'use strict';
    
    function isExtensionError(message) {
        if (!message) return false;
        const msg = String(message).toLowerCase();
        const keywords = [
            'content.js', 'extension://', 'chrome-extension://',
            'moz-extension://', 'edge-extension://', 'safari-extension://',
            'unknown error', '[pwa]', '[ux]',
            'initialisation des managers', 'tous les managers initialisés',
            'pwamanager', 'service worker enregistré',
            'initialisation du système', 'aucun prompt disponible',
            'enregistrement du service worker', 'service worker enregistré avec succès',
            'événement beforeinstallprompt', 'application déjà installée',
            'prompt disponible', 'affichage du bouton', 'bouton créé',
            'bouton affiché', 'deferredprompt', 'userchoice',
            'installation acceptée', 'installation refusée',
            'serviceworkerregistration', 'navigationpreload'
        ];
        return keywords.some(keyword => msg.includes(keyword));
    }
    
    if (window.console) {
        const originalConsoleError = window.console.error;
        const originalConsoleWarn = window.console.warn;
        const originalConsoleLog = window.console.log;
        
        window.console.error = function(...args) {
            const message = args.map(arg => String(arg)).join(' ').toLowerCase();
            if (isExtensionError(message) ||
                (message.includes('401') && message.includes('favorites/check')) ||
                (message.includes('unauthorized') && message.includes('favorites'))) {
                return; // Ne pas afficher
            }
            originalConsoleError.apply(console, args);
        };
        
        window.console.warn = function(...args) {
            const message = args.map(arg => String(arg)).join(' ').toLowerCase();
            if (isExtensionError(message)) {
                return; // Ne pas afficher
            }
            originalConsoleWarn.apply(console, args);
        };
        
        window.console.log = function(...args) {
            const message = args.map(arg => String(arg)).join(' ').toLowerCase();
            if (isExtensionError(message)) {
                return; // Ne pas afficher
            }
            originalConsoleLog.apply(console, args);
        };
    }
    
    function handleUnhandledRejection(event) {
        try {
            if (!event || !event.reason) return false;
            
            const errorSource = String(event.reason.stack || event.reason.toString() || '').toLowerCase();
            const errorMessage = String(event.reason.message || event.reason.toString() || '').toLowerCase();
            const errorFile = String(event.reason.fileName || event.reason.source || event.reason.filename || '').toLowerCase();
            
            const isExtensionError = 
                errorSource.includes('content.js') || 
                errorSource.includes('extension://') || 
                errorSource.includes('chrome-extension://') ||
                errorSource.includes('moz-extension://') ||
                errorSource.includes('edge-extension://') ||
                errorSource.includes('safari-extension://') ||
                errorFile.includes('content.js') ||
                errorFile.includes('extension://') ||
                (errorMessage.includes('unknown error') && (errorSource.includes('content') || errorFile.includes('content')));
            
            if (isExtensionError) {
                if (event.preventDefault) event.preventDefault();
                if (event.stopPropagation) event.stopPropagation();
                if (event.stopImmediatePropagation) event.stopImmediatePropagation();
                return true;
            }
        } catch (e) {
            // Ignorer silencieusement
        }
        return false;
    }
    
    function handleError(event) {
        try {
            if (!event) return false;
            const errorSource = String(event.filename || event.source || event.target?.src || event.fileName || '').toLowerCase();
            const errorMessage = String(event.message || '').toLowerCase();
            
            if (errorSource.includes('content.js') || 
                errorSource.includes('extension://') || 
                errorSource.includes('chrome-extension://') ||
                errorSource.includes('moz-extension://') ||
                (errorMessage.includes('unknown error') && errorSource.includes('content'))) {
                if (event.preventDefault) event.preventDefault();
                if (event.stopPropagation) event.stopPropagation();
                return true;
            }
        } catch (e) {
            // Ignorer
        }
        return false;
    }
    
    if (window.addEventListener) {
        window.addEventListener('unhandledrejection', handleUnhandledRejection, { 
            capture: true, 
            passive: false,
            once: false
        });
        window.addEventListener('error', handleError, { 
            capture: true, 
            passive: false,
            once: false
        });
    }
    
    if (typeof window.onunhandledrejection !== 'undefined') {
        const originalOnUnhandledRejection = window.onunhandledrejection;
        window.onunhandledrejection = function(event) {
            if (!handleUnhandledRejection(event) && originalOnUnhandledRejection) {
                return originalOnUnhandledRejection.call(this, event);
            }
            return false;
        };
    }
    
    if (typeof window.onerror !== 'undefined') {
        const originalOnError = window.onerror;
        window.onerror = function(msg, source, lineno, colno, error) {
            const event = { 
                filename: source, 
                message: msg, 
                error: error,
                lineno: lineno,
                colno: colno
            };
            if (handleError(event)) {
                return true; // Erreur gérée, ne pas afficher
            }
            if (originalOnError) {
                return originalOnError.call(this, msg, source, lineno, colno, error);
            }
            return false;
        };
    }
})();

