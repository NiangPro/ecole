<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle avec NiangProgrammeur.')">
    <meta name="keywords" content="@yield('meta_keywords', 'formation développement web, HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress, IA, tutoriel gratuit, apprendre programmation, cours en ligne')">
    <meta name="author" content="Bassirou Niang - NiangProgrammeur">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta property="og:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP et plus encore.')">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')">
    <meta property="twitter:description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web.')">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">
    
    @stack('meta')
    
    <title>@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    @php
        $adsenseSettings = \App\Models\AdSenseSetting::first();
    @endphp
    
    @if($adsenseSettings && $adsenseSettings->adsense_code)
        {!! $adsenseSettings->adsense_code !!}
    @endif
    
    <!-- Google Analytics -->
    @php
        $siteSettings = \App\Models\SiteSetting::first();
        $gaId = $siteSettings->google_analytics_id ?? config('services.google_analytics.id');
    @endphp
    
    @if($gaId)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
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
    </script>
    @endif
    
    <style>
        /* GLOBAL STYLES */
        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
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
    </style>
    @yield('styles')
</head>
<body class="bg-black text-white">
    @include('partials.navigation')
    
    @include('partials.schema-org')
    
    @yield('content')
    
    @include('partials.footer')
    
    <!-- Cookie Banner -->
    @include('partials.cookie-banner')
    
    <!-- WhatsApp Chatbot Widget -->
    @php
        $siteSettings = \App\Models\SiteSetting::first();
        $whatsappNumber = $siteSettings->contact_phone ?? '+221783123657';
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
    <style>
        .whatsapp-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .whatsapp-button {
            position: relative;
            width: 60px;
            height: 60px;
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
            font-size: 32px;
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
        }
    </style>
    <script>
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
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configuration Toastr
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

        // Afficher les messages de succès
        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Succès');
        @endif

        // Afficher les messages d'erreur
        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Erreur');
        @endif

        // Afficher les messages d'info
        @if(session('info'))
            toastr.info('{{ session('info') }}', 'Information');
        @endif

        // Afficher les messages d'avertissement
        @if(session('warning'))
            toastr.warning('{{ session('warning') }}', 'Attention');
        @endif
    </script>
    
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
