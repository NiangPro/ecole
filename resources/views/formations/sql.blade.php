@extends('layouts.app')

@section('title', trans('app.formations.sql.title') . ' | NiangProgrammeur')

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
            radial-gradient(circle at 20% 30%, rgba(51, 103, 145, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(51, 103, 145, 0.15) 0%, transparent 50%);
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
        background: linear-gradient(180deg, #336791 0%, #2a5475 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2d5f8a 0%, #244a6f 100%);
    }
    .sidebar h3 {
        color: #336791;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(51, 103, 145, 0.2);
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
        background: #336791;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #336791;
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
        border-top: 2px solid rgba(51, 103, 145, 0.2);
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
        border-left: 4px solid #336791;
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
        border: 2px solid #336791;
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
        content: 'SQL';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #336791;
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
        background: #336791;
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
    
    /* Styles Prism.js pour la coloration syntaxique SQL */
    .code-box code[class*="language-"],
    .code-box pre[class*="language-"] {
        color: #e2e8f0;
        text-shadow: none;
        font-family: 'Courier New', 'Consolas', 'Monaco', 'Fira Code', monospace;
        background: transparent !important;
    }
    
    /* Couleurs pour les tokens SQL - Style VS Code Dark */
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
        background-color: #336791;
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
        border-color: rgba(51, 103, 145, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .sidebar h3 {
        color: #336791;
        border-bottom-color: rgba(51, 103, 145, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(51, 103, 145, 0.2) 0%, rgba(51, 103, 145, 0.1) 100%);
        color: #336791;
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
        border-left-color: #336791;
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
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="tutorial-header">
    <div class="tutorial-header-content">
        <h1>
            <i class="fas fa-database" style="margin-right: 15px;"></i>
            {{ trans('app.formations.sql.title') }}
        </h1>
        <p>{{ trans('app.formations.sql.subtitle') }}</p>
    </div>
</div>

<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.sql.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.sql.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #3776ab; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.sql.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'select', 'where', 'joins', 'aggregate', 'groupby', 'subqueries', 'insert', 'tables', 'indexes'];
            @endphp
            @foreach(trans('app.formations.sql.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.sql.intro_title') }}</h1>
            <p>{{ trans('app.formations.sql.intro_text') }}</p>

            <h3>{{ trans('app.formations.sql.what_is_title') }}</h3>
            <p>{!! trans('app.formations.sql.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.sql.why_important_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.sql.why_important_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.sql.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.sql.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.sql.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.sql.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.sql.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.sql.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.sql.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.sql.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.sql.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.sql.use_cases_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $icon = '';
                    $label = '';
                    $description = '';
                    if (preg_match('/^([🌐📊💼🏦🏥🛒📱🔍]) (.+)$/u', $item, $matches)) {
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

            <h2 id="syntax">{{ trans('app.formations.sql.syntax_title') }}</h2>
            <p>{!! trans('app.formations.sql.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Commentaire SQL (ligne unique)
/* Commentaire multi-lignes */

-- Requête SELECT de base
SELECT * FROM utilisateurs;

-- Sélectionner des colonnes spécifiques
SELECT nom, email FROM utilisateurs;

-- Filtrer avec WHERE
SELECT nom, age FROM utilisateurs WHERE age >= 18;

-- Trier avec ORDER BY
SELECT nom, age FROM utilisateurs ORDER BY age DESC;

-- Limiter les résultats
SELECT * FROM utilisateurs LIMIT 10;</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.sql.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.sql.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.sql.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.sql.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Requête complexe avec plusieurs clauses
SELECT 
    u.nom,
    u.email,
    COUNT(c.id) AS nombre_commandes,
    SUM(c.montant) AS total_achats
FROM utilisateurs u
LEFT JOIN commandes c ON u.id = c.utilisateur_id
WHERE u.ville = 'Dakar'
    AND u.age >= 18
GROUP BY u.id, u.nom, u.email
HAVING COUNT(c.id) > 0
ORDER BY total_achats DESC
LIMIT 10;</code></pre>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.sql.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.sql.datatypes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Types numériques
INT                 -- Entier (ex: 42)
BIGINT              -- Grand entier (ex: 1234567890)
DECIMAL(10, 2)      -- Nombre décimal avec précision (ex: 19.99)
FLOAT               -- Nombre à virgule flottante (ex: 3.14)
DOUBLE              -- Double précision

-- Types texte
VARCHAR(255)        -- Chaîne de caractères variable (max 255)
CHAR(10)            -- Chaîne de caractères fixe (10 caractères)
TEXT                -- Texte long (illimité)
TINYTEXT            -- Texte très court

-- Types date/heure
DATE                -- Date uniquement (YYYY-MM-DD)
TIME                -- Heure uniquement (HH:MM:SS)
DATETIME            -- Date et heure (YYYY-MM-DD HH:MM:SS)
TIMESTAMP           -- Horodatage (auto-mise à jour)

-- Types booléens
BOOLEAN             -- Booléen (TRUE/FALSE)
TINYINT(1)          -- Alternative (0/1)

-- Types binaires
BLOB                -- Données binaires
JSON                -- Données JSON (PostgreSQL, MySQL 5.7+)

-- Exemple de création de table avec types
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    age INT,
    solde DECIMAL(10, 2) DEFAULT 0.00,
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.sql.datatypes_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.sql.datatypes_rules_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.sql.operators_title') }}</h2>
            <p>{{ trans('app.formations.sql.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Opérateurs de comparaison
SELECT * FROM produits WHERE prix > 100;        -- Supérieur à
SELECT * FROM produits WHERE prix < 50;         -- Inférieur à
SELECT * FROM produits WHERE prix >= 100;       -- Supérieur ou égal
SELECT * FROM produits WHERE prix <= 50;        -- Inférieur ou égal
SELECT * FROM produits WHERE prix = 99.99;      -- Égal à
SELECT * FROM produits WHERE prix != 100;       -- Différent de
SELECT * FROM produits WHERE prix <> 100;       -- Différent de (alternative)

-- Opérateurs logiques
SELECT * FROM utilisateurs 
WHERE age >= 18 AND ville = 'Dakar';           -- ET logique

SELECT * FROM produits 
WHERE categorie = 'Électronique' OR prix < 50;  -- OU logique

SELECT * FROM commandes 
WHERE NOT statut = 'annulée';                  -- NON logique

-- Opérateur LIKE (recherche de motifs)
SELECT * FROM utilisateurs WHERE nom LIKE 'B%';     -- Commence par 'B'
SELECT * FROM utilisateurs WHERE email LIKE '%@gmail.com';  -- Se termine par
SELECT * FROM produits WHERE nom LIKE '%téléphone%'; -- Contient 'téléphone'

-- Opérateur IN (dans une liste)
SELECT * FROM produits 
WHERE categorie IN ('Électronique', 'Informatique', 'Téléphonie');

-- Opérateur BETWEEN (entre deux valeurs)
SELECT * FROM produits 
WHERE prix BETWEEN 50 AND 200;

-- Opérateur IS NULL / IS NOT NULL
SELECT * FROM utilisateurs WHERE email IS NULL;
SELECT * FROM utilisateurs WHERE email IS NOT NULL;</code></pre>
            </div>

            <h2 id="select">{{ trans('app.formations.sql.select_title') }}</h2>
            <p>{!! trans('app.formations.sql.select_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- SELECT de base : récupérer toutes les colonnes
SELECT * FROM utilisateurs;

-- SELECT avec colonnes spécifiques
SELECT nom, email, age FROM utilisateurs;

-- SELECT avec alias de colonnes
SELECT 
    nom AS nom_utilisateur,
    email AS adresse_email,
    age AS age_utilisateur
FROM utilisateurs;

-- SELECT avec calculs
SELECT 
    nom,
    prix,
    quantite,
    prix * quantite AS total
FROM commandes;

-- SELECT DISTINCT : éliminer les doublons
SELECT DISTINCT ville FROM utilisateurs;

-- SELECT avec ORDER BY : trier les résultats
SELECT nom, age FROM utilisateurs ORDER BY age ASC;   -- Croissant
SELECT nom, age FROM utilisateurs ORDER BY age DESC;  -- Décroissant
SELECT nom, age, ville FROM utilisateurs ORDER BY ville, age DESC;  -- Tri multiple

-- SELECT avec LIMIT : limiter le nombre de résultats
SELECT * FROM produits LIMIT 10;              -- 10 premiers
SELECT * FROM produits LIMIT 10 OFFSET 20;     -- 10 résultats à partir du 21ème</code></pre>
            </div>

            <h2 id="where">{{ trans('app.formations.sql.where_title') }}</h2>
            <p>{!! trans('app.formations.sql.where_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- WHERE simple
SELECT * FROM utilisateurs WHERE age >= 18;

-- WHERE avec plusieurs conditions (AND)
SELECT * FROM produits 
WHERE categorie = 'Électronique' AND prix < 100;

-- WHERE avec OR
SELECT * FROM commandes 
WHERE statut = 'en_attente' OR statut = 'en_cours';

-- WHERE avec LIKE (recherche de motifs)
SELECT * FROM utilisateurs WHERE nom LIKE 'B%';        -- Commence par 'B'
SELECT * FROM utilisateurs WHERE email LIKE '%@gmail.com';  -- Se termine par
SELECT * FROM produits WHERE nom LIKE '%téléphone%';   -- Contient 'téléphone'

-- WHERE avec IN
SELECT * FROM produits 
WHERE categorie IN ('Électronique', 'Informatique', 'Téléphonie');

-- WHERE avec BETWEEN
SELECT * FROM commandes 
WHERE date_commande BETWEEN '2024-01-01' AND '2024-12-31';

-- WHERE avec IS NULL / IS NOT NULL
SELECT * FROM utilisateurs WHERE email IS NULL;
SELECT * FROM utilisateurs WHERE email IS NOT NULL;</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.sql.conditions_title') }}</h2>
            <p>{!! trans('app.formations.sql.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Condition simple
SELECT * FROM utilisateurs WHERE age >= 18;

-- Conditions multiples avec AND
SELECT * FROM produits 
WHERE categorie = 'Électronique' AND prix < 100;

-- Conditions multiples avec OR
SELECT * FROM commandes 
WHERE statut = 'en_attente' OR statut = 'en_cours';

-- Combinaison AND/OR avec parenthèses
SELECT * FROM utilisateurs 
WHERE (ville = 'Dakar' OR ville = 'Thiès') AND age >= 18;

-- Utilisation de NOT
SELECT * FROM produits 
WHERE NOT categorie = 'Alimentaire';

-- Conditions avec LIKE
SELECT * FROM utilisateurs 
WHERE nom LIKE 'B%' AND email LIKE '%@gmail.com';

-- Conditions avec IN
SELECT * FROM produits 
WHERE id IN (1, 5, 10, 15);

-- Conditions avec BETWEEN
SELECT * FROM commandes 
WHERE date_commande BETWEEN '2024-01-01' AND '2024-12-31';

-- Conditions avec IS NULL
SELECT * FROM utilisateurs 
WHERE telephone IS NULL;</code></pre>
            </div>

            <h2 id="joins">{{ trans('app.formations.sql.joins_title') }}</h2>
            <p>{{ trans('app.formations.sql.joins_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- INNER JOIN (jointure interne)
-- Retourne uniquement les lignes qui ont une correspondance dans les deux tables
SELECT u.nom, u.email, c.montant, c.date_commande
FROM utilisateurs u
INNER JOIN commandes c ON u.id = c.utilisateur_id;

-- LEFT JOIN (jointure gauche)
-- Retourne toutes les lignes de la table de gauche, même sans correspondance
SELECT u.nom, u.email, c.montant
FROM utilisateurs u
LEFT JOIN commandes c ON u.id = c.utilisateur_id;

-- RIGHT JOIN (jointure droite)
-- Retourne toutes les lignes de la table de droite
SELECT u.nom, p.nom AS produit
FROM commandes c
RIGHT JOIN produits p ON c.produit_id = p.id;

-- FULL JOIN (jointure complète) - PostgreSQL, SQL Server
SELECT u.nom, c.montant
FROM utilisateurs u
FULL OUTER JOIN commandes c ON u.id = c.utilisateur_id;

-- Jointure multiple (plusieurs tables)
SELECT u.nom, p.nom AS produit, c.quantite, c.montant
FROM utilisateurs u
INNER JOIN commandes c ON u.id = c.utilisateur_id
INNER JOIN produits p ON c.produit_id = p.id;

-- Auto-jointure (jointure d'une table avec elle-même)
SELECT e1.nom AS employe, e2.nom AS manager
FROM employes e1
LEFT JOIN employes e2 ON e1.manager_id = e2.id;</code></pre>
            </div>

            <h2 id="aggregate">{{ trans('app.formations.sql.aggregate_title') }}</h2>
            <p>{!! trans('app.formations.sql.aggregate_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- COUNT : Compter le nombre de lignes
SELECT COUNT(*) FROM utilisateurs;
SELECT COUNT(*) FROM commandes WHERE statut = 'livrée';

-- COUNT avec DISTINCT
SELECT COUNT(DISTINCT ville) FROM utilisateurs;

-- SUM : Somme des valeurs
SELECT SUM(montant) FROM commandes;
SELECT SUM(quantite * prix) AS total FROM commandes;

-- AVG : Moyenne
SELECT AVG(prix) FROM produits;
SELECT AVG(age) FROM utilisateurs WHERE ville = 'Dakar';

-- MAX : Valeur maximale
SELECT MAX(prix) FROM produits;
SELECT MAX(date_commande) FROM commandes;

-- MIN : Valeur minimale
SELECT MIN(prix) FROM produits;
SELECT MIN(age) FROM utilisateurs;

-- Combinaison de fonctions d'agrégation
SELECT 
    COUNT(*) AS nombre_commandes,
    SUM(montant) AS total_ventes,
    AVG(montant) AS moyenne_vente,
    MAX(montant) AS plus_grande_commande,
    MIN(montant) AS plus_petite_commande
FROM commandes
WHERE date_commande >= '2024-01-01';</code></pre>
            </div>

            <h2 id="groupby">{{ trans('app.formations.sql.groupby_title') }}</h2>
            <p>{!! trans('app.formations.sql.groupby_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- GROUP BY simple
SELECT ville, COUNT(*) AS nombre_utilisateurs
FROM utilisateurs
GROUP BY ville;

-- GROUP BY avec plusieurs colonnes
SELECT ville, statut, COUNT(*) AS nombre
FROM commandes
GROUP BY ville, statut;

-- GROUP BY avec fonctions d'agrégation
SELECT 
    categorie,
    COUNT(*) AS nombre_produits,
    AVG(prix) AS prix_moyen,
    SUM(stock) AS stock_total
FROM produits
GROUP BY categorie;

-- HAVING : Filtrer les groupes (après GROUP BY)
SELECT 
    utilisateur_id,
    COUNT(*) AS nombre_commandes,
    SUM(montant) AS total
FROM commandes
GROUP BY utilisateur_id
HAVING COUNT(*) > 5;  -- Seulement les utilisateurs avec plus de 5 commandes

-- GROUP BY avec ORDER BY
SELECT 
    ville,
    COUNT(*) AS nombre_utilisateurs,
    AVG(age) AS age_moyen
FROM utilisateurs
GROUP BY ville
ORDER BY nombre_utilisateurs DESC;

-- GROUP BY avec WHERE et HAVING
SELECT 
    categorie,
    AVG(prix) AS prix_moyen
FROM produits
WHERE stock > 0  -- Filtrer avant le GROUP BY
GROUP BY categorie
HAVING AVG(prix) > 50  -- Filtrer après le GROUP BY
ORDER BY prix_moyen DESC;</code></pre>
            </div>

            <h2 id="insert">{{ trans('app.formations.sql.insert_title') }}</h2>
            <p>{!! trans('app.formations.sql.insert_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- INSERT : Insérer de nouvelles lignes
INSERT INTO utilisateurs (nom, email, age, ville)
VALUES ('Bassirou Niang', 'bassirou@example.com', 25, 'Dakar');

-- INSERT multiple
INSERT INTO utilisateurs (nom, email, age, ville)
VALUES 
    ('Aminata Diallo', 'aminata@example.com', 30, 'Thiès'),
    ('Ibrahima Ba', 'ibrahima@example.com', 28, 'Dakar'),
    ('Fatou Sarr', 'fatou@example.com', 22, 'Saint-Louis');

-- INSERT avec SELECT (copier des données)
INSERT INTO utilisateurs_archive (nom, email, age)
SELECT nom, email, age
FROM utilisateurs
WHERE date_creation < '2020-01-01';

-- UPDATE : Modifier des lignes existantes
UPDATE utilisateurs
SET age = 26, ville = 'Thiès'
WHERE id = 1;

-- UPDATE multiple colonnes
UPDATE produits
SET prix = prix * 1.1,  -- Augmenter de 10%
    stock = stock - 5
WHERE categorie = 'Électronique';

-- UPDATE avec condition
UPDATE commandes
SET statut = 'livrée', date_livraison = CURRENT_DATE
WHERE id = 100 AND statut = 'en_cours';

-- DELETE : Supprimer des lignes
DELETE FROM utilisateurs
WHERE id = 5;

-- DELETE avec condition
DELETE FROM commandes
WHERE statut = 'annulée' AND date_commande < '2023-01-01';

-- DELETE avec JOIN (MySQL)
DELETE u FROM utilisateurs u
LEFT JOIN commandes c ON u.id = c.utilisateur_id
WHERE c.id IS NULL;  -- Supprimer les utilisateurs sans commandes

-- TRUNCATE : Vider une table (plus rapide que DELETE)
TRUNCATE TABLE logs;  -- Supprime toutes les lignes mais garde la structure</code></pre>
            </div>

            <h2 id="subqueries">{{ trans('app.formations.sql.subqueries_title') }}</h2>
            <p>{{ trans('app.formations.sql.subqueries_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Sous-requête dans WHERE (scalaire)
SELECT * FROM produits
WHERE prix > (SELECT AVG(prix) FROM produits);

-- Sous-requête avec IN
SELECT * FROM utilisateurs
WHERE id IN (
    SELECT utilisateur_id 
    FROM commandes 
    WHERE montant > 1000
);

-- Sous-requête avec NOT IN
SELECT * FROM produits
WHERE id NOT IN (
    SELECT produit_id 
    FROM commandes
);

-- Sous-requête dans SELECT (corrélée)
SELECT 
    nom,
    (SELECT COUNT(*) 
     FROM commandes c 
     WHERE c.utilisateur_id = u.id) AS nombre_commandes
FROM utilisateurs u;

-- Sous-requête dans FROM (table dérivée)
SELECT 
    ville,
    COUNT(*) AS nombre_utilisateurs
FROM (
    SELECT DISTINCT ville, id
    FROM utilisateurs
    WHERE age >= 18
) AS utilisateurs_majeurs
GROUP BY ville;

-- Sous-requête avec EXISTS
SELECT * FROM utilisateurs u
WHERE EXISTS (
    SELECT 1 
    FROM commandes c 
    WHERE c.utilisateur_id = u.id
);

-- Sous-requête avec ALL
SELECT * FROM produits
WHERE prix > ALL (
    SELECT prix 
    FROM produits 
    WHERE categorie = 'Alimentaire'
);

-- Sous-requête avec ANY/SOME
SELECT * FROM produits
WHERE prix > ANY (
    SELECT prix 
    FROM produits 
    WHERE categorie = 'Électronique'
);</code></pre>
            </div>

            <h2 id="tables">{{ trans('app.formations.sql.tables_title') }}</h2>
            <p>{!! trans('app.formations.sql.tables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Création de table simple
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    age INT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Création avec contraintes
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL CHECK (prix > 0),
    stock INT DEFAULT 0 CHECK (stock >= 0),
    categorie_id INT,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    UNIQUE (nom)
);

-- Contraintes principales
-- PRIMARY KEY : Clé primaire (unique et non nulle)
-- FOREIGN KEY : Clé étrangère (référence une autre table)
-- UNIQUE : Valeur unique dans la colonne
-- NOT NULL : La colonne ne peut pas être NULL
-- CHECK : Vérifie une condition
-- DEFAULT : Valeur par défaut

-- ALTER TABLE : Modifier une table existante
ALTER TABLE utilisateurs
ADD COLUMN telephone VARCHAR(20);

ALTER TABLE utilisateurs
MODIFY COLUMN email VARCHAR(255) NOT NULL;

ALTER TABLE utilisateurs
DROP COLUMN telephone;

-- DROP TABLE : Supprimer une table
DROP TABLE IF EXISTS ancienne_table;</code></pre>
            </div>

            <h2 id="create">{{ trans('app.formations.sql.tables_title') }}</h2>
            <p>{!! trans('app.formations.sql.tables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Création de table simple
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    age INT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Création avec contraintes
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL CHECK (prix > 0),
    stock INT DEFAULT 0 CHECK (stock >= 0),
    categorie_id INT,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    UNIQUE (nom)
);

-- Contraintes principales
-- PRIMARY KEY : Clé primaire (unique et non nulle)
-- FOREIGN KEY : Clé étrangère (référence une autre table)
-- UNIQUE : Valeur unique dans la colonne
-- NOT NULL : La colonne ne peut pas être NULL
-- CHECK : Vérifie une condition
-- DEFAULT : Valeur par défaut

-- ALTER TABLE : Modifier une table existante
ALTER TABLE utilisateurs
ADD COLUMN telephone VARCHAR(20);

ALTER TABLE utilisateurs
MODIFY COLUMN email VARCHAR(255) NOT NULL;

ALTER TABLE utilisateurs
DROP COLUMN telephone;

ALTER TABLE produits
ADD CONSTRAINT fk_categorie
FOREIGN KEY (categorie_id) REFERENCES categories(id);

-- DROP TABLE : Supprimer une table
DROP TABLE IF EXISTS ancienne_table;</code></pre>
            </div>

            <h2 id="indexes">{{ trans('app.formations.sql.indexes_title') }}</h2>
            <p>{{ trans('app.formations.sql.indexes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-sql">-- Créer un index simple
CREATE INDEX idx_email ON utilisateurs(email);

-- Créer un index unique
CREATE UNIQUE INDEX idx_email_unique ON utilisateurs(email);

-- Créer un index composite (plusieurs colonnes)
CREATE INDEX idx_nom_ville ON utilisateurs(nom, ville);

-- Index sur une expression (PostgreSQL)
CREATE INDEX idx_nom_lower ON utilisateurs(LOWER(nom));

-- Vérifier les index d'une table (MySQL)
SHOW INDEX FROM utilisateurs;

-- Supprimer un index
DROP INDEX idx_email ON utilisateurs;

-- Index automatiques
-- PRIMARY KEY crée automatiquement un index
-- UNIQUE crée automatiquement un index

-- Bonnes pratiques pour les index
-- ✅ Créer des index sur les colonnes utilisées dans WHERE
-- ✅ Créer des index sur les colonnes utilisées dans JOIN
-- ✅ Créer des index sur les colonnes utilisées dans ORDER BY
-- ❌ Éviter trop d'index (ralentissent les INSERT/UPDATE)
-- ❌ Éviter les index sur des colonnes peu utilisées

-- EXPLAIN : Analyser l'exécution d'une requête
EXPLAIN SELECT * FROM utilisateurs WHERE email = 'test@example.com';

-- ANALYZE TABLE : Mettre à jour les statistiques (MySQL)
ANALYZE TABLE utilisateurs;</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.sql.indexes_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.sql.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.sql.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.sql.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.sql.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.sql.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.sql.next_steps_further_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $icon = '';
                        $label = '';
                        $description = '';
                        if (preg_match('/^([📚🔧📦🌐📊🤖🔐]) (.+)$/u', $item, $matches)) {
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
                <a href="{{ route('formations.ia') }}" class="nav-btn">{{ trans('app.formations.sql.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.sql.nav_next') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>
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
</script>
@endsection
