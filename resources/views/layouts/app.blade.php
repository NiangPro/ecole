<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-56V4D8K6');</script>
    <!-- End Google Tag Manager -->
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
        $faviconPng = asset('images/logo.png');
        $faviconIco = url('/favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconIco }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $faviconPng }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconPng }}">
    
    <!-- Preconnect — limité aux 4 origines critiques -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
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
    
    <!-- CSS critique - synchrone pour éviter le FOUC -->
    <link rel="stylesheet" href="{{ asset('css/critical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">

    <!-- Tailwind CSS v4 + CSS moderne compilé par Vite (remplace le CDN) -->
    @vite(['resources/css/app.css'])
    
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
    
    @if($adsenseSettings && $adsenseClientId)
        <!-- AdSense Auto Ads - Chargé de manière différée -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}"
                crossorigin="anonymous"></script>
    @elseif($adsenseSettings && $adsenseSettings->adsense_code && strpos($adsenseSettings->adsense_code, '<script') !== false)
        <!-- AdSense - Code complet fourni -->
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
<body class="bg-white text-gray-900 light-mode-forced" lang="{{ app()->getLocale() }}">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-56V4D8K6"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

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
                    '{{ asset("js/performance.js") }}',
                    '{{ asset("js/intelligent-prefetch.js") }}',
                    '{{ asset("js/lazy-loading.js") }}',
                    '{{ asset("js/pwa-manager.js") }}',
                    '{{ asset("js/analytics-tracker.js") }}',
                    '{{ asset("js/main.js") }}',
                    '{{ asset("js/ux-improvements.js") }}?v=2.2',
                    '{{ asset("js/social-features.js") }}'
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
    
</body>
</html>
