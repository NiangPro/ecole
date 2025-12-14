<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle avec NiangProgrammeur.')">
    <meta name="keywords" content="@yield('meta_keywords', 'formation développement web, HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress, IA, tutoriel gratuit, apprendre programmation, cours en ligne')">
    <meta name="author" content="Bassirou Niang - NiangProgrammeur">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon - Logo du site (placé tôt pour un chargement prioritaire) -->
    @php
        $faviconPng = asset('images/logo.png');
        $faviconIco = url('/favicon.ico');
    @endphp
    <!-- Favicon ICO (priorité pour compatibilité navigateurs) -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconIco }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconIco }}">
    <!-- Favicon PNG (meilleure qualité) - Plusieurs formats pour compatibilité maximale -->
    <link rel="preload" as="image" href="{{ $faviconPng }}" fetchpriority="high">
    <link rel="icon" type="image/png" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $faviconPng }}">
    <!-- Fallback supplémentaire pour tous les navigateurs -->
    <link rel="icon" href="{{ $faviconPng }}" type="image/png">
    <link rel="icon" href="{{ $faviconIco }}" type="image/x-icon">
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $faviconPng }}">
    
    <!-- Canonical URL -->
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    
    <!-- Hreflang pour support multilingue -->
    @php
        $currentUrl = url()->current();
        $currentLocale = app()->getLocale();
        $alternateLocale = $currentLocale === 'fr' ? 'en' : 'fr';
        // Générer l'URL alternative en changeant la langue
        $alternateUrl = route('language.set', ['locale' => $alternateLocale]) . '?redirect=' . urlencode($currentUrl);
    @endphp
    <link rel="alternate" hreflang="{{ $currentLocale }}" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ $alternateUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta property="og:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP et plus encore.')">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="NiangProgrammeur">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta name="twitter:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web.')">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    
    <!-- DNS Prefetch et Preconnect optimisés pour améliorer les performances -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//images.unsplash.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    
    <!-- Preconnect pour les ressources critiques (priorité) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#06b6d4">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NiangProgrammeur">
    
    @stack('meta')
    @stack('head')
    @stack('preload_images')
    
    <!-- Surcharger console.error IMMÉDIATEMENT (AVANT tout autre script) -->
    <script>
        (function() {
            'use strict';
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
            // DOIT être fait AVANT tout autre script
            try {
                // Wrapper pour classList.add
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
                    
                    // Wrapper pour classList.remove
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
                    
                    // Wrapper pour classList.toggle
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
                    
                    // Wrapper pour classList.contains
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
            
            // Surcharger aussi console.log IMMÉDIATEMENT pour masquer [UX] et [PWA]
            // DOIT être fait AVANT tout autre script pour intercepter tous les messages
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
                    
                    // Liste exhaustive de tous les messages à masquer
                    const messagesToHide = [
                        '[pwa]', '[ux]', '[pwa]', '[ux]',
                        'initialisation des managers',
                        'tous les managers initialisés',
                        'pwamanager',
                        'service worker enregistré',
                        'initialisation du système',
                        'aucun prompt disponible',
                        'content.js',
                        'unknown error',
                        'enregistrement du service worker',
                        'service worker enregistré avec succès',
                        'événement beforeinstallprompt',
                        'application déjà installée',
                        'prompt disponible',
                        'affichage du bouton',
                        'bouton créé',
                        'bouton affiché',
                        'deferredprompt',
                        'userchoice',
                        'installation acceptée',
                        'installation refusée'
                    ];
                    
                    // Vérifier si le message contient un des mots-clés à masquer
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
                
                // Si c'est une requête vers /api/favorites/check
                if (urlString.includes('/api/favorites/check')) {
                    // Vérifier l'authentification AVANT de faire la requête
                    if (typeof window.isAuthenticated === 'undefined') {
                        window.isAuthenticated = document.body.dataset.authenticated === 'true' || 
                                                 document.querySelector('[data-user-id]') !== null;
                    }
                    
                    // Si l'utilisateur n'est pas authentifié, NE PAS faire la requête
                    if (!window.isAuthenticated) {
                        // Retourner une promesse résolue avec une réponse 401 silencieuse
                        // Cela évite l'erreur réseau visible dans la console
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
                
                // Pour les autres requêtes, faire la requête normale
                return originalFetch.apply(this, args);
            };
        })();
    </script>
    
    <!-- Gestionnaire d'erreurs ULTRA-PRIORITAIRE - Doit être le PREMIER script -->
    <script>
        (function() {
            'use strict';
            
            // Intercepter TOUTES les erreurs d'extensions AVANT qu'elles n'apparaissent
            // Surcharger console.error et console.warn IMMÉDIATEMENT
            if (window.console) {
                const originalConsoleError = window.console.error;
                const originalConsoleWarn = window.console.warn;
                const originalConsoleLog = window.console.log;
                
                // Fonction pour détecter les erreurs d'extensions et messages PWA/UX
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
                
                // Masquer les erreurs d'extensions dans console.error
                window.console.error = function(...args) {
                    const message = args.map(arg => String(arg)).join(' ').toLowerCase();
                    if (isExtensionError(message) ||
                        (message.includes('401') && message.includes('favorites/check')) ||
                        (message.includes('unauthorized') && message.includes('favorites'))) {
                        return; // Ne pas afficher
                    }
                    originalConsoleError.apply(console, args);
                };
                
                // Masquer les avertissements d'extensions dans console.warn
                window.console.warn = function(...args) {
                    const message = args.map(arg => String(arg)).join(' ').toLowerCase();
                    if (isExtensionError(message)) {
                        return; // Ne pas afficher
                    }
                    originalConsoleWarn.apply(console, args);
                };
                
                // Optionnel : masquer aussi les logs d'extensions
                window.console.log = function(...args) {
                    const message = args.map(arg => String(arg)).join(' ').toLowerCase();
                    if (isExtensionError(message)) {
                        return; // Ne pas afficher
                    }
                    originalConsoleLog.apply(console, args);
                };
            }
            
            // Gestionnaire d'erreurs de promesses - Version ultra-robuste
            function handleUnhandledRejection(event) {
                try {
                    if (!event || !event.reason) return false;
                    
                    const errorSource = String(event.reason.stack || event.reason.toString() || '').toLowerCase();
                    const errorMessage = String(event.reason.message || event.reason.toString() || '').toLowerCase();
                    const errorFile = String(event.reason.fileName || event.reason.source || event.reason.filename || '').toLowerCase();
                    
                    // Détecter toutes les variantes d'extensions
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
            
            // Gestionnaire d'erreurs JavaScript classiques
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
            
            // Ajouter les gestionnaires IMMÉDIATEMENT avec capture (priorité maximale)
            if (window.addEventListener) {
                // Utiliser capture: true pour intercepter AVANT les autres gestionnaires
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
            
            // Surcharger window.onunhandledrejection si disponible
            if (typeof window.onunhandledrejection !== 'undefined') {
                const originalOnUnhandledRejection = window.onunhandledrejection;
                window.onunhandledrejection = function(event) {
                    if (!handleUnhandledRejection(event) && originalOnUnhandledRejection) {
                        return originalOnUnhandledRejection.call(this, event);
                    }
                    return false;
                };
            }
            
            // Surcharger window.onerror si disponible
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
    </script>
    
    <title>@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')</title>
    
    <!-- Variable globale pour l'authentification (doit être définie avant tous les scripts) -->
    <script>
        window.userIsAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        // S'assurer que c'est bien une chaîne pour la comparaison
        if (typeof window.userIsAuthenticated === 'boolean') {
            window.userIsAuthenticated = window.userIsAuthenticated.toString();
        }
    </script>
    
    <!-- CSS critique minimal pour éviter le FOUC -->
    <style id="critical-css">
        /* Styles critiques minimaux - Above the fold */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #ffffff;
            color: #1e293b;
            overflow-x: hidden;
        }
        
        
        /* Hero Section - Critique pour LCP */
        .hero-section {
            position: relative;
            z-index: 2;
            width: 100%;
            min-height: 65vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 40px 60px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                        url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=75') center/cover no-repeat;
            background-attachment: fixed;
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            text-align: center;
        }
        
        .main-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 30px;
            color: #fff;
        }
        
        .subtitle {
            font-size: clamp(1rem, 2vw, 1.3rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        
        @media (max-width: 768px) {
            .hero-section {
                min-height: 55vh;
                padding: 60px 20px 40px;
            }
            
            .main-title {
                font-size: clamp(1.8rem, 4vw, 2.2rem);
                line-height: 1.3;
                margin-bottom: 20px;
            }
        }
        
        /* CSS Critique pour la page About - Centrage immédiat */
        .container {
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }
        
        /* Centrage du contenu hero immédiatement pour About */
        section.relative.min-h-screen > div.container {
            text-align: center !important;
        }
        
        section.relative.min-h-screen > div.container > div {
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center !important;
        }
        
        /* Centrage du texte dans le hero */
        section.relative.min-h-screen h1,
        section.relative.min-h-screen p {
            text-align: center !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        
        /* Centrage des conteneurs de largeur maximale */
        .max-w-5xl {
            max-width: 64rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            display: block !important;
        }
        
        .max-w-7xl {
            max-width: 80rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            display: block !important;
        }
        
        .max-w-3xl {
            max-width: 48rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            display: block !important;
        }
        
        /* Force le centrage du hero content avant le chargement de Tailwind */
        section.relative.min-h-screen .container > div.max-w-5xl {
            display: block !important;
            width: 100% !important;
            max-width: 64rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center !important;
        }
        
        section.relative.min-h-screen .container > div.max-w-5xl > h1,
        section.relative.min-h-screen .container > div.max-w-5xl > p {
            text-align: center !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        
        section.relative.min-h-screen .container > div.max-w-5xl > p.max-w-3xl {
            max-width: 48rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center !important;
        }
    </style>
    
    <!-- Script IMMÉDIAT pour appliquer les styles critiques avant le rendu -->
    <script>
        (function() {
            'use strict';
            // Appliquer les styles critiques IMMÉDIATEMENT via JavaScript
            // Ce script s'exécute AVANT que le DOM ne soit rendu
            function applyCriticalStyles() {
                // Créer un style inline pour forcer le centrage
                const criticalStyle = document.createElement('style');
                criticalStyle.id = 'about-critical-inline';
                criticalStyle.textContent = `
                    /* Force le centrage IMMÉDIAT */
                    body section.relative.min-h-screen,
                    body section.relative.min-h-screen > div,
                    body section.relative.min-h-screen > div > div {
                        text-align: center !important;
                    }
                    body .container {
                        margin-left: auto !important;
                        margin-right: auto !important;
                        text-align: center !important;
                    }
                    body .max-w-5xl,
                    body .max-w-7xl,
                    body .max-w-3xl {
                        margin-left: auto !important;
                        margin-right: auto !important;
                        display: block !important;
                        text-align: center !important;
                    }
                    body section.relative.min-h-screen h1,
                    body section.relative.min-h-screen p {
                        text-align: center !important;
                        margin-left: auto !important;
                        margin-right: auto !important;
                    }
                `;
                // Insérer AVANT le premier élément du head
                if (document.head) {
                    const firstChild = document.head.firstChild;
                    if (firstChild) {
                        document.head.insertBefore(criticalStyle, firstChild);
                    } else {
                        document.head.appendChild(criticalStyle);
                    }
                } else {
                    // Si head n'existe pas encore, attendre et réessayer
                    (function checkHead() {
                        if (document.head) {
                            const firstChild = document.head.firstChild;
                            if (firstChild) {
                                document.head.insertBefore(criticalStyle, firstChild);
                            } else {
                                document.head.appendChild(criticalStyle);
                            }
                        } else {
                            setTimeout(checkHead, 0);
                        }
                    })();
                }
            }
            
            // Appliquer immédiatement
            if (document.readyState === 'loading' || document.readyState === 'interactive') {
                applyCriticalStyles();
            } else {
                // DOM déjà chargé, appliquer quand même
                applyCriticalStyles();
            }
        })();
    </script>
    
    <!-- Tailwind CSS - Chargé de manière SYNCHRONE pour éviter le FOUC -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- reCAPTCHA v3 (invisible) -->
    @php
        $recaptchaSiteKey = config('services.recaptcha.site_key', '');
    @endphp
    @if(!empty($recaptchaSiteKey))
    <!-- reCAPTCHA v3 - Chargé de manière différée pour ne pas bloquer le rendu -->
    <script>
        // Fonction pour exécuter reCAPTCHA avant la soumission du formulaire
        function executeRecaptcha(formId, callback) {
            if (typeof grecaptcha === 'undefined') {
                // reCAPTCHA non chargé, continuer sans vérification
                callback();
                return;
            }
            
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptchaSiteKey }}', {action: 'submit'}).then(function(token) {
                    // Ajouter le token au formulaire
                    const form = document.getElementById(formId);
                    if (form) {
                        // Supprimer le token précédent s'il existe
                        const existingToken = form.querySelector('input[name="g-recaptcha-response"]');
                        if (existingToken) {
                            existingToken.remove();
                        }
                        
                        // Ajouter le nouveau token
                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = 'g-recaptcha-response';
                        tokenInput.value = token;
                        form.appendChild(tokenInput);
                        
                        // Exécuter le callback
                        callback();
                    }
                });
            });
        }
        
        // Charger reCAPTCHA après le chargement de la page
        window.addEventListener('load', function() {
            const script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        });
    </script>
    @endif
    <!-- Font Awesome - Chargement synchrone pour éviter le FOUC -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts optimisé avec preload et font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Google Fonts - Chargement synchrone pour éviter le FOUC -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&family=Orbitron:wght@400;700;900&display=swap">
    <!-- Force font-display: swap pour toutes les polices -->
    <style>
        @font-face {
            font-family: 'Inter';
            font-display: swap;
        }
        @font-face {
            font-family: 'Poppins';
            font-display: swap;
        }
        @font-face {
            font-family: 'Orbitron';
            font-display: swap;
        }
    </style>
    
    <!-- Toastr CSS - Chargé de manière synchrone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- UX Improvements CSS -->
    <link rel="stylesheet" href="{{ asset('css/ux-improvements.css') }}">
    
    <!-- Social Features CSS -->
    <link rel="stylesheet" href="{{ asset('css/social-features.css') }}">
    
    @php
        $adsenseSettings = \Illuminate\Support\Facades\Cache::remember('adsense_settings', 3600, function () {
            return \App\Models\AdSenseSetting::first();
        });
        
        // Extraire l'ID client du code AdSense
        $adsenseClientId = null;
        if ($adsenseSettings && $adsenseSettings->adsense_code) {
            // Chercher ca-pub-XXXXXXXXXXXXXXX dans le code
            if (preg_match('/ca-pub-([0-9]+)/', $adsenseSettings->adsense_code, $matches)) {
                $adsenseClientId = 'ca-pub-' . $matches[1];
            }
            // Si le code contient déjà le script complet, l'utiliser tel quel
            $adsenseCode = $adsenseSettings->adsense_code;
        }
    @endphp
    
    @if($adsenseSettings && $adsenseClientId)
        <!-- AdSense Auto Ads - Chargé de manière différée -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}"
                crossorigin="anonymous"></script>
    @elseif($adsenseSettings && $adsenseSettings->adsense_code && strpos($adsenseSettings->adsense_code, '<script') !== false)
        <!-- AdSense - Code complet fourni -->
        {!! $adsenseSettings->adsense_code !!}
    @endif
    
    @php
        // Configuration Ezoic
        $ezoicSettings = \Illuminate\Support\Facades\Cache::remember('ezoic_settings', 3600, function () {
            return \App\Models\EzoicSetting::first();
        });
    @endphp
    
    @if($ezoicSettings)
        @if($ezoicSettings->privacy_scripts)
            <!-- Ezoic Privacy Scripts (DOIT être chargé en premier) -->
            {!! $ezoicSettings->privacy_scripts !!}
        @endif
        
        @if($ezoicSettings->ezoic_code)
            <!-- Ezoic Header Script (chargé APRÈS les Privacy Scripts) -->
            {!! $ezoicSettings->ezoic_code !!}
        @endif
    @endif
    
    <!-- Google Analytics -->
    @php
        $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return \App\Models\SiteSetting::first();
        });
        $gaId = $siteSettings->google_analytics_id ?? config('services.google_analytics.id');
    @endphp
    
    @if($gaId)
    <!-- Google tag (gtag.js) - Chargé de manière différée pour ne pas bloquer le rendu -->
    <script>
        // Charger Google Analytics de manière asynchrone après le chargement de la page
        window.addEventListener('load', function() {
            const script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id={{ $gaId }}';
            document.head.appendChild(script);
            
            script.onload = function() {
                try {
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    
                    // Vérifier le consentement cookies
                    const cookieConsent = localStorage.getItem('cookieConsent');
                    if (cookieConsent === 'accepted') {
                        try {
                            gtag('config', '{{ $gaId }}');
                        } catch (configError) {
                            console.warn('Erreur lors de la configuration gtag:', configError);
                        }
                    } else if (cookieConsent === 'refused') {
                        try {
                            gtag('config', '{{ $gaId }}', {
                                'anonymize_ip': true,
                                'storage': 'none'
                            });
                        } catch (configError) {
                            console.warn('Erreur lors de la configuration gtag:', configError);
                        }
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement de Google Analytics:', error);
                }
            };
            
            script.onerror = function() {
                console.warn('Erreur lors du chargement du script Google Analytics');
            };
        });
    </script>
    @endif
    
    <style>
        /* GLOBAL STYLES */
        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        
        html.loaded,
        html.tailwind-loaded {
            visibility: visible !important;
        }
        
        /* Le body est toujours visible */
        body {
            margin: 0;
            padding: 0;
            padding-top: 60px;
            overflow-x: hidden;
            min-height: 100vh;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        * {
            box-sizing: border-box;
        }
        
        /* Back to Top Button */
        .back-to-top-button {
            position: fixed;
            bottom: 170px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border: 2px solid rgba(6, 182, 212, 0.3);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 10000;
            opacity: 0;
            transform: translateY(20px) scale(0.8);
        }
        
        /* Quand le widget de langue n'existe pas, le bouton back to top prend sa place */
        .back-to-top-button.no-language-widget {
            bottom: 120px;
        }
        
        .back-to-top-button.show {
            display: flex;
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        
        .back-to-top-button:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
            border-color: rgba(6, 182, 212, 0.6);
            background: linear-gradient(135deg, #14b8a6, #06b6d4);
        }
        
        .back-to-top-button:active {
            transform: translateY(-3px) scale(1.05);
        }
        
        .back-to-top-tooltip {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(15, 23, 42, 0.95);
            color: #fff;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 11px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        
        body:not(.dark-mode) .back-to-top-tooltip {
            background: rgba(255, 255, 255, 0.95) !important;
            color: rgba(30, 41, 59, 0.9) !important;
            border-color: rgba(6, 182, 212, 0.3) !important;
        }
        
        .back-to-top-button:hover .back-to-top-tooltip {
            opacity: 1;
        }
        
        body:not(.dark-mode) .back-to-top-button {
            background: linear-gradient(135deg, #06b6d4, #14b8a6) !important;
            border-color: rgba(6, 182, 212, 0.4) !important;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3) !important;
        }
        
        body:not(.dark-mode) .back-to-top-button:hover {
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5) !important;
        }
        
        @media (max-width: 768px) {
            .back-to-top-button {
                bottom: 170px;
                right: 18px;
                width: 36px;
                height: 36px;
                font-size: 14px;
                z-index: 10000;
            }
            
            .back-to-top-button.no-language-widget {
                bottom: 120px;
            }
            
            .navbar-language-button {
                width: 32px;
                height: 32px;
            }
            
            .navbar-language-flag {
                width: 18px;
                height: 13px;
            }
            
            .dark-mode-widget {
                bottom: 70px;
                right: 12px;
                z-index: 9999;
            }
            
            .dark-mode-button {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            
            .whatsapp-widget {
                bottom: 10px;
                right: 12px;
                z-index: 9998;
            }
            
            .whatsapp-button {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
            
            .back-to-top-tooltip {
                display: none;
            }
        }
        
        /* Language Widget dans la navbar */
        .navbar-language-widget {
            display: flex;
            align-items: center;
            margin-right: 12px;
        }
        
        .navbar-language-button {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: #06b6d4;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: visible;
        }
        
        .navbar-language-flag {
            width: 22px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
            display: block;
        }
        
        .navbar-language-button:hover {
            background: rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.5);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }
        
        .navbar-language-tooltip {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-5px);
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        
        .navbar-language-tooltip::after {
            content: '';
            position: absolute;
            bottom: 100%;
            right: 12px;
            border: 5px solid transparent;
            border-bottom-color: rgba(15, 23, 42, 0.95);
        }
        
        .navbar-language-button:hover .navbar-language-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Dark Mode Widget */
        .dark-mode-widget {
            position: fixed;
            bottom: 70px;
            right: 20px;
            z-index: 9998;
        }
        
        .dark-mode-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #334155, #475569);
            border: 2px solid rgba(6, 182, 212, 0.3);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .dark-mode-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 25px rgba(6, 182, 212, 0.4);
            border-color: rgba(6, 182, 212, 0.5);
        }
        
        .dark-mode-button.active {
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border-color: rgba(6, 182, 212, 0.6);
        }
        
        .dark-mode-tooltip {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(15, 23, 42, 0.95);
            color: #fff;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 11px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        
        .dark-mode-tooltip::after {
            content: '';
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-left-color: rgba(15, 23, 42, 0.95);
        }
        
        .dark-mode-button:hover .dark-mode-tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        /* Dark Mode Styles */
        body.dark-mode {
            background: #0a0a0f !important;
            color: #e5e7eb !important;
        }
        
        body.dark-mode .bg-canvas {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
        }
        
        body.dark-mode .navbar-modern {
            background: rgba(15, 23, 42, 0.95) !important;
        }
        
        body.dark-mode .navbar-modern.scrolled {
            background: rgba(15, 23, 42, 0.98) !important;
        }
        
        body.dark-mode [style*="rgba(51, 65, 85"] {
            background: rgba(15, 23, 42, 0.8) !important;
        }
        
        body.dark-mode [style*="rgba(51, 65, 85, 0.5"] {
            background: rgba(15, 23, 42, 0.6) !important;
        }
        
        body.dark-mode [style*="rgba(51, 65, 85, 0.7"] {
            background: rgba(15, 23, 42, 0.8) !important;
        }
        
        body.dark-mode [style*="rgba(51, 65, 85, 0.85"] {
            background: rgba(15, 23, 42, 0.95) !important;
        }
        
        body.dark-mode [style*="rgba(71, 85, 105"] {
            background: rgba(30, 41, 59, 0.8) !important;
        }
        
        .whatsapp-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .whatsapp-button {
            position: relative;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            animation: whatsappPulse 2s ease-in-out infinite;
        }
        
        .whatsapp-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
        }
        
        .whatsapp-button i {
            font-size: 18px;
            color: #fff;
            z-index: 1;
        }
        
        .whatsapp-tooltip {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            bottom: -6px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid rgba(0, 0, 0, 0.9);
        }
        
        .whatsapp-button:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        @keyframes whatsappPulse {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            }
            50% {
                box-shadow: 0 4px 30px rgba(37, 211, 102, 0.8);
            }
        }
        
        .whatsapp-popup {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 320px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            display: none;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }
        
        .whatsapp-popup.active {
            display: block;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .whatsapp-header {
            background: linear-gradient(135deg, #25D366, #128C7E);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
        }
        
        .whatsapp-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .whatsapp-name {
            font-weight: 700;
            font-size: 16px;
            flex: 1;
        }
        
        .whatsapp-status {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .whatsapp-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            transition: transform 0.2s;
        }
        
        .whatsapp-close:hover {
            transform: rotate(90deg);
        }
        
        .whatsapp-body {
            padding: 20px;
            min-height: 100px;
            background: #f0f0f0;
        }
        
        .whatsapp-message {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .whatsapp-footer {
            padding: 15px 20px;
            background: #f0f0f0;
        }
        
        .whatsapp-send-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: #fff;
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .whatsapp-send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        }
        
        @media (max-width: 768px) {
            .whatsapp-popup {
                width: calc(100vw - 40px);
                right: -10px;
            }
            
            .whatsapp-widget {
                bottom: 15px;
                right: 15px;
            }
            
            .dark-mode-widget {
                bottom: 75px;
                right: 15px;
            }
            
            .language-widget {
                bottom: 120px;
                right: 15px;
            }
            
            .back-to-top-button {
                bottom: 170px;
                right: 15px;
            }
        }
    </style>
    @yield('styles')
    
    <!-- Style critique pour éviter le FOUC -->
    <style>
        /* Masquer le contenu jusqu'à ce que les CSS soient chargés */
        html {
            visibility: hidden;
            opacity: 0;
        }
        html.css-loaded {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        /* Fallback pour navigateurs sans JavaScript */
        body {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
    <script>
        // Marquer comme chargé une fois que les CSS critiques sont prêts
        (function() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.documentElement.classList.add('css-loaded');
                });
            } else {
                document.documentElement.classList.add('css-loaded');
            }
        })();
    </script>
</head>
<body class="bg-white text-gray-900" lang="{{ app()->getLocale() }}">
    @if($ezoicSettings && $ezoicSettings->ezoic_body_code)
        <!-- Ezoic Code (Body - début) -->
        {!! $ezoicSettings->ezoic_body_code !!}
    @endif
    
    <!-- Skip Links pour l'accessibilité -->
    <div class="skip-links">
        <a href="#main-content" class="skip-link">Aller au contenu principal</a>
        <a href="#navigation" class="skip-link">Aller à la navigation</a>
        <a href="#footer" class="skip-link">Aller au pied de page</a>
    </div>
    
    @include('partials.navigation')
    
    @include('partials.schema-org')
    
    <main id="main-content" role="main">
        <!-- Breadcrumbs -->
        @include('partials.breadcrumbs')
        
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    <!-- Cookie Banner -->
    @include('partials.cookie-banner')
    
    <!-- Back to Top Button -->
    <button id="back-to-top" class="back-to-top-button" onclick="scrollToTop()" title="Retour en haut" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
        <span class="back-to-top-tooltip">Retour en haut</span>
    </button>
    
    @php
        // Pages où afficher le widget de langue
        $showLanguageWidget = request()->routeIs([
            'home',
            'formations.all',
            'formations.html5',
            'formations.css3',
            'formations.javascript',
            'formations.php',
            'formations.bootstrap',
            'formations.java',
            'formations.sql',
            'formations.c',
            'formations.cpp',
            'formations.csharp',
            'formations.dart',
            'formations.git',
            'formations.wordpress',
            'formations.ia',
            'formations.python',
            'formations.go',
            'formations.rust',
            'formations.ruby',
            'formations.cybersecurite',
            'formations.data-science',
            'formations.big-data',
            'exercices',
            'exercices.language',
            'exercices.detail',
            'exercices.run',
            'quiz',
            'quiz.language',
            'quiz.result',
            'monetization.index',
            'monetization.donations',
            'monetization.donations.alias',
            'monetization.affiliates',
            'monetization.courses',
            'monetization.course.show',
            'payment.wave',
            'docs'
        ]);
    @endphp
    
    
    <!-- Dark Mode Toggle Button -->
    <div id="dark-mode-widget" class="dark-mode-widget">
        <button id="dark-mode-toggle" class="dark-mode-button" onclick="toggleDarkMode()" title="Basculer le mode sombre">
            <i class="fas fa-moon" id="dark-mode-icon"></i>
            <span class="dark-mode-tooltip" id="dark-mode-tooltip">Activer le mode sombre</span>
        </button>
    </div>
    
    <!-- WhatsApp Chatbot Widget -->
    @php
        $whatsappNumber = \App\Models\SiteSetting::get('contact_phone', '+221783123657');
        // Nettoyer le numéro pour WhatsApp (enlever espaces, +, etc.)
        $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
    @endphp
    @if($whatsappNumber)
    <div id="whatsapp-widget" class="whatsapp-widget">
        <div class="whatsapp-button" onclick="toggleWhatsApp()" title="Cliquer pour discuter avec NiangProgrammeur">
            <i class="fab fa-whatsapp"></i>
            <span class="whatsapp-tooltip">Cliquer pour discuter avec NiangProgrammeur</span>
        </div>
        <div class="whatsapp-popup" id="whatsappPopup">
            <div class="whatsapp-header">
                <div class="whatsapp-avatar">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div class="whatsapp-name">NiangProgrammeur</div>
                    <div class="whatsapp-status">En ligne</div>
                </div>
                <button class="whatsapp-close" onclick="toggleWhatsApp()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="whatsapp-body">
                <div class="whatsapp-message">
                    <p>Bonjour ! 👋</p>
                    <p>Comment puis-je vous aider aujourd'hui ?</p>
                </div>
            </div>
            <div class="whatsapp-footer">
                <a href="https://wa.me/{{ $whatsappNumber }}?text=Bonjour,%20je%20souhaite%20en%20savoir%20plus%20sur%20vos%20formations." 
                   target="_blank" 
                   class="whatsapp-send-btn">
                    <i class="fab fa-whatsapp"></i>
                    Ouvrir WhatsApp
                </a>
            </div>
        </div>
    </div>
    <script>
        // Back to Top Button
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Afficher/masquer le bouton back to top au scroll
        window.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('back-to-top');
            if (backToTopButton) {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            }
        });
        
        // Ajuster la position du bouton back to top (le widget de langue est maintenant dans la navbar)
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopButton = document.getElementById('back-to-top');
            if (backToTopButton) {
                    backToTopButton.classList.add('no-language-widget');
            }
        });
        
        // Dark Mode Toggle
        function toggleDarkMode() {
            const body = document.body;
            const isDark = body.classList.toggle('dark-mode');
            const icon = document.getElementById('dark-mode-icon');
            const tooltip = document.getElementById('dark-mode-tooltip');
            const button = document.getElementById('dark-mode-toggle');
            
            // Sauvegarder la préférence
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
            
            // Mettre à jour l'icône et le tooltip
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                tooltip.textContent = 'Désactiver le mode sombre';
                button.classList.add('active');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                tooltip.textContent = 'Activer le mode sombre';
                button.classList.remove('active');
            }
        }
        
        // Initialiser le dark mode au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            const body = document.body;
            const icon = document.getElementById('dark-mode-icon');
            const tooltip = document.getElementById('dark-mode-tooltip');
            const button = document.getElementById('dark-mode-toggle');
            
            if (darkMode === 'enabled') {
                body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                tooltip.textContent = 'Désactiver le mode sombre';
                button.classList.add('active');
            }
        });
        
        // Language Toggle Function
        function toggleLanguage() {
            // Récupérer la langue actuelle depuis l'attribut lang du body
            const currentLang = document.body.getAttribute('lang') || 'fr';
            const newLang = currentLang === 'fr' ? 'en' : 'fr';
            
            // Récupérer l'URL actuelle
            const currentUrl = window.location.pathname + window.location.search;
            
            // Rediriger vers la route de changement de langue avec l'URL actuelle en paramètre
            window.location.href = '{{ route("language.set", ":locale") }}'.replace(':locale', newLang) + '?redirect=' + encodeURIComponent(currentUrl);
        }
        
        function toggleWhatsApp() {
            const popup = document.getElementById('whatsappPopup');
            popup.classList.toggle('active');
        }
        
        // Fermer en cliquant en dehors
        document.addEventListener('click', function(event) {
            const widget = document.getElementById('whatsapp-widget');
            const popup = document.getElementById('whatsappPopup');
            if (!widget.contains(event.target) && popup.classList.contains('active')) {
                popup.classList.remove('active');
            }
        });
    </script>
    @endif
    
    <!-- Toastr JS - Chargé de manière différée -->
    <script>
        // Charger Toastr après le chargement de la page pour ne pas bloquer le rendu
        window.addEventListener('load', function() {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js';
            script.async = true;
            document.head.appendChild(script);
            
            script.onload = function() {
                if (typeof toastr !== 'undefined') {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    // Afficher les messages d'erreur, info et warning
                    @if(session('error'))
                        toastr.error('{{ addslashes(session('error')) }}', 'Erreur');
                    @endif

                    @if(session('info'))
                        toastr.info('{{ addslashes(session('info')) }}', 'Information');
                    @endif

                    @if(session('warning'))
                        toastr.warning('{{ addslashes(session('warning')) }}', 'Attention');
                    @endif
                }
            };
        });
    </script>
    
    <!-- Scripts JS - Chargement différé avec requestIdleCallback -->
    <script>
        // Charger les scripts JS de manière non-bloquante
        (function() {
            function loadScripts() {
                const scripts = [
                    '{{ asset("js/main.js") }}',
                    '{{ asset("js/ux-improvements.js") }}?v=2.2'
                ];
                
                scripts.forEach(function(src) {
                    const script = document.createElement('script');
                    script.src = src;
                    script.defer = true;
                    script.async = true;
                    document.body.appendChild(script);
                });
            }
            
            // Utiliser requestIdleCallback si disponible, sinon après window.load
            if ('requestIdleCallback' in window) {
                requestIdleCallback(loadScripts, { timeout: 2000 });
            } else {
                window.addEventListener('load', loadScripts);
            }
        })();
    </script>
    
    @yield('scripts')
    
    <!-- CSS non critique chargé en bas de page pour ne pas bloquer le rendu -->
    @stack('styles')
    
    <!-- UX Improvements JS -->
    <script src="{{ asset('js/ux-improvements.js') }}?v=2.1" defer></script>
    
    <!-- Social Features JS -->
    <script src="{{ asset('js/social-features.js') }}?v=2.0" defer></script>
    
    <script>
        // Définir si l'utilisateur est authentifié
        document.body.dataset.authenticated = {{ Auth::check() ? 'true' : 'false' }};
        @if(Auth::check())
        document.body.dataset.userId = {{ Auth::id() }};
        @endif
    </script>
    
    @if($ezoicSettings && $ezoicSettings->ezoic_footer_code)
        <!-- Ezoic Code (Footer) -->
        {!! $ezoicSettings->ezoic_footer_code !!}
    @endif
</body>
</html>
