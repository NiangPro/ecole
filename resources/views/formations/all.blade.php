@extends('layouts.app')

@section('title', 'Toutes les Formations | NiangProgrammeur')

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
        background: #ffffff !important;
        color: #1e293b !important;
        overflow-x: hidden;
    }
    
    /* Dark Mode Styles */
    body.dark-mode {
        background: #0a0a0f !important;
        color: #ffffff !important;
    }
    
    /* Hero Section */
    .hero-section {
        position: relative;
        padding: 120px 20px 80px;
        text-align: center;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        overflow: hidden;
        z-index: 1;
    }
    
    body.dark-mode .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
        z-index: -1;
    }
    
    .hero-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        color: white;
        margin-bottom: 20px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 40px;
        animation: fadeInUp 1s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Formations Grid */
    .formations-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 80px 20px;
        position: relative;
        z-index: 2;
    }
    
    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        color: #1e293b !important;
        font-family: 'Orbitron', sans-serif;
    }
    
    body.dark-mode .section-title {
        color: #ffffff !important;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.2rem;
        color: #475569 !important;
        margin-bottom: 60px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }
    
    body.dark-mode .section-subtitle {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .formations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    
    .formation-card {
        position: relative;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 40px;
        backdrop-filter: blur(20px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1);
        text-decoration: none;
        display: block;
        color: inherit;
    }
    
    body.dark-mode .formation-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9));
        border: 2px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.2);
    }
    
    .formation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .formation-card:hover::before {
        left: 100%;
    }
    
    .formation-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .formation-card:hover {
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.4);
    }
    
    .formation-icon {
        font-size: 4.5rem;
        margin-bottom: 20px;
        display: block;
        transition: transform 0.3s ease;
    }
    
    .formation-card:hover .formation-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .formation-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #1e293b !important;
        font-family: 'Orbitron', sans-serif;
    }
    
    body.dark-mode .formation-name {
        color: #ffffff !important;
    }
    
    .formation-description {
        color: #475569 !important;
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 1rem;
    }
    
    body.dark-mode .formation-description {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .formation-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #06b6d4;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 10px;
    }
    
    .formation-link:hover {
        color: #14b8a6;
        gap: 12px;
    }
    
    body.dark-mode .formation-link {
        color: #06b6d4;
    }
    
    body.dark-mode .formation-link:hover {
        color: #14b8a6;
    }
    
    .formation-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }
    
    /* Stats Section */
    .stats-section {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05));
        padding: 60px 20px;
        margin-top: 80px;
        border-radius: 30px;
    }
    
    body.dark-mode .stats-section {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .stat-card {
        text-align: center;
        padding: 30px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    body.dark-mode .stat-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.2);
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 900;
        color: #06b6d4;
        font-family: 'Orbitron', sans-serif;
        margin-bottom: 10px;
    }
    
    .stat-label {
        font-size: 1.1rem;
        color: #475569 !important;
        font-weight: 600;
    }
    
    body.dark-mode .stat-label {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .formations-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .formation-card {
            padding: 30px;
        }
        
        .section-title {
            font-size: 2rem;
        }
    }
    
    /* Floating Animation */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .formation-card:nth-child(odd) {
        animation: float 6s ease-in-out infinite;
    }
    
    .formation-card:nth-child(even) {
        animation: float 6s ease-in-out infinite reverse;
        animation-delay: 0.5s;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">
            <i class="fas fa-graduation-cap" style="margin-right: 15px;"></i>
            Toutes les Formations
        </h1>
        <p class="hero-subtitle">
            Découvrez notre collection complète de formations en développement web et programmation. Chaque formation a été soigneusement 
            conçue pour vous guider pas à pas, des concepts fondamentaux aux techniques avancées. Que vous soyez débutant ou développeur 
            expérimenté, nos cours interactifs, exercices pratiques et quiz vous permettront d'acquérir les compétences nécessaires pour 
            réussir dans le développement web moderne. Toutes nos formations sont entièrement gratuites et accessibles 24/7, sans aucune 
            limitation de temps ou de contenu.
        </p>
    </div>
</section>

<!-- Formations Section -->
<section class="formations-container">
    <!-- Stats Section -->
    <div class="stats-section" style="margin-top: 0; margin-bottom: 60px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ count($formations) }}+</div>
                <div class="stat-label">Formations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100+</div>
                <div class="stat-label">Exercices</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Disponible</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">Gratuit</div>
            </div>
        </div>
    </div>
    
    <h2 class="section-title">Nos Formations</h2>
    <p class="section-subtitle">
        Choisissez la formation qui correspond à vos besoins et commencez votre parcours d'apprentissage dès aujourd'hui. Chaque formation 
        comprend des leçons détaillées avec exemples de code, des exercices pratiques pour renforcer vos compétences, des quiz pour tester 
        vos connaissances, et des projets réels pour mettre en pratique ce que vous avez appris. Nos formations sont régulièrement mises à 
        jour pour refléter les dernières technologies et meilleures pratiques de l'industrie. Que vous souhaitiez devenir développeur frontend, 
        backend, full-stack, ou vous spécialiser dans une technologie spécifique, nous avons la formation qu'il vous faut.
    </p>
    
    <div class="formations-grid">
        @foreach($formations as $formation)
        <a href="{{ $formation['route'] }}" class="formation-card">
            <div class="formation-badge">
                <i class="fas fa-star"></i> Disponible
            </div>
            <i class="{{ $formation['icon'] }} formation-icon" style="color: {{ $formation['color'] }};"></i>
            <h3 class="formation-name">{{ $formation['name'] }}</h3>
            <p class="formation-description">{{ $formation['description'] }}</p>
            <span class="formation-link">
                Commencer la formation
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>
        @endforeach
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.formation-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1
        });
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endsection

