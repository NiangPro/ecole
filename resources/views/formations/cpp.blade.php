@extends('layouts.app')

@section('title', trans('app.formations.cpp.title') . ' | NiangProgrammeur')

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
            radial-gradient(circle at 20% 30%, rgba(0, 89, 156, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(0, 89, 156, 0.15) 0%, transparent 50%);
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
        border: 1px solid rgba(0, 89, 156, 0.2);
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
        background: linear-gradient(180deg, #00599c 0%, #004080 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #003d6b 0%, #002d4f 100%);
    }
    .sidebar h3 {
        color: #00599c;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(0, 89, 156, 0.2);
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
        background: #00599c;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(0, 89, 156, 0.1) 0%, rgba(0, 89, 156, 0.05) 100%);
        color: #00599c;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 89, 156, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #00599c 0%, #004080 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(0, 89, 156, 0.3);
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
        background: rgba(0, 89, 156, 0.1) !important;
        border: 2px solid rgba(0, 89, 156, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(0, 89, 156, 0.2) !important;
        border-color: rgba(0, 89, 156, 0.5) !important;
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
        background: linear-gradient(135deg, #00599c, #004080);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(0, 89, 156, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(55, 118, 171, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #004080, #003366);
        transform: rotate(90deg);
    }
    
    .sidebar-overlay {
        display: none !important;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        /* backdrop-filter: blur(5px); */ /* Désactivé pour éviter le flou */
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
        border-top: 2px solid rgba(0, 89, 156, 0.2);
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
        border-left: 4px solid #00599c;
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
        border: 2px solid #00599c;
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
        content: 'C++';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #00599c;
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
        background: #00599c;
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
        text-shadow: none !important; /* Pas de flou */
    }
    
    /* Supprimer tout flou sur le code */
    .code-box pre,
    .code-box code,
    .code-box pre code,
    .code-box pre code .token {
        text-shadow: none !important;
        filter: none !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Styles Prism.js pour la coloration syntaxique C */
    .code-box code[class*="language-"],
    .code-box pre[class*="language-"] {
        color: #e2e8f0;
        text-shadow: none;
        font-family: 'Courier New', 'Consolas', 'Monaco', 'Fira Code', monospace;
        background: transparent !important;
    }
    
    /* Couleurs pour les tokens C - Style VS Code Dark */
    .code-box code[class*="language-"] .token.comment,
    .code-box code[class*="language-"] .token.prolog,
    .code-box code[class*="language-"] .token.doctype,
    .code-box code[class*="language-"] .token.cdata {
        color: #6a9955 !important;
        font-style: italic;
    }
    .code-box code[class*="language-"] .token.string,
    .code-box code[class*="language-"] .token.attr-value {
        color: #ce9178 !important;
    }
    .code-box code[class*="language-"] .token.keyword,
    .code-box code[class*="language-"] .token.boolean {
        color: #569cd6 !important;
        font-weight: 500;
    }
    .code-box code[class*="language-"] .token.operator {
        color: #d4d4d4 !important;
    }
    .code-box code[class*="language-"] .token.function {
        color: #dcdcaa !important;
    }
    .code-box code[class*="language-"] .token.class-name {
        color: #4ec9b0 !important;
    }
    .code-box code[class*="language-"] .token.number {
        color: #b5cea8 !important;
    }
    .code-box code[class*="language-"] .token.punctuation {
        color: #d4d4d4 !important;
    }
    .code-box code[class*="language-"] .token.variable,
    .code-box code[class*="language-"] .token.property {
        color: #9cdcfe !important;
    }
    
    /* S'assurer que les tokens Prism héritent correctement */
    .code-box pre code .token {
        font-size: 14px;
        line-height: 1.6;
        font-weight: 400;
        text-shadow: none !important; /* Pas de flou sur les tokens */
    }
    
    /* Styles généraux pour tous les tokens Prism */
    .code-box .token {
        text-shadow: none !important;
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
        background-color: #00599c;
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
    /* Styles Prism.js pour mode sombre */
    body.dark-mode .code-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    body.dark-mode .code-box pre code {
        color: #e2e8f0;
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
        border-color: rgba(168, 185, 204, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .sidebar h3 {
        color: #00599c;
        border-bottom-color: rgba(0, 89, 156, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(0, 89, 156, 0.2) 0%, rgba(0, 89, 156, 0.1) 100%);
        color: #00599c;
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
        border-left-color: #00599c;
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
    
    body.dark-mode .nav-buttons {
        border-top-color: rgba(255, 255, 255, 0.2);
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
            <i class="fab fa-cuttlefish" style="margin-right: 15px;"></i>
            {{ trans('app.formations.cpp.title') }}
    </h1>
        <p>{{ trans('app.formations.cpp.subtitle') }}</p>
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button data-favorite 
                    data-favorite-type="formation" 
                    data-favorite-slug="cpp" 
                    data-favorite-name="{{ trans('app.formations.cpp.title') }}"
                    style="background: rgba(255, 255, 255, 0.2); border: 2px solid rgba(255, 255, 255, 0.4); color: white; padding: 12px 24px; border-radius: 10px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; backdrop-filter: blur(10px);">
                <i class="far fa-heart"></i>
                <span>Ajouter aux favoris</span>
            </button>
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
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.cpp.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.cpp.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #00599c; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.cpp.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'functions', 'classes', 'inheritance', 'polymorphism', 'templates', 'stl'];
            @endphp
            @foreach(trans('app.formations.cpp.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.cpp.intro_title') }}</h1>
            <p>{{ trans('app.formations.cpp.intro_text') }}</p>

            <h3>{{ trans('app.formations.cpp.what_is_title') }}</h3>
            <p>{!! trans('app.formations.cpp.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.why_important_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cpp.why_important_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.cpp.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.cpp.why_learn_text') ?? '' }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cpp.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.cpp.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.cpp.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cpp.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.cpp.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.cpp.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.cpp.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.cpp.use_cases_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $icon = '';
                    $label = '';
                    $description = '';
                    if (preg_match('/^([🎮🖥️💼🔧📡🔬💾🌐]) (.+)$/u', $item, $matches)) {
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

            <h2 id="syntax">{{ trans('app.formations.cpp.syntax_title') }}</h2>
            <p>{!! trans('app.formations.cpp.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-cpp">// Premier programme C++
#include <iostream>
using namespace std;

int main() {
    cout << "Bonjour, monde !" << endl;
    return 0;
}

// Variables
int age = 25;
string nom = "NiangProgrammeur";

// Affichage formaté
cout << "Je m'appelle " << nom << " et j'ai " << age << " ans" << endl;

// Opérations simples
int resultat = 10 + 5;
cout << "10 + 5 = " << resultat << endl;
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cpp.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.cpp.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.cpp.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

// Définition d'une fonction
int calculer_moyenne(int nombres[], int taille) {
    if (taille == 0) {
        return 0;
    }
    int somme = 0;
    for (int i = 0; i < taille; i++) {
        somme += nombres[i];
    }
    return somme / taille;
}

// Classe simple
class Personne {
public:
    string nom;
    int age;
    
    void afficher() {
        cout << "Nom: " << nom << ", Age: " << age << endl;
    }
};

// Fonction principale
int main() {
    int notes[] = {15, 18, 12, 20, 16};
    int moyenne = calculer_moyenne(notes, 5);
    cout << "La moyenne est : " << moyenne << endl;
    
    // Utilisation d'une classe
    Personne p;
    p.nom = "Bassirou";
    p.age = 25;
    p.afficher();
    
    return 0;
}</code></pre>
            </div>

            <h2 id="variables">{{ trans('app.formations.cpp.variables_title') }}</h2>
            <p>{!! trans('app.formations.cpp.variables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

int main() {
    // Déclaration de variables
    int age = 30;                    // Entier
    float prix = 19.99f;             // Nombre décimal (simple précision)
    double decimal = 3.14159;         // Nombre décimal (double précision)
    char lettre = 'A';               // Caractère unique
    string nom = "C++";              // Chaîne de caractères (C++)
    bool est_vrai = true;            // Booléen
    
    // Affichage avec cout
    cout << "Age: " << age << endl;
    cout << "Prix: " << prix << endl;
    cout << "Decimal: " << decimal << endl;
    cout << "Lettre: " << lettre << endl;
    cout << "Nom: " << nom << endl;
    cout << "Est vrai: " << est_vrai << endl;
    
    // Déclaration puis assignation
    int nombre;
    nombre = 10;
    cout << "Nombre: " << nombre << endl;
    
    // Constantes avec const
    const int TAILLE = 50;
    const string MESSAGE = "Bonjour";
    
    // Constantes avec constexpr (C++11)
    constexpr double PI = 3.14159;
    constexpr int MAX_SIZE = 100;
    
    // Noms de variables valides
    int age_utilisateur = 25;
    string nom_utilisateur = "Bassirou";
    float _prive = 3.14;  // Possible mais non recommandé
    
    return 0;
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cpp.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.cpp.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.cpp.datatypes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

int main() {
    // Types entiers
    char c = 'A';              // 1 octet (-128 à 127 ou 0 à 255)
    short s = 100;             // 2 octets (-32768 à 32767)
    int i = 1000;              // 4 octets (généralement)
    long l = 100000L;          // 4 ou 8 octets
    long long ll = 1000000LL;  // 8 octets
    
    // Types entiers non signés
    unsigned char uc = 200;
    unsigned int ui = 5000;
    unsigned long ul = 100000UL;
    
    // Types décimaux
    float f = 3.14f;           // 4 octets (simple précision)
    double d = 3.14159;        // 8 octets (double précision)
    long double ld = 3.141592653589793L;  // 10 ou 16 octets
    
    // Type booléen
    bool est_vrai = true;
    bool est_faux = false;
    
    // Chaînes de caractères (C++)
    string chaine = "Hello";   // Classe string (C++)
    char tableau[] = "World";  // Tableau de caractères (style C)
    
    // Affichage avec cout
    cout << "char: " << c << endl;
    cout << "int: " << i << endl;
    cout << "float: " << f << endl;
    cout << "double: " << d << endl;
    cout << "bool: " << est_vrai << endl;
    cout << "string: " << chaine << endl;
    
    // Taille des types (en octets)
    cout << "Taille de int: " << sizeof(int) << " octets" << endl;
    cout << "Taille de float: " << sizeof(float) << " octets" << endl;
    cout << "Taille de double: " << sizeof(double) << " octets" << endl;
    
    return 0;
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.cpp.datatypes_list_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.cpp.operators_title') }}</h2>
            <p>{{ trans('app.formations.cpp.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <cmath>  // Pour pow()
using namespace std;

int main() {
    int a = 10, b = 3;
    
    // Opérateurs arithmétiques
    cout << a << " + " << b << " = " << (a + b) << endl;      // Addition: 13
    cout << a << " - " << b << " = " << (a - b) << endl;      // Soustraction: 7
    cout << a << " * " << b << " = " << (a * b) << endl;      // Multiplication: 30
    cout << a << " / " << b << " = " << (a / b) << endl;      // Division entière: 3
    cout << a << " % " << b << " = " << (a % b) << endl;      // Modulo (reste): 1
    
    // Division avec float
    float resultat = (float)a / b;
    cout << a << " / " << b << " = " << resultat << endl;     // Division: 3.33
    
    // Puissance (nécessite cmath)
    double puissance = pow(a, b);
    cout << a << "^" << b << " = " << puissance << endl;      // Puissance: 1000
    
    // Opérateurs de comparaison
    cout << (a > b) << endl;   // true (1)
    cout << (a < b) << endl;   // false (0)
    cout << (a >= b) << endl;  // true (1)
    cout << (a <= b) << endl;  // false (0)
    cout << (a == b) << endl;  // false (0)
    cout << (a != b) << endl;  // true (1)
    
    // Opérateurs logiques
    bool x = true, y = false;
    cout << (x && y) << endl;  // ET logique: false (0)
    cout << (x || y) << endl;  // OU logique: true (1)
    cout << !x << endl;       // NON logique: false (0)
    
    // Opérateurs d'assignation
    int c = 5;
    c += 3;  // Équivalent à c = c + 3 (c devient 8)
    c -= 2;  // Équivalent à c = c - 2 (c devient 6)
    c *= 2;  // Équivalent à c = c * 2 (c devient 12)
    c /= 3;  // Équivalent à c = c / 3 (c devient 4)
    c %= 3;  // Équivalent à c = c % 3 (c devient 1)
    
    // Opérateurs d'incrémentation/décrémentation
    int i = 5;
    cout << "i = " << i++ << endl;  // Post-incrémentation: affiche 5, puis i = 6
    cout << "i = " << ++i << endl;  // Pré-incrémentation: i = 7, puis affiche 7
    
    return 0;
}</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.cpp.conditions_title') }}</h2>
            <p>{!! trans('app.formations.cpp.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
using namespace std;

int main() {
    // Structure if simple
    int age = 20;
    
    if (age >= 18) {
        cout << "Vous êtes majeur" << endl;
    } else {
        cout << "Vous êtes mineur" << endl;
    }
    
    // Structure if/else if/else
    age = 15;
    
    if (age >= 18) {
        cout << "Vous êtes majeur" << endl;
        cout << "Vous pouvez voter" << endl;
    } else if (age >= 13) {
        cout << "Vous êtes adolescent" << endl;
    } else if (age >= 6) {
        cout << "Vous êtes enfant" << endl;
    } else {
        cout << "Vous êtes un bébé" << endl;
    }
    
    // Conditions multiples
    int note = 85;
    string mention;
    
    if (note >= 90) {
        mention = "Excellent";
    } else if (note >= 80) {
        mention = "Très bien";
    } else if (note >= 70) {
        mention = "Bien";
    } else if (note >= 60) {
        mention = "Assez bien";
    } else {
        mention = "Insuffisant";
    }
    
    cout << "Votre mention : " << mention << endl;
    
    // Opérateur ternaire (expression conditionnelle)
    age = 20;
    string statut = (age >= 18) ? "Majeur" : "Mineur";
    cout << statut << endl;
    
    // Conditions avec &&/||
    age = 25;
    bool permis = true;
    
    if (age >= 18 && permis) {
        cout << "Vous pouvez conduire" << endl;
    } else {
        cout << "Vous ne pouvez pas conduire" << endl;
    }
    
    return 0;
}</code></pre>
            </div>

            <h2 id="loops">{{ trans('app.formations.cpp.loops_title') }}</h2>
            <p>{!! trans('app.formations.cpp.loops_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <vector>
using namespace std;

int main() {
    // Boucle for classique
    for (int i = 0; i < 5; i++) {
        cout << i << " ";  // Affiche 0, 1, 2, 3, 4
    }
    cout << endl;
    
    // Boucle for avec range (C++11)
    vector<int> nombres = {1, 2, 3, 4, 5};
    for (int n : nombres) {
        cout << n << " ";  // Affiche 1, 2, 3, 4, 5
    }
    cout << endl;
    
    // Boucle for avec pas
    for (int i = 0; i < 10; i += 2) {
        cout << i << " ";  // Affiche 0, 2, 4, 6, 8
    }
    cout << endl;
    
    // Boucle for avec vector
    vector<string> fruits = {"pomme", "banane", "orange"};
    for (const string& fruit : fruits) {
        cout << "J'aime les " << fruit << endl;
    }
    
    // Boucle while
    int compteur = 0;
    while (compteur < 5) {
        cout << compteur << " ";
        compteur++;
    }
    cout << endl;
    
    // Boucle do-while (exécute au moins une fois)
    int i = 0;
    do {
        cout << i << " ";
        i++;
    } while (i < 5);
    cout << endl;
    
    // Boucle while avec break
    compteur = 0;
    while (true) {
        cout << compteur << " ";
        compteur++;
        if (compteur >= 5) {
            break;  // Sortir de la boucle
        }
    }
    cout << endl;
    
    // continue (passer à l'itération suivante)
    for (int i = 0; i < 10; i++) {
        if (i % 2 == 0) {  // Si i est pair
            continue;       // Passer au suivant
        }
        cout << i << " ";  // Affiche seulement les impairs: 1, 3, 5, 7, 9
    }
    cout << endl;
    
    return 0;
}</code></pre>
            </div>

            <h2 id="functions">{{ trans('app.formations.cpp.functions_title') }}</h2>
            <p>{!! trans('app.formations.cpp.functions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

// Fonction simple (sans paramètres)
void dire_bonjour() {
    cout << "Bonjour !" << endl;
}

// Fonction avec paramètres
string saluer(string nom) {
    return "Bonjour, " + nom + " !";
}

// Fonction avec plusieurs paramètres
int additionner(int a, int b) {
    return a + b;
}

// Fonction avec paramètres par défaut
string saluer_personne(string nom, string message = "Bonjour") {
    return message + ", " + nom + " !";
}

// Fonction avec surcharge (même nom, paramètres différents)
int multiplier(int a, int b) {
    return a * b;
}

double multiplier(double a, double b) {
    return a * b;
}

// Fonction inline (pour petites fonctions)
inline int carre(int x) {
    return x * x;
}

int main() {
    // Appel de fonctions
    dire_bonjour();
    
    string message = saluer("C++");
    cout << message << endl;  // "Bonjour, C++ !"
    
    int resultat = additionner(5, 3);
    cout << resultat << endl;  // 8
    
    cout << saluer_personne("Bassirou") << endl;              // "Bonjour, Bassirou !"
    cout << saluer_personne("Bassirou", "Salut") << endl;     // "Salut, Bassirou !"
    
    // Surcharge de fonctions
    cout << multiplier(5, 3) << endl;        // 15 (int)
    cout << multiplier(5.5, 3.2) << endl;    // 17.6 (double)
    
    // Fonction inline
    cout << carre(5) << endl;  // 25
    
    return 0;
}</code></pre>
            </div>

            <h2 id="classes">{{ trans('app.formations.cpp.classes_title') }}</h2>
            <p>{{ trans('app.formations.cpp.classes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

// Définition d'une classe
class Personne {
private:
    string nom;
    int age;
    
public:
    // Constructeur
    Personne(string n, int a) {
        nom = n;
        age = a;
    }
    
    // Méthodes (getters)
    string getNom() {
        return nom;
    }
    
    int getAge() {
        return age;
    }
    
    // Méthodes (setters)
    void setNom(string n) {
        nom = n;
    }
    
    void setAge(int a) {
        age = a;
    }
    
    // Méthode pour afficher
    void afficher() {
        cout << "Nom: " << nom << ", Age: " << age << endl;
    }
};

int main() {
    // Création d'objets
    Personne p1("Bassirou", 25);
    Personne p2("Aminata", 30);
    
    // Utilisation des méthodes
    p1.afficher();
    p2.afficher();
    
    // Modification via setters
    p1.setAge(26);
    p1.afficher();
    
    // Accès via getters
    cout << "Nom de p1: " << p1.getNom() << endl;
    cout << "Age de p1: " << p1.getAge() << endl;
    
    return 0;
}</code></pre>
            </div>

            <h2 id="inheritance">{{ trans('app.formations.cpp.inheritance_title') }}</h2>
            <p>{{ trans('app.formations.cpp.inheritance_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

// Classe de base
class Animal {
protected:
    string nom;
    
public:
    Animal(string n) : nom(n) {}
    
    void manger() {
        cout << nom << " mange" << endl;
    }
    
    virtual void faireBruit() {
        cout << nom << " fait un bruit" << endl;
    }
};

// Classe dérivée
class Chien : public Animal {
public:
    Chien(string n) : Animal(n) {}
    
    void faireBruit() override {
        cout << nom << " aboie" << endl;
    }
    
    void aboyer() {
        cout << nom << " aboie fort !" << endl;
    }
};

// Classe dérivée
class Chat : public Animal {
public:
    Chat(string n) : Animal(n) {}
    
    void faireBruit() override {
        cout << nom << " miaule" << endl;
    }
};

int main() {
    Chien chien("Rex");
    Chat chat("Mimi");
    
    chien.manger();      // Hérité de Animal
    chien.faireBruit();  // Surchargé dans Chien
    chien.aboyer();      // Spécifique à Chien
    
    chat.manger();       // Hérité de Animal
    chat.faireBruit();   // Surchargé dans Chat
    
    return 0;
}</code></pre>
            </div>

            <h2 id="polymorphism">🔄 Polymorphisme</h2>
            <p>Le polymorphisme permet à des objets de classes différentes d'être traités de manière uniforme via une interface commune. En C++, cela se fait avec des fonctions virtuelles.</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <vector>
using namespace std;

// Classe de base avec fonction virtuelle
class Forme {
public:
    virtual double calculerAire() = 0;  // Fonction virtuelle pure
    virtual void afficher() {
        cout << "Forme" << endl;
    }
};

// Classe dérivée
class Cercle : public Forme {
private:
    double rayon;
    
public:
    Cercle(double r) : rayon(r) {}
    
    double calculerAire() override {
        return 3.14159 * rayon * rayon;
    }
    
    void afficher() override {
        cout << "Cercle de rayon " << rayon << endl;
    }
};

// Classe dérivée
class Rectangle : public Forme {
private:
    double largeur, hauteur;
    
public:
    Rectangle(double l, double h) : largeur(l), hauteur(h) {}
    
    double calculerAire() override {
        return largeur * hauteur;
    }
    
    void afficher() override {
        cout << "Rectangle " << largeur << "x" << hauteur << endl;
    }
};

int main() {
    // Polymorphisme avec pointeurs
    Forme* forme1 = new Cercle(5.0);
    Forme* forme2 = new Rectangle(4.0, 6.0);
    
    forme1->afficher();
    cout << "Aire: " << forme1->calculerAire() << endl;
    
    forme2->afficher();
    cout << "Aire: " << forme2->calculerAire() << endl;
    
    // Polymorphisme avec vector
    vector<Forme*> formes;
    formes.push_back(new Cercle(3.0));
    formes.push_back(new Rectangle(2.0, 4.0));
    
    for (Forme* f : formes) {
        f->afficher();
        cout << "Aire: " << f->calculerAire() << endl;
    }
    
    // Libération mémoire
    delete forme1;
    delete forme2;
    for (Forme* f : formes) {
        delete f;
    }
    
    return 0;
}</code></pre>
            </div>

            <h2 id="templates">📦 Templates</h2>
            <p>Les templates permettent de créer des fonctions et classes génériques qui fonctionnent avec différents types de données. C'est une fonctionnalité puissante de C++.</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <string>
using namespace std;

// Template de fonction
template<typename T>
T maximum(T a, T b) {
    return (a > b) ? a : b;
}

// Template de classe
template<typename T>
class Pile {
private:
    T* elements;
    int taille;
    int sommet;
    
public:
    Pile(int t) : taille(t), sommet(-1) {
        elements = new T[taille];
    }
    
    ~Pile() {
        delete[] elements;
    }
    
    void empiler(T element) {
        if (sommet < taille - 1) {
            elements[++sommet] = element;
        }
    }
    
    T depiler() {
        if (sommet >= 0) {
            return elements[sommet--];
        }
        return T();
    }
    
    bool estVide() {
        return sommet == -1;
    }
};

int main() {
    // Utilisation du template de fonction
    cout << maximum(10, 20) << endl;           // 20 (int)
    cout << maximum(3.5, 2.8) << endl;         // 3.5 (double)
    cout << maximum('a', 'z') << endl;         // 'z' (char)
    cout << maximum(string("abc"), string("xyz")) << endl;  // "xyz" (string)
    
    // Utilisation du template de classe
    Pile<int> pileInt(10);
    pileInt.empiler(10);
    pileInt.empiler(20);
    pileInt.empiler(30);
    
    while (!pileInt.estVide()) {
        cout << pileInt.depiler() << " ";
    }
    cout << endl;
    
    Pile<string> pileString(10);
    pileString.empiler("Premier");
    pileString.empiler("Deuxième");
    pileString.empiler("Troisième");
    
    while (!pileString.estVide()) {
        cout << pileString.depiler() << " ";
    }
    cout << endl;
    
    return 0;
}</code></pre>
            </div>

            <h2 id="stl">{{ trans('app.formations.cpp.stl_title') }}</h2>
            <p>{{ trans('app.formations.cpp.stl_text') }}</p>

            <div class="code-box">
                <pre><code class="language-cpp">#include <iostream>
#include <vector>
#include <list>
#include <map>
#include <algorithm>
#include <string>
using namespace std;

int main() {
    // Vector (tableau dynamique)
    vector<int> nombres = {3, 1, 4, 1, 5, 9, 2, 6};
    
    nombres.push_back(8);  // Ajouter un élément
    nombres.pop_back();    // Retirer le dernier élément
    
    cout << "Taille: " << nombres.size() << endl;
    
    // Parcourir avec range-based for (C++11)
    for (int n : nombres) {
        cout << n << " ";
    }
    cout << endl;
    
    // Trier avec algorithm
    sort(nombres.begin(), nombres.end());
    cout << "Trié: ";
    for (int n : nombres) {
        cout << n << " ";
    }
    cout << endl;
    
    // List (liste doublement chaînée)
    list<string> mots = {"C++", "est", "puissant"};
    mots.push_back("!");
    mots.push_front("Le");
    
    for (const string& mot : mots) {
        cout << mot << " ";
    }
    cout << endl;
    
    // Map (dictionnaire)
    map<string, int> ages;
    ages["Bassirou"] = 25;
    ages["Aminata"] = 30;
    ages["Ibrahima"] = 28;
    
    for (const auto& paire : ages) {
        cout << paire.first << " a " << paire.second << " ans" << endl;
    }
    
    // Algorithmes STL
    vector<int> vec = {1, 2, 3, 4, 5};
    
    // Trouver un élément
    auto it = find(vec.begin(), vec.end(), 3);
    if (it != vec.end()) {
        cout << "Trouvé: " << *it << endl;
    }
    
    // Compter
    int count = count_if(vec.begin(), vec.end(), [](int x) { return x > 2; });
    cout << "Éléments > 2: " << count << endl;
    
    // Transformer
    transform(vec.begin(), vec.end(), vec.begin(), [](int x) { return x * 2; });
    cout << "Doublé: ";
    for (int n : vec) {
        cout << n << " ";
    }
    cout << endl;
    
    return 0;
}</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.cpp.memory_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.cpp.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.cpp.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.cpp.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.cpp.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.cpp.next_steps_further_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $icon = '';
                        $label = '';
                        $description = '';
                        if (preg_match('/^([📚🔧💾🌐📊🎮]) (.+)$/u', $item, $matches)) {
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
                <a href="{{ route('formations.c') }}" class="nav-btn">{{ trans('app.formations.cpp.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.cpp.nav_next') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-cpp.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>
    // Initialiser Prism.js après le chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        function highlightCode() {
        if (typeof Prism !== 'undefined') {
                // Forcer la coloration de tous les blocs de code
                const codeElements = document.querySelectorAll('code[class*="language-"]');
                codeElements.forEach(function(code) {
                    try {
                        Prism.highlightElement(code);
                    } catch (e) {
                        console.error('Erreur Prism:', e);
                    }
                });
                Prism.highlightAll();
            }
        }
        
        highlightCode();
        setTimeout(highlightCode, 200);
        setTimeout(highlightCode, 500);
    });
    
    // Réinitialiser après le chargement complet
    window.addEventListener('load', function() {
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
        const formationSlug = 'cpp';
        const sections = [
            'intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 
            'loops', 'functions', 'classes', 'inheritance', 'polymorphism', 'templates', 'stl'
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
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation C++.</p>
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
