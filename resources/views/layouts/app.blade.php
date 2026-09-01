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
    
    <!-- SEO amélioré -->
    <meta name="language" content="{{ app()->getLocale() }}">
    <meta name="geo.region" content="SN">
    <meta name="geo.placename" content="Sénégal">
    <meta name="theme-color" content="#06b6d4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NiangProgrammeur">
    <meta name="application-name" content="NiangProgrammeur">
    <meta name="msapplication-TileColor" content="#06b6d4">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">
    
    <!-- Favicon - Logo du site (placé tôt pour un chargement prioritaire) -->
    @php
        $faviconPng = \App\Models\SiteSetting::logoUrl();
        $faviconIco = url('/favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconIco }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconPng }}">
    
    <!-- Preconnect — origines critiques uniquement (max 4) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fundingchoicesmessages.google.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
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
    @php
        // Pages qui poussent déjà leur propre og:image/twitter:image via @push('meta')
        // (image de couverture réelle : document, article emploi, annonce...).
        // On évite d'émettre une balise par défaut en doublon pour elles.
        $ogOverrideRoutes = ['documents.show', 'emplois.article', 'emplois.offres', 'emplois.category', 'admin-docs.show'];
        $hasCustomOgImage = in_array(optional(request()->route())->getName(), $ogOverrideRoutes, true);

        $defaultOgImage = asset('images/og-homepage.jpg');
        if (request()->routeIs('epreuves.*')) {
            $defaultOgImage = asset('images/og-epreuves.jpg');
        } elseif (request()->routeIs('formations.*')) {
            $defaultOgImage = asset('images/og-formations.jpg');
        } elseif (request()->routeIs('emplois')) {
            $defaultOgImage = asset('images/og-emplois.jpg');
        }
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta property="og:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP et plus encore.')">
    @if(!$hasCustomOgImage)
    <meta property="og:image" content="{{ $defaultOgImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif
    <meta property="og:site_name" content="NiangProgrammeur">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta name="twitter:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web.')">
    @if(!$hasCustomOgImage)
    <meta name="twitter:image" content="{{ $defaultOgImage }}">
    @endif


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
    
    <!-- Scripts critiques - Chargés de manière asynchrone pour ne pas bloquer le rendu -->
    <script src="{{ asset('js/critical-init.js') }}" defer></script>
    <script src="{{ asset('js/error-handler.js') }}" defer></script>
    
    <title>@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')</title>
    
    <!-- Variable globale pour l'authentification (doit être définie avant tous les scripts) -->
    <script>
        window.userIsAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        // S'assurer que c'est bien une chaîne pour la comparaison
        if (typeof window.userIsAuthenticated === 'boolean') {
            window.userIsAuthenticated = window.userIsAuthenticated.toString();
        }
    </script>
    
    <!-- font-display:swap pour Font Awesome (avant son chargement) -->
    <style>
    @font-face{font-family:"Font Awesome 6 Free";font-style:normal;font-weight:900;font-display:swap;src:url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/webfonts/fa-solid-900.woff2") format("woff2")}
    @font-face{font-family:"Font Awesome 6 Brands";font-style:normal;font-weight:400;font-display:swap;src:url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/webfonts/fa-brands-400.woff2") format("woff2")}
    </style>

    <!-- CSS critique inline (above the fold — aucun HTTP request) -->
    <style>*{box-sizing:border-box}html,body{margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;background:#fff;color:#1e293b;overflow-x:hidden}body.dark-mode{background:#0a0f1c;color:#e5e5e5}.hero-section{position:relative;z-index:2;width:100%;min-height:65vh;display:flex;align-items:center;justify-content:center;padding:80px 40px 60px;overflow:hidden;background:linear-gradient(135deg,rgba(15,23,42,.85) 0%,rgba(30,41,59,.9) 100%)}.hero-content{max-width:1200px;margin:0 auto;width:100%;text-align:center}.main-title{font-size:clamp(2.5rem,5vw,4rem);font-weight:900;line-height:1.2;margin-bottom:30px;color:#fff}.subtitle{font-size:clamp(1rem,2vw,1.3rem);color:rgba(255,255,255,.9);margin-bottom:40px;line-height:1.6}@media (max-width:768px){.hero-section{min-height:55vh;padding:60px 20px 40px}.main-title{font-size:clamp(1.8rem,4vw,2.2rem);line-height:1.3;margin-bottom:20px}}.container{width:100%!important;margin-left:auto!important;margin-right:auto!important;padding-left:1rem!important;padding-right:1rem!important}@media (min-width:640px){.container{padding-left:1.5rem!important;padding-right:1.5rem!important}}section.relative.min-h-screen>div.container{text-align:center!important}section.relative.min-h-screen>div.container>div{margin-left:auto!important;margin-right:auto!important;text-align:center!important}section.relative.min-h-screen h1,section.relative.min-h-screen p{text-align:center!important;margin-left:auto!important;margin-right:auto!important}.max-w-5xl{max-width:64rem!important;margin-left:auto!important;margin-right:auto!important;display:block!important}.max-w-7xl{max-width:80rem!important;margin-left:auto!important;margin-right:auto!important;display:block!important}.max-w-3xl{max-width:48rem!important;margin-left:auto!important;margin-right:auto!important;display:block!important}
    /* Critique — hero de la page d'accueil (.hp-hero), élément LCP confirmé par Lighthouse.
       Peint le texte immédiatement sans attendre app.css/homepage.css (render-blocking). */
    .hp-hero{position:relative;min-height:60dvh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:5rem 1.5rem 4rem;overflow:hidden;background:oklch(5% 0.02 230)}
    .hp-hero>div{position:relative;z-index:1}
    .hp-hero__eyebrow{display:inline-flex;align-items:center;gap:.5rem;padding:.375rem 1rem;border-radius:999px;border:1px solid oklch(65% 0.20 200/30%);background:oklch(65% 0.20 200/8%);font-size:.8125rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:oklch(65% 0.20 200);margin-bottom:2rem}
    .hp-hero__title{font-family:Poppins,'Poppins Fallback',ui-sans-serif,system-ui,sans-serif;font-size:clamp(2.75rem,6vw,5rem);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:oklch(97% 0 0);max-width:22ch;margin-inline:auto;margin-bottom:1.5rem}
    .hp-hero__title .accent{background:linear-gradient(135deg,oklch(65% 0.20 200),oklch(65% 0.18 170));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent}
    .hp-hero__subtitle{font-size:clamp(1.05rem,2vw,1.25rem);font-weight:700;color:oklch(90% 0 0);max-width:55ch;margin-inline:auto;margin-bottom:2.5rem;line-height:1.7}
    </style>

    @php
        $viteManifest = \Illuminate\Support\Facades\Cache::remember('vite_manifest', 3600, function () {
            return json_decode(file_get_contents(public_path('build/manifest.json')), true);
        });
        $viteCssFile  = $viteManifest['resources/css/app.css']['file'] ?? null;
    @endphp

    <!-- Preload hints — démarrer le téléchargement tôt sans différer l'application -->
    @php $navCssV = \Illuminate\Support\Facades\Cache::remember('nav_css_mtime', 3600, fn() => filemtime(public_path('css/navigation.css'))); @endphp
    <link rel="preload" href="{{ asset('css/navigation.css') }}?v={{ $navCssV }}" as="style">
    @if($viteCssFile)
    <link rel="preload" href="{{ asset('build/' . $viteCssFile) }}" as="style">
    @endif

    <!-- CSS synchrone (bloquant mais sans CLS — styles appliqués avant le premier paint) -->
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}?v={{ $navCssV }}">
    @if($viteCssFile)
    <link rel="stylesheet" href="{{ asset('build/' . $viteCssFile) }}">
    @endif
    {{-- CSS spécifique à la page (chargé après app.css pour respect des priorités) --}}
    @stack('page_css')

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
    <!-- Font Awesome - Chargement asynchrone avec preload -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></noscript>
    
    <!-- Poppins 900 (latin) préchargé directement en woff2 : évite le double aller-retour
         CSS Google Fonts → fichier woff2 pour le H1 du hero, élément LCP de la home -->
    <link rel="preload" as="font" type="font/woff2" href="https://fonts.gstatic.com/s/poppins/v24/pxiByp8kv8JHgFVrLBT5Z1xlFd2JQEk.woff2" crossorigin>

    <!-- Google Fonts - Chargement asynchrone (Inter + Poppins uniquement) -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@700;800;900&display=swap"></noscript>
    
    <!-- Toastr CSS - Chargement asynchrone -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></noscript>
    
    <!-- UX Improvements CSS - Chargement asynchrone -->
    <link rel="preload" href="{{ asset('css/ux-improvements.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/ux-improvements.css') }}"></noscript>

    <!-- Social Features CSS - Chargement asynchrone -->
    <link rel="preload" href="{{ asset('css/social-features.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/social-features.css') }}"></noscript>
    
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
    
    {{-- AdSense déplacé en bas de body pour ne pas bloquer le rendu --}}
    
    
    <!-- Google Analytics -->
    @php
        $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, fn() => \App\Models\SiteSetting::first());
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
    
    @yield('styles')
</head>
<body class="light-mode-forced" lang="{{ app()->getLocale() }}">

    <!-- Skip Links pour l'accessibilité -->
    <div class="skip-links">
        <a href="#main-content" class="skip-link">Aller au contenu principal</a>
        <a href="#navigation" class="skip-link">Aller à la navigation</a>
        <a href="#footer" class="skip-link">Aller au pied de page</a>
    </div>
    
    @include('partials.navigation')

    @auth
    @if(auth()->user()->isAdmin())
    @php $__maint = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, fn() => \App\Models\SiteSetting::first()); @endphp
    @if($__maint?->maintenance_mode)
    <div id="maint-admin-bar" style="position:fixed;bottom:1.25rem;left:50%;transform:translateX(-50%);z-index:9999;background:linear-gradient(90deg,#f59e0b,#d97706);color:#000;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:.75rem;padding:.45rem 1rem .45rem 1.1rem;border-radius:999px;box-shadow:0 4px 20px rgba(0,0,0,.35);white-space:nowrap;">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" style="flex-shrink:0"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
        <span>Mode maintenance actif — Les visiteurs voient la page de maintenance</span>
        <a href="{{ route('admin.settings') }}" style="color:#000;text-decoration:none;background:rgba(0,0,0,.15);padding:.18rem .6rem;border-radius:999px;font-size:.72rem;">⚙ Gérer</a>
        <button onclick="document.getElementById('maint-admin-bar').remove()" style="background:none;border:none;cursor:pointer;padding:0 .2rem;color:#000;font-size:1.1rem;line-height:1;" title="Masquer">&times;</button>
    </div>
    @endif
    @endif
    @endauth

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

            // En dark mode, on retire light-mode-forced pour que le media query puisse agir
            if (isDark) {
                body.classList.remove('light-mode-forced');
            } else {
                body.classList.add('light-mode-forced');
            }

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
                body.classList.remove('light-mode-forced');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                tooltip.textContent = 'Désactiver le mode sombre';
                button.classList.add('active');
            }
            // Si darkMode est null ou 'disabled', light-mode-forced reste actif (défaut clair)
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

    <!-- Publicités vidéo YouTube (np-youtube-ad) : l'iframe du lecteur n'est créée
         qu'au clic sur le bouton play, jamais au chargement de la page — voir
         partials/youtube-ad-card.blade.php. Lancée avec autoplay+boucle : autorisé
         par les navigateurs car déclenché par un geste utilisateur (le clic). -->
    <script>
        function nAdTrackAdClick(adId) {
            fetch('/api/ads/' + adId + '/click', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json'
                }
            }).catch(function() {});
        }

        function nAdYouTubePlay(adId, btn) {
            const stage = btn.closest('[data-youtube-stage]');
            if (!stage || stage.dataset.youtubeLoaded === '1') return;
            stage.dataset.youtubeLoaded = '1';

            const videoId = stage.dataset.youtubeId;
            const thumb = stage.querySelector('[data-youtube-thumb]');
            const embed = stage.querySelector('[data-youtube-embed]');

            if (thumb) thumb.style.display = 'none';
            if (embed && videoId) {
                const iframe = document.createElement('iframe');
                iframe.src = 'https://www.youtube-nocookie.com/embed/' + videoId
                    + '?autoplay=1&mute=0&loop=1&playlist=' + videoId
                    + '&rel=0&modestbranding=1&playsinline=1';
                iframe.width = '100%';
                iframe.height = '100%';
                iframe.frameBorder = '0';
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                iframe.allowFullscreen = true;
                embed.appendChild(iframe);
                embed.hidden = false;
            }

            nAdTrackAdClick(adId);
        }
    </script>

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
    @php
        // public/js/*.js n'est pas construit par Vite : ces fichiers étaient servis en clair
        // (commentaires, noms de variables complets) sur CHAQUE page publique. `npm run build`
        // génère désormais les .min.js correspondants (scripts/minify-js.js) ; on les sert en
        // production uniquement, avec repli automatique sur le fichier source si le .min.js
        // n'a pas encore été généré (évite tout 404 si le build n'a pas encore tourné).
        $__jsMin = function (string $name) {
            $isProd = app()->environment('production');
            $minExists = file_exists(public_path("js/{$name}.min.js"));
            return ($isProd && $minExists) ? asset("js/{$name}.min.js") : asset("js/{$name}.js");
        };
    @endphp
    <script>
        // Charger les scripts JS de manière non-bloquante
        (function() {
            function loadScripts() {
                const scripts = [
                    '{{ $__jsMin('performance') }}',
                    '{{ $__jsMin('intelligent-prefetch') }}',
                    '{{ $__jsMin('lazy-loading') }}',
                    '{{ $__jsMin('pwa-manager') }}',
                    '{{ $__jsMin('analytics-tracker') }}',
                    '{{ $__jsMin('main') }}',
                    '{{ $__jsMin('ux-improvements') }}?v=2.2',
                    '{{ $__jsMin('social-features') }}'
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
    
    <!-- Configuration PWA -->
    <script>
        // Clé publique VAPID pour les notifications push (à configurer dans .env)
        window.VAPID_PUBLIC_KEY = '{{ config("services.vapid.public_key", "") }}';
        
        // Enregistrer le Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>
    
    @yield('scripts')
    @stack('scripts')
    
    <!-- CSS non critique chargé en bas de page pour ne pas bloquer le rendu -->
    @stack('styles')
    
    <!-- Social Features JS - Déjà chargé dans loadScripts() -->
    
    <script>
        // Définir si l'utilisateur est authentifié
        document.body.dataset.authenticated = {{ Auth::check() ? 'true' : 'false' }};
        @if(Auth::check())
        document.body.dataset.userId = {{ Auth::id() }};
        @endif
    </script>

    <!-- Google Tag Manager (déplacé en bas de body — non bloquant) -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-56V4D8K6');</script>

    <!-- AdSense — chargé en différé après interaction ou 3s pour ne pas bloquer le rendu.
         Le push page-level "enable_page_level_ads" (Auto ads legacy) a été RETIRÉ : il
         injectait ~24 blocs in-content sans espace réservé (mesuré via PerformanceObserver
         sur les pages articles), cause principale du CLS ~0,38-0,43 mobile. Le script reste
         chargé uniquement pour les unités MANUELLES (<ins>) qui, elles, réservent leur
         hauteur (voir components/adsense-unit.blade.php, epreuves dl-ad-slot).
         ⚠️ IMPORTANT : les Auto ads modernes sont pilotées côté tableau de bord AdSense,
         pas par ce flag. Pour couper réellement l'auto-injection, il faut AUSSI les
         désactiver dans AdSense → Annonces → par site → Auto ads (ou n'y garder que les
         formats à espace réservé). Ce code seul ne suffit pas si elles sont ON côté compte.
         Pour réactiver un jour les Auto ads page-level de façon contrôlée : remettre le
         push { enable_page_level_ads:true } dans s.onload ci-dessous. -->
    @if(!($hideAds ?? false) && $adsenseSettings && $adsenseClientId)
    <script>
    (function(){
        var done=false;
        function loadAds(){
            if(done)return; done=true;
            var s=document.createElement('script');
            s.async=true;
            s.src='https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}';
            s.crossOrigin='anonymous';
            // Pas de push page-level ici : seules les unités manuelles <ins> (avec min-height
            // réservé) pushent {} de leur côté. Évite l'injection in-content sans espace réservé.
            document.head.appendChild(s);
        }
        window.addEventListener('scroll',loadAds,{once:true,passive:true});
        window.addEventListener('mousemove',loadAds,{once:true,passive:true});
        window.addEventListener('touchstart',loadAds,{once:true,passive:true});
        setTimeout(loadAds,3000);
    })();
    </script>
    @elseif(!($hideAds ?? false) && $adsenseSettings && !empty($adsenseSettings->adsense_code) && strpos($adsenseSettings->adsense_code, '<script') !== false)
    {!! $adsenseSettings->adsense_code !!}
    @endif

</body>
</html>
