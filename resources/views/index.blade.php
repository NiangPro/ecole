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
    
    /* Hero Section Moderne et Lumineuse */
    .hero-section {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
        padding: 20px 20px 60px;
        /* max-width: 1200px; */
        margin: 0 auto;
    }
    
    .hero-left {
        text-align: left;
    }
    
    .hero-right {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Badge moderne */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.4);
        border-radius: 50px;
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #06b6d4;
    }
    
    .badge-icon {
        width: 6px;
        height: 6px;
        background: #06b6d4;
        border-radius: 50%;
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 10px #06b6d4;
            opacity: 1;
        }
        50% {
            box-shadow: 0 0 20px #06b6d4;
            opacity: 0.7;
        }
    }
    
    /* Titre principal moderne */
    .main-title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(1.8rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.2;
        margin-bottom: 20px;
        color: #fff;
    }
    
    .title-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }
    
    .subtitle {
        font-size: clamp(1.1rem, 2.5vw, 1.5rem);
        font-weight: 400;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 25px;
        line-height: 1.6;
    }
    
    /* Description */
    .hero-description {
        font-size: clamp(1rem, 1.8vw, 1.15rem);
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.8;
        margin-bottom: 40px;
    }
    
    .highlight {
        color: #06b6d4;
        font-weight: 600;
    }
    
    /* Illustration 3D */
    .hero-illustration {
        position: relative;
        width: 100%;
        max-width: 500px;
        aspect-ratio: 1;
    }
    
    .floating-card {
        position: absolute;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        padding: 25px;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
        animation: float-card 6s ease-in-out infinite;
    }
    
    .card-1 {
        top: 10%;
        left: 10%;
        width: 200px;
        animation-delay: 0s;
    }
    
    .card-2 {
        top: 40%;
        right: 5%;
        width: 180px;
        animation-delay: 2s;
    }
    
    .card-3 {
        bottom: 15%;
        left: 20%;
        width: 220px;
        animation-delay: 4s;
    }
    
    @keyframes float-card {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(2deg);
        }
    }
    
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: block;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }
    
    .card-text {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
    }
    
    /* Central Glow */
    .central-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        animation: glow-pulse 4s ease-in-out infinite;
    }
    
    @keyframes glow-pulse {
        0%, 100% {
            opacity: 0.5;
            transform: translate(-50%, -50%) scale(1);
        }
        50% {
            opacity: 0.8;
            transform: translate(-50%, -50%) scale(1.1);
        }
    }
    
    /* Boutons CTA 3D */
    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 80px;
    }
    
    .btn-3d {
        position: relative;
        padding: 18px 40px;
        font-size: 1.1rem;
        font-weight: 700;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.4);
    }
    
    .btn-primary:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.6);
    }
    
    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    
    .btn-primary:hover::before {
        left: 100%;
    }
    
    .btn-secondary {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        border: 2px solid #06b6d4;
        backdrop-filter: blur(20px);
    }
    
    .btn-secondary:hover {
        background: rgba(6, 182, 212, 0.2);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }
    
    /* Stats Cards 3D */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto 100px;
    }
    
    .stat-card {
        position: relative;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 40px 30px;
        backdrop-filter: blur(20px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-10px) rotateX(5deg);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.3);
    }
    
    .stat-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        position: relative;
        z-index: 1;
    }
    
    .stat-number {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }
    
    .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 2px;
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
            grid-template-columns: 1fr;
            padding: 100px 20px 40px;
            gap: 30px;
        }
        
        .hero-left {
            text-align: center;
        }
        
        .badge-modern {
            justify-content: center;
        }
        
        .hero-right {
            order: -1;
        }
        
        .hero-illustration {
            max-width: 350px;
            margin: 0 auto;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .cta-buttons {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-3d {
            width: 100%;
            justify-content: center;
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
            padding: 90px 15px 30px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .floating-card {
            padding: 15px;
        }
        
        .card-1, .card-2, .card-3 {
            width: 130px;
        }
        
        .main-title {
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
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

<!-- Hero Section -->
<section class="hero-section">
    <!-- Left Content -->
    <div class="hero-left">
        <!-- Badge -->
        <div class="badge-modern">
            <span class="badge-icon"></span>
            <span>Plateforme 100% Gratuite</span>
        </div>
        
        <!-- Titre Principal -->
        <h1 class="main-title">
            Apprenez le <span class="title-gradient">Développement Web</span> avec NiangProgrammeur
        </h1>
        
        <p class="subtitle">
            Formations complètes et pratiques pour devenir développeur web professionnel
        </p>
        
        <p class="hero-description">
            Maîtrisez <span class="highlight">HTML5</span>, <span class="highlight">CSS3</span>, <span class="highlight">JavaScript</span>, <span class="highlight">PHP</span> et bien plus encore. 
            Accès illimité à tous les cours, exercices interactifs et quiz de validation.
        </p>
        
        <!-- CTA Buttons -->
        <div class="cta-buttons">
            <a href="#technologies" class="btn-3d btn-primary">
                <i class="fas fa-play-circle"></i>
                Commencer Maintenant
            </a>
            <a href="{{ route('exercices') }}" class="btn-3d btn-secondary">
                <i class="fas fa-code"></i>
                Voir les Exercices
            </a>
        </div>
    </div>
    
    <!-- Right Illustration -->
    <div class="hero-right">
        <div class="hero-illustration">
            <!-- Central Glow -->
            <div class="central-glow"></div>
            
            <!-- Floating Cards -->
            <div class="floating-card card-1">
                <i class="fab fa-html5 card-icon" style="color: #e34c26;"></i>
                <div class="card-title">HTML5</div>
                <div class="card-text">Structure web moderne</div>
            </div>
            
            <div class="floating-card card-2">
                <i class="fab fa-css3-alt card-icon" style="color: #264de4;"></i>
                <div class="card-title">CSS3</div>
                <div class="card-text">Design responsive</div>
            </div>
            
            <div class="floating-card card-3">
                <i class="fab fa-js card-icon" style="color: #f0db4f;"></i>
                <div class="card-title">JavaScript</div>
                <div class="card-text">Interactivité web</div>
            </div>
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
<section style="position: relative; z-index: 2; padding: 60px 20px; max-width: 1200px; margin: 0 auto;">
    <h2 class="section-title">Pratiquez avec nos Exercices & Quiz</h2>
    <p class="section-subtitle">
        Renforcez vos compétences avec des exercices pratiques et testez vos connaissances avec nos quiz interactifs
    </p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
        <!-- Exercices Card -->
        <div class="stat-card" style="text-align: left; padding: 40px;">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px;">
                <i class="fas fa-code" style="font-size: 2rem; color: #06b6d4;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 15px;">Exercices Pratiques</h3>
            <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.8; margin-bottom: 25px;">
                Plus de 100 exercices interactifs pour chaque technologie. Écrivez du code directement dans votre navigateur et validez vos solutions en temps réel.
            </p>
            <a href="{{ route('exercices') }}" class="tech-link" style="display: inline-flex; align-items: center; gap: 8px; color: #06b6d4; font-weight: 600; text-decoration: none;">
                Commencer les exercices <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Quiz Card -->
        <div class="stat-card" style="text-align: left; padding: 40px;">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(168, 85, 247, 0.2)); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px;">
                <i class="fas fa-question-circle" style="font-size: 2rem; color: #a855f7;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 15px;">Quiz Interactifs</h3>
            <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.8; margin-bottom: 25px;">
                Testez vos connaissances avec nos quiz détaillés. Obtenez un score et des explications pour chaque question pour progresser rapidement.
            </p>
            <a href="{{ route('quiz') }}" class="tech-link" style="display: inline-flex; align-items: center; gap: 8px; color: #a855f7; font-weight: 600; text-decoration: none;">
                Faire un quiz <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

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
@endsection
