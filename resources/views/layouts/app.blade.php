<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle avec NiangProgrammeur.')">
    <meta name="keywords" content="@yield('meta_keywords', 'formation développement web, HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress, IA, tutoriel gratuit, apprendre programmation, cours en ligne')">
    <meta name="author" content="Bassirou Niang - NiangProgrammeur">
    <meta name="robots" content="index, follow">
    
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
    
    <title>@yield('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations-3d.css') }}">
    
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
    
    @yield('styles')
</head>
<body class="bg-black text-white">
    @include('partials.navigation')
    
    @yield('content')
    
    @include('partials.footer')
    
    <!-- Cookie Banner -->
    @include('partials.cookie-banner')
    
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
