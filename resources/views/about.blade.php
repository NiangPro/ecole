@extends('layouts.app')

@section('title', 'À Propos - NiangProgrammeur | Développeur Full-Stack & Formateur')
@section('meta_description', 'Découvrez l\'histoire de Bassirou Niang, développeur Full-Stack et formateur passionné. Plus de 5 ans d\'expérience chez Sunucode, spécialisé en Laravel, React et Vue.js.')
@section('meta_keywords', 'Bassirou Niang, NiangProgrammeur, développeur full-stack, formateur développement web, Sunucode, Laravel expert, React Vue.js')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .skill-bar {
        height: 8px;
        background: rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .skill-progress {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        transition: width 2s ease-out;
    }
    
    /* Dark Mode Styles */
    /* Mode clair - Hero Section */
    section.relative.min-h-screen {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.95) 0%, rgba(241, 245, 249, 0.95) 100%) !important;
    }
    
    body.dark-mode section.relative.min-h-screen {
        background: #000 !important;
    }
    
    /* Mode clair - Background Effects */
    .absolute.inset-0.opacity-20 .bg-cyan-500,
    .absolute.inset-0.opacity-20 .bg-teal-500 {
        opacity: 0.1 !important;
    }
    
    body.dark-mode .absolute.inset-0.opacity-20 .bg-cyan-500,
    body.dark-mode .absolute.inset-0.opacity-20 .bg-teal-500 {
        opacity: 0.2 !important;
    }
    
    /* Mode clair - Text Colors */
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body.dark-mode .text-gray-300 {
        color: rgba(209, 213, 219, 1) !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body.dark-mode .text-gray-400 {
        color: rgba(156, 163, 175, 1) !important;
    }
    
    body:not(.dark-mode) .text-white {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body.dark-mode .text-white {
        color: #fff !important;
    }
    
    /* Mode clair - Cards */
    .bg-gradient-to-br.from-gray-900\/90.to-black\/90 {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .bg-gradient-to-br.from-gray-900\/90.to-black\/90 {
        background: linear-gradient(to bottom right, rgba(17, 24, 39, 0.9), rgba(0, 0, 0, 0.9)) !important;
    }
    
    .bg-black\/50 {
        background: rgba(248, 250, 252, 0.5) !important;
    }
    
    body.dark-mode .bg-black\/50 {
        background: rgba(0, 0, 0, 0.5) !important;
    }
    
    /* Mode clair - Sections */
    .bg-gradient-to-b.from-black.to-gray-900 {
        background: linear-gradient(to bottom, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1)) !important;
    }
    
    body.dark-mode .bg-gradient-to-b.from-black.to-gray-900 {
        background: linear-gradient(to bottom, #000, rgba(17, 24, 39, 1)) !important;
    }
    
    .bg-gray-900 {
        background: rgba(241, 245, 249, 1) !important;
    }
    
    body.dark-mode .bg-gray-900 {
        background: rgba(17, 24, 39, 1) !important;
    }
    
    .bg-gray-900\/50 {
        background: rgba(248, 250, 252, 0.5) !important;
    }
    
    body.dark-mode .bg-gray-900\/50 {
        background: rgba(17, 24, 39, 0.5) !important;
    }
    
    .bg-gradient-to-br.from-gray-800\/50.to-black\/50 {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 0.5), rgba(255, 255, 255, 0.5)) !important;
    }
    
    body.dark-mode .bg-gradient-to-br.from-gray-800\/50.to-black\/50 {
        background: linear-gradient(to bottom right, rgba(31, 41, 55, 0.5), rgba(0, 0, 0, 0.5)) !important;
    }
    
    .bg-gradient-to-b.from-gray-900.to-black {
        background: linear-gradient(to bottom, rgba(241, 245, 249, 1), rgba(248, 250, 252, 1)) !important;
    }
    
    body.dark-mode .bg-gradient-to-b.from-gray-900.to-black {
        background: linear-gradient(to bottom, rgba(17, 24, 39, 1), #000) !important;
    }
    
    .bg-black {
        background: rgba(248, 250, 252, 1) !important;
    }
    
    body.dark-mode .bg-black {
        background: #000 !important;
    }
    
    /* Mode clair - Text in cards */
    .text-gray-500 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body.dark-mode .text-gray-500 {
        color: rgba(107, 114, 128, 1) !important;
    }
    
    .text-gray-600 {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    body.dark-mode .text-gray-600 {
        color: rgba(75, 85, 99, 1) !important;
    }
    
    /* Mode clair - CTA Section */
    .bg-gradient-to-br.from-cyan-600\/20.to-teal-600\/20 {
        background: linear-gradient(to bottom right, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)) !important;
    }
    
    body.dark-mode .bg-gradient-to-br.from-cyan-600\/20.to-teal-600\/20 {
        background: linear-gradient(to bottom right, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)) !important;
    }
    
    .bg-gray-800 {
        background: rgba(248, 250, 252, 0.8) !important;
    }
    
    body.dark-mode .bg-gray-800 {
        background: rgba(31, 41, 55, 1) !important;
    }
    
    .hover\:bg-gray-700:hover {
        background: rgba(241, 245, 249, 0.9) !important;
    }
    
    body.dark-mode .hover\:bg-gray-700:hover {
        background: rgba(55, 65, 81, 1) !important;
    }
    
    /* Force text colors in light mode for all white/gray text */
    body:not(.dark-mode) h1,
    body:not(.dark-mode) h2,
    body:not(.dark-mode) h3,
    body:not(.dark-mode) p {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* ============================================
       DESIGN ULTRA MODERNE - SECTION À PROPOS
       ============================================ */
    
    .modern-about-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 60px;
        align-items: start;
        margin-top: 40px;
    }
    
    @media (max-width: 968px) {
        .modern-about-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }
    
    /* Avatar Section Moderne */
    .avatar-section-modern {
        position: sticky;
        top: 100px;
    }
    
    .avatar-wrapper-modern {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .avatar-glow {
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.4), rgba(20, 184, 166, 0.4));
        filter: blur(40px);
        animation: pulse-glow 3s ease-in-out infinite;
        z-index: 0;
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            transform: scale(1);
            opacity: 0.6;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
    
    .avatar-image-modern {
        position: relative;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid transparent;
        background: linear-gradient(135deg, #06b6d4, #14b8a6) padding-box,
                    linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(20, 184, 166, 0.3)) border-box;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.4),
                    0 0 0 20px rgba(6, 182, 212, 0.1),
                    inset 0 0 60px rgba(6, 182, 212, 0.2);
        z-index: 1;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .avatar-image-modern:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.5),
                    0 0 0 25px rgba(6, 182, 212, 0.15);
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(1.05) contrast(1.1);
    }
    
    .status-badge-modern {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(15, 23, 42, 0.95);
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);
        z-index: 2;
        animation: float-badge 3s ease-in-out infinite;
    }
    
    @keyframes float-badge {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .status-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(34, 197, 94, 0.6);
        animation: pulse-ring 2s ease-out infinite;
    }
    
    @keyframes pulse-ring {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    
    .status-badge-modern i {
        color: white;
        font-size: 1.5rem;
        z-index: 1;
        position: relative;
    }
    
    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }
    
    .float-element {
        position: absolute;
        width: 50px;
        height: 50px;
        background: rgba(6, 182, 212, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #06b6d4;
        animation: float-around 20s linear infinite;
    }
    
    .float-element.float-1 {
        top: 10%;
        left: -10%;
        animation-delay: 0s;
    }
    
    .float-element.float-2 {
        top: 50%;
        right: -15%;
        animation-delay: 5s;
    }
    
    .float-element.float-3 {
        bottom: 20%;
        left: -10%;
        animation-delay: 10s;
    }
    
    .float-element.float-4 {
        top: 30%;
        right: -10%;
        animation-delay: 15s;
    }
    
    @keyframes float-around {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        25% {
            transform: translate(20px, -20px) rotate(90deg);
        }
        50% {
            transform: translate(-10px, -30px) rotate(180deg);
        }
        75% {
            transform: translate(-20px, 10px) rotate(270deg);
        }
    }
    
    /* Content Section Moderne */
    .content-section-modern {
        position: relative;
    }
    
    .content-header-modern {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: rgba(6, 182, 212, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 20px;
    }
    
    .badge-dot {
        width: 8px;
        height: 8px;
        background: #06b6d4;
        border-radius: 50%;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.5;
            transform: scale(1.2);
        }
    }
    
    .title-modern {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 3rem;
        font-weight: 900;
        margin: 0;
    }
    
    .title-line {
        flex: 1;
        height: 3px;
        background: linear-gradient(90deg, transparent, #06b6d4, transparent);
        border-radius: 2px;
    }
    
    .title-text {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Timeline Cards */
    .timeline-cards {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline-cards::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 2px;
    }
    
    .timeline-card {
        position: relative;
        margin-bottom: 40px;
        padding-left: 40px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 20px;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        border: 4px solid rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2),
                    0 4px 12px rgba(6, 182, 212, 0.3);
        z-index: 2;
    }
    
    .timeline-content {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    .timeline-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .timeline-card:hover .timeline-content {
        transform: translateX(10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
    }
    
    .timeline-card:hover .timeline-content::before {
        left: 100%;
    }
    
    body:not(.dark-mode) .timeline-content {
        background: rgba(255, 255, 255, 0.7) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .timeline-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #06b6d4;
        margin-bottom: 20px;
        border: 2px solid rgba(6, 182, 212, 0.3);
    }
    
    .timeline-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 15px;
    }
    
    body:not(.dark-mode) .timeline-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    .timeline-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
        font-size: 1rem;
    }
    
    body:not(.dark-mode) .timeline-text {
        color: rgba(30, 41, 59, 0.85) !important;
    }
    
    /* Contact Cards Moderne */
    .contact-cards-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 40px;
    }
    
    .contact-card-modern {
        position: relative;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    body:not(.dark-mode) .contact-card-modern {
        background: rgba(255, 255, 255, 0.7) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .contact-hover-effect {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .contact-card-modern:hover {
        transform: translateY(-5px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.3);
    }
    
    .contact-card-modern:hover .contact-hover-effect {
        left: 100%;
    }
    
    .contact-icon-wrapper {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #06b6d4;
        border: 2px solid rgba(6, 182, 212, 0.3);
        flex-shrink: 0;
    }
    
    .contact-info {
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 5px;
    }
    
    body:not(.dark-mode) .contact-label {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .contact-value {
        font-size: 1rem;
        font-weight: 600;
        color: #06b6d4;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .contact-value:hover {
        color: #14b8a6;
    }
    
    @media (max-width: 768px) {
        .modern-about-container {
            gap: 30px;
        }
        
        .avatar-section-modern {
            position: relative;
            top: 0;
        }
        
        .avatar-image-modern {
            width: 220px;
            height: 220px;
        }
        
        .title-modern {
            font-size: 2rem;
            flex-direction: column;
            gap: 10px;
        }
        
        .title-line {
            width: 100px;
        }
        
        .timeline-cards {
            padding-left: 30px;
        }
        
        .contact-cards-modern {
            grid-template-columns: 1fr;
        }
    }
    
    body:not(.dark-mode) .text-xl {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-2xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-3xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-4xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-5xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-6xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-7xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Keep gradient text as is */
    body:not(.dark-mode) .gradient-text {
        -webkit-text-fill-color: transparent !important;
    }
    
    /* Mode clair - Email et Téléphone dans les cartes de contact */
    body:not(.dark-mode) a[href^="mailto:"],
    body:not(.dark-mode) a[href^="tel:"] {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) a[href^="mailto:"]:hover,
    body:not(.dark-mode) a[href^="tel:"]:hover {
        color: #06b6d4 !important;
    }
    
    /* Mode clair - Bouton "Me contacter" */
    body:not(.dark-mode) .bg-gray-800,
    body:not(.dark-mode) .hover\:bg-gray-700:hover {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .bg-gray-800 a,
    body:not(.dark-mode) .hover\:bg-gray-700:hover a {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Mode clair - Textes dans les cartes de contact */
    body:not(.dark-mode) .text-sm.font-semibold {
        color: rgba(30, 41, 59, 0.9) !important;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen bg-black pt-32 pb-20 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Hero Content -->
        <div class="max-w-5xl mx-auto text-center mb-20">
            <h1 class="text-6xl md:text-7xl font-black mb-6">
                Bonjour, C'est <span class="gradient-text">Bassirou Niang</span>
            </h1>
            <p class="text-2xl md:text-3xl text-gray-300 mb-8">
                Je suis <span class="text-cyan-400 font-bold">NiangProgrammeur</span>
            </p>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Développeur Full-Stack & Formateur passionné
            </p>
        </div>
        
        <!-- Profile Card - Design Ultra Moderne -->
        <div class="max-w-7xl mx-auto">
            <div class="modern-about-container">
                <!-- Avatar Section avec effet Glassmorphism -->
                <div class="avatar-section-modern">
                    <div class="avatar-wrapper-modern">
                        <div class="avatar-glow"></div>
                        <div class="avatar-image-modern">
                            <img src="{{ asset('images/about.jpg') }}" alt="Bassirou Niang" class="avatar-img">
                        </div>
                        <div class="status-badge-modern">
                            <div class="status-pulse"></div>
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="floating-elements">
                            <div class="float-element float-1"><i class="fab fa-html5"></i></div>
                            <div class="float-element float-2"><i class="fab fa-js"></i></div>
                            <div class="float-element float-3"><i class="fab fa-laravel"></i></div>
                            <div class="float-element float-4"><i class="fab fa-react"></i></div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Section avec Cards Modulaires -->
                <div class="content-section-modern">
                    <div class="content-header-modern">
                        <div class="badge-modern">
                            <span class="badge-dot"></span>
                            <span>Développeur Full-Stack</span>
                        </div>
                        <h2 class="title-modern">
                            <span class="title-line"></span>
                            <span class="title-text">À propos de moi</span>
                            <span class="title-line"></span>
                        </h2>
                    </div>
                    
                    <!-- Timeline Cards -->
                    <div class="timeline-cards">
                        <div class="timeline-card">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <h3 class="timeline-title">Les Débuts</h3>
                                <p class="timeline-text">
                                    Voici l'histoire d'un développeur passionné qui a suivi une voie conventionnelle pour arriver à son métier actuel. 
                                    Issu d'une famille modeste, dès mon plus jeune âge, j'étais fasciné par la technologie et passais des heures sur mon 
                                    ordinateur personnel à explorer différents programmes et langages de programmation. Cette curiosité précoce m'a conduit à 
                                    découvrir le monde fascinant du développement web, où chaque ligne de code représente une possibilité infinie de création.
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-card">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h3 class="timeline-title">Formation Académique</h3>
                                <p class="timeline-text">
                                    Mon parcours académique a commencé par un Baccalauréat Scientifique au Lycée Seydina Limamou Laye, où j'ai développé 
                                    une solide base en mathématiques et en logique. Cette formation m'a préparé pour ma Licence Professionnelle en Génie Logiciel 
                                    à l'Institut Supérieur d'Informatique, où j'ai approfondi mes connaissances en programmation, algorithmes et architecture logicielle. 
                                    J'ai ensuite complété ma formation par une spécialisation en Développement Web & Mobile à l'Access Code School, où j'ai maîtrisé 
                                    les technologies modernes du développement web.
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-card">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <h3 class="timeline-title">Expérience Professionnelle</h3>
                                <p class="timeline-text">
                                    Au fil du temps, ma passion est devenue évidente auprès de mes collègues qui ont commencé à me confier des projets liés 
                                    au développement web. Petit-à-petit, je me suis constitué un portefeuille impressionnant avec diverses réalisations allant 
                                    du simple site vitrine jusqu'à la création complète d'applications complexes. Chaque projet a été une opportunité d'apprendre, 
                                    de grandir et de perfectionner mes compétences techniques et créatives.
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-card">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <h3 class="timeline-title">Chez Sunucode</h3>
                                <p class="timeline-text">
                                    Aujourd'hui, avec plus de 5 ans d'expérience professionnelle chez Sunucode, j'ai eu l'opportunité de travailler sur des projets 
                                    variés et stimulants. J'ai développé des applications web complexes utilisant Laravel, React et Vue.js, formé et accompagné 
                                    de nombreux développeurs juniors, et géré des projets d'envergure nécessitant une architecture logicielle solide. Cette expérience 
                                    m'a permis de comprendre que le développement web ne se limite pas à écrire du code, mais implique également la résolution 
                                    de problèmes complexes, la collaboration en équipe, et la transmission de connaissances.
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-card">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h3 class="timeline-title">NiangProgrammeur</h3>
                                <p class="timeline-text">
                                    C'est cette passion pour l'enseignement et le partage de connaissances qui m'a poussé à créer NiangProgrammeur, une plateforme 
                                    entièrement gratuite dédiée à l'apprentissage du développement web. Mon objectif est de démocratiser l'accès à l'éducation 
                                    en programmation, particulièrement en Afrique, où les ressources éducatives de qualité sont parfois limitées. Je crois fermement 
                                    que tout le monde devrait avoir la possibilité d'apprendre à coder, indépendamment de sa situation financière ou géographique.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Cards Moderne -->
                    <div class="contact-cards-modern">
                        <div class="contact-card-modern">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info">
                                <p class="contact-label">Email</p>
                                <a href="mailto:NiangProgrammeur@gmail.com" class="contact-value">
                                    NiangProgrammeur@gmail.com
                                </a>
                            </div>
                            <div class="contact-hover-effect"></div>
                        </div>
                        
                        <div class="contact-card-modern">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info">
                                <p class="contact-label">Téléphone</p>
                                <a href="tel:+221783123657" class="contact-value">
                                    +221 78 312 36 57
                                </a>
                            </div>
                            <div class="contact-hover-effect"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Compétences Section -->
<section class="py-20 bg-gradient-to-b from-black to-gray-900">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Compétences & Capacités</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            Maîtrise approfondie des technologies modernes du développement web, acquise à travers des années d'expérience pratique, 
            de formation continue et de projets réels. Chaque technologie que je maîtrise a été apprise et perfectionnée dans le cadre 
            de projets concrets, garantissant une compréhension pratique et applicable.
        </p>
        
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
            @php
                $skills = [
                    ['name' => 'HTML5 & CSS3', 'level' => 95, 'icon' => 'fab fa-html5', 'color' => 'from-orange-500 to-red-500'],
                    ['name' => 'JavaScript & TypeScript', 'level' => 90, 'icon' => 'fab fa-js', 'color' => 'from-yellow-500 to-orange-500'],
                    ['name' => 'PHP & Laravel', 'level' => 88, 'icon' => 'fab fa-php', 'color' => 'from-purple-500 to-pink-500'],
                    ['name' => 'React & Vue.js', 'level' => 85, 'icon' => 'fab fa-react', 'color' => 'from-blue-500 to-cyan-500'],
                    ['name' => 'Node.js & Express', 'level' => 82, 'icon' => 'fab fa-node', 'color' => 'from-green-500 to-teal-500'],
                    ['name' => 'Git & GitHub', 'level' => 92, 'icon' => 'fab fa-git-alt', 'color' => 'from-red-500 to-orange-500'],
                    ['name' => 'WordPress', 'level' => 87, 'icon' => 'fab fa-wordpress', 'color' => 'from-blue-600 to-cyan-600'],
                    ['name' => 'Intelligence Artificielle', 'level' => 75, 'icon' => 'fas fa-brain', 'color' => 'from-purple-600 to-pink-600'],
                    ['name' => 'Python', 'level' => 80, 'icon' => 'fab fa-python', 'color' => 'from-blue-500 to-yellow-500'],
                    ['name' => 'Flutter', 'level' => 78, 'icon' => 'fab fa-flutter', 'color' => 'from-cyan-500 to-blue-500'],
                ];
            @endphp
            
            @foreach($skills as $skill)
            <div class="bg-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-cyan-500/20 hover:border-cyan-500/50 transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $skill['color'] }} rounded-xl flex items-center justify-center">
                        <i class="{{ $skill['icon'] }} text-white text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $skill['name'] }}</h3>
                        <p class="text-sm text-gray-400">{{ $skill['level'] }}%</p>
                    </div>
                </div>
                <div class="skill-bar">
                    <div class="skill-progress" style="width: {{ $skill['level'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Formation Section -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Mes Formations</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            L'éducation ne se limite pas seulement au domaine scolaire mais englobe également l'apprentissage tout au long de la vie. 
            Mon parcours éducatif reflète ma conviction que l'apprentissage est un processus continu qui ne s'arrête jamais. Chaque 
            formation que j'ai suivie m'a apporté des compétences précieuses et m'a préparé pour les défis professionnels à venir.
        </p>
        
        <div class="max-w-4xl mx-auto space-y-8">
            @php
                $formations = [
                    [
                        'school' => 'Access Code School',
                        'degree' => 'Spécialisation Développeur Web & Mobile',
                        'period' => '2019-2020',
                        'icon' => 'fas fa-code',
                        'color' => 'cyan'
                    ],
                    [
                        'school' => 'Institut Supérieur D\'Informatique',
                        'degree' => 'Licence Professionnelle Génie Logiciel',
                        'period' => '2016-2019',
                        'icon' => 'fas fa-graduation-cap',
                        'color' => 'teal'
                    ],
                    [
                        'school' => 'Lycée Seydina Limamou Laye',
                        'degree' => 'Baccalauréat Scientifique S2',
                        'period' => '2013-2016',
                        'icon' => 'fas fa-school',
                        'color' => 'purple'
                    ],
                ];
            @endphp
            
            @foreach($formations as $formation)
            <div class="bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-xl rounded-2xl p-8 border border-{{ $formation['color'] }}-500/30 hover:border-{{ $formation['color'] }}-500/60 transition">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-{{ $formation['color'] }}-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="{{ $formation['icon'] }} text-{{ $formation['color'] }}-400 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold mb-2">{{ $formation['school'] }}</h3>
                        <p class="text-lg text-{{ $formation['color'] }}-400 mb-2">{{ $formation['degree'] }}</p>
                        <p class="text-gray-500 flex items-center gap-2">
                            <i class="fas fa-calendar"></i>
                            {{ $formation['period'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Expériences Section -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-black">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Expériences Professionnelles</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            Mon parcours professionnel dans le développement web témoigne d'une évolution constante, de l'apprentissage des bases 
            à la maîtrise des technologies avancées. Chaque expérience professionnelle a contribué à façonner mon expertise et ma 
            vision du développement web moderne.
        </p>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-cyan-500/20 hover:border-cyan-500/50 transition">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold mb-1">Sunucode</h3>
                                <a href="https://sunucode.com/" target="_blank" class="text-cyan-400 hover:text-cyan-300 transition text-sm flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i>
                                    sunucode.com
                                </a>
                            </div>
                        </div>
                        
                        <p class="text-xl text-cyan-400 mb-3 font-semibold">
                            <i class="fas fa-code mr-2"></i>Développeur Full-Stack & Formateur
                        </p>
                        
                        <p class="text-gray-400 flex items-center gap-2 mb-6">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="font-semibold">2020 - Présent</span>
                            <span class="text-gray-600">•</span>
                            <span class="text-green-400">{{ \Carbon\Carbon::parse('2020-01-01')->diffForHumans(null, true) }}</span>
                        </p>
                        
                        <div class="space-y-3">
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Développement d'applications web complexes avec Laravel, React et Vue.js, incluant la conception de 
                                systèmes backend robustes, la création d'interfaces utilisateur modernes et réactives, et l'intégration 
                                d'APIs RESTful pour des solutions complètes et performantes.
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Formation et accompagnement de développeurs juniors, avec la création de programmes de mentorat, 
                                l'organisation de sessions de code review, et le partage de bonnes pratiques pour accélérer leur 
                                progression professionnelle et technique.
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Gestion de projets et architecture logicielle, incluant la planification de sprints, la coordination 
                                d'équipes multidisciplinaires, la conception d'architectures scalables, et l'optimisation des performances 
                                pour garantir la qualité et la maintenabilité des applications.
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Développement de solutions e-commerce et applications métier sur mesure, avec intégration de systèmes 
                                de paiement, gestion de stocks, et interfaces d'administration complètes pour répondre aux besoins 
                                spécifiques des clients.
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Optimisation SEO et performance web, avec l'implémentation de techniques avancées de référencement, 
                                l'optimisation des temps de chargement, et l'amélioration de l'expérience utilisateur pour maximiser 
                                la visibilité et l'engagement.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <span class="px-6 py-3 bg-green-500/20 text-green-400 rounded-xl text-sm font-bold text-center border border-green-500/30">
                            <i class="fas fa-briefcase mr-2"></i>En cours
                        </span>
                        <span class="px-6 py-3 bg-cyan-500/20 text-cyan-400 rounded-xl text-sm font-bold text-center border border-cyan-500/30">
                            <i class="fas fa-clock mr-2"></i>Temps plein
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Plus de {{ \Carbon\Carbon::parse('2020-01-01')->diffInYears() }} ans d'expérience professionnelle
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-cyan-600/20 to-teal-600/20 rounded-3xl p-12 border border-cyan-500/30 text-center">
            <h2 class="text-4xl font-bold mb-6">
                <span class="gradient-text">Prêt à apprendre ?</span>
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Rejoignez des milliers d'apprenants et commencez votre parcours dans le développement web
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}" class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-105 transition">
                    <i class="fas fa-home mr-2"></i>Retour à l'accueil
                </a>
                <a href="{{ route('home') }}#contact" class="px-8 py-4 bg-gray-800 hover:bg-gray-700 rounded-xl font-bold text-lg border border-cyan-500/30 hover:border-cyan-500/60 transition">
                    <i class="fas fa-envelope mr-2"></i>Me contacter
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
