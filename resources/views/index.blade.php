@extends('layouts.app')

@section('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')
@section('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle avec des tutoriels complets et pratiques.')
@section('meta_keywords', 'formation développement web gratuit, apprendre HTML, apprendre CSS, JavaScript tutoriel, PHP Laravel, cours programmation, développeur web, formation en ligne gratuite')

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
        overflow-x: hidden;
        color: #fff;
    }
    
    /* Scroll Smooth */
    html {
        scroll-behavior: smooth;
    }
    
    /* Animations 3D Surrealistes */
    @keyframes float3d {
        0%, 100% { transform: translateY(0) rotateX(0deg) rotateY(0deg) rotateZ(0deg); }
        25% { transform: translateY(-30px) rotateX(10deg) rotateY(10deg) rotateZ(5deg); }
        50% { transform: translateY(-15px) rotateX(-10deg) rotateY(-10deg) rotateZ(-5deg); }
        75% { transform: translateY(-25px) rotateX(5deg) rotateY(15deg) rotateZ(3deg); }
    }
    
    @keyframes rotate3d {
        0% { transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg); }
        100% { transform: rotateX(360deg) rotateY(360deg) rotateZ(360deg); }
    }
    
    @keyframes pulse3d {
        0%, 100% { 
            transform: scale(1) translateZ(0);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.5), 0 0 40px rgba(6, 182, 212, 0.3);
        }
        50% { 
            transform: scale(1.1) translateZ(50px);
            box-shadow: 0 0 60px rgba(6, 182, 212, 0.8), 0 0 100px rgba(6, 182, 212, 0.5);
        }
    }
    
    @keyframes glitch {
        0%, 100% { transform: translate(0); }
        20% { transform: translate(-2px, 2px); }
        40% { transform: translate(-2px, -2px); }
        60% { transform: translate(2px, 2px); }
        80% { transform: translate(2px, -2px); }
    }
    
    @keyframes neon-glow {
        0%, 100% { 
            text-shadow: 0 0 10px #0ff, 0 0 20px #0ff, 0 0 30px #0ff, 0 0 40px #0ff;
        }
        50% { 
            text-shadow: 0 0 20px #0ff, 0 0 40px #0ff, 0 0 60px #0ff, 0 0 80px #0ff, 0 0 100px #0ff;
        }
    }
    
    @keyframes hologram {
        0%, 100% { opacity: 1; transform: translateZ(0); }
        50% { opacity: 0.7; transform: translateZ(20px); }
    }
    
    @keyframes particle-float {
        0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translate(100px, -100vh) rotate(360deg); opacity: 0; }
    }
    
    /* Background 3D */
    .bg-3d {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(135deg, #000000 0%, #0a0a1a 50%, #000000 100%);
    }
    
    .bg-3d::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 50%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        animation: hologram 4s ease-in-out infinite;
    }
    
    /* Particles 3D */
    .particle {
        position: absolute;
        width: 3px;
        height: 3px;
        background: linear-gradient(45deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.8);
        animation: particle-float 15s linear infinite;
    }
    
    /* Hero Section 3D */
    .hero-3d {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        perspective: 2000px;
        padding-top: 80px;
    }
    
    .hero-content {
        transform-style: preserve-3d;
    }
    
    .hero-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 3s ease infinite;
        text-transform: uppercase;
        letter-spacing: 5px;
        line-height: 1.1;
        margin-bottom: 2rem;
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .hero-subtitle {
        font-size: 1.8rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 300;
        line-height: 1.6;
        max-width: 900px;
        margin: 0 auto 3rem;
    }
    
    .hero-stats {
        display: flex;
        gap: 4rem;
        justify-content: center;
        margin-top: 4rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
        animation: float3d 6s ease-in-out infinite;
    }
    
    .stat-number {
        font-size: 4rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-family: 'Orbitron', sans-serif;
    }
    
    .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 0.5rem;
    }
    
    /* Cards 3D */
    .card-3d {
        background: rgba(10, 10, 26, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.5s ease;
        transform-style: preserve-3d;
        perspective: 1000px;
        position: relative;
        overflow: hidden;
    }
    
    .card-3d::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.5s ease;
    }
    
    .card-3d:hover {
        transform: translateY(-20px) rotateX(10deg) rotateY(10deg) scale(1.05);
        box-shadow: 
            0 20px 60px rgba(6, 182, 212, 0.4),
            0 0 40px rgba(6, 182, 212, 0.3),
            inset 0 0 20px rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.8);
    }
    
    .card-3d:hover::before {
        top: 100%;
        left: 100%;
    }
    
    /* Holographic Button */
    .btn-hologram {
        position: relative;
        padding: 0.6rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 50px;
        color: #000;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
    }
    
    .btn-hologram::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-hologram:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.7), 0 8px 25px rgba(6, 182, 212, 0.3);
    }
    
    .btn-hologram:hover::before {
        left: 100%;
    }
    
    /* Geometric Shapes 3D */
    .shape-3d {
        position: absolute;
        border: 2px solid rgba(6, 182, 212, 0.3);
        animation: rotate3d 20s linear infinite;
    }
    
    .shape-cube {
        width: 100px;
        height: 100px;
        top: 20%;
        right: 10%;
    }
    
    .shape-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        top: 60%;
        left: 5%;
        animation: pulse3d 4s ease-in-out infinite;
    }
    
    .shape-triangle {
        width: 0;
        height: 0;
        border-left: 75px solid transparent;
        border-right: 75px solid transparent;
        border-bottom: 130px solid rgba(6, 182, 212, 0.2);
        top: 40%;
        right: 20%;
        animation: float3d 10s ease-in-out infinite;
    }
    
    /* Tech Grid */
    .tech-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }
    
    .tech-icon {
        font-size: 4rem;
        animation: float3d 5s ease-in-out infinite;
    }
    
    /* Glitch Effect */
    .glitch {
        /* animation: glitch 0.3s infinite; */
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
            letter-spacing: 2px;
        }
        .hero-subtitle {
            font-size: 1.2rem;
        }
        .stat-number {
            font-size: 2.5rem;
        }
        .hero-stats {
            gap: 2rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Background 3D -->
<div class="bg-3d"></div>

<!-- Particles 3D -->
<div id="particles-container"></div>

<!-- Geometric Shapes -->
<div class="shape-3d shape-cube"></div>
<div class="shape-3d shape-circle"></div>
<div class="shape-3d shape-triangle"></div>

<!-- Hero Section 3D -->
<section class="hero-3d">
    <div class="container mx-auto px-6 text-center">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="glitch">NIANGPROGRAMMEUR</span>
            </h1>
            <p class="hero-subtitle">
                Plongez dans l'univers du développement web avec des formations ultra-modernes, immersives et 100% gratuites
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center mb-12">
                <a href="#formations" class="btn-hologram">Commencer Maintenant</a>
                <a href="#about" class="btn-hologram" style="background: transparent; border: 2px solid #06b6d4; color: #06b6d4;">En savoir plus</a>
            </div>
            
            <!-- Hero Stats -->
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">8+</div>
                    <div class="stat-label">Formations</div>
                </div>
                <div class="stat-item" style="animation-delay: 0.5s;">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Gratuit</div>
                </div>
                <div class="stat-item" style="animation-delay: 1s;">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Accès</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-32 relative">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-6xl font-bold text-center mb-8" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Pourquoi NiangProgrammeur ?
            </h2>
            <p class="text-xl text-center text-gray-300 mb-16 leading-relaxed">
                Une plateforme d'apprentissage moderne conçue pour les développeurs ambitieux qui veulent maîtriser les technologies web les plus demandées
            </p>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-3d text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-cyan-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-rocket text-cyan-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Apprentissage Rapide</h3>
                    <p class="text-gray-400 leading-relaxed">Contenu structuré et optimisé pour un apprentissage efficace et progressif</p>
                </div>
                
                <div class="card-3d text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-cyan-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-code text-cyan-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Pratique Intensive</h3>
                    <p class="text-gray-400 leading-relaxed">Exemples de code réels et exercices pratiques pour chaque concept</p>
                </div>
                
                <div class="card-3d text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-cyan-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-infinity text-cyan-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Accès Illimité</h3>
                    <p class="text-gray-400 leading-relaxed">Toutes les formations accessibles 24/7 sans aucune restriction</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technologies Section -->
<section id="formations" class="py-32 relative">
    <div class="container mx-auto px-6">
        <h2 class="text-6xl font-bold text-center mb-8" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Nos Formations
        </h2>
        <p class="text-xl text-center text-gray-300 mb-20 max-w-3xl mx-auto">
            Maîtrisez les technologies les plus demandées du marché avec nos formations complètes et détaillées
        </p>
        <div class="tech-grid">
            <a href="{{ route('formations.html5') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-html5 text-orange-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">HTML5</h3>
                <p class="text-gray-400">Structure web moderne</p>
            </a>
            <a href="{{ route('formations.css3') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-css3-alt text-blue-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">CSS3</h3>
                <p class="text-gray-400">Styles avancés</p>
            </a>
            <a href="{{ route('formations.javascript') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-js-square text-yellow-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">JavaScript</h3>
                <p class="text-gray-400">Interactivité dynamique</p>
            </a>
            <a href="{{ route('formations.php') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-php text-cyan-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">PHP</h3>
                <p class="text-gray-400">Backend puissant</p>
            </a>
            <a href="{{ route('formations.git') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-git-alt text-orange-500 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">Git</h3>
                <p class="text-gray-400">Contrôle de version</p>
            </a>
            <a href="{{ route('formations.wordpress') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-wordpress text-blue-500 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">WordPress</h3>
                <p class="text-gray-400">CMS professionnel</p>
            </a>
            <a href="{{ route('formations.ia') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fas fa-brain text-teal-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">IA</h3>
                <p class="text-gray-400">Intelligence artificielle</p>
            </a>
            <a href="{{ route('formations.bootstrap') }}" class="card-3d text-center" style="text-decoration: none; color: inherit;">
                <i class="fab fa-bootstrap text-purple-400 tech-icon"></i>
                <h3 class="text-2xl font-bold mt-4 mb-2">Bootstrap</h3>
                <p class="text-gray-400">Framework CSS</p>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-32 relative">
    <div class="container mx-auto px-6">
        <h2 class="text-6xl font-bold text-center mb-20" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Ce que vous allez apprendre
        </h2>
        
        <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-red-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-laptop-code text-orange-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Frontend Development</h3>
                        <p class="text-gray-400 leading-relaxed">HTML5, CSS3, JavaScript, Bootstrap - Créez des interfaces utilisateur modernes et responsives</p>
                    </div>
                </div>
            </div>
            
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-server text-purple-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Backend Development</h3>
                        <p class="text-gray-400 leading-relaxed">PHP, MySQL, PDO - Développez des applications web dynamiques et sécurisées</p>
                    </div>
                </div>
            </div>
            
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-wordpress text-blue-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">CMS & Frameworks</h3>
                        <p class="text-gray-400 leading-relaxed">WordPress - Créez des sites web professionnels rapidement et efficacement</p>
                    </div>
                </div>
            </div>
            
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500/20 to-green-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-brain text-teal-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Intelligence Artificielle</h3>
                        <p class="text-gray-400 leading-relaxed">Machine Learning, Deep Learning, NLP - Découvrez l'IA et ses applications</p>
                    </div>
                </div>
            </div>
            
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-code-branch text-red-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Version Control</h3>
                        <p class="text-gray-400 leading-relaxed">Git & GitHub - Gérez vos projets et collaborez efficacement en équipe</p>
                    </div>
                </div>
            </div>
            
            <div class="card-3d">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-graduation-cap text-yellow-400 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Best Practices</h3>
                        <p class="text-gray-400 leading-relaxed">Code propre, sécurité, performance - Développez comme un professionnel</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-32 relative">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="card-3d text-center p-16">
                <h2 class="text-5xl font-bold mb-6" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Prêt à commencer votre voyage ?
                </h2>
                <p class="text-xl text-gray-300 mb-10 leading-relaxed">
                    Rejoignez des milliers d'apprenants et devenez un développeur web compétent
                </p>
                <a href="#formations" class="btn-hologram text-lg px-12 py-4">
                    Accéder aux formations gratuitement
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-32 relative">
    <div class="container mx-auto px-6">
        <h2 class="text-6xl font-bold text-center mb-8" style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Contactez-nous
        </h2>
        <p class="text-xl text-center text-gray-300 mb-16">
            Une question ? N'hésitez pas à nous contacter
        </p>
        <div class="max-w-3xl mx-auto">
            <div class="card-3d">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-cyan-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-cyan-400 text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xl mb-2">Email</h4>
                            <a href="mailto:NiangProgrammeur@gmail.com" class="text-cyan-400 hover:text-cyan-300 transition">NiangProgrammeur@gmail.com</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-cyan-500/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-cyan-400 text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xl mb-2">Téléphone</h4>
                            <a href="tel:+221783123657" class="text-cyan-400 hover:text-cyan-300 transition">+221 78 312 36 57</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Créer des particules 3D
    const particlesContainer = document.getElementById('particles-container');
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
        particlesContainer.appendChild(particle);
    }
</script>
@endsection
