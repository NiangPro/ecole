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
    
    /* ============================================
       RÉALISATIONS SECTION ULTRA MODERNE - CARDS OVERLAY
       ============================================ */
    
    .achievements-section-ultra {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0f0f23 100%);
        position: relative;
    }
    
    body:not(.dark-mode) .achievements-section-ultra {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
    }
    
    /* Animated Background Orbs */
    .achievements-bg-animated {
        position: absolute;
        inset: 0;
        overflow: hidden;
        z-index: 0;
    }
    
    .bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.3;
        animation: float-orb 20s ease-in-out infinite;
    }
    
    .bg-orb-1 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, #06b6d4, transparent);
        top: -200px;
        left: -200px;
        animation-delay: 0s;
    }
    
    .bg-orb-2 {
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, #14b8a6, transparent);
        bottom: -300px;
        right: -300px;
        animation-delay: 7s;
    }
    
    .bg-orb-3 {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, #8b5cf6, transparent);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: 14s;
    }
    
    @keyframes float-orb {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        33% {
            transform: translate(50px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-30px, 30px) scale(0.9);
        }
    }
    
    /* Title Gradient */
    .achievements-title-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #8b5cf6 100%);
        background-size: 200% 200%;
        animation: gradient-shift 4s ease infinite;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Achievements Grid */
    .achievements-grid-ultra {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .achievements-grid-ultra {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }
    
    /* Achievement Card */
    .achievement-card-ultra {
        position: relative;
        height: 450px;
        border-radius: 24px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }
    
    .achievement-card-ultra:hover {
        transform: translateY(-10px) scale(1.02);
    }
    
    /* Card Background */
    .achievement-card-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    
    .achievement-bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .achievement-card-ultra:hover .achievement-bg-image {
        transform: scale(1.15);
    }
    
    .achievement-bg-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    }
    
    .achievement-bg-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        z-index: 1;
    }
    
    .achievement-bg-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.3) 0%, rgba(20, 184, 166, 0.3) 100%);
        opacity: 0;
        transition: opacity 0.5s ease;
        z-index: 2;
    }
    
    .achievement-card-ultra:hover .achievement-bg-gradient {
        opacity: 1;
    }
    
    /* Card Overlay Content */
    .achievement-card-overlay {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 30px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, transparent 60%);
        transition: background 0.5s ease;
    }
    
    .achievement-card-ultra:hover .achievement-card-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, transparent 50%);
    }
    
    /* Icon Badge */
    .achievement-icon-badge {
        position: absolute;
        top: 25px;
        right: 25px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.9), rgba(20, 184, 166, 0.9));
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 4;
    }
    
    .achievement-icon-badge i {
        font-size: 24px;
        color: #fff;
    }
    
    .achievement-card-ultra:hover .achievement-icon-badge {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.6);
    }
    
    /* Title */
    .achievement-title-ultra {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease;
    }
    
    body:not(.dark-mode) .achievement-title-ultra {
        color: #fff !important;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.8);
    }
    
    .achievement-card-ultra:hover .achievement-title-ultra {
        transform: translateX(5px);
    }
    
    /* Description */
    .achievement-description-ultra {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.6;
        margin-bottom: 20px;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
        opacity: 0.9;
    }
    
    body:not(.dark-mode) .achievement-description-ultra {
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 1), 0 1px 3px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.9) !important;
        font-weight: 500 !important;
        opacity: 0.9 !important;
    }
    
    /* Force white color for all text in achievement description in light mode - Multiple selectors for maximum specificity */
    body:not(.dark-mode) .achievement-card-overlay .achievement-description-ultra,
    body:not(.dark-mode) .achievements-section-ultra .achievement-description-ultra,
    body:not(.dark-mode) .achievement-card-ultra .achievement-description-ultra,
    body:not(.dark-mode) .achievement-description-ultra {
        color: rgba(255, 255, 255, 0.9) !important;
        -webkit-text-fill-color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Override any general paragraph styles in light mode for achievement descriptions */
    body:not(.dark-mode) .achievement-card-overlay p.achievement-description-ultra {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Link Button */
    .achievement-link-ultra {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.9), rgba(20, 184, 166, 0.9));
        backdrop-filter: blur(10px);
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
        width: fit-content;
    }
    
    .achievement-link-ultra:hover {
        transform: translateX(5px) scale(1.05);
        box-shadow: 0 6px 30px rgba(6, 182, 212, 0.5);
        background: linear-gradient(135deg, rgba(6, 182, 212, 1), rgba(20, 184, 166, 1));
    }
    
    .achievement-link-ultra i {
        transition: transform 0.3s ease;
    }
    
    .achievement-link-ultra:hover i {
        transform: translateX(5px);
    }
    
    /* Glow Effect */
    .achievement-glow-effect {
        position: absolute;
        inset: -2px;
        border-radius: 24px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #8b5cf6);
        opacity: 0;
        filter: blur(20px);
        transition: opacity 0.5s ease;
        z-index: -1;
    }
    
    .achievement-card-ultra:hover .achievement-glow-effect {
        opacity: 0.6;
    }
    
    /* Light Mode Adjustments */
    body:not(.dark-mode) .achievement-card-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.85) 40%, transparent 70%);
    }
    
    body:not(.dark-mode) .achievement-card-ultra:hover .achievement-card-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.98) 0%, rgba(0, 0, 0, 0.9) 40%, transparent 60%);
    }
    
    /* Light Mode - Enhanced overlay for description area */
    body:not(.dark-mode) .achievement-card-overlay::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
        pointer-events: none;
        z-index: 0;
    }
    
    body:not(.dark-mode) .achievement-card-overlay > * {
        position: relative;
        z-index: 1;
    }
    
    /* Light Mode - Section Title and Description */
    body:not(.dark-mode) .achievements-section-ultra .text-gray-400 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .achievements-section-ultra h2 {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    /* General paragraph in achievements section - but NOT the description in cards */
    body:not(.dark-mode) .achievements-section-ultra > .container > .text-center p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    /* EXCEPTION: Force white for achievement description in cards */
    body:not(.dark-mode) .achievements-section-ultra p.achievement-description-ultra {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* ============================================
       FORMATIONS CARDS ULTRA MODERNES
       ============================================ */
    
    .formations-grid-modern {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    @media (max-width: 1024px) {
        .formations-grid-modern {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .formations-grid-modern {
            grid-template-columns: 1fr;
        }
    }
    
    .formation-card-modern {
        position: relative;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    body:not(.dark-mode) .formation-card-modern {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.15) !important;
    }
    
    .formation-card-modern:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 25px 60px rgba(6, 182, 212, 0.4);
    }
    
    .formation-card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.3) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    
    .formation-card-modern:hover .formation-card-glow {
        opacity: 1;
    }
    
    .formation-card-image-wrapper {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    }
    
    .formation-card-image-wrapper.no-image {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .formation-card-image-wrapper.no-image::before {
        content: '🏫';
        font-size: 4rem;
        opacity: 0.3;
    }
    
    .formation-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        filter: brightness(0.9) contrast(1.1);
    }
    
    .formation-card-modern:hover .formation-card-image {
        transform: scale(1.15) rotate(2deg);
        filter: brightness(1) contrast(1.2);
    }
    
    .formation-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.7) 100%);
        transition: background 0.5s ease;
    }
    
    .formation-card-modern:hover .formation-card-overlay {
        background: linear-gradient(180deg, transparent 0%, rgba(6, 182, 212, 0.2) 30%, rgba(0, 0, 0, 0.8) 100%);
    }
    
    .formation-card-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.9), rgba(20, 184, 166, 0.9));
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
        z-index: 2;
        transition: all 0.4s ease;
    }
    
    .formation-card-modern:hover .formation-card-badge {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 32px rgba(6, 182, 212, 0.6);
    }
    
    .formation-card-content {
        padding: 30px;
        position: relative;
        z-index: 1;
    }
    
    .formation-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        gap: 15px;
    }
    
    .formation-card-school {
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.3;
        flex: 1;
    }
    
    body:not(.dark-mode) .formation-card-school {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    .formation-card-period {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #06b6d4;
        white-space: nowrap;
    }
    
    .formation-card-period i {
        font-size: 0.75rem;
    }
    
    .formation-card-degree {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    body:not(.dark-mode) .formation-card-degree {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    .formation-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }
    
    .formation-card-progress {
        flex: 1;
        height: 6px;
        background: rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .formation-card-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        transition: width 0.6s ease;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }
    
    .formation-card-status {
        padding: 6px 14px;
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #22c55e;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .formation-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .formation-card-school {
            font-size: 1.2rem;
        }
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
    
    /* ============================================
       COMPÉTENCES SECTION ULTRA MODERNE
       ============================================ */
    
    .skills-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
    }
    
    @media (max-width: 768px) {
        .skills-grid-modern {
            grid-template-columns: 1fr;
        }
    }
    
    .skill-card-modern {
        position: relative;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 30px;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    body:not(.dark-mode) .skill-card-modern {
        background: rgba(255, 255, 255, 0.95) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.15) !important;
    }
    
    .skill-card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.3) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    
    .skill-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 25px 60px rgba(6, 182, 212, 0.4);
    }
    
    .skill-card-modern:hover .skill-card-glow {
        opacity: 1;
    }
    
    .skill-card-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }
    
    .skill-icon-wrapper {
        position: relative;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(6, 182, 212, 0.3);
        flex-shrink: 0;
        transition: all 0.4s ease;
    }
    
    .skill-card-modern:hover .skill-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    }
    
    .skill-icon {
        font-size: 2rem;
        color: #06b6d4;
    }
    
    .skill-icon-svg {
        width: 40px;
        height: 40px;
    }
    
    .skill-info {
        flex: 1;
    }
    
    .skill-name {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    
    body:not(.dark-mode) .skill-name {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    .skill-level-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.4);
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #06b6d4;
    }
    
    .skill-progress-wrapper {
        margin-bottom: 20px;
    }
    
    .skill-progress-track {
        width: 100%;
        height: 10px;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    
    .skill-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        position: relative;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
    }
    
    .skill-progress-glow {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: progress-shine 2s infinite;
    }
    
    @keyframes progress-shine {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    .skill-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }
    
    .skill-status {
        padding: 6px 14px;
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .skill-stars {
        display: flex;
        gap: 4px;
    }
    
    .skill-stars .fa-star {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .skill-stars .star-filled {
        color: #fbbf24;
        text-shadow: 0 0 8px rgba(251, 191, 36, 0.5);
    }
    
    body:not(.dark-mode) .skill-stars .fa-star {
        color: rgba(30, 41, 59, 0.3) !important;
    }
    
    body:not(.dark-mode) .skill-stars .star-filled {
        color: #f59e0b !important;
    }
    
    /* ============================================
       EXPÉRIENCES SECTION ULTRA MODERNE
       ============================================ */
    
    .experience-card-modern {
        position: relative;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 32px;
        padding: 40px;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }
    
    body:not(.dark-mode) .experience-card-modern {
        background: rgba(255, 255, 255, 0.95) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2) !important;
    }
    
    .experience-card-glow {
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.2) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    
    .experience-card-modern:hover {
        transform: translateY(-10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 30px 80px rgba(6, 182, 212, 0.4);
    }
    
    .experience-card-modern:hover .experience-card-glow {
        opacity: 1;
    }
    
    .experience-card-header {
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 35px;
        padding-bottom: 30px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.1);
        flex-wrap: wrap;
    }
    
    .experience-company-logo {
        position: relative;
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(6, 182, 212, 0.3);
        flex-shrink: 0;
        transition: all 0.4s ease;
    }
    
    .logo-glow {
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 20px;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: -1;
        filter: blur(10px);
    }
    
    .experience-card-modern:hover .experience-company-logo {
        transform: scale(1.1) rotate(5deg);
        border-color: rgba(6, 182, 212, 0.6);
    }
    
    .experience-card-modern:hover .logo-glow {
        opacity: 0.5;
    }
    
    .experience-company-logo i {
        font-size: 2.5rem;
        color: #06b6d4;
    }
    
    .experience-company-info {
        flex: 1;
        min-width: 200px;
    }
    
    .experience-company-name {
        font-size: 2rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    body:not(.dark-mode) .experience-company-name {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .experience-company-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #06b6d4;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .experience-company-link:hover {
        color: #14b8a6;
        transform: translateX(3px);
    }
    
    .experience-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .experience-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 16px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 2px solid;
        transition: all 0.3s ease;
    }
    
    .experience-badge-active {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }
    
    .experience-badge-fulltime {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        color: #06b6d4;
    }
    
    .experience-card-body {
        margin-bottom: 30px;
    }
    
    .experience-role {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.3rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 20px;
    }
    
    .experience-role i {
        font-size: 1.5rem;
    }
    
    .experience-period {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 35px;
        font-size: 1rem;
    }
    
    body:not(.dark-mode) .experience-period {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .period-text {
        font-weight: 700;
    }
    
    .period-separator {
        color: rgba(255, 255, 255, 0.3);
    }
    
    .period-duration {
        color: #22c55e;
        font-weight: 600;
    }
    
    .experience-achievements {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    .achievement-item {
        display: flex;
        gap: 20px;
        padding: 25px;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.15);
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    
    .achievement-item:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateX(5px);
    }
    
    .achievement-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #22c55e;
        font-size: 1.3rem;
    }
    
    .achievement-content {
        flex: 1;
    }
    
    .achievement-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 10px;
    }
    
    body:not(.dark-mode) .achievement-title {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    .achievement-description {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.7;
        font-size: 0.95rem;
    }
    
    body:not(.dark-mode) .achievement-description {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .experience-card-footer {
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.1);
    }
    
    .experience-stats {
        display: flex;
        justify-content: space-around;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 20px;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        min-width: 120px;
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-5px);
    }
    
    .stat-item i {
        font-size: 1.8rem;
        color: #06b6d4;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    body:not(.dark-mode) .stat-label {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    @media (max-width: 768px) {
        .experience-card-modern {
            padding: 25px;
        }
        
        .experience-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .experience-company-logo {
            width: 70px;
            height: 70px;
        }
        
        .experience-stats {
            flex-direction: column;
        }
        
        .stat-item {
            width: 100%;
        }
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

<!-- Compétences Section - Design Ultra Moderne -->
<section class="py-20 bg-gradient-to-b from-black to-gray-900 relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-teal-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-1 h-12 bg-gradient-to-b from-cyan-500 to-teal-500 rounded-full"></div>
                <h2 class="text-5xl md:text-6xl font-black">
                    <span class="gradient-text">Compétences & Capacités</span>
                </h2>
                <div class="w-1 h-12 bg-gradient-to-b from-cyan-500 to-teal-500 rounded-full"></div>
            </div>
            <p class="text-center text-gray-400 mb-16 max-w-3xl mx-auto text-lg">
                Maîtrise approfondie des technologies modernes du développement web, acquise à travers des années d'expérience pratique, 
                de formation continue et de projets réels. Chaque technologie que je maîtrise a été apprise et perfectionnée dans le cadre 
                de projets concrets, garantissant une compréhension pratique et applicable.
            </p>
        </div>
        
        <div class="max-w-7xl mx-auto">
            <div class="skills-grid-modern">
                @php
                    $skills = [
                        ['name' => 'HTML5 & CSS3', 'level' => 95, 'icon' => 'fab fa-html5', 'color' => 'from-orange-500 to-red-500', 'iconColor' => '#e34c26'],
                        ['name' => 'JavaScript & TypeScript', 'level' => 90, 'icon' => 'fab fa-js', 'color' => 'from-yellow-500 to-orange-500', 'iconColor' => '#f7df1e'],
                        ['name' => 'PHP & Laravel', 'level' => 88, 'icon' => 'fab fa-php', 'color' => 'from-purple-500 to-pink-500', 'iconColor' => '#777bb4'],
                        ['name' => 'React & Vue.js', 'level' => 85, 'icon' => 'fab fa-react', 'color' => 'from-blue-500 to-cyan-500', 'iconColor' => '#61dafb'],
                        ['name' => 'React Native', 'level' => 83, 'icon' => 'fab fa-react', 'color' => 'from-blue-400 to-cyan-400', 'iconColor' => '#61dafb'],
                        ['name' => 'Node.js & Express', 'level' => 82, 'icon' => 'fab fa-node', 'color' => 'from-green-500 to-teal-500', 'iconColor' => '#339933'],
                        ['name' => 'Java', 'level' => 80, 'icon' => 'fab fa-java', 'color' => 'from-orange-600 to-red-600', 'iconColor' => '#ed8b00'],
                        ['name' => 'Git & GitHub', 'level' => 92, 'icon' => 'fab fa-git-alt', 'color' => 'from-red-500 to-orange-500', 'iconColor' => '#f05032'],
                        ['name' => 'WordPress', 'level' => 87, 'icon' => 'fab fa-wordpress', 'color' => 'from-blue-600 to-cyan-600', 'iconColor' => '#21759b'],
                        ['name' => 'Intelligence Artificielle', 'level' => 75, 'icon' => 'fas fa-brain', 'color' => 'from-purple-600 to-pink-600', 'iconColor' => '#9333ea'],
                        ['name' => 'Python', 'level' => 80, 'icon' => 'fab fa-python', 'color' => 'from-blue-500 to-yellow-500', 'iconColor' => '#3776ab'],
                        ['name' => 'Flutter', 'level' => 78, 'icon' => 'flutter', 'color' => 'from-cyan-500 to-blue-500', 'iconColor' => '#02569b', 'isFlutter' => true],
                    ];
                @endphp
                
                @foreach($skills as $skill)
                <div class="skill-card-modern">
                    <div class="skill-card-glow"></div>
                    <div class="skill-card-header">
                        <div class="skill-icon-wrapper">
                            @if(isset($skill['isFlutter']) && $skill['isFlutter'])
                            <!-- Flutter Icon SVG - Logo Officiel -->
                            <svg class="skill-icon-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.314 0L2.3 12l3.85 3.85L14.314 7.68l3.85 3.85L13.02 16.68l-2.13-2.13-2.13 2.13L13.02 20.94l8.16-8.15 3.85 3.85L14.314 27.36L2.3 15.35l3.85-3.85L14.314 19.66l3.85-3.85L13.02 10.66l-2.13 2.13-2.13-2.13L13.02 6.4l8.16 8.15 3.85-3.85L14.314 0z" fill="#02569B"/>
                                <path d="M14.314 0L2.3 12l3.85 3.85L14.314 7.68l3.85 3.85L13.02 16.68l-2.13-2.13-2.13 2.13L13.02 20.94l8.16-8.15 3.85 3.85L14.314 27.36L2.3 15.35l3.85-3.85L14.314 19.66l3.85-3.85L13.02 10.66l-2.13 2.13-2.13-2.13L13.02 6.4l8.16 8.15 3.85-3.85L14.314 0z" fill="#60CAFC"/>
                            </svg>
                            @else
                            <i class="{{ $skill['icon'] }} skill-icon"></i>
                            @endif
                        </div>
                        <div class="skill-info">
                            <h3 class="skill-name">{{ $skill['name'] }}</h3>
                            <div class="skill-level-badge">{{ $skill['level'] }}%</div>
                        </div>
                    </div>
                    <div class="skill-progress-wrapper">
                        <div class="skill-progress-track">
                            <div class="skill-progress-fill" style="width: {{ $skill['level'] }}%">
                                <div class="skill-progress-glow"></div>
                            </div>
                        </div>
                    </div>
                    <div class="skill-card-footer">
                        <span class="skill-status">Maîtrisé</span>
                        <div class="skill-stars">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < ($skill['level'] / 20) ? 'star-filled' : 'star-empty' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Réalisations Section - Design Ultra Moderne avec Cards Overlay -->
@if(isset($showAchievementsSection) && $showAchievementsSection && isset($achievements) && $achievements->count() > 0)
<section class="achievements-section-ultra py-24 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="achievements-bg-animated">
        <div class="bg-orb bg-orb-1"></div>
        <div class="bg-orb bg-orb-2"></div>
        <div class="bg-orb bg-orb-3"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-4 mb-6">
                <div class="w-1.5 h-16 bg-gradient-to-b from-cyan-400 via-teal-400 to-cyan-400 rounded-full animate-pulse"></div>
                <h2 class="text-6xl md:text-7xl font-black tracking-tight">
                    <span class="achievements-title-gradient">Mes Réalisations</span>
                </h2>
                <div class="w-1.5 h-16 bg-gradient-to-b from-cyan-400 via-teal-400 to-cyan-400 rounded-full animate-pulse"></div>
            </div>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Découvrez quelques-unes de mes réalisations et projets qui témoignent de mon expertise et de ma passion pour le développement web.
            </p>
        </div>
        
        <!-- Achievements Grid -->
        <div class="max-w-7xl mx-auto">
            <div class="achievements-grid-ultra">
                @foreach($achievements as $index => $achievement)
                <div class="achievement-card-ultra" data-index="{{ $index }}">
                    <!-- Image Background with Overlay -->
                    @if($achievement->image)
                    <div class="achievement-card-bg">
                        <img src="{{ $achievement->image_url }}" alt="{{ $achievement->title }}" class="achievement-bg-image">
                        <div class="achievement-bg-overlay"></div>
                        <div class="achievement-bg-gradient"></div>
                    </div>
                    @else
                    <div class="achievement-card-bg">
                        <div class="achievement-bg-placeholder"></div>
                        <div class="achievement-bg-overlay"></div>
                        <div class="achievement-bg-gradient"></div>
                    </div>
                    @endif
                    
                    <!-- Card Content Overlay -->
                    <div class="achievement-card-overlay">
                        <!-- Icon Badge -->
                        @if($achievement->icon)
                        <div class="achievement-icon-badge">
                            <i class="{{ $achievement->icon }}"></i>
                        </div>
                        @endif
                        
                        <!-- Title -->
                        <h3 class="achievement-title-ultra">{{ $achievement->title }}</h3>
                        
                        <!-- Description -->
                        @if($achievement->description)
                        <p class="achievement-description-ultra">{{ Str::limit($achievement->description, 150) }}</p>
                        @endif
                        
                        <!-- Link Button -->
                        @if($achievement->link_url)
                        <a href="{{ $achievement->link_url }}" target="_blank" rel="noopener noreferrer" class="achievement-link-ultra">
                            <span>Voir le projet</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                    
                    <!-- Glow Effect -->
                    <div class="achievement-glow-effect"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Formation Section - Design Ultra Moderne -->
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
        
        <div class="max-w-7xl mx-auto">
            <div class="formations-grid-modern">
                @php
                    $formations = [
                        [
                            'school' => 'Access Code School',
                            'degree' => 'Spécialisation Développeur Web & Mobile',
                            'period' => '2019-2020',
                            'icon' => 'fas fa-code',
                            'color' => 'cyan',
                            'image' => 'https://cdn-s-www.estrepublicain.fr/images/0285FB16-360C-4210-92C8-B4FA09471706/NW_raw/au-senegal-la-jeune-generation-est-rompue-aux-metiers-du-numerique-elle-apprend-tres-vite-1669215275.jpg'
                        ],
                        [
                            'school' => 'Institut Supérieur D\'Informatique',
                            'degree' => 'Licence Professionnelle Génie Logiciel',
                            'period' => '2016-2019',
                            'icon' => 'fas fa-graduation-cap',
                            'color' => 'teal',
                            'image' => 'https://www.groupeisi.com/wp-content/uploads/2018/12/ISI-KM-2-1024x683.jpg'
                        ],
                        [
                            'school' => 'Lycée Seydina Limamou Laye',
                            'degree' => 'Baccalauréat Scientifique S2',
                            'period' => '2013-2016',
                            'icon' => 'fas fa-school',
                            'color' => 'purple',
                            'image' => 'https://www.ecolesausenegal.org/uploads/articles/images/1599178281.jpg'
                        ],
                    ];
                @endphp
                
                @foreach($formations as $formation)
                <div class="formation-card-modern">
                    <div class="formation-card-image-wrapper">
                        <img src="{{ $formation['image'] }}" alt="{{ $formation['school'] }}" class="formation-card-image" loading="lazy" onerror="this.style.display='none'; this.parentElement.classList.add('no-image');">
                        <div class="formation-card-overlay"></div>
                        <div class="formation-card-badge">
                            <i class="{{ $formation['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="formation-card-content">
                        <div class="formation-card-header">
                            <h3 class="formation-card-school">{{ $formation['school'] }}</h3>
                            <div class="formation-card-period">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $formation['period'] }}</span>
                            </div>
                        </div>
                        <p class="formation-card-degree">{{ $formation['degree'] }}</p>
                        <div class="formation-card-footer">
                            <div class="formation-card-progress">
                                <div class="formation-card-progress-bar" style="width: {{ 100 - ($loop->index * 10) }}%"></div>
                            </div>
                            <span class="formation-card-status">Diplômé</span>
                        </div>
                    </div>
                    <div class="formation-card-glow"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Expériences Section - Design Ultra Moderne -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-black relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-1/4 right-0 w-96 h-96 bg-cyan-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-1/4 left-0 w-96 h-96 bg-teal-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-1 h-12 bg-gradient-to-b from-cyan-500 to-teal-500 rounded-full"></div>
                <h2 class="text-5xl md:text-6xl font-black">
                    <span class="gradient-text">Expériences Professionnelles</span>
                </h2>
                <div class="w-1 h-12 bg-gradient-to-b from-cyan-500 to-teal-500 rounded-full"></div>
            </div>
            <p class="text-center text-gray-400 mb-16 max-w-3xl mx-auto text-lg">
                Mon parcours professionnel dans le développement web témoigne d'une évolution constante, de l'apprentissage des bases 
                à la maîtrise des technologies avancées. Chaque expérience professionnelle a contribué à façonner mon expertise et ma 
                vision du développement web moderne.
            </p>
        </div>
        
        <div class="max-w-6xl mx-auto">
            <div class="experience-card-modern">
                <div class="experience-card-glow"></div>
                <div class="experience-card-header">
                    <div class="experience-company-logo">
                        <div class="logo-glow"></div>
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="experience-company-info">
                        <h3 class="experience-company-name">Sunucode</h3>
                        <a href="https://sunucode.com/" target="_blank" class="experience-company-link">
                            <i class="fas fa-external-link-alt"></i>
                            <span>sunucode.com</span>
                        </a>
                    </div>
                    <div class="experience-badges">
                        <span class="experience-badge experience-badge-active">
                            <i class="fas fa-briefcase"></i>
                            <span>En cours</span>
                        </span>
                        <span class="experience-badge experience-badge-fulltime">
                            <i class="fas fa-clock"></i>
                            <span>Temps plein</span>
                        </span>
                    </div>
                </div>
                
                <div class="experience-card-body">
                    <div class="experience-role">
                        <i class="fas fa-code"></i>
                        <span>Développeur Full-Stack & Formateur</span>
                    </div>
                    
                    <div class="experience-period">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="period-text">2020 - Présent</span>
                        <span class="period-separator">•</span>
                        <span class="period-duration">{{ \Carbon\Carbon::parse('2020-01-01')->diffForHumans(null, true) }}</span>
                    </div>
                    
                    <div class="experience-achievements">
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="achievement-content">
                                <h4 class="achievement-title">Développement Full-Stack</h4>
                                <p class="achievement-description">
                                    Développement d'applications web complexes avec Laravel, React et Vue.js, incluant la conception de 
                                    systèmes backend robustes, la création d'interfaces utilisateur modernes et réactives, et l'intégration 
                                    d'APIs RESTful pour des solutions complètes et performantes.
                                </p>
                            </div>
                        </div>
                        
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="achievement-content">
                                <h4 class="achievement-title">Formation & Mentorat</h4>
                                <p class="achievement-description">
                                    Formation et accompagnement de développeurs juniors, avec la création de programmes de mentorat, 
                                    l'organisation de sessions de code review, et le partage de bonnes pratiques pour accélérer leur 
                                    progression professionnelle et technique.
                                </p>
                            </div>
                        </div>
                        
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="achievement-content">
                                <h4 class="achievement-title">Gestion de Projets</h4>
                                <p class="achievement-description">
                                    Gestion de projets et architecture logicielle, incluant la planification de sprints, la coordination 
                                    d'équipes multidisciplinaires, la conception d'architectures scalables, et l'optimisation des performances 
                                    pour garantir la qualité et la maintenabilité des applications.
                                </p>
                            </div>
                        </div>
                        
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="achievement-content">
                                <h4 class="achievement-title">Solutions E-commerce</h4>
                                <p class="achievement-description">
                                    Développement de solutions e-commerce et applications métier sur mesure, avec intégration de systèmes 
                                    de paiement, gestion de stocks, et interfaces d'administration complètes pour répondre aux besoins 
                                    spécifiques des clients.
                                </p>
                            </div>
                        </div>
                        
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="achievement-content">
                                <h4 class="achievement-title">Optimisation & SEO</h4>
                                <p class="achievement-description">
                                    Optimisation SEO et performance web, avec l'implémentation de techniques avancées de référencement, 
                                    l'optimisation des temps de chargement, et l'amélioration de l'expérience utilisateur pour maximiser 
                                    la visibilité et l'engagement.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="experience-card-footer">
                    <div class="experience-stats">
                        <div class="stat-item">
                            <i class="fas fa-calendar-check"></i>
                            <span class="stat-value">{{ number_format(\Carbon\Carbon::parse('2020-01-01')->diffInDays() / 365.25, 2, '.', '') }}</span>
                            <span class="stat-label">Années</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-project-diagram"></i>
                            <span class="stat-value">120+</span>
                            <span class="stat-label">Projets</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <span class="stat-value">300+</span>
                            <span class="stat-label">Formés</span>
                        </div>
                    </div>
                </div>
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
