@extends('layouts.app')

@section('title', trans('app.formations.cybersecurite.title') . ' | NiangProgrammeur')

@section('styles')
<!-- Prism.js pour la coloration syntaxique -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<style>
    * {
        box-sizing: border-box;
    }
    html {
        overflow-x: hidden;
        scroll-behavior: smooth;
        height: 100%;
    }
    body {
        background-color: #fff !important;
        color: #000 !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        height: 100%;
    }
    .tutorial-header {
        position: relative;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
        color: white;
        padding: 120px 20px 80px;
        text-align: center;
        width: 100%;
        margin: 0;
        overflow: hidden;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tutorial-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(55, 118, 171, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(55, 118, 171, 0.15) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .tutorial-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="60" height="60" fill="url(%23grid)"/></svg>');
        opacity: 0.4;
        pointer-events: none;
    }
    
    .tutorial-header-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }
    
    .tutorial-header h1 {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    
    .tutorial-header p {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s ease;
        line-height: 1.6;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
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
    
    body.dark-mode .tutorial-header {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.95) 100%),
                    url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80') center/cover no-repeat;
        background-attachment: fixed;
    }
    .tutorial-content {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        width: 100%;
        min-height: calc(100vh - 70px);
    }
    .content-wrapper {
        display: flex;
        gap: 20px;
        padding: 20px;
        width: 100%;
        margin: 0;
        align-items: flex-start;
        position: relative;
    }
    .sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 15px 25px 25px 25px;
        border-radius: 15px;
        position: -webkit-sticky;
        position: sticky;
        top: 60px;
        align-self: flex-start;
        height: calc(100vh - 60px);
        max-height: calc(100vh - 60px);
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(55, 118, 171, 0.2);
        z-index: 10;
    }
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #ff6b35 0%, #d45a2a 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #d45a2a 0%, #b84a1f 100%);
    }
    .sidebar h3 {
        color: #ff6b35;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(55, 118, 171, 0.2);
    }
    .sidebar a {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #2c3e50;
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 6px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    .sidebar a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #ff6b35;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #ff6b35;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #ff6b35 0%, #d45a2a 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(55, 118, 171, 0.3);
        transform: translateX(5px);
    }
    .sidebar a.active::before {
        transform: scaleY(1);
        background: white;
    }
    
    .sidebar-close-btn {
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(55, 118, 171, 0.1) !important;
        border: 2px solid rgba(55, 118, 171, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(55, 118, 171, 0.2) !important;
        border-color: rgba(55, 118, 171, 0.5) !important;
        transform: rotate(90deg) scale(1.1);
    }
    
    /* FORCER le sidebar à ne PAS être sticky en mobile - PROTECTION MAXIMALE */
    @media (max-width: 992px) {
        .sidebar,
        .sidebar#tutorialSidebar,
        aside.sidebar,
        .content-wrapper .sidebar {
            position: fixed !important;
            top: auto !important;
            align-self: auto !important;
            flex-shrink: 0 !important;
            width: 85% !important;
            max-width: 400px !important;
        }
    }
    
    /* Menu Burger Mobile - Caché par défaut sur desktop */
    .sidebar-toggle-btn {
        display: none !important;
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ff6b35, #d45a2a);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(55, 118, 171, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #d45a2a, #b84a1f);
        transform: rotate(90deg);
    }
    
    .sidebar-overlay {
        display: none !important;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 9998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .sidebar-overlay.active {
        display: block !important;
        opacity: 1;
    }
    
    .main-content {
        flex: 1;
        min-width: 0;
        background: white;
        padding: 30px;
        border-radius: 5px;
        overflow-x: hidden;
        max-width: calc(100% - 300px);
    }
    .main-content h1 {
        color: #000;
        font-size: 42px;
        margin-bottom: 10px;
    }
    .main-content h2 {
        color: #000;
        font-size: 32px;
        margin-top: 30px;
        margin-bottom: 15px;
        padding-top: 20px;
        border-top: 2px solid rgba(55, 118, 171, 0.2);
    }
    .main-content h3 {
        color: #000;
        font-size: 24px;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    .main-content p {
        color: #000;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 16px;
    }
    .example-box {
        background-color: #E7E9EB;
        border-left: 4px solid #ff6b35;
        padding: 20px;
        margin: 20px 0;
        border-radius: 5px;
    }
    .example-box h3 {
        color: #000;
        margin-bottom: 10px;
    }
    .code-box {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #ff6b35;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(55, 118, 171, 0.1);
        position: relative;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    .code-box::before {
        content: 'cybersecurite';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #ff6b35;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 1;
    }
    
    /* Bouton de copie - Même taille que le label cybersecurite */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 100px;
        background: #ff6b35;
        color: white;
        border: none;
        padding: 2px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        height: auto;
        line-height: 1.4;
    }
    
    .copy-code-btn:hover {
        background: #2A5A87;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }
    
    .copy-code-btn:active {
        transform: translateY(0);
    }
    
    .copy-code-btn.copied {
        background: rgba(34, 197, 94, 0.9);
        padding: 2px 10px;
    }
    
    .copy-code-btn.copied:hover {
        background: rgba(34, 197, 94, 1);
    }
    
    .copy-code-btn i {
        font-size: 12px;
    }
    .code-box pre {
        margin: 0;
        padding: 0;
        background: transparent !important;
        overflow-x: auto;
    }
    .code-box pre code {
        display: block;
        padding: 0;
        color: #e2e8f0;
        line-height: 1.6;
        font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
        font-size: 14px;
        white-space: pre;
        overflow-x: auto;
    }
    .note-box {
        background-color: #ffffcc;
        border-left: 4px solid #ffeb3b;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
    }
    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
    .nav-btn {
        background-color: #ff6b35;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #d45a2a;
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.3);
    }
        @media (max-width: 992px) {
            .tutorial-content {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            
            .content-wrapper {
                flex-direction: column;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
            
            /* Sidebar caché par défaut en mobile - FORCER avec toutes les propriétés */
            .sidebar,
            .sidebar#tutorialSidebar,
            aside.sidebar {
                display: none !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 85% !important;
                max-width: 400px !important;
                height: 70vh !important;
                max-height: 600px !important;
                border-radius: 20px 20px 0 0 !important;
                transform: translateY(100%) !important;
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease !important;
                z-index: 9999 !important;
                box-shadow: 0 -10px 50px rgba(0, 0, 0, 0.3) !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                opacity: 0 !important;
                visibility: hidden !important;
                top: auto !important;
                align-self: auto !important;
                flex-shrink: 0 !important;
            }
            
            /* Sidebar visible quand actif */
            .sidebar.active,
            .sidebar#tutorialSidebar.active,
            aside.sidebar.active {
                display: block !important;
                transform: translateY(0) !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Bouton burger visible en mobile - FORCER */
            .sidebar-toggle-btn,
            button.sidebar-toggle-btn,
            #sidebarToggle {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .main-content {
                width: 100% !important;
                max-width: 100% !important;
                padding: 20px;
                box-sizing: border-box;
                margin: 0 !important;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100% !important;
                max-width: 100% !important;
                height: 80vh;
                max-height: 80vh;
                border-radius: 25px 25px 0 0;
            }
            
            .sidebar-toggle-btn {
                display: flex !important;
                width: 55px;
                height: 55px;
                font-size: 22px;
                bottom: 15px;
                left: 15px;
            }
        }
    /* Dark Mode Styles */
    body.dark-mode {
        background-color: #0a0a0f !important;
        color: #e5e7eb !important;
    }
    
    body.dark-mode .tutorial-content {
        background: #0a0a0f;
    }
    
    body.dark-mode .sidebar {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%);
        border-color: rgba(55, 118, 171, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .sidebar h3 {
        color: #ff6b35;
        border-bottom-color: rgba(55, 118, 171, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.2) 0%, rgba(55, 118, 171, 0.1) 100%);
        color: #ff6b35;
    }
    
    body.dark-mode .sidebar a.active {
        color: white;
    }
    
    body.dark-mode .sidebar a.active::before {
        background: rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode .main-content {
        background: rgba(15, 23, 42, 0.5);
    }
    
    body.dark-mode .main-content h1,
    body.dark-mode .main-content h2,
    body.dark-mode .main-content h3 {
        color: #ffffff !important;
    }
    
    body.dark-mode .main-content p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .main-content ul,
    body.dark-mode .main-content ol {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .main-content li {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .example-box {
        background-color: rgba(30, 41, 59, 0.8);
        border-left-color: #ff6b35;
    }
    
    body.dark-mode .example-box h3 {
        color: #ffffff !important;
    }
    
    body.dark-mode .example-box p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .note-box {
        background-color: rgba(251, 191, 36, 0.15);
        border-left-color: #fbbf24;
    }
    
    body.dark-mode .note-box p {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .code-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-color: rgba(55, 118, 171, 0.5);
    }
    
    body.dark-mode .code-box pre code {
        color: #e2e8f0;
    }
    
    body.dark-mode .nav-buttons {
        border-top-color: rgba(255, 255, 255, 0.2);
    }
    
    body.dark-mode .nav-btn {
        background-color: #ff6b35;
        color: white;
    }
    
    body.dark-mode .nav-btn:hover {
        background-color: #d45a2a;
    }
    
    body.dark-mode .sidebar-toggle-btn {
        background: linear-gradient(135deg, #ff6b35, #d45a2a);
    }
    
    body.dark-mode .sidebar-toggle-btn:hover {
        box-shadow: 0 12px 35px rgba(55, 118, 171, 0.6);
    }
    
    body.dark-mode .sidebar-overlay {
        background: rgba(0, 0, 0, 0.8);
    }
    
    body.dark-mode [style*="color: #000"],
    body.dark-mode [style*="color:#000"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Dark mode pour la section progression/CTA */
    body.dark-mode [style*="background: linear-gradient(135deg, rgba(4, 170, 109"] {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.2) 0%, rgba(4, 170, 109, 0.1) 100%) !important;
        border-color: rgba(4, 170, 109, 0.4) !important;
    }
    
    body.dark-mode [style*="color: #2c3e50"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode [style*="background: white"] {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: rgba(4, 170, 109, 0.4) !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode [style*="background: linear-gradient(135deg, rgba(4, 170, 109, 0.15)"] {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.25) 0%, rgba(6, 182, 212, 0.15) 100%) !important;
        border-color: rgba(4, 170, 109, 0.5) !important;
    }
    
    /* Animations pour les notifications */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="tutorial-header">
    <div class="tutorial-header-content">
        <h1>
        <i class="fab fa-cybersecurite" style="margin-right: 15px;"></i>
            {{ trans('app.formations.cybersecurite.title') }}
    </h1>
        <p>{{ trans('app.formations.cybersecurite.subtitle') }}</p>
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button data-favorite 
                    data-favorite-type="formation" 
                    data-favorite-slug="cybersecurite" 
                    data-favorite-name="{{ trans('app.formations.cybersecurite.title') }}"
                    style="background: rgba(255, 255, 255, 0.2); border: 2px solid rgba(255, 255, 255, 0.4); color: white; padding: 12px 24px; border-radius: 10px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; backdrop-filter: blur(10px);">
                <i class="far fa-heart"></i>
                <span>Ajouter aux favoris</span>
            </button>
            <a href="{{ route('monetization.donations') }}" 
               style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.3)); border: 2px solid rgba(239, 68, 68, 0.5); color: white; padding: 12px 24px; border-radius: 10px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; backdrop-filter: blur(10px); text-decoration: none; cursor: pointer;"
               onmouseover="this.style.background='linear-gradient(135deg, rgba(239, 68, 68, 0.5), rgba(220, 38, 38, 0.5))'; this.style.borderColor='rgba(239, 68, 68, 0.7)'; this.style.transform='translateY(-2px)';"
               onmouseout="this.style.background='linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.3))'; this.style.borderColor='rgba(239, 68, 68, 0.5)'; this.style.transform='translateY(0)';">
                <i class="fas fa-heart"></i>
                <span>Faire un don</span>
            </a>
        </div>
    </div>
</div>

<!-- Progression ou Message d'incitation -->
@auth
    @if($progress)
    <div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
        <div style="background: linear-gradient(135deg, rgba(4, 170, 109, 0.1) 0%, rgba(4, 170, 109, 0.05) 100%); border: 2px solid rgba(4, 170, 109, 0.3); border-radius: 15px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(4, 170, 109, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1; min-width: 250px;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #04AA6D; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-chart-line"></i>
                        {{ trans('app.formations.progress.title') }}
                    </h3>
                    <div style="width: 100%; height: 12px; background: rgba(4, 170, 109, 0.2); border-radius: 6px; overflow: hidden; margin-bottom: 0.75rem;">
                        <div id="progress-bar-fill" style="height: 100%; width: {{ $progress->progress_percentage }}%; background: linear-gradient(90deg, #04AA6D, #06b6d4); transition: width 0.6s ease; border-radius: 6px;"></div>
                    </div>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.9rem; color: #2c3e50;">
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-percentage" style="color: #04AA6D;"></i>
                            <strong id="progress-percentage-text">{{ $progress->progress_percentage }}%</strong> {{ trans('app.formations.progress.completed') }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-clock" style="color: #06b6d4;"></i>
                            <strong id="time-spent-text">{{ $progress->time_spent_minutes }}</strong> {{ trans('app.profile.dashboard.overview.minutes') }}
                        </span>
                        @if($progress->completed_at)
                            <span style="display: flex; align-items: center; gap: 0.5rem; color: #10b981;">
                                <i class="fas fa-check-circle"></i>
                                {{ trans('app.formations.progress.completed_on') }} {{ $progress->completed_at->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="{{ route('dashboard.formations') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(4, 170, 109, 0.3);">
                        <i class="fas fa-tachometer-alt"></i>
                        {{ trans('app.formations.progress.view_dashboard') }}
                    </a>
                    @if($progress->progress_percentage < 100)
                    <a href="#intro" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #04AA6D; border: 2px solid #04AA6D; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease;">
                        <i class="fas fa-arrow-down"></i>
                        {{ trans('app.formations.progress.continue') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
@else
    <div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
        <div style="background: linear-gradient(135deg, rgba(4, 170, 109, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%); border: 2px solid rgba(4, 170, 109, 0.4); border-radius: 15px; padding: 2rem; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.2); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(4, 170, 109, 0.2) 0%, transparent 70%); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: start; gap: 1.5rem; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #04AA6D, #06b6d4); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; box-shadow: 0 4px 15px rgba(4, 170, 109, 0.4);">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin: 0;">
                                {{ trans('app.formations.cta.title') }}
                            </h3>
                        </div>
                        <p style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 1.5rem; line-height: 1.7;">
                            {{ trans('app.formations.cta.description') }}
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit1') }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit2') }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #2c3e50; font-size: 0.95rem;">
                                <i class="fas fa-check-circle" style="color: #04AA6D;"></i>
                                <span>{{ trans('app.formations.cta.benefit3') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(4, 170, 109, 0.4);">
                                <i class="fas fa-user-plus"></i>
                                {{ trans('app.formations.cta.create_account') }}
                            </a>
                            <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: white; color: #04AA6D; border: 2px solid #04AA6D; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ trans('app.formations.cta.login') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth

<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.cybersecurite.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.cybersecurite.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #ff6b35; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.cybersecurite.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'functions', 'lists', 'modules'];
            @endphp
            @foreach(trans('app.formations.cybersecurite.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.cybersecurite.intro_title') }}</h1>
            <p>{!! trans('app.formations.cybersecurite.intro_text') !!}</p>

            <h3>{{ trans('app.formations.cybersecurite.what_is_title') }}</h3>
            <p>{!! trans('app.formations.cybersecurite.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.why_popular_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.why_popular_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.cybersecurite.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.cybersecurite.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cybersecurite.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.cybersecurite.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.cybersecurite.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cybersecurite.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.cybersecurite.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.cybersecurite.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.cybersecurite.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cybersecurite.use_cases_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $icon = '';
                    $label = '';
                    $description = '';
                    if (preg_match('/^([🌐📊🤖🔧📱🎮🔬]) (.+)$/u', $item, $matches)) {
                        $icon = $matches[1];
                        $rest = $matches[2];
                        $labelParts = explode(' - ', $rest);
                        $label = $labelParts[0] ?? '';
                        $description = $labelParts[1] ?? '';
                    } else {
                        $labelParts = explode(' - ', $item);
                        $label = $labelParts[0] ?? '';
                        $description = $labelParts[1] ?? '';
                    }
                @endphp
                <li>@if($icon){{ $icon }} @endif<strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h2 id="syntax">{{ trans('app.formations.cybersecurite.syntax_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.syntax_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>🔐 Les Principes Fondamentaux de la Sécurité</h3>
            <p>La cybersécurité repose sur trois principes fondamentaux connus sous le nom de <strong>Triade CIA</strong> :</p>
            
            <div class="example-box" style="background-color: #e3f2fd; border-left-color: #2196f3;">
                <h3 style="color: #000; margin-bottom: 15px;">📋 La Triade CIA</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Confidentialité (Confidentiality)</strong> : Garantir que les informations ne sont accessibles qu'aux personnes autorisées. Cela implique le chiffrement des données sensibles, la gestion des accès, et la protection contre les fuites d'informations.</li>
                    <li><strong>Intégrité (Integrity)</strong> : Assurer que les données n'ont pas été modifiées, altérées ou supprimées de manière non autorisée. Les mécanismes incluent les signatures numériques, les checksums, et les contrôles d'accès stricts.</li>
                    <li><strong>Disponibilité (Availability)</strong> : Garantir que les systèmes et données sont accessibles quand ils sont nécessaires. Cela implique la protection contre les attaques par déni de service (DDoS), la redondance, et la planification de la continuité d'activité.</li>
                </ul>
            </div>

            <h3>🛡️ Défense en Profondeur</h3>
            <p>La défense en profondeur (Defense in Depth) est une stratégie de sécurité qui utilise plusieurs couches de protection. Si une couche échoue, les autres continuent de protéger le système :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Couche physique</strong> : Contrôle d'accès aux locaux, caméras de surveillance, badges d'accès</li>
                <li><strong>Couche réseau</strong> : Pare-feu, systèmes de détection d'intrusion (IDS), segmentation réseau</li>
                <li><strong>Couche système</strong> : Antivirus, correctifs de sécurité, durcissement des systèmes</li>
                <li><strong>Couche application</strong> : Validation des entrées, authentification forte, gestion des sessions</li>
                <li><strong>Couche données</strong> : Chiffrement, sauvegardes, classification des données</li>
                <li><strong>Couche utilisateur</strong> : Formation, sensibilisation, politiques de sécurité</li>
            </ul>

            <h2 id="variables">{{ trans('app.formations.cybersecurite.variables_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.variables_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>🔍 Types de Vulnérabilités Courantes</h3>
            <p>Les vulnérabilités sont des faiblesses dans les systèmes qui peuvent être exploitées par des attaquants. Voici les principales catégories :</p>

            <div class="example-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
                <h3 style="color: #000; margin-bottom: 15px;">⚠️ Vulnérabilités Web</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Injection SQL</strong> : Permet à un attaquant d'exécuter des commandes SQL malveillantes en injectant du code dans les requêtes. Protection : requêtes paramétrées, validation des entrées.</li>
                    <li><strong>XSS (Cross-Site Scripting)</strong> : Injection de scripts malveillants dans des pages web vues par d'autres utilisateurs. Protection : échappement des données, Content Security Policy (CSP).</li>
                    <li><strong>CSRF (Cross-Site Request Forgery)</strong> : Force un utilisateur authentifié à exécuter des actions non désirées. Protection : tokens CSRF, vérification du referer.</li>
                    <li><strong>Authentification faible</strong> : Mots de passe faibles, absence d'authentification multi-facteurs. Protection : politiques de mots de passe robustes, MFA obligatoire.</li>
                </ul>
            </div>

            <div class="example-box" style="background-color: #f8d7da; border-left-color: #dc3545;">
                <h3 style="color: #000; margin-bottom: 15px;">🚨 Vulnérabilités Système</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Privilèges excessifs</strong> : Utilisateurs ou processus avec plus de permissions que nécessaire. Protection : principe du moindre privilège.</li>
                    <li><strong>Exposition de données</strong> : Données sensibles accessibles sans autorisation. Protection : chiffrement, contrôle d'accès, classification des données.</li>
                    <li><strong>Configuration incorrecte</strong> : Paramètres de sécurité par défaut ou mal configurés. Protection : durcissement des systèmes, audits de configuration.</li>
                    <li><strong>Logiciels obsolètes</strong> : Versions non mises à jour avec des vulnérabilités connues. Protection : gestion des correctifs, inventaire des logiciels.</li>
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.cybersecurite.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.datatypes_text') }}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.datatypes_list_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h3>🔐 Chiffrement Symétrique</h3>
            <p>Le chiffrement symétrique utilise la même clé pour chiffrer et déchiffrer les données. C'est rapide et efficace pour de grandes quantités de données :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>AES (Advanced Encryption Standard)</strong> : Standard le plus utilisé, avec des clés de 128, 192 ou 256 bits. Utilisé pour chiffrer les données au repos et en transit.</li>
                <li><strong>DES/3DES</strong> : Ancien standard, maintenant obsolète. 3DES utilise trois clés DES pour améliorer la sécurité.</li>
                <li><strong>ChaCha20</strong> : Alternative moderne à AES, particulièrement efficace sur les processeurs mobiles.</li>
            </ul>

            <h3>🔑 Chiffrement Asymétrique</h3>
            <p>Le chiffrement asymétrique utilise une paire de clés (publique et privée). La clé publique chiffre, la clé privée déchiffre :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>RSA</strong> : Algorithme basé sur la factorisation de grands nombres premiers. Utilisé pour SSL/TLS, signatures numériques.</li>
                <li><strong>ECC (Elliptic Curve Cryptography)</strong> : Plus efficace que RSA, utilise des courbes elliptiques. Utilisé dans les certificats modernes.</li>
                <li><strong>Diffie-Hellman</strong> : Permet l'échange sécurisé de clés sur un canal non sécurisé. Base de nombreux protocoles sécurisés.</li>
            </ul>

            <h3>📝 Hash et Intégrité</h3>
            <p>Les fonctions de hachage transforment des données de taille variable en une valeur de taille fixe (hash). Utilisées pour vérifier l'intégrité :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>SHA-256</strong> : Standard actuel pour les hash sécurisés. Utilisé dans Bitcoin, Git, et de nombreux systèmes.</li>
                <li><strong>MD5</strong> : Ancien algorithme, maintenant considéré comme non sécurisé. À éviter pour les applications critiques.</li>
                <li><strong>HMAC</strong> : Hash-based Message Authentication Code. Combine une fonction de hash avec une clé secrète pour l'authentification.</li>
            </ul>

            <h2 id="operators">{{ trans('app.formations.cybersecurite.operators_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.operators_text') }}</p>

            <h3>🌐 Architecture de Sécurité Réseau</h3>
            <p>La sécurité réseau protège les infrastructures contre les attaques et les accès non autorisés :</p>

            <div class="example-box" style="background-color: #e8f5e9; border-left-color: #4caf50;">
                <h3 style="color: #000; margin-bottom: 15px;">🛡️ Composants de Sécurité Réseau</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Pare-feu (Firewall)</strong> : Filtre le trafic réseau selon des règles prédéfinies. Peut être basé sur les ports, les adresses IP, ou le contenu. Types : pare-feu réseau, pare-feu applicatif (WAF), pare-feu personnel.</li>
                    <li><strong>VPN (Virtual Private Network)</strong> : Crée un tunnel chiffré pour sécuriser les communications à distance. Protocoles : IPSec, OpenVPN, WireGuard.</li>
                    <li><strong>IDS/IPS</strong> : Systèmes de détection (IDS) et de prévention (IPS) d'intrusion. Surveillent le trafic pour détecter les activités suspectes et peuvent bloquer automatiquement les attaques.</li>
                    <li><strong>Segmentation réseau</strong> : Divise le réseau en zones séparées (DMZ, réseau interne, réseau invité) pour limiter la propagation des attaques.</li>
                    <li><strong>NAC (Network Access Control)</strong> : Contrôle l'accès au réseau en vérifiant l'identité et la conformité des appareils avant de leur accorder l'accès.</li>
                </ul>
            </div>

            <h3>🔒 Protocoles de Sécurité</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>SSL/TLS</strong> : Protocoles de chiffrement pour sécuriser les communications web. HTTPS utilise TLS pour chiffrer le trafic HTTP.</li>
                <li><strong>SSH (Secure Shell)</strong> : Protocole pour l'accès sécurisé aux serveurs distants. Remplace Telnet et FTP non sécurisés.</li>
                <li><strong>DNSSEC</strong> : Extension de DNS qui ajoute l'authentification pour prévenir les attaques de détournement DNS.</li>
                <li><strong>802.1X</strong> : Standard pour l'authentification des appareils sur les réseaux locaux (LAN). Utilisé dans les réseaux d'entreprise.</li>
            </ul>

            <h2 id="conditions">{{ trans('app.formations.cybersecurite.conditions_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.conditions_text') !!}</p>

            <h3>🔐 Sécurité des Applications Web</h3>
            <p>La sécurité applicative protège les applications contre les vulnérabilités et les attaques :</p>

            <div class="example-box" style="background-color: #fff3e0; border-left-color: #ff9800;">
                <h3 style="color: #000; margin-bottom: 15px;">🛡️ Bonnes Pratiques de Sécurité Applicative</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Validation des entrées</strong> : Toujours valider et nettoyer toutes les données entrantes (formulaires, API, fichiers uploadés). Utiliser des whitelists plutôt que des blacklists.</li>
                    <li><strong>Authentification forte</strong> : Implémenter l'authentification multi-facteurs (MFA), utiliser des mots de passe robustes, limiter les tentatives de connexion.</li>
                    <li><strong>Gestion des sessions</strong> : Utiliser des tokens de session sécurisés, implémenter une expiration automatique, régénérer les tokens après connexion.</li>
                    <li><strong>Protection CSRF</strong> : Utiliser des tokens CSRF pour toutes les actions modifiant l'état (POST, PUT, DELETE).</li>
                    <li><strong>Content Security Policy (CSP)</strong> : Définir des politiques strictes pour limiter les sources de contenu exécutable et prévenir les attaques XSS.</li>
                    <li><strong>Gestion des erreurs</strong> : Ne pas exposer d'informations sensibles dans les messages d'erreur. Logger les erreurs de manière sécurisée.</li>
                </ul>
            </div>

            <h3>🔍 OWASP Top 10</h3>
            <p>L'OWASP (Open Web Application Security Project) publie régulièrement une liste des 10 risques de sécurité les plus critiques pour les applications web :</p>
            <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Injection</strong> : SQL, NoSQL, OS command injection</li>
                <li><strong>Authentification défaillante</strong> : Mots de passe faibles, sessions non sécurisées</li>
                <li><strong>Exposition de données sensibles</strong> : Données non chiffrées, informations exposées</li>
                <li><strong>Composants avec vulnérabilités connues</strong> : Bibliothèques et frameworks obsolètes</li>
                <li><strong>Contrôle d'accès défaillant</strong> : Permissions insuffisantes, élévation de privilèges</li>
                <li><strong>Configuration de sécurité incorrecte</strong> : Paramètres par défaut, erreurs de configuration</li>
                <li><strong>XSS (Cross-Site Scripting)</strong> : Injection de scripts malveillants</li>
                <li><strong>Désérialisation non sécurisée</strong> : Exécution de code à distance</li>
                <li><strong>Journalisation et surveillance insuffisantes</strong> : Manque de détection des incidents</li>
                <li><strong>SSRF (Server-Side Request Forgery)</strong> : Forcer le serveur à faire des requêtes non autorisées</li>
            </ol>

            <h2 id="loops">{{ trans('app.formations.cybersecurite.loops_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.loops_text') !!}</p>

            <h3>🎯 Méthodologie de Test de Pénétration</h3>
            <p>Les tests de pénétration (pentest) évaluent la sécurité d'un système en simulant des attaques réelles :</p>

            <div class="example-box" style="background-color: #f3e5f5; border-left-color: #9c27b0;">
                <h3 style="color: #000; margin-bottom: 15px;">📋 Phases d'un Pentest</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Reconnaissance</strong> : Collecte d'informations sur la cible (OSINT, scan de ports, énumération). Identifier les systèmes, services, et technologies utilisées.</li>
                    <li><strong>Scanning</strong> : Analyse approfondie pour identifier les vulnérabilités. Utilisation d'outils comme Nmap, Nessus, OpenVAS.</li>
                    <li><strong>Gaining Access</strong> : Exploitation des vulnérabilités identifiées pour obtenir un accès initial au système.</li>
                    <li><strong>Maintaining Access</strong> : Installation de backdoors ou de persistance pour maintenir l'accès même après correction des vulnérabilités initiales.</li>
                    <li><strong>Covering Tracks</strong> : Effacement des traces d'activité (logs, fichiers temporaires). Important pour comprendre comment un attaquant pourrait masquer son activité.</li>
                    <li><strong>Reporting</strong> : Documentation complète des vulnérabilités trouvées, des risques associés, et des recommandations de correction.</li>
                </ol>
            </div>

            <h3>🛠️ Outils de Pentest</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Metasploit</strong> : Framework d'exploitation le plus utilisé. Contient des milliers d'exploits et de payloads.</li>
                <li><strong>Burp Suite</strong> : Outil complet pour les tests de sécurité web. Proxy intercepteur, scanner de vulnérabilités, fuzzer.</li>
                <li><strong>Nmap</strong> : Scanner de ports et de services réseau. Permet de découvrir les systèmes et services actifs.</li>
                <li><strong>Wireshark</strong> : Analyseur de paquets réseau. Permet d'examiner le trafic réseau en temps réel.</li>
                <li><strong>OWASP ZAP</strong> : Scanner de sécurité web open-source. Alternative gratuite à Burp Suite.</li>
                <li><strong>John the Ripper</strong> : Outil de cassage de mots de passe. Teste la robustesse des mots de passe.</li>
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Important :</strong> Les tests de pénétration doivent toujours être effectués avec une autorisation écrite explicite. Effectuer des tests non autorisés est illégal et peut entraîner des poursuites judiciaires.</p>
            </div>

            <h2 id="functions">{{ trans('app.formations.cybersecurite.functions_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.functions_text') !!}</p>

            <h3>🚨 Gestion des Incidents de Sécurité</h3>
            <p>La gestion des incidents permet de répondre efficacement aux cyberattaques et de minimiser les dommages :</p>

            <div class="example-box" style="background-color: #ffebee; border-left-color: #f44336;">
                <h3 style="color: #000; margin-bottom: 15px;">📋 Phases de Gestion d'Incident</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Préparation</strong> : Élaboration d'un plan de réponse aux incidents, formation de l'équipe, mise en place d'outils de détection et de réponse.</li>
                    <li><strong>Détection</strong> : Identification des incidents via la surveillance, les alertes, ou les rapports d'utilisateurs. Utilisation de SIEM (Security Information and Event Management).</li>
                    <li><strong>Containment</strong> : Isolation de l'incident pour empêcher sa propagation. Containment à court terme (immédiat) et à long terme (permanent).</li>
                    <li><strong>Éradication</strong> : Suppression de la cause de l'incident. Nettoyage des systèmes compromis, correction des vulnérabilités.</li>
                    <li><strong>Récupération</strong> : Restauration des systèmes et services à un état sécurisé. Vérification que les systèmes fonctionnent correctement.</li>
                    <li><strong>Post-incident</strong> : Analyse de l'incident, identification des leçons apprises, amélioration des processus et de la sécurité.</li>
                </ol>
            </div>

            <h3>🔍 Détection et Réponse</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>SIEM</strong> : Systèmes qui collectent, analysent et corrèlent les logs de sécurité. Exemples : Splunk, IBM QRadar, ArcSight.</li>
                <li><strong>SOAR</strong> : Security Orchestration, Automation and Response. Automatise la réponse aux incidents de sécurité.</li>
                <li><strong>EDR</strong> : Endpoint Detection and Response. Surveille et répond aux menaces sur les endpoints (postes de travail, serveurs).</li>
                <li><strong>Threat Intelligence</strong> : Collecte et analyse d'informations sur les menaces pour améliorer la détection et la prévention.</li>
            </ul>

            <h2 id="lists">{{ trans('app.formations.cybersecurite.lists_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.lists_text') }}</p>

            <h3>📜 Réglementations et Conformité</h3>
            <p>La conformité aux réglementations est essentielle pour les organisations qui traitent des données personnelles :</p>

            <div class="example-box" style="background-color: #e1f5fe; border-left-color: #03a9f4;">
                <h3 style="color: #000; margin-bottom: 15px;">🌍 Principales Réglementations</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>RGPD (Règlement Général sur la Protection des Données)</strong> : Réglementation européenne qui protège les données personnelles. Obligations : consentement, droit à l'oubli, notification des violations, DPO (Data Protection Officer).</li>
                    <li><strong>ISO 27001</strong> : Standard international pour la gestion de la sécurité de l'information. Définit un système de management de la sécurité de l'information (SMSI).</li>
                    <li><strong>PCI DSS</strong> : Standard de sécurité pour les organisations qui traitent les cartes de paiement. Obligatoire pour les commerçants et processeurs de paiement.</li>
                    <li><strong>HIPAA</strong> : Loi américaine pour la protection des informations de santé. S'applique aux organisations de santé aux États-Unis.</li>
                    <li><strong>NIS (Network and Information Systems Directive)</strong> : Directive européenne pour la sécurité des réseaux et systèmes d'information.</li>
                </ul>
            </div>

            <h3>✅ Bonnes Pratiques de Conformité</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Classification des données</strong> : Identifier et classer les données selon leur sensibilité (publique, interne, confidentielle, restreinte).</li>
                <li><strong>Gestion des accès</strong> : Principe du moindre privilège, révision régulière des accès, séparation des fonctions.</li>
                <li><strong>Audits réguliers</strong> : Vérifier la conformité aux politiques et réglementations. Audits internes et externes.</li>
                <li><strong>Documentation</strong> : Maintenir une documentation complète des politiques, procédures, et contrôles de sécurité.</li>
                <li><strong>Formation</strong> : Sensibiliser régulièrement les employés aux risques de sécurité et aux bonnes pratiques.</li>
            </ul>

            <h2 id="modules">{{ trans('app.formations.cybersecurite.modules_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.modules_text') }}</p>

            <h3>🛡️ Bonnes Pratiques de Sécurité</h3>
            <p>Adopter de bonnes pratiques de sécurité est essentiel pour protéger efficacement les systèmes et données :</p>

            <div class="example-box" style="background-color: #f1f8e9; border-left-color: #8bc34a;">
                <h3 style="color: #000; margin-bottom: 15px;">✅ Checklist de Sécurité</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Authentification multi-facteurs (MFA)</strong> : Activer la MFA pour tous les comptes critiques, surtout pour les administrateurs.</li>
                    <li><strong>Mots de passe robustes</strong> : Utiliser des mots de passe complexes (minimum 12 caractères, mélange de caractères). Utiliser un gestionnaire de mots de passe.</li>
                    <li><strong>Mises à jour régulières</strong> : Appliquer les correctifs de sécurité dès leur disponibilité. Automatiser les mises à jour quand possible.</li>
                    <li><strong>Sauvegardes</strong> : Effectuer des sauvegardes régulières et tester leur restauration. Stocker les sauvegardes hors site et chiffrées.</li>
                    <li><strong>Chiffrement</strong> : Chiffrer les données au repos et en transit. Utiliser des protocoles sécurisés (HTTPS, TLS).</li>
                    <li><strong>Surveillance</strong> : Mettre en place une surveillance continue des systèmes. Configurer des alertes pour les activités suspectes.</li>
                    <li><strong>Formation</strong> : Former régulièrement les utilisateurs aux risques de sécurité (phishing, ingénierie sociale).</li>
                    <li><strong>Politiques de sécurité</strong> : Élaborer et faire respecter des politiques de sécurité claires. Réviser régulièrement.</li>
                    <li><strong>Tests de sécurité</strong> : Effectuer des tests de pénétration réguliers et des audits de sécurité.</li>
                    <li><strong>Plan de réponse aux incidents</strong> : Avoir un plan documenté et testé pour répondre aux incidents de sécurité.</li>
                </ul>
            </div>

            <h3>🔐 Principe du Moindre Privilège</h3>
            <p>Le principe du moindre privilège (Principle of Least Privilege) stipule que les utilisateurs et processus ne doivent avoir que les permissions minimales nécessaires pour accomplir leurs tâches :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>Limiter les privilèges d'administrateur aux seules personnes qui en ont vraiment besoin</li>
                <li>Utiliser des comptes séparés pour les tâches administratives et les tâches quotidiennes</li>
                <li>Réviser régulièrement les permissions et supprimer les accès inutiles</li>
                <li>Implémenter une séparation des fonctions pour les tâches critiques</li>
            </ul>

            <h2 id="oop">{{ trans('app.formations.cybersecurite.oop_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.oop_text') }}</p>

            <h3>🛠️ Outils de Sécurité Essentiels</h3>
            <p>De nombreux outils existent pour aider à sécuriser les systèmes et détecter les vulnérabilités :</p>

            <div class="example-box" style="background-color: #fce4ec; border-left-color: #e91e63;">
                <h3 style="color: #000; margin-bottom: 15px;">🔧 Catégories d'Outils</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Analyse de vulnérabilités</strong> : Nessus, OpenVAS, Qualys - Scannent les systèmes pour identifier les vulnérabilités connues</li>
                    <li><strong>Analyse de code</strong> : SonarQube, Checkmarx, Veracode - Détectent les vulnérabilités dans le code source</li>
                    <li><strong>Gestion des secrets</strong> : HashiCorp Vault, AWS Secrets Manager - Stockent et gèrent les secrets de manière sécurisée</li>
                    <li><strong>Gestion des identités</strong> : Active Directory, Okta, Auth0 - Centralisent l'authentification et l'autorisation</li>
                    <li><strong>Surveillance réseau</strong> : Wireshark, tcpdump, Zeek - Analysent le trafic réseau pour détecter les anomalies</li>
                    <li><strong>Gestion des correctifs</strong> : WSUS, SCCM, Ansible - Automatisent l'application des correctifs de sécurité</li>
                </ul>
            </div>

            <h3>☁️ Sécurité Cloud</h3>
            <p>La sécurité cloud présente des défis et opportunités uniques :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Modèle de responsabilité partagée</strong> : Le fournisseur cloud sécurise l'infrastructure, vous sécurisez vos données et applications</li>
                <li><strong>IAM (Identity and Access Management)</strong> : Gestion centralisée des identités et accès dans le cloud</li>
                <li><strong>Chiffrement</strong> : Chiffrer les données au repos et en transit dans le cloud</li>
                <li><strong>Conformité</strong> : S'assurer que les services cloud respectent les réglementations (RGPD, HIPAA, etc.)</li>
                <li><strong>Configuration sécurisée</strong> : Configurer correctement les services cloud (S3 buckets, groupes de sécurité, etc.)</li>
                <li><strong>Surveillance</strong> : Utiliser les outils de monitoring cloud (CloudTrail, CloudWatch, Azure Monitor)</li>
            </ul>

            <h2 id="files">{{ trans('app.formations.cybersecurite.files_title') }}</h2>
            <p>{!! trans('app.formations.cybersecurite.files_text') !!}</p>

            <h3>☁️ Sécurité Cloud et IoT</h3>
            <p>Les environnements cloud et les objets connectés (IoT) présentent des défis de sécurité spécifiques :</p>

            <div class="example-box" style="background-color: #e0f2f1; border-left-color: #009688;">
                <h3 style="color: #000; margin-bottom: 15px;">🌐 Sécurité Cloud</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Configuration sécurisée</strong> : Vérifier les configurations par défaut des services cloud (S3, RDS, etc.). Utiliser des outils comme AWS Config, Azure Policy.</li>
                    <li><strong>Gestion des identités</strong> : Utiliser IAM pour contrôler finement les accès. Principe du moindre privilège dans le cloud.</li>
                    <li><strong>Chiffrement</strong> : Activer le chiffrement pour toutes les données sensibles. Utiliser les clés de gestion (KMS) du fournisseur cloud.</li>
                    <li><strong>Surveillance</strong> : Activer les logs et la surveillance (CloudTrail, CloudWatch, Azure Monitor). Configurer des alertes.</li>
                    <li><strong>Backup et Disaster Recovery</strong> : Planifier des sauvegardes régulières et tester les procédures de récupération.</li>
                </ul>
            </div>

            <div class="example-box" style="background-color: #fff9c4; border-left-color: #fbc02d;">
                <h3 style="color: #000; margin-bottom: 15px;">📱 Sécurité IoT</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Authentification des appareils</strong> : Utiliser des certificats numériques pour authentifier les appareils IoT.</li>
                    <li><strong>Chiffrement des communications</strong> : Chiffrer toutes les communications entre les appareils IoT et les serveurs.</li>
                    <li><strong>Mises à jour sécurisées</strong> : Mettre en place un mécanisme de mise à jour sécurisé (OTA - Over-The-Air) pour les appareils.</li>
                    <li><strong>Isolation réseau</strong> : Isoler les appareils IoT dans un réseau séparé (VLAN) pour limiter l'impact d'une compromission.</li>
                    <li><strong>Gestion des vulnérabilités</strong> : Surveiller et corriger les vulnérabilités connues des appareils IoT.</li>
                </ul>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.cybersecurite.files_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.cybersecurite.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.cybersecurite.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cybersecurite.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.cybersecurite.next_steps_further_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $icon = '';
                        $label = '';
                        $description = '';
                        if (preg_match('/^([📚🔧📦🌐📊🤖]) (.+)$/u', $item, $matches)) {
                            $icon = $matches[1];
                            $rest = $matches[2];
                            $labelParts = explode(' - ', $rest);
                            $label = $labelParts[0] ?? '';
                            $description = $labelParts[1] ?? '';
                        } else {
                            $labelParts = explode(' - ', $item);
                            $label = $labelParts[0] ?? '';
                            $description = $labelParts[1] ?? '';
                        }
                    @endphp
                    <li>@if($icon){{ $icon }} @endif<strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.ruby') }}" class="nav-btn">{{ trans('app.formations.cybersecurite.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.cybersecurite.nav_next') }}</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // SCRIPT DE GESTION MOBILE - DOIT S'EXÉCUTER EN PREMIER
    (function() {
        'use strict';
        
        // FLAG pour éviter les boucles infinies
        let isApplyingStyles = false;
        let hasInitialized = false;
        
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        function forceMobileSidebarState() {
            // Éviter les appels récursifs
            if (isApplyingStyles) {
                return;
            }
            
            const sidebar = document.getElementById('tutorialSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (!sidebar || !sidebarToggle) {
                return;
            }
            
            isApplyingStyles = true;
            
            try {
                if (isMobile()) {
                    // FORCER le sidebar à être caché avec styles inline
                    if (!sidebar.classList.contains('active')) {
                        const currentDisplay = window.getComputedStyle(sidebar).display;
                        if (currentDisplay !== 'none') {
                            sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important; top: auto !important; align-self: auto !important;';
                        }
                    }
                    
                    // FORCER le bouton burger à être visible
                    const toggleDisplay = window.getComputedStyle(sidebarToggle).display;
                    if (toggleDisplay === 'none' || toggleDisplay === '') {
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #ff6b35, #d45a2a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
                    }
                    
                    // FORCER l'overlay à être caché
                    if (sidebarOverlay && !sidebarOverlay.classList.contains('active')) {
                        sidebarOverlay.style.cssText = 'display: none !important; opacity: 0 !important; visibility: hidden !important;';
                    }
                } else {
                    // Desktop : restaurer les styles normaux
                    if (sidebar.classList.contains('active')) {
                        // Ne pas modifier si actif (peut être ouvert manuellement)
                        return;
                    }
                    sidebar.style.cssText = '';
                    sidebarToggle.style.cssText = 'display: none !important;';
                }
            } finally {
                // Réinitialiser le flag après un court délai
                setTimeout(function() {
                    isApplyingStyles = false;
                }, 50);
            }
        }
        
        // Fonction d'initialisation unique
        function initMobileSidebar() {
            if (hasInitialized) {
                return;
            }
            hasInitialized = true;
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    forceMobileSidebarState();
                    // Une seule réexécution après un court délai
                    setTimeout(forceMobileSidebarState, 300);
                });
            } else {
                forceMobileSidebarState();
                setTimeout(forceMobileSidebarState, 300);
            }
        }
        
        // Initialiser une seule fois
        initMobileSidebar();
        
        // Surveiller les changements de taille (avec debounce)
        let resizeTimer;
        let lastWidth = window.innerWidth;
        window.addEventListener('resize', function() {
            const currentWidth = window.innerWidth;
            // Ne réagir que si on change vraiment de mode (mobile/desktop)
            const wasMobile = lastWidth <= 992;
            const isNowMobile = currentWidth <= 992;
            
            if (wasMobile !== isNowMobile) {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    hasInitialized = false; // Réinitialiser pour permettre la réinitialisation
                    initMobileSidebar();
                    lastWidth = currentWidth;
                }, 200);
            }
        });
    })();
</script>
<script src="{{ asset('js/sidebar-sticky.js') }}"></script>
<script src="{{ asset('js/sidebar-navigation.js') }}"></script>
<script>
    // Gestion du menu burger mobile - S'exécute après les autres scripts
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('tutorialSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');
        const toggleIcon = document.getElementById('sidebarToggleIcon');
        
        // Fonction pour vérifier si on est en mobile
        function isMobile() {
            return window.innerWidth <= 992;
        }
        
        // S'assurer que le sidebar est caché par défaut en mobile
        function initSidebar() {
            if (isMobile() && sidebar) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                if (sidebarClose) sidebarClose.style.display = 'none';
                if (sidebarToggle) sidebarToggle.classList.remove('active');
                
                // FORCER le sidebar à être caché avec styles inline
                sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important; top: auto !important; align-self: auto !important;';
                
                // FORCER le bouton burger à être visible
                if (sidebarToggle) {
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #ff6b35, #d45a2a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
                }
            } else if (!isMobile() && sidebar) {
                // Sur desktop, s'assurer que le sidebar est visible normalement
                sidebar.classList.remove('active');
                sidebar.style.cssText = '';
                if (sidebarToggle) {
                    sidebarToggle.style.cssText = 'display: none !important;';
                }
            }
        }
        
        function openSidebar() {
            if (!sidebar || !sidebarOverlay) return;
            sidebar.classList.add('active');
            sidebar.style.cssText = 'display: block !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(0) !important; z-index: 9999 !important; opacity: 1 !important; visibility: visible !important; box-shadow: 0 -10px 50px rgba(0, 0, 0, 0.3) !important; overflow-y: auto !important; overflow-x: hidden !important;';
            sidebarOverlay.classList.add('active');
            if (sidebarOverlay) sidebarOverlay.style.cssText = 'display: block !important; opacity: 1 !important; visibility: visible !important;';
            if (sidebarClose) sidebarClose.style.display = 'flex';
            if (sidebarToggle) sidebarToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            if (!sidebar || !sidebarOverlay) return;
            sidebar.classList.remove('active');
            if (isMobile()) {
                sidebar.style.cssText = 'display: none !important; position: fixed !important; bottom: 0 !important; left: 0 !important; width: 85% !important; max-width: 400px !important; height: 70vh !important; max-height: 600px !important; border-radius: 20px 20px 0 0 !important; transform: translateY(100%) !important; z-index: 9999 !important; opacity: 0 !important; visibility: hidden !important;';
            }
            sidebarOverlay.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.style.cssText = 'display: none !important; opacity: 0 !important; visibility: hidden !important;';
            if (sidebarClose) sidebarClose.style.display = 'none';
            if (sidebarToggle) sidebarToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Initialiser le sidebar au chargement (une seule fois)
        let sidebarInitialized = false;
        function initSidebarOnce() {
            if (sidebarInitialized) return;
            sidebarInitialized = true;
            initSidebar();
        }
        
        // Initialiser après un court délai pour laisser les autres scripts s'exécuter
        setTimeout(initSidebarOnce, 100);
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar && sidebar.classList.contains('active')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSidebar();
            });
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Fermer le sidebar quand on clique sur un lien
        if (sidebar) {
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (isMobile()) {
                        setTimeout(closeSidebar, 300);
                    }
                });
            });
        }
        
        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
        
        // Gérer le redimensionnement (avec debounce)
        let resizeTimer;
        let lastWidth = window.innerWidth;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const currentWidth = window.innerWidth;
                const wasMobile = lastWidth <= 992;
                const isNowMobile = currentWidth <= 992;
                
                // Réinitialiser seulement si on change de mode
                if (wasMobile !== isNowMobile) {
                    sidebarInitialized = false;
                    initSidebarOnce();
                    lastWidth = currentWidth;
                }
            }, 200);
        });
    });
</script>
<!-- Prism.js pour la coloration syntaxique -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-cybersecurite.min.js"></script>
<script>
    // Initialiser Prism.js après le chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    });
    
    // Fonction pour copier le code
    function copyCodeToClipboard(button, codeElement) {
        const codeText = codeElement.innerText || codeElement.textContent;
        
        navigator.clipboard.writeText(codeText).then(function() {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('copied');
            button.setAttribute('title', 'Copié !');
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('copied');
                button.setAttribute('title', 'Copier le code');
            }, 2000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            const textArea = document.createElement('textarea');
            textArea.value = codeText;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('copied');
                button.setAttribute('title', 'Copié !');
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copied');
                    button.setAttribute('title', 'Copier le code');
                }, 2000);
            } catch (err) {
                console.error('Erreur lors de la copie (fallback):', err);
                alert('Impossible de copier le code. Veuillez le sélectionner manuellement.');
            }
            document.body.removeChild(textArea);
        });
    }
    
    // Ajouter les boutons de copie à tous les blocs de code
    document.addEventListener('DOMContentLoaded', function() {
        const codeBoxes = document.querySelectorAll('.code-box');
        
        codeBoxes.forEach(function(codeBox) {
            if (codeBox.querySelector('.copy-code-btn')) {
                return;
            }
            
            const codeElement = codeBox.querySelector('code');
            if (!codeElement) {
                return;
            }
            
            const copyButton = document.createElement('button');
            copyButton.className = 'copy-code-btn';
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
            copyButton.setAttribute('aria-label', 'Copier le code');
            copyButton.setAttribute('title', 'Copier le code');
            
            copyButton.addEventListener('click', function() {
                copyCodeToClipboard(copyButton, codeElement);
            });
            
            codeBox.appendChild(copyButton);
        });
    });
    
    // Système de suivi automatique de progression
    @auth
    (function() {
        const formationSlug = 'cybersecurite';
        const sections = [
            'intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 
            'loops', 'functions', 'lists', 'modules'
        ];
        
        let completedSections = new Set();
        let progressData = null;
        
        function loadProgress() {
            fetch(`/api/formation-progress/${formationSlug}`)
                .then(response => response.json())
                .then(data => {
                    progressData = data;
                    if (data.completed_sections) {
                        completedSections = new Set(data.completed_sections);
                    }
                    updateProgressDisplay();
                })
                .catch(err => console.error('Erreur chargement progression:', err));
        }
        
        function updateProgressDisplay() {
            const progressBar = document.getElementById('progress-bar-fill');
            const progressText = document.getElementById('progress-percentage-text');
            const timeSpentText = document.getElementById('time-spent-text');
            if (progressBar && progressData) progressBar.style.width = progressData.progress_percentage + '%';
            if (progressText && progressData) progressText.textContent = progressData.progress_percentage + '%';
            if (timeSpentText && progressData) timeSpentText.textContent = progressData.time_spent_minutes;
        }
        
        function markSectionAsCompleted(sectionId) {
            if (completedSections.has(sectionId)) return;
            completedSections.add(sectionId);
            
            fetch('/api/formation-progress/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    formation_slug: formationSlug,
                    section_id: sectionId,
                    completed: true
                })
            })
            .then(response => response.json())
            .then(data => {
                progressData = data;
                updateProgressDisplay();
                if (data.progress_percentage === 100) showCompletionNotification();
            })
            .catch(err => console.error('Erreur mise à jour progression:', err));
        }
        
        function showCompletionNotification() {
            const notification = document.createElement('div');
            notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #04AA6D, #06b6d4); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(4, 170, 109, 0.4); z-index: 10000; max-width: 350px; animation: slideInRight 0.5s ease;';
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;">🎉 Formation Complétée !</h4>
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation cybersecurite.</p>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.5s ease';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
        
        const observerOptions = {
            root: null,
            rootMargin: '-10% 0px -10% 0px',
            threshold: 0.3
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.id;
                    if (sectionId && sections.includes(sectionId) && !completedSections.has(sectionId)) {
                        setTimeout(() => {
                            const rect = entry.target.getBoundingClientRect();
                            if (rect.top < window.innerHeight && rect.bottom > 0) {
                                markSectionAsCompleted(sectionId);
                                observer.unobserve(entry.target);
                            }
                        }, 5000);
                    }
                }
            });
        }, observerOptions);
        
        document.addEventListener('DOMContentLoaded', function() {
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) observer.observe(section);
            });
            loadProgress();
        });
        
        setInterval(loadProgress, 30000);
    })();
    @endauth
    
</script>
@endsection
