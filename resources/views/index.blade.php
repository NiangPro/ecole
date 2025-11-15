@extends('layouts.app')

@section('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')
@section('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle.')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700;900&display=swap');
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: #000;
        color: #fff;
        overflow-x: hidden;
    }
    
    /* Background moderne et lumineux */
    .bg-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }
    
    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }
    
    .shape {
        position: absolute;
        opacity: 0.15;
        animation: float 20s ease-in-out infinite;
    }
    
    .shape-1 {
        width: 400px;
        height: 400px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        top: 5%;
        left: -5%;
        filter: blur(100px);
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 500px;
        height: 500px;
        background: linear-gradient(225deg, #14b8a6, #06b6d4);
        border-radius: 50%;
        bottom: 5%;
        right: -5%;
        filter: blur(120px);
        animation-delay: 5s;
    }
    
    .shape-3 {
        width: 350px;
        height: 350px;
        background: linear-gradient(315deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        top: 40%;
        right: 10%;
        filter: blur(110px);
        animation-delay: 10s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        25% {
            transform: translate(30px, -30px) scale(1.1);
        }
        50% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        75% {
            transform: translate(20px, 30px) scale(1.05);
        }
    }
    
    /* Hero Section Style Sunu Code - Design Centré et Professionnel */
    .hero-section {
        position: relative;
        z-index: 2;
        width: 100%;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 40px 80px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.85) 0%, rgba(10, 10, 15, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
    }
    
    /* Overlay pour améliorer la lisibilité */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, rgba(0, 0, 0, 0.7) 100%);
        z-index: 0;
    }
    
    .hero-section > * {
        position: relative;
        z-index: 1;
    }
    
    /* Hero Content - Centré comme Sunu Code */
    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        text-align: center;
        animation: fadeInUp 1s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Titre Principal - Style Sunu Code */
    .main-title {
        font-family: 'Inter', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        line-height: 1.2;
        margin-bottom: 25px;
        color: #fff;
        letter-spacing: -0.02em;
    }
    
    .title-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }
    
    /* Sous-titre descriptif */
    .subtitle {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        font-weight: 400;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 40px;
        line-height: 1.6;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Boutons CTA - Style Sunu Code */
    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 60px;
    }
    
    .btn-3d {
        position: relative;
        padding: 16px 36px;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
    }
    
    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.6);
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-4px);
    }
    
    
    /* Stats Section Ultra Moderne - Refonte Complète */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto 100px;
        padding: 0 20px;
    }
    
    .stat-card {
        position: relative;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.05) 100%);
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        border: 2px solid rgba(6, 182, 212, 0.15);
        border-radius: 24px;
        padding: 35px 25px;
        backdrop-filter: blur(30px);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    /* Effet de brillance animé */
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(6, 182, 212, 0.1), transparent 30%);
        animation: rotate 8s linear infinite;
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    /* Effet de glow au survol */
    .stat-card::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 28px;
        padding: 2px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.5s;
        z-index: -1;
    }
    
    .stat-card:hover::after {
        opacity: 1;
        animation: borderGlow 2s ease-in-out infinite;
    }
    
    @keyframes borderGlow {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    
    .stat-card:hover {
        transform: translateY(-15px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 25px 60px rgba(6, 182, 212, 0.4), 0 0 40px rgba(6, 182, 212, 0.2);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        position: relative;
        z-index: 1;
        transition: all 0.5s ease;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.2);
    }
    
    .stat-card:hover .stat-icon {
        transform: rotateY(360deg) scale(1.1);
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.4), rgba(20, 184, 166, 0.4));
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.4);
    }
    
    .stat-number {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        line-height: 1.1;
        position: relative;
        z-index: 1;
        animation: gradientShift 3s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .stat-label {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.75);
        font-weight: 600;
        text-transform: none;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }
    
    .stat-card:hover .stat-label {
        color: rgba(255, 255, 255, 0.95);
    }
    
    /* Technologies Section */
    .tech-section {
        position: relative;
        z-index: 2;
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 900;
        text-align: center;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.6);
        max-width: 700px;
        margin: 0 auto 40px;
    }
    
    .tech-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }
    
    .tech-card {
        position: relative;
        background: rgba(10, 10, 26, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 40px;
        backdrop-filter: blur(20px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        overflow: hidden;
    }
    
    .tech-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .tech-card:hover::after {
        left: 100%;
    }
    
    .tech-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.3);
    }
    
    .tech-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        display: block;
    }
    
    .tech-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #fff;
    }
    
    .tech-desc {
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .tech-link {
        color: #06b6d4;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.3s;
    }
    
    .tech-link:hover {
        gap: 12px;
    }
    
    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateX(-50%) translateY(0);
        }
        50% {
            transform: translateX(-50%) translateY(-20px);
        }
    }
    
    .scroll-text {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.5);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }
    
    .scroll-icon {
        font-size: 2rem;
        color: #06b6d4;
    }
    
    /* Responsive */
    @media (max-width: 968px) {
        .hero-section {
            padding: 80px 20px 60px;
            min-height: auto;
            background-attachment: scroll;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-3d {
            width: 100%;
            max-width: 320px;
            justify-content: center;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-card {
            padding: 30px 20px;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .tech-section {
            padding: 40px 20px;
        }
        
        .tech-grid {
            gap: 20px;
        }
    }
    
    @media (max-width: 480px) {
        .hero-section {
            padding: 70px 15px 50px;
        }
        
        .main-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .tech-card {
            padding: 30px;
        }
    }
</style>
@endsection

@section('content')
<!-- Background Canvas -->
<div class="bg-canvas"></div>

<!-- Floating Shapes -->
<div class="floating-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
</div>

<!-- Hero Section Style Sunu Code -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="main-title">
            Apprenez la <span class="title-gradient">Programmation</span><br>
            Gratuitement avec <span class="title-gradient">NiangProgrammeur</span>
        </h1>
        
        <p class="subtitle">
            La meilleure plateforme gratuite pour apprendre le développement web. Maîtrisez HTML, CSS, JavaScript, PHP, Laravel et bien plus encore avec nos tutoriels interactifs, exemples pratiques et exercices.
        </p>
        
        <div class="cta-buttons">
            <a href="#technologies" class="btn-3d btn-primary">
                <i class="fas fa-book-open"></i>
                Commencer à apprendre
            </a>
            <a href="{{ route('exercices') }}" class="btn-3d btn-secondary">
                <i class="fas fa-code"></i>
                Essayer gratuitement
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section style="position: relative; z-index: 2; padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i>
            </div>
            <div class="stat-number">8+</div>
            <div class="stat-label">Technologies</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-code" style="color: #14b8a6;"></i>
            </div>
            <div class="stat-number">100+</div>
            <div class="stat-label">Exercices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-infinity" style="color: #06b6d4;"></i>
            </div>
            <div class="stat-number">24/7</div>
            <div class="stat-label">Disponible</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-gift" style="color: #14b8a6;"></i>
            </div>
            <div class="stat-number">100%</div>
            <div class="stat-label">Gratuit</div>
        </div>
    </div>
</section>

<!-- Exercices & Quiz Section -->
<section style="position: relative; z-index: 2; padding: 60px 20px; max-width: 1600px; margin: 0 auto;">
    <h2 class="section-title">Pratiquez avec nos Exercices & Quiz</h2>
    <p class="section-subtitle">
        Renforcez vos compétences avec des exercices pratiques et testez vos connaissances avec nos quiz interactifs
    </p>
    
    <div style="display: grid; grid-template-columns: {{ isset($sidebarAds) && $sidebarAds->count() > 0 ? '1fr 300px' : '1fr' }}; gap: 30px; margin-bottom: 60px; align-items: start;">
        <!-- Cards Exercices & Quiz -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
        <!-- Exercices Card -->
            <div class="stat-card" style="text-align: left; padding: 30px;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-code" style="font-size: 1.8rem; color: #06b6d4;"></i>
            </div>
                <h3 style="font-size: 1.3rem; font-weight: 700; color: #fff; margin-bottom: 12px;">Exercices Pratiques</h3>
                <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.7; margin-bottom: 20px; font-size: 0.9rem;">
                Plus de 100 exercices interactifs pour chaque technologie. Écrivez du code directement dans votre navigateur et validez vos solutions en temps réel.
            </p>
                <a href="{{ route('exercices') }}" class="tech-link" style="display: inline-flex; align-items: center; gap: 8px; color: #06b6d4; font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                Commencer les exercices <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Quiz Card -->
            <div class="stat-card" style="text-align: left; padding: 30px;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(168, 85, 247, 0.2)); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-question-circle" style="font-size: 1.8rem; color: #a855f7;"></i>
            </div>
                <h3 style="font-size: 1.3rem; font-weight: 700; color: #fff; margin-bottom: 12px;">Quiz Interactifs</h3>
                <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.7; margin-bottom: 20px; font-size: 0.9rem;">
                Testez vos connaissances avec nos quiz détaillés. Obtenez un score et des explications pour chaque question pour progresser rapidement.
            </p>
                <a href="{{ route('quiz') }}" class="tech-link" style="display: inline-flex; align-items: center; gap: 8px; color: #a855f7; font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                Faire un quiz <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        </div>
        
        <!-- Sidebar Publicités -->
        @if(isset($sidebarAds) && $sidebarAds->count() > 0)
        <aside style="position: sticky; top: 80px; align-self: flex-start;">
            @foreach($sidebarAds as $ad)
            <div class="ad-container" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 20px; backdrop-filter: blur(10px);">
                <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" style="display: block; text-decoration: none;">
                    @if($ad->image)
                    <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}" 
                         alt="{{ $ad->name }}" 
                         style="width: 100%; height: auto; border-radius: 12px; display: block;"
                         onerror="this.style.display='none'">
                    @endif
                </a>
            </div>
            @php
                $ad->incrementImpressions();
            @endphp
            @endforeach
        </aside>
        @endif
    </div>
</section>

<!-- Section Publicitaire Moderne après Exercices & Quiz -->
@if(isset($homepageAds) && $homepageAds->count() > 0)
<section style="position: relative; z-index: 2; padding: 60px 20px; max-width: 1600px; margin: 0 auto;">
    <div class="modern-ads-container">
        @foreach($homepageAds as $ad)
        <div class="modern-ad-card">
            <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" class="modern-ad-link">
                @if($ad->image)
                <div class="modern-ad-image-wrapper">
                    <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}" 
                         alt="{{ $ad->name }}" 
                         class="modern-ad-image"
                         onerror="this.style.display='none'">
                    <div class="modern-ad-overlay">
                        <div class="modern-ad-content">
                            <h3 class="modern-ad-title">{{ $ad->name }}</h3>
                            @if($ad->description)
                            <p class="modern-ad-description">{{ $ad->description }}</p>
                            @endif
                            <span class="modern-ad-cta">Découvrir <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
                @endif
            </a>
        </div>
        @php
            $ad->incrementImpressions();
        @endphp
        @endforeach
    </div>
</section>
@endif

<style>
    @media (max-width: 1200px) {
        section[style*="grid-template-columns: 1fr 300px"] > div[style*="display: grid"] {
            grid-template-columns: 1fr !important;
        }
        
        section[style*="grid-template-columns: 1fr 300px"] > aside {
            display: none !important;
        }
    }
    
    .ad-container {
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ad-container img {
        max-width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    
    .ad-container:hover img {
        transform: scale(1.05);
    }
    
    /* Modern Ads Section - Full Width */
    .modern-ads-container {
        display: flex;
        flex-direction: column;
        gap: 40px;
        margin-top: 40px;
    }
    
    .modern-ad-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.3);
        backdrop-filter: blur(20px);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    .modern-ad-card:hover {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4);
    }
    
    .modern-ad-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    
    .modern-ad-image-wrapper {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modern-ad-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .modern-ad-card:hover .modern-ad-image {
        transform: scale(1.08);
    }
    
    .modern-ad-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.6) 50%, rgba(15, 23, 42, 0.9) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px;
    }
    
    .modern-ad-content {
        text-align: center;
        max-width: 800px;
        z-index: 2;
    }
    
    .modern-ad-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 20px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        background: linear-gradient(135deg, #fff 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .modern-ad-description {
        font-size: clamp(1rem, 2vw, 1.3rem);
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 30px;
        line-height: 1.8;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    
    .modern-ad-cta {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 40px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .modern-ad-card:hover .modern-ad-cta {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(6, 182, 212, 0.6);
        gap: 16px;
    }
    
    @media (max-width: 768px) {
        .modern-ad-image-wrapper {
            height: 300px;
        }
        
        .modern-ad-overlay {
            padding: 30px 20px;
        }
        
        .modern-ad-cta {
            padding: 14px 30px;
            font-size: 1rem;
        }
    }
</style>

<script>
    function trackAdClick(adId) {
        fetch('/api/ads/' + adId + '/click', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json'
            }
        }).catch(err => console.log('Ad click tracking error:', err));
    }
</script>

<!-- Technologies Section -->
<section id="technologies" class="tech-section">
    <h2 class="section-title">Technologies Enseignées</h2>
    <p class="section-subtitle">
        Maîtrisez les technologies les plus demandées du marché avec nos formations complètes et pratiques
    </p>
    
    <div class="tech-grid">
        <!-- HTML5 -->
        <div class="tech-card">
            <i class="fab fa-html5 tech-icon" style="color: #e34c26;"></i>
            <h3 class="tech-name">HTML5</h3>
            <p class="tech-desc">
                Apprenez les fondamentaux du web avec HTML5. Structure, sémantique et bonnes pratiques.
            </p>
            <a href="{{ route('formations.html5') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- CSS3 -->
        <div class="tech-card">
            <i class="fab fa-css3-alt tech-icon" style="color: #264de4;"></i>
            <h3 class="tech-name">CSS3</h3>
            <p class="tech-desc">
                Créez des designs modernes et responsives avec CSS3, Flexbox et Grid.
            </p>
            <a href="{{ route('formations.css3') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- JavaScript -->
        <div class="tech-card">
            <i class="fab fa-js tech-icon" style="color: #f0db4f;"></i>
            <h3 class="tech-name">JavaScript</h3>
            <p class="tech-desc">
                Maîtrisez JavaScript ES6+, DOM manipulation et programmation asynchrone.
            </p>
            <a href="{{ route('formations.javascript') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- PHP -->
        <div class="tech-card">
            <i class="fab fa-php tech-icon" style="color: #8993be;"></i>
            <h3 class="tech-name">PHP</h3>
            <p class="tech-desc">
                Développez des applications web dynamiques avec PHP et MySQL.
            </p>
            <a href="{{ route('formations.php') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Bootstrap -->
        <div class="tech-card">
            <i class="fab fa-bootstrap tech-icon" style="color: #7952b3;"></i>
            <h3 class="tech-name">Bootstrap</h3>
            <p class="tech-desc">
                Créez rapidement des interfaces responsives avec le framework Bootstrap.
            </p>
            <a href="{{ route('formations.bootstrap') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Git -->
        <div class="tech-card">
            <i class="fab fa-git-alt tech-icon" style="color: #f34f29;"></i>
            <h3 class="tech-name">Git</h3>
            <p class="tech-desc">
                Gérez vos projets avec Git et GitHub. Versioning et collaboration.
            </p>
            <a href="{{ route('formations.git') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- WordPress -->
        <div class="tech-card">
            <i class="fab fa-wordpress tech-icon" style="color: #21759b;"></i>
            <h3 class="tech-name">WordPress</h3>
            <p class="tech-desc">
                Créez des sites web professionnels avec WordPress. Thèmes et plugins.
            </p>
            <a href="{{ route('formations.wordpress') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- IA -->
        <div class="tech-card">
            <i class="fas fa-robot tech-icon" style="color: #06b6d4;"></i>
            <h3 class="tech-name">Intelligence Artificielle</h3>
            <p class="tech-desc">
                Découvrez l'IA, le Machine Learning et les applications pratiques.
            </p>
            <a href="{{ route('formations.ia') }}" class="tech-link">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Latest Jobs Section -->
@if(isset($latestJobs) && $latestJobs->count() > 0)
<section style="position: relative; z-index: 2; padding: 80px 20px; max-width: 1600px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 class="section-title">💼 Dernières Opportunités d'Emploi</h2>
        <p class="section-subtitle">
            Découvrez les dernières offres d'emploi, bourses et opportunités professionnelles publiées au Sénégal
        </p>
    </div>
    
    <div class="latest-jobs-grid" style="margin-bottom: 40px;">
        @foreach($latestJobs as $job)
        <a href="{{ route('emplois.article', $job->slug) }}" style="text-decoration: none; display: block;">
            <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95)); border: 2px solid rgba(6, 182, 212, 0.25); border-radius: 24px; overflow: hidden; transition: all 0.5s ease; height: 100%;">
                @if($job->cover_image)
                <div style="width: 100%; height: 180px; overflow: hidden;">
                    <img src="{{ $job->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($job->cover_image) : $job->cover_image }}" 
                         alt="{{ $job->title }}" 
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                         onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop'">
                </div>
                @else
                <div style="width: 100%; height: 180px; background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-briefcase text-5xl text-cyan-400/50"></i>
                </div>
                @endif
                
                <div style="padding: 24px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(6, 182, 212, 0.15); color: #06b6d4; border-radius: 18px; font-size: 0.75rem; font-weight: 700; margin-bottom: 12px; border: 1px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-folder"></i>{{ $job->category->name }}
                    </span>
                    
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #fff; margin-bottom: 12px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3em;">
                        {{ $job->title }}
                    </h3>
                    
                    <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.6; margin-bottom: 16px; font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $job->excerpt ?? Str::limit(strip_tags($job->content), 80) }}
                    </p>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                        <div style="display: flex; align-items: center; gap: 10px; color: rgba(255, 255, 255, 0.6); font-size: 0.8rem;">
                            <span><i class="fas fa-calendar" style="color: #06b6d4;"></i> {{ $job->published_at ? $job->published_at->format('d/m/Y') : '' }}</span>
                        </div>
                        <span style="padding: 8px 18px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; border-radius: 10px; font-weight: 700; font-size: 0.8rem; transition: all 0.3s ease;">
                            Voir <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('emplois') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 32px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; border-radius: 12px; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
            Voir toutes les opportunités <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<style>
    .latest-jobs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
    
    @media (max-width: 1024px) {
        .latest-jobs-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 768px) {
        .latest-jobs-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    .latest-jobs-grid > a:hover > div {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }
    
    .latest-jobs-grid > a:hover img {
        transform: scale(1.1);
    }
</style>
@endif
@endsection
