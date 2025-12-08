@extends('layouts.app')

@section('title', trans('app.formations.python.title') . ' | NiangProgrammeur')

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
        background: linear-gradient(180deg, #3776ab 0%, #2d5f8a 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2d5f8a 0%, #244a6f 100%);
    }
    .sidebar h3 {
        color: #3776ab;
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
        background: #3776ab;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #3776ab;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #3776ab 0%, #2d5f8a 100%);
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
        background: linear-gradient(135deg, #3776ab, #2d5f8a);
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
        background: linear-gradient(135deg, #2d5f8a, #244a6f);
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
        border-left: 4px solid #3776ab;
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
        border: 2px solid #3776ab;
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
        content: 'Python';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #3776ab;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 1;
    }
    
    /* Bouton de copie - Même taille que le label Python */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 100px;
        background: #3776ab;
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
        background-color: #3776ab;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #2d5f8a;
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
        color: #3776ab;
        border-bottom-color: rgba(55, 118, 171, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.2) 0%, rgba(55, 118, 171, 0.1) 100%);
        color: #3776ab;
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
        border-left-color: #3776ab;
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
        background-color: #3776ab;
        color: white;
    }
    
    body.dark-mode .nav-btn:hover {
        background-color: #2d5f8a;
    }
    
    body.dark-mode .sidebar-toggle-btn {
        background: linear-gradient(135deg, #3776ab, #2d5f8a);
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
        <i class="fab fa-python" style="margin-right: 15px;"></i>
            {{ trans('app.formations.python.title') }}
    </h1>
        <p>{{ trans('app.formations.python.subtitle') }}</p>
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button data-favorite 
                    data-favorite-type="formation" 
                    data-favorite-slug="python" 
                    data-favorite-name="{{ trans('app.formations.python.title') }}"
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
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.python.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.python.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #3776ab; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.python.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'functions', 'lists', 'modules', 'oop', 'files'];
            @endphp
            @foreach(trans('app.formations.python.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.python.intro_title') }}</h1>
            <p>{!! trans('app.formations.python.intro_text') !!}</p>

            <h3>{{ trans('app.formations.python.what_is_title') }}</h3>
            <p>{!! trans('app.formations.python.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.python.why_popular_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.python.why_popular_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.python.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.python.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.python.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.python.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.python.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.python.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.python.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.python.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.python.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.python.use_cases_items') as $item)
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

            <h2 id="syntax">{{ trans('app.formations.python.syntax_title') }}</h2>
            <p>{!! trans('app.formations.python.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Premier programme Python
print("Bonjour, monde !")

# Variables
nom = "NiangProgrammeur"
age = 25

# F-strings pour formater les chaînes (Python 3.6+)
print(f"Je m'appelle {nom} et j'ai {age} ans")

# Opérations simples
resultat = 10 + 5
print(f"10 + 5 = {resultat}")</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.python.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.python.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.python.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.python.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># Définition d'une fonction
def calculer_moyenne(nombres):
    """Calcule la moyenne d'une liste de nombres."""
    if len(nombres) == 0:
        return 0
    somme = sum(nombres)
    moyenne = somme / len(nombres)
    return moyenne

# Utilisation
notes = [15, 18, 12, 20, 16]
moyenne = calculer_moyenne(notes)
print(f"La moyenne est : {moyenne}")</code></pre>
            </div>

            <h2 id="variables">{{ trans('app.formations.python.variables_title') }}</h2>
            <p>{!! trans('app.formations.python.variables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Déclaration de variables
nom = "Python"          # String (chaîne de caractères)
age = 30                # Integer (entier)
prix = 19.99            # Float (nombre décimal)
est_actif = True        # Boolean (booléen)
valeur_nulle = None     # NoneType (valeur nulle)

# Affichage
print(nom)
print(age)
print(prix)
print(est_actif)
print(valeur_nulle)

# Réassignation (changement de type)
variable = 10
print(type(variable))   # <class 'int'>

variable = "Dix"
print(type(variable))   # <class 'str'>

# Noms de variables valides
nom_utilisateur = "Bassirou"
age_utilisateur = 25
_privé = "variable privée"
CONSTANTE = 3.14159     # Convention pour les constantes</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.python.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.python.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.python.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.python.datatypes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># Types de base (scalaires)
texte = "Hello"                    # str (string)
nombre = 42                        # int (integer)
decimal = 3.14                     # float (floating point)
booleen = True                     # bool (boolean)
valeur_nulle = None                # NoneType

# Collections (structures de données)
liste = [1, 2, 3, 4, 5]           # list (liste ordonnée, modifiable)
tuple = (1, 2, 3)                 # tuple (liste ordonnée, immuable)
dictionnaire = {"nom": "Python", "age": 30}  # dict (paires clé-valeur)
ensemble = {1, 2, 3, 4}           # set (ensemble unique, non ordonné)

# Vérifier le type
print(type(texte))                # <class 'str'>
print(type(nombre))               # <class 'int'>
print(type(liste))                # <class 'list'>
print(type(dictionnaire))         # <class 'dict'>

# Conversion de types
age_str = str(25)                 # Convertir en string
age_int = int("25")               # Convertir en entier
prix_float = float("19.99")       # Convertir en décimal</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.python.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.python.datatypes_list_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.python.operators_title') }}</h2>
            <p>{{ trans('app.formations.python.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># Opérateurs arithmétiques
a = 10
b = 3

print(a + b)    # Addition: 13
print(a - b)    # Soustraction: 7
print(a * b)    # Multiplication: 30
print(a / b)    # Division: 3.3333333333333335
print(a // b)   # Division entière: 3
print(a % b)    # Modulo (reste): 1
print(a ** b)   # Puissance: 1000

# Opérateurs de comparaison
print(a > b)    # True (supérieur à)
print(a < b)    # False (inférieur à)
print(a >= b)   # True (supérieur ou égal)
print(a <= b)   # False (inférieur ou égal)
print(a == b)   # False (égalité)
print(a != b)   # True (différent)

# Opérateurs logiques
x = True
y = False
print(x and y)  # False (ET logique)
print(x or y)   # True (OU logique)
print(not x)    # False (NON logique)

# Opérateurs d'assignation
c = 5
c += 3          # Équivalent à c = c + 3 (c devient 8)
c -= 2          # Équivalent à c = c - 2 (c devient 6)
c *= 2          # Équivalent à c = c * 2 (c devient 12)
c /= 3          # Équivalent à c = c / 3 (c devient 4.0)

# Opérateurs d'identité
liste1 = [1, 2, 3]
liste2 = [1, 2, 3]
liste3 = liste1

print(liste1 is liste2)    # False (objets différents)
print(liste1 is liste3)    # True (même objet)
print(liste1 == liste2)    # True (valeurs égales)</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.python.conditions_title') }}</h2>
            <p>{!! trans('app.formations.python.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Structure if simple
age = 20

if age >= 18:
    print("Vous êtes majeur")
else:
    print("Vous êtes mineur")

# Structure if/elif/else
age = 15

if age >= 18:
    print("Vous êtes majeur")
    print("Vous pouvez voter")
elif age >= 13:
    print("Vous êtes adolescent")
elif age >= 6:
    print("Vous êtes enfant")
else:
    print("Vous êtes un bébé")

# Conditions multiples
note = 85

if note >= 90:
    mention = "Excellent"
elif note >= 80:
    mention = "Très bien"
elif note >= 70:
    mention = "Bien"
elif note >= 60:
    mention = "Assez bien"
else:
    mention = "Insuffisant"

print(f"Votre mention : {mention}")

# Opérateur ternaire (expression conditionnelle)
age = 20
statut = "Majeur" if age >= 18 else "Mineur"
print(statut)

# Conditions avec and/or
age = 25
permis = True

if age >= 18 and permis:
    print("Vous pouvez conduire")
else:
    print("Vous ne pouvez pas conduire")</code></pre>
            </div>

            <h2 id="loops">{{ trans('app.formations.python.loops_title') }}</h2>
            <p>{!! trans('app.formations.python.loops_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Boucle for avec range()
for i in range(5):
    print(i)  # Affiche 0, 1, 2, 3, 4

# range() avec début et fin
for i in range(1, 6):
    print(i)  # Affiche 1, 2, 3, 4, 5

# range() avec pas
for i in range(0, 10, 2):
    print(i)  # Affiche 0, 2, 4, 6, 8

# Boucle for avec liste
fruits = ["pomme", "banane", "orange"]
for fruit in fruits:
    print(f"J'aime les {fruit}")

# Boucle for avec index (enumerate)
fruits = ["pomme", "banane", "orange"]
for index, fruit in enumerate(fruits):
    print(f"{index}: {fruit}")

# Boucle while
compteur = 0
while compteur < 5:
    print(compteur)
    compteur += 1

# Boucle while avec break
compteur = 0
while True:
    print(compteur)
    compteur += 1
    if compteur >= 5:
        break  # Sortir de la boucle

# continue (passer à l'itération suivante)
for i in range(10):
    if i % 2 == 0:  # Si i est pair
        continue    # Passer au suivant
    print(i)       # Affiche seulement les impairs: 1, 3, 5, 7, 9

# Boucle for avec else
for i in range(5):
    print(i)
else:
    print("Boucle terminée")  # Exécuté si la boucle se termine normalement</code></pre>
            </div>

            <h2 id="functions">{{ trans('app.formations.python.functions_title') }}</h2>
            <p>{!! trans('app.formations.python.functions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Fonction simple (sans paramètres)
def dire_bonjour():
    print("Bonjour !")

dire_bonjour()  # Appel de la fonction

# Fonction avec paramètres
def saluer(nom):
    return f"Bonjour, {nom} !"

message = saluer("Python")
print(message)  # "Bonjour, Python !"

# Fonction avec plusieurs paramètres
def additionner(a, b):
    return a + b

resultat = additionner(5, 3)
print(resultat)  # 8

# Fonction avec paramètres par défaut
def saluer_personne(nom, message="Bonjour"):
    return f"{message}, {nom} !"

print(saluer_personne("Bassirou"))              # "Bonjour, Bassirou !"
print(saluer_personne("Bassirou", "Salut"))     # "Salut, Bassirou !"

# Fonction avec arguments nommés
def creer_personne(nom, age, ville="Dakar"):
    return f"{nom}, {age} ans, habite à {ville}"

print(creer_personne("Bassirou", 25))
print(creer_personne(age=25, nom="Bassirou", ville="Thiès"))

# Fonction avec *args (arguments variables)
def additionner_nombres(*args):
    return sum(args)

print(additionner_nombres(1, 2, 3, 4, 5))  # 15

# Fonction avec **kwargs (arguments nommés variables)
def afficher_info(**kwargs):
    for cle, valeur in kwargs.items():
        print(f"{cle}: {valeur}")

afficher_info(nom="Bassirou", age=25, ville="Dakar")

# Fonction lambda (fonction anonyme)
carre = lambda x: x ** 2
print(carre(5))  # 25

# Utilisation de lambda avec map()
nombres = [1, 2, 3, 4, 5]
carres = list(map(lambda x: x ** 2, nombres))
print(carres)  # [1, 4, 9, 16, 25]</code></pre>
            </div>

            <h2 id="lists">{{ trans('app.formations.python.lists_title') }}</h2>
            <p>{{ trans('app.formations.python.lists_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># ========== LISTES ==========
# Création de listes
nombres = [1, 2, 3, 4, 5]
fruits = ["pomme", "banane", "orange"]
liste_mixte = [1, "deux", 3.0, True]

# Accès aux éléments (index commence à 0)
print(fruits[0])        # "pomme" (premier élément)
print(fruits[-1])      # "orange" (dernier élément)

# Modification
fruits[1] = "mangue"    # Remplacer "banane" par "mangue"

# Méthodes des listes
fruits.append("kiwi")           # Ajouter à la fin
fruits.insert(1, "ananas")      # Insérer à l'index 1
fruits.remove("pomme")          # Supprimer un élément
fruits.pop()                    # Supprimer le dernier élément
fruits.pop(0)                   # Supprimer l'élément à l'index 0

# Autres méthodes utiles
print(len(fruits))              # Longueur de la liste
print(fruits.count("banane"))   # Compter les occurrences
fruits.sort()                   # Trier la liste
fruits.reverse()                # Inverser la liste

# Slicing (tranches)
nombres = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
print(nombres[2:5])     # [2, 3, 4] (de l'index 2 à 4)
print(nombres[:3])      # [0, 1, 2] (du début à l'index 2)
print(nombres[3:])      # [3, 4, 5, 6, 7, 8, 9] (de l'index 3 à la fin)
print(nombres[::2])     # [0, 2, 4, 6, 8] (tous les 2 éléments)

# ========== DICTIONNAIRES ==========
# Création de dictionnaires
personne = {
    "nom": "Bassirou",
    "age": 25,
    "ville": "Dakar"
}

# Accès aux valeurs
print(personne["nom"])          # "Bassirou"
print(personne.get("age"))      # 25 (méthode get() plus sûre)
print(personne.get("email", "Non renseigné"))  # Valeur par défaut

# Modification et ajout
personne["age"] = 26            # Modifier
personne["email"] = "bassirou@example.com"  # Ajouter

# Méthodes des dictionnaires
print(personne.keys())          # Toutes les clés
print(personne.values())        # Toutes les valeurs
print(personne.items())         # Toutes les paires clé-valeur

# Parcourir un dictionnaire
for cle, valeur in personne.items():
    print(f"{cle}: {valeur}")

# Supprimer
del personne["email"]           # Supprimer une clé
personne.pop("ville")           # Supprimer et retourner la valeur</code></pre>
            </div>

            <h2 id="modules">{{ trans('app.formations.python.modules_title') }}</h2>
            <p>{{ trans('app.formations.python.modules_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># Importer un module complet
import math

print(math.sqrt(16))        # 4.0 (racine carrée)
print(math.pi)              # 3.141592653589793
print(math.cos(0))          # 1.0

# Importer avec un alias
import datetime as dt
maintenant = dt.datetime.now()
print(maintenant)

# Importer des fonctions spécifiques
from math import sqrt, pi
print(sqrt(25))             # 5.0
print(pi)                   # 3.141592653589793

# Importer tout d'un module (non recommandé)
from math import *
print(sin(0))               # 0.0

# Modules standards utiles
import random
print(random.randint(1, 100))  # Nombre aléatoire entre 1 et 100

import os
print(os.getcwd())          # Répertoire courant

import sys
print(sys.version)          # Version de Python

# Créer son propre module
# Créer un fichier mon_module.py avec :
# def ma_fonction():
#     return "Hello from module"
#
# Puis l'importer :
# import mon_module
# print(mon_module.ma_fonction())</code></pre>
            </div>

            <h2 id="oop">{{ trans('app.formations.python.oop_title') }}</h2>
            <p>{{ trans('app.formations.python.oop_text') }}</p>

            <div class="code-box">
                <pre><code class="language-python"># Définir une classe
class Personne:
    # Constructeur (méthode spéciale __init__)
    def __init__(self, nom, age):
        self.nom = nom      # Attribut d'instance
        self.age = age
    
    # Méthode d'instance
    def se_presenter(self):
        return f"Je m'appelle {self.nom} et j'ai {self.age} ans"
    
    def avoir_ans(self, annees):
        self.age += annees
        return f"Dans {annees} ans, j'aurai {self.age} ans"

# Créer des objets (instances)
personne1 = Personne("Bassirou", 25)
personne2 = Personne("Aminata", 30)

# Utiliser les méthodes
print(personne1.se_presenter())
print(personne2.se_presenter())
print(personne1.avoir_ans(5))

# Accéder aux attributs
print(personne1.nom)
print(personne1.age)

# Classe avec attributs de classe
class Voiture:
    # Attribut de classe (partagé par toutes les instances)
    nombre_voitures = 0
    
    def __init__(self, marque, modele):
        self.marque = marque
        self.modele = modele
        Voiture.nombre_voitures += 1
    
    def __str__(self):
        return f"{self.marque} {self.modele}"

voiture1 = Voiture("Toyota", "Corolla")
voiture2 = Voiture("Honda", "Civic")
print(f"Nombre de voitures créées : {Voiture.nombre_voitures}")

# Héritage
class Etudiant(Personne):
    def __init__(self, nom, age, ecole):
        super().__init__(nom, age)  # Appeler le constructeur parent
        self.ecole = ecole
    
    def etudier(self):
        return f"{self.nom} étudie à {self.ecole}"

etudiant = Etudiant("Bassirou", 25, "UCAD")
print(etudiant.se_presenter())  # Méthode héritée
print(etudiant.etudier())       # Méthode spécifique</code></pre>
            </div>

            <h2 id="files">{{ trans('app.formations.python.files_title') }}</h2>
            <p>{!! trans('app.formations.python.files_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-python"># Écrire dans un fichier (mode 'w' = write)
with open("fichier.txt", "w", encoding="utf-8") as f:
    f.write("Bonjour Python !\n")
    f.write("Ceci est la deuxième ligne\n")

# Lire un fichier (mode 'r' = read)
with open("fichier.txt", "r", encoding="utf-8") as f:
    contenu = f.read()
    print(contenu)

# Lire ligne par ligne
with open("fichier.txt", "r", encoding="utf-8") as f:
    for ligne in f:
        print(ligne.strip())  # strip() enlève les sauts de ligne

# Lire toutes les lignes dans une liste
with open("fichier.txt", "r", encoding="utf-8") as f:
    lignes = f.readlines()
    print(lignes)

# Ajouter à un fichier (mode 'a' = append)
with open("fichier.txt", "a", encoding="utf-8") as f:
    f.write("Nouvelle ligne ajoutée\n")

# Modes de fichier
# 'r'  - Lecture (défaut)
# 'w'  - Écriture (écrase le fichier existant)
# 'a'  - Ajout (ajoute à la fin)
# 'x'  - Création exclusive (erreur si existe)
# 'b'  - Mode binaire (rb, wb)
# 't'  - Mode texte (défaut, rt, wt)
# '+'  - Lecture et écriture (r+, w+, a+)

# Gestion d'erreurs
try:
    with open("fichier_inexistant.txt", "r") as f:
        contenu = f.read()
except FileNotFoundError:
    print("Le fichier n'existe pas")
except PermissionError:
    print("Permission refusée")
except Exception as e:
    print(f"Erreur : {e}")</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.python.files_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.python.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.python.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.python.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.python.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.python.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.python.next_steps_further_items') as $item)
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
                <a href="{{ route('formations.ia') }}" class="nav-btn">{{ trans('app.formations.python.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.python.nav_next') }}</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3776ab, #2d5f8a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3776ab, #2d5f8a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
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
        const formationSlug = 'python';
        const sections = [
            'intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 
            'loops', 'functions', 'lists', 'modules', 'oop', 'files'
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
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation Python.</p>
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
