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
    
    <!-- Canonical URL -->
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    
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
    
    <!-- DNS Prefetch pour améliorer les performances -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    
    <!-- Preconnect pour les ressources critiques -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    @stack('meta')
    
    <title>@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- CSS critique inline pour éviter le FOUC - DOIT être en premier -->
    <style id="critical-css">
        /* Masquer le body immédiatement pour éviter le FOUC */
        body {
            opacity: 0;
            visibility: hidden;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
        }
        
        /* Afficher le body une fois que tout est chargé */
        body.loaded {
            opacity: 1;
            visibility: visible;
        }
        
        /* Masquer le HTML jusqu'à ce que Tailwind soit chargé */
        html:not(.tailwind-loaded) {
            visibility: hidden;
        }
        
        html.tailwind-loaded {
            visibility: visible;
        }
        
        /* Loader minimal pendant le chargement */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }
        
        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        
        .page-loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(6, 182, 212, 0.2);
            border-top-color: #06b6d4;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Script anti-FOUC qui s'exécute immédiatement -->
    <script>
        // Masquer le body immédiatement AVANT le rendu
        (function() {
            // Masquer le HTML et le body
            if (document.documentElement) {
                document.documentElement.style.visibility = 'hidden';
            }
            if (document.body) {
                document.body.style.opacity = '0';
                document.body.style.visibility = 'hidden';
            }
            
            // Fonction pour afficher le contenu
            function showContent() {
                // Attendre que le DOM soit complètement chargé
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(displayContent, 100);
                    });
                } else {
                    setTimeout(displayContent, 100);
                }
            }
            
            function displayContent() {
                // Masquer le loader
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.classList.add('hidden');
                    setTimeout(function() {
                        if (loader.parentNode) {
                            loader.parentNode.removeChild(loader);
                        }
                    }, 300);
                }
                
                // Afficher le HTML
                if (document.documentElement) {
                    document.documentElement.classList.add('tailwind-loaded');
                    document.documentElement.style.visibility = 'visible';
                }
                
                // Afficher le body
                if (document.body) {
                    document.body.classList.add('loaded');
                    document.body.style.opacity = '1';
                    document.body.style.visibility = 'visible';
                }
            }
            
            // Charger Tailwind CSS
            const tailwindScript = document.createElement('script');
            tailwindScript.src = 'https://cdn.tailwindcss.com';
            tailwindScript.async = true;
            tailwindScript.defer = true;
            
            let tailwindLoaded = false;
            let stylesLoaded = false;
            
            function checkAndShow() {
                if (tailwindLoaded && stylesLoaded) {
                    showContent();
                }
            }
            
            tailwindScript.onload = function() {
                tailwindLoaded = true;
                // Attendre un peu pour que Tailwind s'initialise
                setTimeout(function() {
                    checkAndShow();
                }, 50);
            };
            
            tailwindScript.onerror = function() {
                tailwindLoaded = true; // Considérer comme chargé même en cas d'erreur
                checkAndShow();
            };
            
            document.head.appendChild(tailwindScript);
            
            // Vérifier que les styles personnalisés sont chargés
            function checkStylesLoaded() {
                // Attendre que tous les styles soient appliqués
                if (document.styleSheets.length > 0) {
                    stylesLoaded = true;
                    checkAndShow();
                } else {
                    setTimeout(checkStylesLoaded, 50);
                }
            }
            
            // Démarrer la vérification après un court délai
            setTimeout(checkStylesLoaded, 100);
            
            // Fallback : afficher après 2 secondes maximum
            setTimeout(function() {
                tailwindLoaded = true;
                stylesLoaded = true;
                showContent();
            }, 2000);
        })();
    </script>
    
    <!-- reCAPTCHA v3 (invisible) -->
    @php
        $recaptchaSiteKey = config('services.recaptcha.site_key', '');
    @endphp
    @if(!empty($recaptchaSiteKey))
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}" async defer></script>
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
    </script>
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></noscript>
    <!-- Google Fonts optimisé avec preload et font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet"></noscript>
    
    <!-- Toastr CSS - Chargé de manière asynchrone -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></noscript>
    
    @php
        $adsenseSettings = \Illuminate\Support\Facades\Cache::remember('adsense_settings', 3600, function () {
            return \App\Models\AdSenseSetting::first();
        });
    @endphp
    
    @if($adsenseSettings && $adsenseSettings->adsense_code)
        {!! $adsenseSettings->adsense_code !!}
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
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                
                // Vérifier le consentement cookies
                const cookieConsent = localStorage.getItem('cookieConsent');
                if (cookieConsent === 'accepted') {
                    gtag('config', '{{ $gaId }}');
                } else if (cookieConsent === 'refused') {
                    gtag('config', '{{ $gaId }}', {
                        'anonymize_ip': true,
                        'storage': 'none'
                    });
                }
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
        
        /* S'assurer que le body reste masqué jusqu'à ce qu'il soit marqué comme chargé */
        body:not(.loaded) {
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        body {
            margin: 0;
            padding: 0;
            padding-top: 70px;
            overflow-x: hidden;
            min-height: 100vh;
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
            
            .language-widget {
                bottom: 120px;
                right: 12px;
                z-index: 9997;
            }
            
            .language-button {
                width: 36px;
                height: 36px;
                font-size: 14px;
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
        
        /* Language Widget */
        .language-widget {
            position: fixed;
            bottom: 120px;
            right: 20px;
            z-index: 9997;
        }
        
        .language-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border: 2px solid rgba(6, 182, 212, 0.3);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .language-flag {
            width: 24px;
            height: 18px;
            border-radius: 2px;
            object-fit: cover;
            display: block;
        }
        
        .language-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 25px rgba(6, 182, 212, 0.5);
            border-color: rgba(6, 182, 212, 0.5);
        }
        
        .language-tooltip {
            position: absolute;
            bottom: 60px;
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
        
        .language-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            right: 20px;
            border: 6px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
        }
        
        .language-button:hover .language-tooltip {
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
    
    <!-- Fallback pour navigateurs sans JavaScript -->
    <noscript>
        <style>
            body {
                opacity: 1 !important;
                visibility: visible !important;
            }
            .page-loader {
                display: none !important;
            }
        </style>
    </noscript>
</head>
<body class="bg-black text-white" lang="{{ app()->getLocale() }}">
    <!-- Loader pendant le chargement -->
    <div id="page-loader" class="page-loader">
        <div class="page-loader-spinner"></div>
    </div>
    
    @include('partials.navigation')
    
    @include('partials.schema-org')
    
    @yield('content')
    
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
            'formations.all',
            'formations.html5',
            'formations.css3',
            'formations.javascript',
            'formations.php',
            'formations.bootstrap',
            'formations.java',
            'formations.sql',
            'formations.c',
            'formations.git',
            'formations.wordpress',
            'formations.ia',
            'formations.python',
            'exercices',
            'exercices.language',
            'exercices.detail',
            'exercices.run',
            'quiz',
            'quiz.language',
            'quiz.result'
        ]);
    @endphp
    
    @if($showLanguageWidget)
    @php
        $currentLang = app()->getLocale();
        $tooltipText = $currentLang === 'fr' ? 'Changer en anglais' : 'Switch to French';
    @endphp
    <!-- Language Toggle Button -->
    <div id="language-widget" class="language-widget">
        <button id="language-toggle" class="language-button" onclick="toggleLanguage()" title="{{ $tooltipText }}">
            @if($currentLang === 'fr')
            <!-- Drapeau français -->
            <svg class="language-flag" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                <g fill-rule="evenodd" stroke-width="1pt">
                    <path fill="#fff" d="M0 0h640v480H0z"/>
                    <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                    <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                </g>
            </svg>
            @else
            <!-- Drapeau américain simplifié -->
            <svg class="language-flag" viewBox="0 0 7410 3900" xmlns="http://www.w3.org/2000/svg">
                <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                <path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/>
                <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                <g fill="#fff">
                    <g id="star">
                        <path d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/>
                    </g>
                    <use href="#star" x="988" y="210"/>
                    <use href="#star" x="1976" y="420"/>
                    <use href="#star" x="494" y="420"/>
                    <use href="#star" x="1482" y="630"/>
                    <use href="#star" x="2470" y="630"/>
                    <use href="#star" x="988" y="840"/>
                    <use href="#star" x="1976" y="840"/>
                    <use href="#star" x="494" y="1050"/>
                    <use href="#star" x="1482" y="1260"/>
                    <use href="#star" x="2470" y="1260"/>
                    <use href="#star" x="988" y="1470"/>
                    <use href="#star" x="1976" y="1470"/>
                    <use href="#star" x="494" y="1680"/>
                    <use href="#star" x="1482" y="1890"/>
                    <use href="#star" x="2470" y="1890"/>
                </g>
            </svg>
            @endif
            <span class="language-tooltip" id="language-tooltip">{{ $tooltipText }}</span>
        </button>
    </div>
    @endif
    
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
            
            // Rediriger vers la route de changement de langue
            window.location.href = '{{ route("language.set", ":locale") }}'.replace(':locale', newLang);
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
    
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>
    
    <!-- Script pour marquer le HTML comme chargé et éviter le FOUC -->
    <script>
        // Marquer le HTML comme chargé une fois que tout est prêt
        (function() {
            function markAsLoaded() {
                document.documentElement.classList.add('loaded', 'tailwind-loaded');
                document.documentElement.style.visibility = 'visible';
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', markAsLoaded);
            } else {
                markAsLoaded();
            }
            
            // Fallback : marquer comme chargé après un court délai
            setTimeout(markAsLoaded, 100);
        })();
    </script>
    
    @yield('scripts')
</body>
</html>
