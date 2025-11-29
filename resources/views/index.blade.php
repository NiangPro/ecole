@extends('layouts.app')

@section('title', 'NiangProgrammeur - Formation Gratuite en Développement Web')
@section('meta_description', 'Plateforme de formation gratuite en développement web. Apprenez HTML5, CSS3, JavaScript, PHP, Laravel, Bootstrap, Git, WordPress et Intelligence Artificielle.')

@push('styles')
<!-- CSS non critique - Chargé en bas de page pour ne pas bloquer le rendu -->
<style>
    /* Fonts chargées via preload dans le head - pas de @import bloquant */
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: #ffffff;
        color: rgba(30, 41, 59, 0.9);
        overflow-x: hidden;
    }
    
    /* Scrollbar personnalisée */
    body {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.6) rgba(6, 182, 212, 0.1);
    }
    
    body::-webkit-scrollbar {
        width: 10px;
    }
    
    body::-webkit-scrollbar-track {
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
    }
    
    body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }
    
    body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
        background-clip: padding-box;
    }
    
    body.dark-mode {
        scrollbar-color: rgba(6, 182, 212, 0.7) rgba(15, 23, 42, 0.5);
    }
    
    body.dark-mode::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.5);
    }
    
    body.dark-mode::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        background-clip: padding-box;
    }
    
    body.dark-mode::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
        background-clip: padding-box;
    }
    
    /* Dark Mode Styles */
    body.dark-mode {
        background: #0a0a0f !important;
        color: #fff !important;
    }
    
    /* Background moderne et lumineux */
    .bg-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    }
    
    body.dark-mode .bg-canvas {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
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
        opacity: 0.25;
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
    
    /* Hero Section - Styles déjà dans le CSS critique, ici pour complément */
    .hero-section {
        /* Styles critiques déjà dans le head, ici pour les styles supplémentaires */
    }
    
    body.dark-mode .hero-section {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=75') center/cover no-repeat;
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
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
        z-index: 0;
    }
    
    body.dark-mode .hero-section::before {
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
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
        color: #ffffff;
        letter-spacing: -0.02em;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        position: relative;
    }
    
    body.dark-mode .main-title {
        color: #fff;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    
    /* Arbre de Noël animé avec brillance */
    .christmas-hat {
        position: absolute;
        top: -45px;
        left: -8px;
        font-size: 3.5rem;
        animation: treeBounce 2.5s ease-in-out infinite, treeShine 1.5s ease-in-out infinite;
        z-index: 10;
        filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4));
        line-height: 1;
        text-shadow: 0 0 20px rgba(34, 197, 94, 0.8),
                     0 0 40px rgba(34, 197, 94, 0.6),
                     0 0 60px rgba(34, 197, 94, 0.4);
    }
    
    @keyframes treeBounce {
        0%, 100% {
            transform: translateY(0) rotate(-10deg) scale(1);
        }
        25% {
            transform: translateY(-10px) rotate(8deg) scale(1.08);
        }
        50% {
            transform: translateY(-15px) rotate(-8deg) scale(1.12);
        }
        75% {
            transform: translateY(-10px) rotate(10deg) scale(1.08);
        }
    }
    
    @keyframes treeShine {
        0%, 100% {
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4))
                    drop-shadow(0 0 20px rgba(34, 197, 94, 0.8))
                    drop-shadow(0 0 40px rgba(34, 197, 94, 0.6))
                    drop-shadow(0 0 60px rgba(34, 197, 94, 0.4))
                    brightness(1);
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.8),
                         0 0 40px rgba(34, 197, 94, 0.6),
                         0 0 60px rgba(34, 197, 94, 0.4),
                         0 0 80px rgba(34, 197, 94, 0.3);
        }
        50% {
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4))
                    drop-shadow(0 0 30px rgba(34, 197, 94, 1))
                    drop-shadow(0 0 60px rgba(34, 197, 94, 0.8))
                    drop-shadow(0 0 90px rgba(34, 197, 94, 0.6))
                    drop-shadow(0 0 120px rgba(34, 197, 94, 0.4))
                    brightness(1.3);
            text-shadow: 0 0 30px rgba(34, 197, 94, 1),
                         0 0 60px rgba(34, 197, 94, 0.8),
                         0 0 90px rgba(34, 197, 94, 0.6),
                         0 0 120px rgba(34, 197, 94, 0.4),
                         0 0 150px rgba(34, 197, 94, 0.3);
        }
    }
    
    @media (max-width: 768px) {
        .hero-section {
            min-height: 55vh;
            padding: 60px 20px 40px;
        }
        
        .christmas-hat {
            font-size: 2.5rem;
            top: -35px;
            left: -6px;
        }
        
        .main-title {
            font-size: clamp(1.8rem, 4vw, 2.2rem) !important;
            line-height: 1.3;
            margin-bottom: 20px;
        }
    }
    
    .main-title-wrapper {
        position: relative;
        display: inline-block;
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
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 40px;
        line-height: 1.6;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }
    
    body.dark-mode .subtitle {
        color: rgba(255, 255, 255, 0.8);
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
    .stats-section {
        position: relative;
        z-index: 2;
        padding: 40px 20px;
        margin: 30px 0;
    }
    
    /* Masquer la section stats sur mobile */
    @media (max-width: 768px) {
        .stats-section {
            display: none !important;
        }
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .stat-card {
        position: relative;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.08) 100%);
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.15) 0%, transparent 50%);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 35px 25px;
        backdrop-filter: blur(30px);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        text-align: center;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.15);
    }
    
    body.dark-mode .stat-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.08) 100%);
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.15) 0%, transparent 50%);
        border: 2px solid rgba(6, 182, 212, 0.25);
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
        color: rgba(30, 41, 59, 0.8);
        font-weight: 600;
        text-transform: none;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }
    
    body.dark-mode .stat-label {
        color: rgba(255, 255, 255, 0.75);
    }
    
    .stat-card:hover .stat-label {
        color: rgba(30, 41, 59, 1);
    }
    
    body.dark-mode .stat-card:hover .stat-label {
        color: rgba(255, 255, 255, 0.95);
    }
    
    /* Technologies Section - Carousel Design Exceptionnel */
    .tech-section {
        position: relative;
        z-index: 2;
        padding: 20px 20px 40px;
        max-width: 1600px;
        margin: 0 auto;
        overflow: hidden;
    }
    
    .tech-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
        animation: gradientRotate 20s linear infinite;
        pointer-events: none;
        z-index: 0;
    }
    
    @keyframes gradientRotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 900;
        text-align: center;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 5s ease infinite;
        position: relative;
        z-index: 1;
        text-shadow: 0 0 40px rgba(6, 182, 212, 0.3);
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .section-subtitle {
        text-align: center;
        font-size: clamp(1rem, 2vw, 1.3rem);
        color: rgba(30, 41, 59, 0.7);
        max-width: 800px;
        margin: 0 auto 35px;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    
    body.dark-mode .section-subtitle {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Styles spécifiques pour la section Exercices & Quiz */
    .exercices-quiz-section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 800;
        text-align: center;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 5s ease infinite;
        position: relative;
        z-index: 1;
    }
    
    .exercices-quiz-section-subtitle {
        text-align: center;
        font-size: clamp(1rem, 2vw, 1.2rem);
        color: rgba(30, 41, 59, 0.7);
        width: 100%;
        max-width: 100%;
        margin: 0 auto 60px;
        line-height: 1.8;
        position: relative;
        z-index: 1;
        padding: 0 20px;
    }
    
    body.dark-mode .exercices-quiz-section-subtitle {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Carousel Design Moderne et Épuré */
    .tech-carousel-wrapper {
        position: relative;
        padding: 30px 0 40px;
        z-index: 1;
    }
    
    .tech-carousel {
        overflow: visible;
        padding: 20px 0 30px;
    }
    
    /* Styles pour les slides */
    .tech-carousel .swiper-slide {
        height: auto;
        display: flex;
        min-width: 0;
        flex-shrink: 0;
    }
    
    /* Layout avant initialisation de Swiper - Grid pour afficher 4 cartes */
    .tech-carousel:not(.swiper-initialized) .swiper-wrapper {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        width: 100%;
    }
    
    @media (max-width: 1023px) {
        .tech-carousel:not(.swiper-initialized) .swiper-wrapper {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 25px;
        }
    }
    
    @media (max-width: 639px) {
        .tech-carousel:not(.swiper-initialized) .swiper-wrapper {
            grid-template-columns: 1fr !important;
            gap: 20px;
        }
    }
    
    /* Une fois Swiper initialisé, utiliser le layout flex natif */
    .tech-carousel.swiper-initialized .swiper-wrapper {
        display: flex !important;
    }
    
    /* S'assurer que les slides sont toujours visibles */
    .tech-carousel .swiper-slide {
        opacity: 1 !important;
        visibility: visible !important;
        height: auto !important;
    }
    
    .tech-card-carousel {
        position: relative;
        background: #fff;
        border: none;
        border-radius: 20px;
        padding: 0;
        height: 100%;
        min-height: 320px;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 100%;
    }
    
    body.dark-mode .tech-card-carousel {
        background: rgba(30, 41, 59, 0.95);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
    }
    
    /* Section icône avec gradient */
    .tech-card-icon-section {
        position: relative;
        padding: 35px 30px 25px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(20, 184, 166, 0.08));
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.4s ease;
    }
    
    body.dark-mode .tech-card-icon-section {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12), rgba(20, 184, 166, 0.12));
    }
    
    .tech-card-carousel:hover .tech-card-icon-section {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
    }
    
    .tech-icon-carousel {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.2rem;
        position: relative;
        z-index: 1;
        transition: all 0.4s ease;
    }
    
    .tech-card-carousel:hover .tech-icon-carousel {
        transform: scale(1.08);
    }
    
    .tech-name-carousel {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 0;
        color: rgba(30, 41, 59, 0.95);
        text-align: center;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 1;
    }
    
    body.dark-mode .tech-name-carousel {
        color: #fff;
    }
    
    /* Section contenu */
    .tech-card-content-section {
        padding: 25px 30px 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .tech-desc-carousel {
        color: rgba(30, 41, 59, 0.7);
        line-height: 1.7;
        margin-bottom: 25px;
        text-align: center;
        font-size: 0.9rem;
        flex: 1;
    }
    
    body.dark-mode .tech-desc-carousel {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .tech-link-carousel {
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(6, 182, 212, 0.3);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 100%;
        margin-top: auto;
    }
    
    .tech-link-carousel:hover {
        gap: 12px;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }
    
    .tech-card-carousel:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.2);
    }
    
    body.dark-mode .tech-card-carousel:hover {
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.3);
    }
    
    /* Navigation moderne et épurée */
    .tech-carousel-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 30px auto 0;
        position: relative;
        z-index: 1;
        width: fit-content;
        max-width: 100%;
    }
    
    .tech-carousel-btn {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        color: #fff;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(6, 182, 212, 0.3);
        flex-shrink: 0;
    }
    
    .tech-carousel-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }
    
    .tech-carousel-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: translateY(0);
    }
    
    .tech-carousel-pagination {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 6px;
    }
    
    .tech-carousel-pagination-bullet {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(6, 182, 212, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .tech-carousel-pagination-bullet:hover {
        background: rgba(6, 182, 212, 0.5);
        transform: scale(1.2);
    }
    
    .tech-carousel-pagination-bullet-active {
        background: #06b6d4;
        width: 24px;
        border-radius: 12px;
    }
    
    /* Masquer la pagination sur mobile */
    @media (max-width: 768px) {
        .tech-carousel-pagination {
            display: none !important;
        }
        
        .tech-carousel-nav {
            gap: 20px;
        }
    }
    
    @media (max-width: 968px) {
        .tech-section {
            padding: 40px 20px 30px;
        }
        
        .tech-carousel-wrapper {
            padding: 20px 0 30px;
        }
        
        .tech-carousel {
            padding: 15px 0 25px;
        }
        
        .tech-carousel-nav {
            margin-top: 30px;
        }
        
        .tech-card-carousel {
            padding: 35px 25px;
        }
        
        .tech-icon-carousel {
            width: 80px;
            height: 80px;
            font-size: 3.5rem;
        }
        
        .tech-name-carousel {
            font-size: 1.4rem;
        }
    }
    
    @media (max-width: 768px) {
        .tech-section {
            padding: 35px 15px 25px;
        }
        
        .tech-carousel-wrapper {
            padding: 15px 0 25px;
        }
        
        .tech-carousel-nav {
            margin-top: 25px;
            gap: 8px;
        }
        
        .tech-carousel-btn {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }
        
        .tech-card-carousel {
            padding: 30px 22px;
        }
        
        .tech-icon-carousel {
            width: 70px;
            height: 70px;
            font-size: 3rem;
        }
        
        .tech-name-carousel {
            font-size: 1.3rem;
        }
        
        .tech-desc-carousel {
            font-size: 0.9rem;
        }
        
        .tech-link-carousel {
            padding: 12px 28px;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

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
            <span class="main-title-wrapper">
                @if(date('m') == '12')
                <span class="christmas-hat">🎄</span>
                @endif
                <span>A</span>
            </span>pprenez la <span class="title-gradient">Programmation</span><br>
            Gratuitement avec <span class="title-gradient">NiangProgrammeur</span>
        </h1>
        
        <p class="subtitle">
            La meilleure plateforme gratuite pour apprendre le développement web. Maîtrisez HTML, CSS, JavaScript, PHP, Laravel, Python et bien 
            plus encore avec nos tutoriels interactifs, exemples pratiques et exercices. Notre mission est de démocratiser l'accès à l'éducation 
            en programmation, particulièrement en Afrique, en offrant des formations de qualité professionnelle accessibles à tous, sans aucun 
            frais. Que vous soyez débutant complet ou développeur expérimenté souhaitant vous perfectionner, nos cours progressifs vous guideront 
            vers la maîtrise des technologies modernes du développement web. Apprenez à votre rythme, pratiquez avec plus de 100 exercices interactifs, 
            et testez vos connaissances avec nos quiz détaillés.
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
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i>
            </div>
            <div class="stat-number">9+</div>
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
<section class="exercices-quiz-section" style="position: relative; z-index: 2; padding: 50px 20px 30px; max-width: 1600px; margin: 0 auto;">
    <h2 class="exercices-quiz-section-title">Pratiquez avec nos Exercices & Quiz</h2>
    <p class="exercices-quiz-section-subtitle">
        Renforcez vos compétences avec des exercices pratiques et testez vos connaissances avec nos quiz interactifs. La pratique est essentielle 
        pour maîtriser le développement web, c'est pourquoi nous proposons plus de 100 exercices interactifs couvrant tous les niveaux, du débutant 
        à l'expert. Chaque exercice vous permet d'écrire du code directement dans votre navigateur, de tester vos solutions en temps réel, et de 
        recevoir des feedbacks immédiats. Nos quiz détaillés vous aident à évaluer votre compréhension des concepts clés, avec des explications 
        pour chaque réponse. Cette approche pratique garantit que vous ne vous contentez pas de mémoriser, mais que vous comprenez vraiment 
        comment appliquer vos connaissances dans des situations réelles.
    </p>
    
    <div class="exercices-quiz-container">
        <!-- Cards Exercices & Quiz -->
        <div class="exercices-quiz-cards">
        <!-- Exercices Card -->
            <div class="exercices-quiz-card exercices-card">
                <div class="exercices-quiz-icon-wrapper">
                    <i class="fas fa-code"></i>
                </div>
                <h3 class="exercices-quiz-title">Exercices Pratiques</h3>
                <p class="exercices-quiz-description">
                Plus de 100 exercices interactifs couvrant 9 technologies différentes, répartis en trois niveaux de difficulté. Écrivez du code directement dans votre navigateur avec notre éditeur intégré et recevez des feedbacks immédiats.
            </p>
                <a href="{{ route('exercices') }}" class="exercices-quiz-btn exercices-btn">
                Commencer <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Quiz Card -->
            <div class="exercices-quiz-card quiz-card">
                <div class="exercices-quiz-icon-wrapper quiz-icon-wrapper">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="exercices-quiz-title">Quiz Interactifs</h3>
                <p class="exercices-quiz-description">
                Testez vos connaissances avec nos quiz détaillés couvrant toutes les technologies enseignées. Obtenez un score détaillé avec des explications complètes pour chaque question.
            </p>
                <a href="{{ route('quiz') }}" class="exercices-quiz-btn quiz-btn">
                Faire un quiz <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Formations Card -->
            <div class="exercices-quiz-card formations-card">
                <div class="exercices-quiz-icon-wrapper formations-icon-wrapper">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="exercices-quiz-title">Formations Complètes</h3>
                <p class="exercices-quiz-description">
                Découvrez nos formations complètes et structurées pour maîtriser le développement web. Des parcours progressifs avec des leçons détaillées, des exemples pratiques et des projets réels.
            </p>
                <a href="{{ route('formations.all') }}" class="exercices-quiz-btn formations-btn">
                Voir les formations <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        </div>
        
        <!-- Sidebar Publicités -->
        @if(isset($sidebarAds) && $sidebarAds->count() > 0)
        <aside style="position: sticky; top: 80px; align-self: flex-start;">
            @foreach($sidebarAds as $ad)
            <div class="ad-container" style="background: rgba(51, 65, 85, 0.5); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 20px; backdrop-filter: blur(10px);">
                <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" style="display: block; text-decoration: none;">
                    @if($ad->image)
                    <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}"
                         loading="lazy"
                         decoding="async"
                         alt="{{ $ad->name ?? 'Publicité' }}" 
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

<!-- Section Publicitaire Next-Gen après Exercices & Quiz -->
@if(isset($homepageAds) && $homepageAds->count() > 0)
<section class="nextgen-ads-section">
    <div class="nextgen-ads-container">
        @foreach($homepageAds as $ad)
        <article class="nextgen-ad-card" data-tilt>
            <div class="ad-sponsor-label">
                <i class="fas fa-star"></i>
                <span>Partenariat</span>
            </div>
            <a href="{{ $ad->link_url ?? '#' }}" target="_blank" onclick="trackAdClick({{ $ad->id }})" class="nextgen-ad-link">
                <div class="ad-background-pattern"></div>
                <div class="ad-content-wrapper">
                    @if($ad->image)
                    <div class="ad-image-container">
                        <div class="image-frame">
                            <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}"
                                 alt="{{ $ad->name ?? 'Publicité' }}"
                                 class="ad-main-image"
                                 loading="lazy"
                                 decoding="async"
                                 onerror="this.style.display='none'">
                            <div class="image-reflection"></div>
                        </div>
                    </div>
                    @endif
                    <div class="ad-text-section">
                        <div class="ad-title-container">
                            <h2 class="ad-main-title">
                                <span class="title-line">{{ $ad->name }}</span>
                            </h2>
                            <div class="title-underline"></div>
                        </div>
                        @if($ad->description)
                        <p class="ad-subtitle">{{ $ad->description }}</p>
                        @endif
                        <div class="ad-stats">
                            <div class="stat-bubble">
                                <i class="fas fa-rocket"></i>
                                <span>Innovant</span>
                            </div>
                            <div class="stat-bubble">
                                <i class="fas fa-award"></i>
                                <span>Certifié</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ad-action-bar">
                    <button class="ad-action-btn" type="button">
                        <span class="btn-text">Explorer</span>
                        <span class="btn-icon">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="btn-ripple"></span>
                    </button>
                </div>
                <div class="ad-corner-accent"></div>
                <div class="ad-hover-light"></div>
            </a>
        </article>
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
        background: rgba(51, 65, 85, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.4);
        backdrop-filter: blur(20px);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    body.dark-mode .modern-ad-card {
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .ad-container {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    body.dark-mode .latest-jobs-grid > a > div {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    /* Section Publicitaire Next-Gen - Design Ultra Moderne Alternatif */
    .nextgen-ads-section {
        margin-top: 0;
        position: relative;
        z-index: 2;
        padding: 20px 20px 30px;
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .nextgen-ads-container {
        display: flex;
        flex-direction: column;
        gap: 50px;
    }
    
    .nextgen-ad-card {
        position: relative;
        perspective: 1000px;
    }
    
    .nextgen-ad-link {
        position: relative;
        display: block;
        text-decoration: none;
        color: inherit;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(6, 182, 212, 0.15);
        border-radius: 0;
        padding: 0;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        transform-style: preserve-3d;
        clip-path: polygon(0 0, calc(100% - 60px) 0, 100% 60px, 100% 100%, 60px 100%, 0 calc(100% - 60px));
    }
    
    body.dark-mode .nextgen-ad-link {
        background: rgba(15, 23, 42, 0.3);
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    .ad-background-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.03;
        background-image: 
            linear-gradient(45deg, rgba(6, 182, 212, 0.1) 25%, transparent 25%),
            linear-gradient(-45deg, rgba(6, 182, 212, 0.1) 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, rgba(6, 182, 212, 0.1) 75%),
            linear-gradient(-45deg, transparent 75%, rgba(6, 182, 212, 0.1) 75%);
        background-size: 40px 40px;
        background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
        animation: pattern-move 20s linear infinite;
    }
    
    @keyframes pattern-move {
        0% { background-position: 0 0, 0 20px, 20px -20px, -20px 0px; }
        100% { background-position: 40px 40px, 40px 60px, 60px 20px, 20px 40px; }
    }
    
    .ad-sponsor-label {
        position: absolute;
        top: 30px;
        right: 30px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        z-index: 10;
        clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 100%, 8px 100%);
    }
    
    .ad-sponsor-label i {
        animation: star-spin 3s linear infinite;
    }
    
    @keyframes star-spin {
        0%, 100% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.2); }
    }
    
    .ad-content-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 50px;
        padding: 50px;
        position: relative;
        z-index: 2;
    }
    
    /* Image Container avec effet 3D */
    .ad-image-container {
        position: relative;
    }
    
    .image-frame {
        position: relative;
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 3px solid rgba(6, 182, 212, 0.2);
        overflow: hidden;
        transform-style: preserve-3d;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .ad-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        filter: grayscale(20%) contrast(1.1);
    }
    
    .image-reflection {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(6, 182, 212, 0.3), transparent);
        opacity: 0;
        transition: opacity 0.6s ease;
    }
    
    .nextgen-ad-card:hover .image-frame {
        transform: rotateY(5deg) rotateX(-5deg) scale(1.05);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
    }
    
    .nextgen-ad-card:hover .ad-main-image {
        transform: scale(1.2);
        filter: grayscale(0%) contrast(1.2);
    }
    
    .nextgen-ad-card:hover .image-reflection {
        opacity: 1;
    }
    
    /* Text Section */
    .ad-text-section {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .ad-title-container {
        position: relative;
        margin-bottom: 20px;
    }
    
    .ad-main-title {
        font-size: clamp(1.5rem, 3vw, 2.5rem);
        font-weight: 900;
        line-height: 1.1;
        margin: 0;
        position: relative;
    }
    
    .title-line {
        display: inline-block;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% 200%;
        animation: title-flow 4s ease infinite;
        position: relative;
    }
    
    @keyframes title-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    body.dark-mode .title-line {
        background: linear-gradient(135deg, #fff, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .title-underline {
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 0;
        height: 4px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        transition: width 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .nextgen-ad-card:hover .title-underline {
        width: 100%;
    }
    
    .ad-subtitle {
        font-size: 1.2rem;
        line-height: 1.8;
        color: rgba(30, 41, 59, 0.7);
        margin: 0 0 30px 0;
        max-width: 600px;
    }
    
    body.dark-mode .ad-subtitle {
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* Stats Bubbles */
    .ad-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .stat-bubble {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: rgba(6, 182, 212, 0.08);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 0;
        clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
        font-size: 0.9rem;
        font-weight: 700;
        color: #06b6d4;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .stat-bubble i {
        font-size: 1rem;
    }
    
    .nextgen-ad-card:hover .stat-bubble {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.2);
    }
    
    body.dark-mode .stat-bubble {
        background: rgba(6, 182, 212, 0.12);
        border-color: rgba(6, 182, 212, 0.25);
    }
    
    /* Action Bar */
    .ad-action-bar {
        padding: 30px 50px;
        background: rgba(6, 182, 212, 0.05);
        border-top: 1px solid rgba(6, 182, 212, 0.1);
        position: relative;
    }
    
    body.dark-mode .ad-action-bar {
        background: rgba(6, 182, 212, 0.08);
        border-top-color: rgba(6, 182, 212, 0.15);
    }
    
    .ad-action-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 40px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border: none;
        font-size: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        overflow: hidden;
        clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .btn-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .ad-action-btn:active .btn-ripple {
        animation: ripple 0.6s ease-out;
    }
    
    .btn-icon {
        transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .nextgen-ad-card:hover .ad-action-btn {
        transform: translateX(10px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
    }
    
    .nextgen-ad-card:hover .btn-icon {
        transform: translateX(8px);
    }
    
    /* Accents */
    .ad-corner-accent {
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, transparent 40%, rgba(6, 182, 212, 0.1) 50%, transparent 60%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%);
        opacity: 0;
        transition: opacity 0.6s ease;
    }
    
    .nextgen-ad-card:hover .ad-corner-accent {
        opacity: 1;
    }
    
    .ad-hover-light {
        position: absolute;
        inset: -50px;
        background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(6, 182, 212, 0.1), transparent 50%);
        opacity: 0;
        transition: opacity 0.6s ease;
        pointer-events: none;
        z-index: 1;
    }
    
    .nextgen-ad-card:hover .ad-hover-light {
        opacity: 1;
    }
    
    .nextgen-ad-card:hover .nextgen-ad-link {
        transform: translateY(-15px) rotateX(2deg);
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 40px 100px rgba(6, 182, 212, 0.25);
    }
    
    body.dark-mode .nextgen-ad-card:hover .nextgen-ad-link {
        box-shadow: 0 40px 100px rgba(6, 182, 212, 0.4);
    }
    
    /* Responsive Next-Gen Ads */
    @media (max-width: 968px) {
        .ad-content-wrapper {
            grid-template-columns: 1fr;
            gap: 30px;
            padding: 40px 30px;
        }
        
        .ad-image-container {
            order: 2;
        }
        
        .image-frame {
            height: 300px;
        }
        
        .ad-action-bar {
            padding: 25px 30px;
        }
        
        .ad-action-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .nextgen-ads-section {
            padding: 60px 15px;
        }
        
        .ad-content-wrapper {
            padding: 30px 20px;
        }
        
        .ad-sponsor-label {
            top: 20px;
            right: 20px;
            padding: 6px 12px;
            font-size: 0.7rem;
        }
        
        .ad-main-title {
            font-size: 2rem;
        }
        
        .ad-subtitle {
            font-size: 1rem;
        }
        
        .image-frame {
            height: 250px;
        }
    }
    
    body.dark-mode .modern-ad-overlay {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.7) 50%, rgba(15, 23, 42, 0.95) 100%) !important;
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
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.75) 0%, rgba(71, 85, 105, 0.6) 50%, rgba(51, 65, 85, 0.8) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px;
    }
    
    body.dark-mode .modern-ad-overlay {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.7) 50%, rgba(15, 23, 42, 0.95) 100%);
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

<!-- Technologies Section - Carousel Ultra Moderne -->
<section id="technologies" class="tech-section">
    <h2 class="section-title">Technologies Enseignées</h2>
    <p class="section-subtitle">
        Maîtrisez les technologies les plus demandées du marché avec nos formations complètes et pratiques.
    </p>
    
    <div class="tech-carousel-wrapper">
        <div class="swiper tech-carousel">
            <div class="swiper-wrapper">
                <!-- HTML5 -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-html5" style="color: #e34c26;"></i>
                            </div>
                            <h3 class="tech-name-carousel">HTML5</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Apprenez les fondamentaux du web avec HTML5. Structure, sémantique et bonnes pratiques.
                            </p>
                            <a href="{{ route('formations.html5') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- CSS3 -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-css3-alt" style="color: #264de4;"></i>
                            </div>
                            <h3 class="tech-name-carousel">CSS3</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Créez des designs modernes et responsives avec CSS3, Flexbox, Grid et animations.
                            </p>
                            <a href="{{ route('formations.css3') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- JavaScript -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-js" style="color: #f0db4f;"></i>
                            </div>
                            <h3 class="tech-name-carousel">JavaScript</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Maîtrisez JavaScript ES6+, DOM manipulation, programmation asynchrone et frameworks.
                            </p>
                            <a href="{{ route('formations.javascript') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- PHP -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-php" style="color: #8993be;"></i>
                            </div>
                            <h3 class="tech-name-carousel">PHP</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Développez des applications web dynamiques avec PHP, MySQL, Laravel et Symfony.
                            </p>
                            <a href="{{ route('formations.php') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Bootstrap -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-bootstrap" style="color: #7952b3;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Bootstrap</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Créez rapidement des interfaces responsives et modernes avec Bootstrap 5.
                            </p>
                            <a href="{{ route('formations.bootstrap') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Git -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-git-alt" style="color: #f34f29;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Git</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Gérez vos projets avec Git et GitHub. Versioning, collaboration et workflows.
                            </p>
                            <a href="{{ route('formations.git') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- WordPress -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-wordpress" style="color: #21759b;"></i>
                            </div>
                            <h3 class="tech-name-carousel">WordPress</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Créez des sites web professionnels avec WordPress. Thèmes et plugins personnalisés.
                            </p>
                            <a href="{{ route('formations.wordpress') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- IA -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fas fa-robot" style="color: #06b6d4;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Intelligence Artificielle</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Découvrez l'IA, le Machine Learning et leurs applications pratiques.
                            </p>
                            <a href="{{ route('formations.ia') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Python -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-python" style="color: #3776ab;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Python</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Apprenez Python pour le web, la data science, l'IA et l'automatisation.
                            </p>
                            <a href="{{ route('formations.python') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Java -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-java" style="color: #ed8b00;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Java</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Maîtrisez Java, l'un des langages les plus populaires. POO, collections, threads et Spring.
                            </p>
                            <a href="{{ route('formations.java') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- SQL -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fas fa-database" style="color: #336791;"></i>
                            </div>
                            <h3 class="tech-name-carousel">SQL</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Apprenez SQL pour gérer les bases de données. Requêtes, jointures, fonctions et optimisation.
                            </p>
                            <a href="{{ route('formations.sql') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Langage C -->
                <div class="swiper-slide">
                    <div class="tech-card-carousel">
                        <div class="tech-card-icon-section">
                            <div class="tech-icon-carousel">
                                <i class="fab fa-c" style="color: #a8b9cc;"></i>
                            </div>
                            <h3 class="tech-name-carousel">Langage C</h3>
                        </div>
                        <div class="tech-card-content-section">
                            <p class="tech-desc-carousel">
                                Découvrez le langage C, fondement de nombreux langages modernes. Pointeurs, structures et mémoire.
                            </p>
                            <a href="{{ route('formations.c') }}" class="tech-link-carousel">
                                Commencer <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tech-carousel-nav">
            <button class="tech-carousel-btn tech-carousel-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="tech-carousel-pagination"></div>
            <button class="tech-carousel-btn tech-carousel-next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

@section('scripts')
<!-- Swiper CSS - Chargement asynchrone pour ne pas bloquer le rendu -->
<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>
<!-- Swiper JS - Chargement optimisé pour éviter le layout shift -->
<script>
    // Charger et initialiser Swiper.js
    (function() {
        function initSwiper() {
            const carousel = document.querySelector('.tech-carousel');
            if (!carousel) return;
            
            if (typeof Swiper !== 'undefined') {
                const techSwiper = new Swiper('.tech-carousel', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 25,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        },
                    },
                    navigation: {
                        nextEl: '.tech-carousel-next',
                        prevEl: '.tech-carousel-prev',
                    },
                    pagination: {
                        el: '.tech-carousel-pagination',
                        clickable: true,
                        renderBullet: function (index, className) {
                            return '<span class="' + className + ' tech-carousel-pagination-bullet"></span>';
                        },
                    },
                });
            } else {
                // Si Swiper n'est pas encore chargé, réessayer
                setTimeout(initSwiper, 100);
            }
        }
        
        // Charger Swiper.js
        const swiperScript = document.createElement('script');
        swiperScript.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
        swiperScript.async = true;
        swiperScript.defer = true;
        
        swiperScript.onload = function() {
            // Attendre que le DOM soit prêt
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSwiper);
            } else {
                initSwiper();
            }
        };
        
        // Ajouter le script
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                document.head.appendChild(swiperScript);
            });
        } else {
            document.head.appendChild(swiperScript);
        }
    })();
</script>
@endsection

<!-- Categories and Sponsored Articles Section -->
<section class="categories-sponsored-section" style="position: relative; z-index: 2; padding: 60px 20px; max-width: 1600px; margin: 0 auto;">
    <div class="categories-sponsored-container" style="display: grid; grid-template-columns: {{ (isset($sponsoredArticles) && $sponsoredArticles->count() > 0) ? '2.5fr 1fr' : '1fr' }}; gap: 30px; align-items: stretch;">
        
        <!-- Partie 1: Catégories d'articles (plus grande) -->
        <div class="categories-section" style="display: flex; flex-direction: column; height: 100%;">
            <div style="text-align: center; margin-bottom: 40px; flex-shrink: 0;">
                <h2 class="section-title">📂 Catégories d'Articles</h2>
                <p class="section-subtitle">
                    Explorez nos différentes catégories d'articles et trouvez le contenu qui vous intéresse
                </p>
            </div>
            
            @if(isset($categories) && $categories->count() > 0)
            <div class="categories-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; flex: 1; overflow-y: auto; min-height: 0;">
                @foreach($categories as $category)
                <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="category-card" style="display: block; text-decoration: none;">
                    <div class="category-card-inner" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 24px; transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden;">
                        <!-- Effet de brillance au survol -->
                        <div class="category-shine" style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(45deg, transparent, rgba(6, 182, 212, 0.1), transparent); transform: rotate(45deg); transition: all 0.5s ease; opacity: 0;"></div>
                        
                        <!-- Icône de la catégorie -->
                        <div class="category-icon" style="width: 60px; height: 60px; margin: 0 auto 16px; background: linear-gradient(135deg, #06b6d4, #14b8a6); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                            @if($category->icon)
                                <i class="{{ $category->icon }}" style="font-size: 28px; color: #fff;"></i>
                            @else
                                <i class="fas fa-folder" style="font-size: 28px; color: #fff;"></i>
                            @endif
                        </div>
                        
                        <!-- Nom de la catégorie -->
                        <h3 class="category-name" style="font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; text-align: center; color: rgba(30, 41, 59, 0.9);">
                            {{ $category->name }}
                        </h3>
                        
                        <!-- Nombre d'articles -->
                        <div class="category-count" style="text-align: center; color: rgba(6, 182, 212, 0.8); font-size: 0.9rem; font-weight: 600;">
                            <i class="fas fa-file-alt" style="margin-right: 6px;"></i>
                            {{ $category->published_articles_count ?? 0 }} article{{ ($category->published_articles_count ?? 0) > 1 ? 's' : '' }}
                        </div>
                        
                        <!-- Badge "Nouveau" si récent -->
                        @if($category->created_at && $category->created_at->gt(now()->subDays(30)))
                        <div style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #f59e0b, #ef4444); color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 700; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);">
                            Nouveau
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 40px; background: rgba(6, 182, 212, 0.05); border-radius: 16px; border: 2px dashed rgba(6, 182, 212, 0.3);">
                <i class="fas fa-folder-open" style="font-size: 48px; color: rgba(6, 182, 212, 0.5); margin-bottom: 16px;"></i>
                <p style="color: rgba(30, 41, 59, 0.6);">Aucune catégorie disponible pour le moment.</p>
            </div>
            @endif
        </div>
        
        <!-- Partie 2: Articles sponsorisés (plus petite) -->
        @if(isset($sponsoredArticles) && $sponsoredArticles->count() > 0)
        <div class="sponsored-section" style="display: flex; flex-direction: column; height: 100%; max-height: 100%;">
            <div style="text-align: center; margin-bottom: 32px; flex-shrink: 0;">
                <div style="display: inline-flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <div style="width: 4px; height: 24px; background: linear-gradient(180deg, #06b6d4, #14b8a6); border-radius: 2px;"></div>
                    <h2 class="section-title" style="font-size: 1.2rem; font-weight: 800; color: rgba(30, 41, 59, 0.95); letter-spacing: -0.5px; margin: 0;">
                        Articles Premium
                    </h2>
                    <div style="width: 4px; height: 24px; background: linear-gradient(180deg, #14b8a6, #06b6d4); border-radius: 2px;"></div>
                </div>
                <p class="section-subtitle" style="font-size: 0.85rem; color: rgba(30, 41, 59, 0.6); margin: 0;">
                    Contenu sélectionné pour vous
                </p>
            </div>
            
            <div class="sponsored-articles-list" style="display: flex; flex-direction: column; gap: 25px; width: 100%; flex: 1; overflow-y: auto; min-height: 0;">
                @foreach($sponsoredArticles as $article)
                <div class="modern-sidebar-ad sponsored-article-ad" style="width: 100%; max-width: 100%;">
                    <!-- Badge Sponsorisé -->
                    <div class="sponsored-badge-top">
                        <i class="fas fa-star"></i>
                        <span>Sponsorisé</span>
                    </div>
                    <a href="{{ route('emplois.article', $article->slug) }}" class="modern-sidebar-ad-link">
                        @if($article->cover_image)
                        <div class="modern-sidebar-ad-image-wrapper">
                            @if($article->cover_type === 'external')
                                <img src="{{ $article->cover_image }}" 
                                     alt="{{ $article->title }}" 
                                     class="modern-sidebar-ad-image"
                                     loading="lazy"
                                     decoding="async"
                                     width="300"
                                     height="180"
                                     onerror="this.style.display='none'">
                            @else
                                <img src="{{ asset('storage/' . $article->cover_image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="modern-sidebar-ad-image"
                                     loading="lazy"
                                     decoding="async"
                                     width="300"
                                     height="180"
                                     onerror="this.style.display='none'">
                            @endif
                            <div class="modern-sidebar-ad-overlay">
                                <div class="modern-sidebar-ad-content">
                                    <h4 class="modern-sidebar-ad-title">{{ $article->title }}</h4>
                                    <span class="modern-sidebar-ad-cta">Découvrir <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="modern-sidebar-ad-image-wrapper" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); min-height: 300px; display: flex; align-items: center; justify-content: center;">
                            <div class="modern-sidebar-ad-overlay">
                                <div class="modern-sidebar-ad-content">
                                    <h4 class="modern-sidebar-ad-title">{{ $article->title }}</h4>
                                    <span class="modern-sidebar-ad-cta">Découvrir <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Latest Jobs Section -->
@if(isset($latestJobs) && $latestJobs->count() > 0)
<section class="latest-jobs-section" style="position: relative; z-index: 2; padding: 50px 20px; max-width: 1600px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 class="section-title">💼 Dernières Opportunités d'Emploi</h2>
        <p class="section-subtitle">
            Découvrez les dernières offres d'emploi, bourses et opportunités professionnelles publiées au Sénégal
        </p>
    </div>
    
    <div class="latest-jobs-grid" style="margin-bottom: 40px;">
        @foreach($latestJobs as $index => $job)
        <a href="{{ route('emplois.article', $job->slug) }}" class="latest-job-item @if($index >= 4) hide-on-mobile @endif" style="text-decoration: none; display: block;">
            <div class="latest-job-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)); border: 2px solid rgba(6, 182, 212, 0.25); border-radius: 20px; overflow: hidden; transition: all 0.5s ease; height: 100%; box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);">
                @if($job->cover_image)
                <div style="width: 100%; height: 140px; overflow: hidden;">
                    <img src="{{ $job->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($job->cover_image) : $job->cover_image }}"
                         loading="lazy"
                         decoding="async"
                         width="400"
                         height="250"
                         alt="{{ $job->title }} - {{ $job->category->name ?? 'Article d\'emploi' }}"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                         onerror="this.src='https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400&h=250&fit=crop'">
                </div>
                @else
                <div class="latest-job-placeholder" style="width: 100%; height: 140px; background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15)); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-briefcase text-4xl" style="color: rgba(6, 182, 212, 0.4);"></i>
                </div>
                @endif
                
                <div style="padding: 18px;">
                    <span class="latest-job-category" style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: rgba(6, 182, 212, 0.1); color: #06b6d4; border-radius: 16px; font-size: 0.7rem; font-weight: 700; margin-bottom: 10px; border: 1px solid rgba(6, 182, 212, 0.25);">
                        <i class="fas fa-folder"></i>{{ $job->category->name }}
                    </span>
                    
                    <h3 class="latest-job-title" style="font-size: 0.95rem; font-weight: 800; color: rgba(30, 41, 59, 0.9); margin-bottom: 12px; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.7em;">
                        {{ $job->title }}
                    </h3>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                        <div class="latest-job-meta" style="display: flex; align-items: center; gap: 8px; color: rgba(30, 41, 59, 0.6); font-size: 0.75rem;">
                            <span><i class="fas fa-calendar" style="color: #06b6d4;"></i> {{ $job->published_at ? $job->published_at->format('d/m/Y') : '' }}</span>
                        </div>
                        <span style="padding: 6px 14px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; border-radius: 8px; font-weight: 700; font-size: 0.75rem; transition: all 0.3s ease;">
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
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    
    @media (max-width: 1200px) {
        .latest-jobs-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 900px) {
        .latest-jobs-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .latest-jobs-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .hide-on-mobile {
            display: none !important;
        }
    }
    
    /* Mode jour - cards des dernières opportunités */
    .latest-job-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border: 2px solid rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .latest-job-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
    }
    
    .latest-job-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .latest-job-excerpt {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .latest-job-meta {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body.dark-mode .latest-job-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
    }
    
    body.dark-mode .latest-job-card:hover {
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .latest-job-title {
        color: #fff !important;
    }
    
    body.dark-mode .latest-job-excerpt {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    body.dark-mode .latest-job-meta {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode [style*="rgba(51, 65, 85, 0.85"] {
        background: rgba(15, 23, 42, 0.9) !important;
    }
    
    body.dark-mode [style*="rgba(71, 85, 105, 0.85"] {
        background: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Mode jour - placeholder pour les images manquantes */
    .latest-job-placeholder {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15)) !important;
    }
    
    body.dark-mode .latest-job-placeholder {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)) !important;
    }
    
    /* Mode jour - catégorie */
    .latest-job-category {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    body.dark-mode .latest-job-category {
        background: rgba(6, 182, 212, 0.15) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    /* Mode jour - cards exercices & quiz */
    .stat-card h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .stat-card p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body.dark-mode .stat-card h3 {
        color: #fff !important;
    }
    
    body.dark-mode .stat-card p {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    /* Container Exercices & Quiz */
    .exercices-quiz-container {
        display: grid;
        grid-template-columns: {{ isset($sidebarAds) && $sidebarAds->count() > 0 ? '1fr 300px' : '1fr' }};
        gap: 30px;
        margin-bottom: 0;
        align-items: start;
    }
    
    .exercices-quiz-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    /* Cards Exercices & Quiz Ultra Modernes */
    .exercices-quiz-card {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.6) 0%, rgba(71, 85, 105, 0.5) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 24px;
        text-align: left;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: visible;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .exercices-quiz-card::before {
        display: none;
    }
    
    .exercices-quiz-card:hover::before {
        display: none;
    }
    
    .exercices-quiz-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
        z-index: 1;
    }
    
    .exercices-quiz-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.8) 0%, rgba(71, 85, 105, 0.7) 100%);
    }
    
    .exercices-quiz-card:hover::after {
        opacity: 1;
    }
    
    body:not(.dark-mode) .exercices-quiz-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.8) 100%);
        border-color: rgba(6, 182, 212, 0.25);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }
    
    body:not(.dark-mode) .exercices-quiz-card:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
    }
    
    .exercices-quiz-icon-wrapper {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 2;
        pointer-events: none;
    }
    
    .exercices-quiz-icon-wrapper::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3));
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .exercices-quiz-card:hover .exercices-quiz-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }
    
    .exercices-quiz-card:hover .exercices-quiz-icon-wrapper::before {
        opacity: 1;
    }
    
    .quiz-icon-wrapper {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(6, 182, 212, 0.2));
    }
    
    .exercices-quiz-icon-wrapper i {
        font-size: 1.6rem;
        color: #06b6d4;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .quiz-icon-wrapper i {
        color: #14b8a6;
    }
    
    .formations-icon-wrapper {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(6, 182, 212, 0.2));
    }
    
    .formations-icon-wrapper i {
        color: #14b8a6;
    }
    
    .exercices-quiz-card:hover .exercices-quiz-icon-wrapper i {
        transform: scale(1.2);
        filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.6));
    }
    
    .exercices-quiz-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
        transition: color 0.3s ease;
        position: relative;
        z-index: 2;
        pointer-events: none;
    }
    
    body:not(.dark-mode) .exercices-quiz-title {
        color: rgba(30, 41, 59, 0.95);
    }
    
    .exercices-quiz-description {
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.6;
        margin-bottom: 18px;
        font-size: 0.85rem;
        transition: color 0.3s ease;
        position: relative;
        z-index: 2;
        pointer-events: none;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .exercices-quiz-description {
        color: rgba(30, 41, 59, 0.75);
    }
    
    /* Styles pour les boutons Exercices & Quiz */
    .exercices-quiz-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #fff;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        border-radius: 50px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        width: 100%;
        border: 2px solid transparent;
        z-index: 10 !important;
        pointer-events: auto !important;
        cursor: pointer;
    }
    
    .quiz-btn {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
        box-shadow: 0 4px 15px rgba(20, 184, 166, 0.4);
    }
    
    .formations-btn {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
        box-shadow: 0 4px 15px rgba(20, 184, 166, 0.4);
    }
    
    .formations-btn:hover {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        box-shadow: 0 12px 35px rgba(20, 184, 166, 0.6);
    }
    
    .exercices-quiz-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
        pointer-events: none;
        z-index: 1;
    }
    
    .exercices-quiz-btn:hover::before {
        left: 100%;
    }
    
    .exercices-quiz-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.6);
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .exercices-btn:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }
    
    .quiz-btn:hover {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        box-shadow: 0 12px 35px rgba(20, 184, 166, 0.6);
    }
    
    .exercices-quiz-btn i {
        transition: transform 0.3s ease;
    }
    
    .exercices-quiz-btn:hover i {
        transform: translateX(8px);
    }
    
    /* Responsive Exercices & Quiz */
    @media (max-width: 1024px) {
        .exercices-quiz-container {
            grid-template-columns: 1fr;
        }
        
        .exercices-quiz-cards {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }
    
    @media (max-width: 768px) {
        .exercices-quiz-card {
            padding: 25px;
        }
        
        .exercices-quiz-icon-wrapper {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
        }
        
        .exercices-quiz-icon-wrapper i {
            font-size: 1.6rem;
        }
        
        .exercices-quiz-title {
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
        
        .exercices-quiz-description {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .exercices-quiz-btn {
            padding: 14px 28px;
            font-size: 0.9rem;
        }
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
    
    /* Styles pour la section Catégories et Articles Sponsorisés */
    @media (max-width: 1024px) {
        .categories-sponsored-container {
            grid-template-columns: 1fr !important;
        }
    }
    
    /* Adaptation automatique si pas d'articles sponsorisés */
    .categories-sponsored-container:has(.sponsored-section:empty) {
        grid-template-columns: 1fr !important;
    }
    
    .categories-sponsored-container:not(:has(.sponsored-section)) {
        grid-template-columns: 1fr !important;
    }
    
    /* Effets de survol pour les cards de catégories */
    .category-card:hover .category-card-inner {
        transform: translateY(-8px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.25);
    }
    
    .category-card:hover .category-shine {
        opacity: 1;
        animation: shine 1.5s ease-in-out;
    }
    
    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }
    
    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }
    
    /* ============================================
       DESIGN ARTICLES SPONSORISÉS - MÊME STYLE QUE PUBLICITÉS
       ============================================ */
    
    /* Limiter la hauteur de la section sponsorisée pour qu'elle ne dépasse pas celle des catégories */
    .sponsored-section {
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 100%;
    }
    
    .sponsored-articles-list {
        flex: 1;
        overflow: hidden;
        min-height: 0;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    /* Réutiliser le style des publicités modernes */
    .sponsored-article-ad {
        background: rgba(51, 65, 85, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        position: relative;
        width: 100%;
        max-width: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-link {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-link {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    /* Badge Sponsorisé */
    .sponsored-badge-top {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.5);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .sponsored-badge-top i {
        font-size: 0.65rem;
        animation: pulse-star 2s ease-in-out infinite;
    }
    
    @keyframes pulse-star {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.8;
        }
    }
    
    body:not(.dark-mode) .sponsored-badge-top {
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.6);
    }
    
    .sponsored-article-ad:hover .sponsored-badge-top {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.7);
    }
    
    body:not(.dark-mode) .sponsored-article-ad {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.1) !important;
    }
    
    .sponsored-article-ad:hover {
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.4);
    }
    
    .sponsored-article-ad .modern-sidebar-ad-image-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        flex: 1;
        min-height: 200px;
        overflow: hidden;
        display: flex;
        align-items: stretch;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .sponsored-article-ad:hover .modern-sidebar-ad-image {
        transform: scale(1.1);
    }
    
    .sponsored-article-ad .modern-sidebar-ad-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(51, 65, 85, 0.7) 50%, rgba(51, 65, 85, 0.85) 100%);
        display: flex;
        align-items: flex-end;
        padding: 25px;
        height: 100%;
    }
    
    body:not(.dark-mode) .sponsored-article-ad .modern-sidebar-ad-overlay {
        background: linear-gradient(180deg, transparent 0%, rgba(30, 41, 59, 0.6) 50%, rgba(30, 41, 59, 0.8) 100%) !important;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-content {
        width: 100%;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .sponsored-article-ad .modern-sidebar-ad-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #06b6d4;
        font-weight: 600;
        font-size: 0.9rem;
        transition: gap 0.3s ease;
    }
    
    .sponsored-article-ad:hover .modern-sidebar-ad-cta {
        gap: 12px;
    }
    
    body:not(.dark-mode) .sponsored-article-ad .modern-sidebar-ad-title {
        color: rgba(255, 255, 255, 0.95) !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7) !important;
    }
    
    
    body:not(.dark-mode) .sponsored-article-ad .modern-sidebar-ad-cta {
        color: #06b6d4 !important;
    }
    
    /* Mode sombre - Catégories */
    body.dark-mode .category-card-inner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .category-name {
        color: #fff !important;
    }
    
    body.dark-mode .category-count {
        color: rgba(6, 182, 212, 0.9) !important;
    }
    
    /* Mode sombre - Articles sponsorisés */
    body.dark-mode .sponsored-card-inner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9)) !important;
        border-color: rgba(255, 193, 7, 0.3) !important;
    }
    
    body.dark-mode .sponsored-title {
        color: #fff !important;
    }
    
    body.dark-mode .sponsored-excerpt {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    /* Responsive pour les catégories */
    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)) !important;
            gap: 15px !important;
        }
        
        .category-card-inner {
            padding: 18px !important;
        }
        
        .category-icon {
            width: 50px !important;
            height: 50px !important;
        }
        
        .category-name {
            font-size: 0.95rem !important;
        }
        
        /* Articles sponsorisés responsive */
        .sponsored-articles-list {
            gap: 20px !important;
            max-height: none !important;
        }
        
        .sponsored-section {
            max-height: none !important;
        }
        
        .sponsored-article-ad {
            max-width: 100% !important;
        }
        
        .sponsored-article-ad .modern-sidebar-ad-image-wrapper {
            min-height: 180px !important;
            height: auto !important;
        }
        
        .sponsored-articles-list {
            overflow-y: auto !important;
        }
        
        .categories-grid {
            overflow-y: auto !important;
        }
        
        .sponsored-article-ad .modern-sidebar-ad-title {
            font-size: 0.95rem !important;
        }
        
        .sponsored-article-ad .modern-sidebar-ad-description {
            font-size: 0.8rem !important;
        }
        
        .sponsored-badge-top {
            top: 10px !important;
            left: 10px !important;
            padding: 5px 10px !important;
            font-size: 0.65rem !important;
        }
    }
</style>
@endif
