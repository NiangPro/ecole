@extends('layouts.app')

@section('title', trans('app.formations.typescript.title') . ' | NiangProgrammeur')

@section('styles')
<!-- Google Fonts - Fira Code pour une meilleure lisibilité du code -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        background: linear-gradient(180deg, #3178c6 0%, #2563eb 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2563eb 0%, #1e40af 100%);
    }
    .sidebar h3 {
        color: #3178c6;
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
        background: #3178c6;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #3178c6;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #3178c6 0%, #2563eb 100%);
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
        background: linear-gradient(135deg, #3178c6, #2563eb);
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
        background: linear-gradient(135deg, #2563eb, #1e40af);
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
        border-left: 4px solid #3178c6;
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
        border: 2px solid #3178c6;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Fira Code', 'Consolas', 'Monaco', 'Courier New', monospace;
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
        content: 'typescript';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #3178c6;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 1;
    }
    
    /* Bouton de copie - Même taille que le label typescript */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 100px;
        background: #3178c6;
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
        line-height: 1.8;
        font-family: 'Fira Code', 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 15px;
        font-weight: 400;
        white-space: pre;
        overflow-x: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    
    /* Forcer la coloration Prism.js */
    .code-box pre code[class*="language-"],
    .code-box pre[class*="language-"] code {
        color: inherit;
        text-shadow: none;
    }
    
    /* Améliorer la netteté pour tous les éléments de code */
    .code-box pre,
    .code-box code,
    .code-box pre code {
        font-feature-settings: "liga" 1, "calt" 1;
        font-variant-ligatures: common-ligatures;
        letter-spacing: 0;
    }
    
    /* Styles spécifiques pour TypeScript - forcer les couleurs Prism */
    .code-box pre code.language-typescript,
    .code-box pre code.language-ts {
        color: #e2e8f0 !important;
    }
    
    .code-box pre code.language-typescript .token.keyword,
    .code-box pre code.language-ts .token.keyword {
        color: #c792ea !important;
    }
    
    .code-box pre code.language-typescript .token.string,
    .code-box pre code.language-ts .token.string {
        color: #c3e88d !important;
    }
    
    .code-box pre code.language-typescript .token.function,
    .code-box pre code.language-ts .token.function {
        color: #82aaff !important;
    }
    
    .code-box pre code.language-typescript .token.number,
    .code-box pre code.language-ts .token.number {
        color: #f78c6c !important;
    }
    
    .code-box pre code.language-typescript .token.comment,
    .code-box pre code.language-ts .token.comment {
        color: #546e7a !important;
        font-style: italic;
    }
    
    .code-box pre code.language-typescript .token.operator,
    .code-box pre code.language-ts .token.operator {
        color: #89ddff !important;
    }
    
    .code-box pre code.language-typescript .token.punctuation,
    .code-box pre code.language-ts .token.punctuation {
        color: #89ddff !important;
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
        background-color: #3178c6;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #2563eb;
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
        color: #3178c6;
        border-bottom-color: rgba(55, 118, 171, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.2) 0%, rgba(55, 118, 171, 0.1) 100%);
        color: #3178c6;
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
        border-left-color: #3178c6;
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
        background-color: #3178c6;
        color: white;
    }
    
    body.dark-mode .nav-btn:hover {
        background-color: #2563eb;
    }
    
    body.dark-mode .sidebar-toggle-btn {
        background: linear-gradient(135deg, #3178c6, #2563eb);
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
        <i class="fab fa-typescript" style="margin-right: 15px;"></i>
            {{ trans('app.formations.typescript.title') }}
    </h1>
        <p>{{ trans('app.formations.typescript.subtitle') }}</p>
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button data-favorite 
                    data-favorite-type="formation" 
                    data-favorite-slug="typescript" 
                    data-favorite-name="{{ trans('app.formations.typescript.title') }}"
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
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.typescript.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.typescript.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #3178c6; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.typescript.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'functions', 'lists', 'modules', 'oop', 'files'];
            @endphp
            @foreach(trans('app.formations.typescript.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.typescript.intro_title') }}</h1>
            <p>{!! trans('app.formations.typescript.intro_text') !!}</p>

            <h3>{{ trans('app.formations.typescript.what_is_title') }}</h3>
            <p>{!! trans('app.formations.typescript.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.why_popular_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.typescript.why_popular_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.typescript.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.typescript.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.typescript.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.typescript.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.typescript.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.typescript.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.typescript.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.typescript.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.typescript.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.typescript.use_cases_items') as $item)
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

            <h2 id="syntax">{{ trans('app.formations.typescript.syntax_title') }}</h2>
            <p>{!! trans('app.formations.typescript.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Premier programme TypeScript
console.log("Bonjour, monde !");

// Variables avec types
let nom: string = "NiangProgrammeur";
let age: number = 25;

// Template literals pour formater les chaînes
console.log(`Je m'appelle ${nom} et j'ai ${age} ans`);

// Opérations simples
let resultat: number = 10 + 5;
console.log(`10 + 5 = ${resultat}`);</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.typescript.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.typescript.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.typescript.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Définition d'une fonction
function calculerMoyenne(nombres: number[]): number {
    // Calcule la moyenne d'un tableau de nombres
    if (nombres.length === 0) {
        return 0;
    }
    const somme = nombres.reduce((acc, val) => acc + val, 0);
    const moyenne = somme / nombres.length;
    return moyenne;
}

// Utilisation
const notes: number[] = [15, 18, 12, 20, 16];
const moyenne = calculerMoyenne(notes);
console.log(`La moyenne est : ${moyenne}`);</code></pre>
            </div>

            <h2 id="variables">{{ trans('app.formations.typescript.variables_title') }}</h2>
            <p>{!! trans('app.formations.typescript.variables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Déclaration de variables avec types
let nom: string = "TypeScript";    // String (chaîne de caractères)
let age: number = 30;              // Number (entier ou décimal)
let prix: number = 19.99;           // Number (nombre décimal)
let estActif: boolean = true;      // Boolean (booléen)
let valeurNulle: null = null;      // null (valeur nulle)
let valeurUndefined: undefined = undefined;  // undefined

// Affichage
console.log(nom);
console.log(age);
console.log(prix);
console.log(estActif);
console.log(valeurNulle);

// Réassignation (même type)
let variable: number = 10;
console.log(typeof variable);      // "number"

// variable = "Dix";                // Erreur : type incompatible

// Noms de variables valides (camelCase)
let nomUtilisateur: string = "Bassirou";
let ageUtilisateur: number = 25;
let _prive: string = "variable privée";
const CONSTANTE: number = 3.14159;  // Constante</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.typescript.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.typescript.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.typescript.datatypes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Types de base
let texte: string = "Hello";       // String (chaîne de caractères)
let nombre: number = 42;           // Number (entier)
let decimal: number = 3.14;        // Number (nombre décimal)
let booleen: boolean = true;        // Boolean (booléen)
let valeurNulle: null = null;      // null (valeur nulle)
let valeurUndefined: undefined = undefined;  // undefined

// Collections (structures de données)
let liste: number[] = [1, 2, 3, 4, 5];  // Array (tableau ordonné, modifiable)
let tuple: [number, number, number] = [1, 2, 3];  // Tuple (tableau à taille fixe)
let dictionnaire: { nom: string; age: number } = { nom: "TypeScript", age: 30 };  // Object (paires clé-valeur)
let ensemble: Set&lt;number&gt; = new Set([1, 2, 3, 4]);  // Set (ensemble unique, non ordonné)

// Vérifier le type
console.log(typeof texte);         // "string"
console.log(typeof nombre);        // "number"
console.log(Array.isArray(liste)); // true
console.log(typeof dictionnaire);  // "object"

// Conversion de types
let ageStr: string = String(25);   // Convertir en string
let ageInt: number = parseInt("25", 10);  // Convertir en entier
let prixFloat: number = parseFloat("19.99");  // Convertir en décimal</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.typescript.datatypes_list_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.typescript.operators_title') }}</h2>
            <p>{{ trans('app.formations.typescript.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Opérateurs arithmétiques
let a: number = 10;
let b: number = 3;

console.log(a + b);    // Addition: 13
console.log(a - b);    // Soustraction: 7
console.log(a * b);    // Multiplication: 30
console.log(a / b);    // Division: 3.3333333333333335
console.log(Math.floor(a / b));  // Division entière: 3
console.log(a % b);    // Modulo (reste): 1
console.log(Math.pow(a, b));  // Puissance: 1000

// Opérateurs de comparaison
console.log(a > b);    // true (supérieur à)
console.log(a < b);    // false (inférieur à)
console.log(a >= b);   // true (supérieur ou égal)
console.log(a <= b);   // false (inférieur ou égal)
console.log(a === b);  // false (égalité stricte)
console.log(a !== b);  // true (différent)

// Opérateurs logiques
let x: boolean = true;
let y: boolean = false;
console.log(x && y);   // false (ET logique)
console.log(x || y);   // true (OU logique)
console.log(!x);       // false (NON logique)

// Opérateurs d'assignation
let c: number = 5;
c += 3;                 // Équivalent à c = c + 3 (c devient 8)
c -= 2;                 // Équivalent à c = c - 2 (c devient 6)
c *= 2;                 // Équivalent à c = c * 2 (c devient 12)
c /= 3;                 // Équivalent à c = c / 3 (c devient 4)

// Comparaison d'objets
let liste1: number[] = [1, 2, 3];
let liste2: number[] = [1, 2, 3];
let liste3 = liste1;

console.log(liste1 === liste2);    // false (références différentes)
console.log(liste1 === liste3);    // true (même référence)
console.log(JSON.stringify(liste1) === JSON.stringify(liste2));  // true (valeurs égales)</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.typescript.conditions_title') }}</h2>
            <p>{!! trans('app.formations.typescript.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Structure if simple
let age: number = 20;

if (age >= 18) {
    console.log("Vous êtes majeur");
} else {
    console.log("Vous êtes mineur");
}

// Structure if/else if/else
let age2: number = 15;

if (age2 >= 18) {
    console.log("Vous êtes majeur");
    console.log("Vous pouvez voter");
} else if (age2 >= 13) {
    console.log("Vous êtes adolescent");
} else if (age2 >= 6) {
    console.log("Vous êtes enfant");
} else {
    console.log("Vous êtes un bébé");
}

// Conditions multiples
let note: number = 85;
let mention: string;

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

console.log(`Votre mention : ${mention}`);

// Opérateur ternaire (expression conditionnelle)
let age3: number = 20;
let statut: string = age3 >= 18 ? "Majeur" : "Mineur";
console.log(statut);

// Conditions avec &&/||
let age4: number = 25;
let permis: boolean = true;

if (age4 >= 18 && permis) {
    console.log("Vous pouvez conduire");
} else {
    console.log("Vous ne pouvez pas conduire");
}</code></pre>
            </div>

            <h2 id="loops">{{ trans('app.formations.typescript.loops_title') }}</h2>
            <p>{!! trans('app.formations.typescript.loops_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Boucle for classique
for (let i = 0; i < 5; i++) {
    console.log(i);  // Affiche 0, 1, 2, 3, 4
}

// Boucle for avec début et fin
for (let i = 1; i <= 5; i++) {
    console.log(i);  // Affiche 1, 2, 3, 4, 5
}

// Boucle for avec pas
for (let i = 0; i < 10; i += 2) {
    console.log(i);  // Affiche 0, 2, 4, 6, 8
}

// Boucle for...of avec tableau
let fruits: string[] = ["pomme", "banane", "orange"];
for (let fruit of fruits) {
    console.log(`J'aime les ${fruit}`);
}

// Boucle for...in avec index
let fruits2: string[] = ["pomme", "banane", "orange"];
for (let index in fruits2) {
    console.log(`${index}: ${fruits2[index]}`);
}

// Boucle while
let compteur: number = 0;
while (compteur < 5) {
    console.log(compteur);
    compteur++;
}

// Boucle while avec break
let compteur2: number = 0;
while (true) {
    console.log(compteur2);
    compteur2++;
    if (compteur2 >= 5) {
        break;  // Sortir de la boucle
    }
}

// continue (passer à l'itération suivante)
for (let i = 0; i < 10; i++) {
    if (i % 2 === 0) {  // Si i est pair
        continue;        // Passer au suivant
    }
    console.log(i);      // Affiche seulement les impairs: 1, 3, 5, 7, 9
}

// Boucle do...while
let compteur3: number = 0;
do {
    console.log(compteur3);
    compteur3++;
} while (compteur3 < 5);</code></pre>
            </div>

            <h2 id="functions">{{ trans('app.formations.typescript.functions_title') }}</h2>
            <p>{!! trans('app.formations.typescript.functions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Fonction simple (sans paramètres)
function direBonjour(): void {
    console.log("Bonjour !");
}

direBonjour();  // Appel de la fonction

// Fonction avec paramètres
function saluer(nom: string): string {
    return `Bonjour, ${nom} !`;
}

let message: string = saluer("TypeScript");
console.log(message);  // "Bonjour, TypeScript !"

// Fonction avec plusieurs paramètres
function additionner(a: number, b: number): number {
    return a + b;
}

let resultat: number = additionner(5, 3);
console.log(resultat);  // 8

// Fonction avec paramètres par défaut
function saluerPersonne(nom: string, message: string = "Bonjour"): string {
    return `${message}, ${nom} !`;
}

console.log(saluerPersonne("Bassirou"));              // "Bonjour, Bassirou !"
console.log(saluerPersonne("Bassirou", "Salut"));     // "Salut, Bassirou !"

// Fonction avec paramètres nommés (via objet)
function creerPersonne({ nom, age, ville = "Dakar" }: { nom: string; age: number; ville?: string }): string {
    return `${nom}, ${age} ans, habite à ${ville}`;
}

console.log(creerPersonne({ nom: "Bassirou", age: 25 }));
console.log(creerPersonne({ nom: "Bassirou", age: 25, ville: "Thiès" }));

// Fonction avec rest parameters (arguments variables)
function additionnerNombres(...args: number[]): number {
    return args.reduce((acc, val) => acc + val, 0);
}

console.log(additionnerNombres(1, 2, 3, 4, 5));  // 15

// Fonction avec objet comme paramètre (équivalent **kwargs)
function afficherInfo(info: { nom: string; age: number; ville?: string }): void {
    for (let cle in info) {
        console.log(`${cle}: ${info[cle as keyof typeof info]}`);
    }
}

afficherInfo({ nom: "Bassirou", age: 25, ville: "Dakar" });

// Fonction fléchée (arrow function)
const carre = (x: number): number => x * x;
console.log(carre(5));  // 25

// Utilisation de map() avec fonction fléchée
let nombres: number[] = [1, 2, 3, 4, 5];
let carres: number[] = nombres.map(x => x * x);
console.log(carres);  // [1, 4, 9, 16, 25]</code></pre>
            </div>

            <h2 id="lists">{{ trans('app.formations.typescript.lists_title') }}</h2>
            <p>{{ trans('app.formations.typescript.lists_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// ========== ARRAYS ==========
// Création de tableaux
let nombres: number[] = [1, 2, 3, 4, 5];
let fruits: string[] = ["pomme", "banane", "orange"];
let listeMixte: (number | string | boolean)[] = [1, "deux", 3.0, true];

// Accès aux éléments (index commence à 0)
console.log(fruits[0]);        // "pomme" (premier élément)
console.log(fruits[fruits.length - 1]);  // "orange" (dernier élément)

// Modification
fruits[1] = "mangue";    // Remplacer "banane" par "mangue"

// Méthodes des tableaux
fruits.push("kiwi");           // Ajouter à la fin
fruits.splice(1, 0, "ananas");  // Insérer à l'index 1
let index = fruits.indexOf("pomme");
if (index > -1) fruits.splice(index, 1);  // Supprimer un élément
fruits.pop();                  // Supprimer le dernier élément
fruits.shift();                // Supprimer le premier élément

// Autres méthodes utiles
console.log(fruits.length);              // Longueur du tableau
console.log(fruits.filter(f => f === "banane").length);  // Compter les occurrences
fruits.sort();                   // Trier le tableau
fruits.reverse();                // Inverser le tableau

// Slicing (tranches) avec slice()
let nombres2: number[] = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
console.log(nombres2.slice(2, 5));     // [2, 3, 4] (de l'index 2 à 4)
console.log(nombres2.slice(0, 3));     // [0, 1, 2] (du début à l'index 2)
console.log(nombres2.slice(3));        // [3, 4, 5, 6, 7, 8, 9] (de l'index 3 à la fin)

// ========== OBJECTS ==========
// Création d'objets
interface Personne {
    nom: string;
    age: number;
    ville?: string;
    email?: string;
}

let personne: Personne = {
    nom: "Bassirou",
    age: 25,
    ville: "Dakar"
};

// Accès aux valeurs
console.log(personne.nom);          // "Bassirou"
console.log(personne["age"]);       // 25
console.log(personne.email || "Non renseigné");  // Valeur par défaut

// Modification et ajout
personne.age = 26;                  // Modifier
personne.email = "bassirou@example.com";  // Ajouter

// Méthodes des objets
console.log(Object.keys(personne));    // Toutes les clés
console.log(Object.values(personne));   // Toutes les valeurs
console.log(Object.entries(personne)); // Toutes les paires clé-valeur

// Parcourir un objet
for (let [cle, valeur] of Object.entries(personne)) {
    console.log(`${cle}: ${valeur}`);
}

// Supprimer
delete personne.email;              // Supprimer une propriété</code></pre>
            </div>

            <h2 id="modules">{{ trans('app.formations.typescript.modules_title') }}</h2>
            <p>{{ trans('app.formations.typescript.modules_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Importer un module complet (ES6 modules)
import * as Math from 'math';

console.log(Math.sqrt(16));        // 4 (racine carrée)
console.log(Math.PI);              // 3.141592653589793
console.log(Math.cos(0));          // 1

// Importer avec un alias
import * as dt from 'date-fns';
let maintenant = dt.now();
console.log(maintenant);

// Importer des fonctions spécifiques
import { sqrt, PI } from 'math';
console.log(sqrt(25));             // 5
console.log(PI);                   // 3.141592653589793

// Importer avec alias
import { sqrt as racineCarree } from 'math';
console.log(racineCarree(16));     // 4

// Modules standards utiles
// Math (built-in)
console.log(Math.random() * 100);  // Nombre aléatoire entre 0 et 100
console.log(Math.floor(Math.random() * 100) + 1);  // Entre 1 et 100

// Date (built-in)
let maintenant2 = new Date();
console.log(maintenant2);

// Créer son propre module
// Créer un fichier monModule.ts avec :
// export function maFonction(): string {
//     return "Hello from module";
// }
//
// Puis l'importer :
// import { maFonction } from './monModule';
// console.log(maFonction());

// Export par défaut
// Dans monModule.ts :
// export default function maFonction() { ... }
//
// Import :
// import maFonction from './monModule';</code></pre>
            </div>

            <h2 id="oop">{{ trans('app.formations.typescript.oop_title') }}</h2>
            <p>{{ trans('app.formations.typescript.oop_text') }}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Définir une classe
class Personne {
    // Propriétés de classe
    nom: string;
    age: number;
    
    // Constructeur
    constructor(nom: string, age: number) {
        this.nom = nom;      // Attribut d'instance
        this.age = age;
    }
    
    // Méthode d'instance
    sePresenter(): string {
        return `Je m'appelle ${this.nom} et j'ai ${this.age} ans`;
    }
    
    avoirAns(annees: number): string {
        this.age += annees;
        return `Dans ${annees} ans, j'aurai ${this.age} ans`;
    }
}

// Créer des objets (instances)
let personne1 = new Personne("Bassirou", 25);
let personne2 = new Personne("Aminata", 30);

// Utiliser les méthodes
console.log(personne1.sePresenter());
console.log(personne2.sePresenter());
console.log(personne1.avoirAns(5));

// Accéder aux attributs
console.log(personne1.nom);
console.log(personne1.age);

// Classe avec propriétés statiques
class Voiture {
    // Propriété statique (partagée par toutes les instances)
    static nombreVoitures: number = 0;
    
    marque: string;
    modele: string;
    
    constructor(marque: string, modele: string) {
        this.marque = marque;
        this.modele = modele;
        Voiture.nombreVoitures++;
    }
    
    toString(): string {
        return `${this.marque} ${this.modele}`;
    }
}

let voiture1 = new Voiture("Toyota", "Corolla");
let voiture2 = new Voiture("Honda", "Civic");
console.log(`Nombre de voitures créées : ${Voiture.nombreVoitures}`);

// Héritage
class Etudiant extends Personne {
    ecole: string;
    
    constructor(nom: string, age: number, ecole: string) {
        super(nom, age);  // Appeler le constructeur parent
        this.ecole = ecole;
    }
    
    etudier(): string {
        return `${this.nom} étudie à ${this.ecole}`;
    }
}

let etudiant = new Etudiant("Bassirou", 25, "UCAD");
console.log(etudiant.sePresenter());  // Méthode héritée
console.log(etudiant.etudier());     // Méthode spécifique</code></pre>
            </div>

            <h2 id="files">{{ trans('app.formations.typescript.files_title') }}</h2>
            <p>{!! trans('app.formations.typescript.files_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-javascript">// Note: TypeScript/JavaScript côté client ne peut pas écrire dans des fichiers
// Pour Node.js (backend) :

// Écrire dans un fichier (mode 'w' = write)
import * as fs from 'fs';

fs.writeFileSync("fichier.txt", "Bonjour TypeScript !\nCeci est la deuxième ligne\n", "utf-8");

// Lire un fichier (mode 'r' = read)
let contenu: string = fs.readFileSync("fichier.txt", "utf-8");
console.log(contenu);

// Lire ligne par ligne
let lignes: string[] = fs.readFileSync("fichier.txt", "utf-8").split('\n');
lignes.forEach(ligne => {
    console.log(ligne.trim());  // trim() enlève les espaces et sauts de ligne
});

// Ajouter à un fichier (mode 'a' = append)
fs.appendFileSync("fichier.txt", "Nouvelle ligne ajoutée\n", "utf-8");

// Méthodes asynchrones (recommandé)
fs.promises.writeFile("fichier.txt", "Contenu", "utf-8")
    .then(() => console.log("Fichier écrit"))
    .catch(err => console.error(err));

// Gestion d'erreurs
try {
    let contenu2: string = fs.readFileSync("fichier_inexistant.txt", "utf-8");
} catch (error) {
    if (error instanceof Error) {
        console.error("Erreur:", error.message);
    }
}

// Avec async/await (recommandé)
async function lireFichier(nomFichier: string): Promise<string> {
    try {
        return await fs.promises.readFile(nomFichier, "utf-8");
    } catch (error) {
        if (error instanceof Error) {
            console.error(`Erreur lors de la lecture: ${error.message}`);
        }
        throw error;
    }
}</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.typescript.files_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.typescript.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.typescript.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.typescript.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.typescript.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.typescript.next_steps_further_items') as $item)
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
                <a href="{{ route('formations.javascript') }}" class="nav-btn">{{ trans('app.formations.typescript.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.typescript.nav_next') }}</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3178c6, #2563eb) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3178c6, #2563eb) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>
    // Créer TypeScript basé sur JavaScript - ATTENDRE que JavaScript soit chargé
    (function() {
        function setupTypeScript() {
            if (typeof Prism === 'undefined' || !Prism.languages) {
                setTimeout(setupTypeScript, 100);
                return;
            }
            
            if (!Prism.languages.javascript) {
                setTimeout(setupTypeScript, 100);
                return;
            }
            
            // Créer TypeScript basé sur JavaScript
            if (!Prism.languages.typescript) {
                Prism.languages.typescript = Prism.languages.extend('javascript', {
                    'keyword': /\b(?:as|async|await|break|case|catch|class|const|constructor|continue|debugger|default|delete|do|else|enum|export|extends|finally|for|from|function|get|if|implements|import|in|instanceof|interface|let|new|null|of|package|private|protected|public|readonly|return|set|static|super|switch|this|throw|try|typeof|undefined|var|void|while|with|yield)\b/,
                    'builtin': /\b(?:string|number|boolean|any|void|never|object|unknown|Array|Promise|Date|RegExp|Error|Map|Set|WeakMap|WeakSet|Record|Partial|Required|Pick|Omit|Readonly)\b/,
                });
                
                // Mapper aussi language-ts vers typescript
                Prism.languages.ts = Prism.languages.typescript;
            }
        }
        
        // Essayer immédiatement
        setupTypeScript();
        
        // Réessayer après le chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupTypeScript);
        } else {
            setupTypeScript();
        }
        
        window.addEventListener('load', setupTypeScript);
    })();
</script>
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
        const formationSlug = 'typescript';
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
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation typescript.</p>
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
