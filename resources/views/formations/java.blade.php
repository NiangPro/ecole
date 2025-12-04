@extends('layouts.app')

@section('title', trans('app.formations.java.title') . ' | NiangProgrammeur')

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
            radial-gradient(circle at 20% 30%, rgba(237, 139, 0, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(237, 139, 0, 0.15) 0%, transparent 50%);
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
        border: 1px solid rgba(237, 139, 0, 0.2);
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
        background: linear-gradient(180deg, #ed8b00 0%, #cc7700 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #cc7700 0%, #aa6500 100%);
    }
    .sidebar h3 {
        color: #ed8b00;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(237, 139, 0, 0.2);
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
        background: #ed8b00;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.1) 0%, rgba(237, 139, 0, 0.05) 100%);
        color: #ed8b00;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(237, 139, 0, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #ed8b00 0%, #cc7700 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(237, 139, 0, 0.3);
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
        background: rgba(237, 139, 0, 0.1) !important;
        border: 2px solid rgba(237, 139, 0, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(237, 139, 0, 0.2) !important;
        border-color: rgba(237, 139, 0, 0.5) !important;
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
        background: linear-gradient(135deg, #ed8b00, #cc7700);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(237, 139, 0, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(237, 139, 0, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #cc7700, #aa6500);
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
        border-top: 2px solid rgba(237, 139, 0, 0.2);
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
        border-left: 4px solid #ed8b00;
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
        border: 2px solid #ed8b00;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', 'Consolas', 'Monaco', 'Fira Code', monospace;
        font-size: 16px;
        line-height: 1.7;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(237, 139, 0, 0.1);
        position: relative;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    .code-box pre {
        margin: 0;
        padding: 0;
        background: transparent !important;
        overflow-x: auto;
    }
    .code-box code {
        font-size: 14px;
        line-height: 1.6;
        color: #e2e8f0;
        font-weight: 400;
    }
    
    /* Classes de coloration manuelle pour Java (comme HTML5) */
    .code-keyword {
        color: #569cd6;
        font-weight: 500;
    }
    .code-string {
        color: #ce9178;
    }
    .code-comment {
        color: #6a9955;
        font-style: italic;
    }
    .code-class {
        color: #4ec9b0;
    }
    .code-function {
        color: #dcdcaa;
    }
    .code-number {
        color: #b5cea8;
    }
    .code-operator {
        color: #d4d4d4;
    }
    .code-punctuation {
        color: #d4d4d4;
    }
    .code-variable {
        color: #9cdcfe;
    }
    .code-type {
        color: #4ec9b0;
    }
    .code-text {
        color: #e2e8f0;
    }
    .code-box code .token {
        font-weight: 400;
    }
    .code-box::before {
        content: 'Java';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #ed8b00;
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
        background: #ed8b00;
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
        background: transparent !important;
    }
    
    /* S'assurer que les tokens Prism héritent correctement */
    .code-box pre code .token {
        font-size: 14px;
        line-height: 1.6;
        font-weight: 400;
        text-shadow: none !important; /* Pas de flou sur les tokens */
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
        background-color: #ed8b00;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #cc7700;
        box-shadow: 0 4px 12px rgba(237, 139, 0, 0.3);
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
    
    /* Styles Prism.js pour la coloration syntaxique Java */
    .code-box code[class*="language-"],
    .code-box pre[class*="language-"] {
        color: #e2e8f0;
        text-shadow: none;
        font-family: 'Courier New', 'Consolas', 'Monaco', 'Fira Code', monospace;
        background: transparent !important;
    }
    
    /* Couleurs pour les tokens Java - Style VS Code Dark */
    .code-box .token.comment,
    .code-box .token.prolog,
    .code-box .token.doctype,
    .code-box .token.cdata {
        color: #6a9955;
        font-style: italic;
    }
    .code-box .token.string,
    .code-box .token.attr-value {
        color: #ce9178;
    }
    .code-box .token.keyword,
    .code-box .token.boolean,
    .code-box .token.operator {
        color: #569cd6;
        font-weight: 500;
    }
    .code-box .token.function {
        color: #dcdcaa;
    }
    .code-box .token.class-name {
        color: #4ec9b0;
    }
    .code-box .token.number {
        color: #b5cea8;
    }
    .code-box .token.punctuation {
        color: #d4d4d4;
    }
    .code-box .token.variable,
    .code-box .token.property {
        color: #9cdcfe;
    }
    .code-box .token.tag {
        color: #569cd6;
    }
    .code-box .token.attr-name {
        color: #9cdcfe;
    }
    .code-box .token.selector {
        color: #d7ba7d;
    }
    .code-box .token.important,
    .code-box .token.bold {
        font-weight: bold;
    }
    .code-box .token.italic {
        font-style: italic;
    }
    
    /* Fallback si Prism ne charge pas - coloration manuelle basique */
    .code-box code:not([class*="language-"]) {
        color: #e2e8f0;
    }
    
    /* S'assurer que les éléments code ont la bonne classe */
    .code-box pre code {
        display: block;
        overflow-x: auto;
    }
    
    /* Forcer l'application des styles Prism même si non chargé */
    .code-box code.language-java {
        color: #e2e8f0;
    }
    
    /* S'assurer que Prism applique les styles */
    .code-box pre[class*="language-"] {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Styles pour les tokens Prism - Forcer l'application */
    .code-box pre code[class*="language-java"] .token.comment {
        color: #6a9955 !important;
        font-style: italic;
    }
    .code-box pre code[class*="language-java"] .token.string {
        color: #ce9178 !important;
    }
    .code-box pre code[class*="language-java"] .token.keyword {
        color: #569cd6 !important;
        font-weight: 500;
    }
    .code-box pre code[class*="language-java"] .token.function {
        color: #dcdcaa !important;
    }
    .code-box pre code[class*="language-java"] .token.class-name {
        color: #4ec9b0 !important;
    }
    .code-box pre code[class*="language-java"] .token.number {
        color: #b5cea8 !important;
    }
    
    /* Styles Prism.js pour la coloration syntaxique Java */
    .code-box code[class*="language-"],
    .code-box pre[class*="language-"] {
        color: #e2e8f0;
        font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
        background: transparent !important;
    }
    
    /* Surcharger les styles Prism pour Java - Style VS Code Dark */
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
    
    /* Styles généraux pour tous les tokens Prism */
    .code-box .token {
        text-shadow: none !important;
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
        border-color: rgba(237, 139, 0, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark-mode .sidebar h3 {
        color: #ed8b00;
        border-bottom-color: rgba(237, 139, 0, 0.3);
    }
    
    body.dark-mode .sidebar a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    body.dark-mode .sidebar a:hover {
        background: linear-gradient(135deg, rgba(237, 139, 0, 0.2) 0%, rgba(237, 139, 0, 0.1) 100%);
        color: #ed8b00;
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
        border-left-color: #ed8b00;
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
            <i class="fab fa-java" style="margin-right: 15px;"></i>
            {{ trans('app.formations.java.title') }}
    </h1>
        <p>{{ trans('app.formations.java.subtitle') }}</p>
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
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.java.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(237, 139, 0, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.java.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #ed8b00; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.java.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'methods', 'arrays', 'oop', 'collections', 'exceptions', 'files'];
            @endphp
            @foreach(trans('app.formations.java.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.java.intro_title') }}</h1>
            <p>{{ trans('app.formations.java.intro_text') }}</p>

            <h3>{{ trans('app.formations.java.what_is_title') }}</h3>
            <p>{!! trans('app.formations.java.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.java.why_popular_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.java.why_popular_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.java.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.java.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.java.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.java.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.java.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.java.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.java.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.java.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.java.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.java.use_cases_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $icon = '';
                    $label = '';
                    $description = '';
                    if (preg_match('/^([📱🌐💼☁️🎮🔧📊🏦]) (.+)$/u', $item, $matches)) {
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

            <h2 id="syntax">{{ trans('app.formations.java.syntax_title') }}</h2>
            <p>{!! trans('app.formations.java.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">// Premier programme Java
public class BonjourMonde {
    public static void main(String[] args) {
        System.out.println("Bonjour, monde !");
        
        // Variables
        String nom = "NiangProgrammeur";
        int age = 25;
        
        // Affichage formaté
        System.out.println("Je m'appelle " + nom + " et j'ai " + age + " ans");
        
        // Opérations simples
        int resultat = 10 + 5;
        System.out.println("10 + 5 = " + resultat);
    }
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.java.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.java.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.java.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.java.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">// Définition d'une classe
public class Calculatrice {
    // Méthode pour calculer la moyenne
    public static double calculerMoyenne(int[] nombres) {
        if (nombres.length == 0) {
            return 0;
        }
        int somme = 0;
        for (int nombre : nombres) {
            somme += nombre;
        }
        double moyenne = (double) somme / nombres.length;
        return moyenne;
    }
    
    // Méthode principale
    public static void main(String[] args) {
        int[] notes = {15, 18, 12, 20, 16};
        double moyenne = calculerMoyenne(notes);
        System.out.println("La moyenne est : " + moyenne);
    }
}</code></pre>
            </div>

            <h2 id="variables">{{ trans('app.formations.java.variables_title') }}</h2>
            <p>{!! trans('app.formations.java.variables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">// Déclaration de variables
String nom = "Java";           // String (chaîne de caractères)
int age = 30;                  // int (entier)
double prix = 19.99;           // double (nombre décimal)
boolean estActif = true;       // boolean (booléen)
Object valeurNulle = null;     // null (valeur nulle)

// Affichage
System.out.println(nom);
System.out.println(age);
System.out.println(prix);
System.out.println(estActif);
System.out.println(valeurNulle);

// Déclaration puis assignation
int nombre;
nombre = 10;
System.out.println(nombre);

// Variables finales (constantes)
final double PI = 3.14159;     // Ne peut pas être modifiée
final String NOM_APPLICATION = "MonApp";

// Noms de variables valides
String nomUtilisateur = "Bassirou";
int ageUtilisateur = 25;
String _prive = "variable privée";  // Possible mais non recommandé</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.java.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.java.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.java.datatypes_title') }}</h2>
            <p>{!! trans('app.formations.java.datatypes_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">// Types primitifs (8 types)
byte petitNombre = 127;           // 8 bits (-128 à 127)
short nombreCourt = 32767;        // 16 bits
int nombre = 42;                  // 32 bits (le plus utilisé)
long grandNombre = 1234567890L;   // 64 bits (notez le L)
float decimal = 3.14f;            // 32 bits (notez le f)
double decimalPrecis = 3.14159;    // 64 bits (le plus utilisé)
char caractere = 'A';              // 16 bits (un seul caractère)
boolean estVrai = true;            // true ou false

// Types objets (références)
String texte = "Hello";            // String (chaîne de caractères)
Integer nombreObjet = 42;          // Wrapper pour int
Double decimalObjet = 3.14;        // Wrapper pour double

// Collections
int[] tableau = {1, 2, 3, 4, 5};  // Tableau
ArrayList&lt;Integer&gt; liste = new ArrayList&lt;&gt;();  // Liste dynamique
HashMap&lt;String, Integer&gt; map = new HashMap&lt;&gt;();  // Map (clé-valeur)

// Vérifier le type
System.out.println(texte.getClass().getName());  // java.lang.String
System.out.println(((Object) nombre).getClass().getName());  // java.lang.Integer (auto-boxing)

// Conversion de types
String ageStr = String.valueOf(25);  // Convertir en String
int ageInt = Integer.parseInt("25");  // Convertir en int
double prixDouble = Double.parseDouble("19.99");  // Convertir en double</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.java.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.java.datatypes_list_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.java.operators_title') }}</h2>
            <p>{{ trans('app.formations.java.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">// Opérateurs arithmétiques
int a = 10;
int b = 3;

System.out.println(a + b);    // Addition: 13
System.out.println(a - b);    // Soustraction: 7
System.out.println(a * b);    // Multiplication: 30
System.out.println(a / b);    // Division entière: 3
System.out.println((double)a / b);  // Division: 3.333...
System.out.println(a % b);    // Modulo (reste): 1
System.out.println(Math.pow(a, b));  // Puissance: 1000.0

// Opérateurs de comparaison
System.out.println(a > b);    // true (supérieur à)
System.out.println(a < b);    // false (inférieur à)
System.out.println(a >= b);   // true (supérieur ou égal)
System.out.println(a <= b);   // false (inférieur ou égal)
System.out.println(a == b);   // false (égalité)
System.out.println(a != b);   // true (différent)

// Opérateurs logiques
boolean x = true;
boolean y = false;
System.out.println(x && y);  // false (ET logique)
System.out.println(x || y);   // true (OU logique)
System.out.println(!x);       // false (NON logique)

// Opérateurs d'assignation
int c = 5;
c += 3;          // Équivalent à c = c + 3 (c devient 8)
c -= 2;          // Équivalent à c = c - 2 (c devient 6)
c *= 2;          // Équivalent à c = c * 2 (c devient 12)
c /= 3;          // Équivalent à c = c / 3 (c devient 4)

// Opérateur instanceof
String texte = "Java";
System.out.println(texte instanceof String);  // true</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.java.conditions_title') }}</h2>
            <p>{!! trans('app.formations.java.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">// Structure if simple
int age = 20;

if (age >= 18) {
    System.out.println("Vous êtes majeur");
} else {
    System.out.println("Vous êtes mineur");
}

// Structure if/else if/else
age = 15;

if (age >= 18) {
    System.out.println("Vous êtes majeur");
    System.out.println("Vous pouvez voter");
} else if (age >= 13) {
    System.out.println("Vous êtes adolescent");
} else if (age >= 6) {
    System.out.println("Vous êtes enfant");
} else {
    System.out.println("Vous êtes un bébé");
}

// Conditions multiples
int note = 85;
String mention;

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

System.out.println("Votre mention : " + mention);

// Opérateur ternaire (expression conditionnelle)
age = 20;
String statut = (age >= 18) ? "Majeur" : "Mineur";
System.out.println(statut);

// Conditions avec &&/||
age = 25;
boolean permis = true;

if (age >= 18 && permis) {
    System.out.println("Vous pouvez conduire");
} else {
    System.out.println("Vous ne pouvez pas conduire");
}

// Switch-case
int jour = 3;
switch (jour) {
    case 1:
        System.out.println("Lundi");
        break;
    case 2:
        System.out.println("Mardi");
        break;
    case 3:
        System.out.println("Mercredi");
        break;
    default:
        System.out.println("Autre jour");
}</code></pre>
            </div>

            <h2 id="loops">{{ trans('app.formations.java.loops_title') }}</h2>
            <p>{!! trans('app.formations.java.loops_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">// Boucle for classique
for (int i = 0; i < 5; i++) {
    System.out.println(i);  // Affiche 0, 1, 2, 3, 4
}

// Boucle for avec début et fin
for (int i = 1; i <= 5; i++) {
    System.out.println(i);  // Affiche 1, 2, 3, 4, 5
}

// Boucle for avec pas
for (int i = 0; i < 10; i += 2) {
    System.out.println(i);  // Affiche 0, 2, 4, 6, 8
}

// Enhanced for loop (for-each) avec tableau
String[] fruits = {"pomme", "banane", "orange"};
for (String fruit : fruits) {
    System.out.println("J'aime les " + fruit);
}

// Boucle for classique avec tableau
for (int i = 0; i < fruits.length; i++) {
    System.out.println(i + ": " + fruits[i]);
}

// Boucle while
int compteur = 0;
while (compteur < 5) {
    System.out.println(compteur);
    compteur++;
}

// Boucle while avec break
compteur = 0;
while (true) {
    System.out.println(compteur);
    compteur++;
    if (compteur >= 5) {
        break;  // Sortir de la boucle
    }
}

// continue (passer à l'itération suivante)
for (int i = 0; i < 10; i++) {
    if (i % 2 == 0) {  // Si i est pair
        continue;      // Passer au suivant
    }
    System.out.println(i);  // Affiche seulement les impairs: 1, 3, 5, 7, 9
}

// Boucle do-while (exécute au moins une fois)
int x = 0;
do {
    System.out.println(x);
    x++;
} while (x < 5);</code></pre>
            </div>

            <h2 id="methods">{{ trans('app.formations.java.methods_title') }}</h2>
            <p>{!! trans('app.formations.java.methods_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-java">public class MethodesExemple {
    // Méthode simple (sans paramètres)
    public static void direBonjour() {
        System.out.println("Bonjour !");
    }
    
    // Méthode avec paramètres
    public static String saluer(String nom) {
        return "Bonjour, " + nom + " !";
    }
    
    // Méthode avec plusieurs paramètres
    public static int additionner(int a, int b) {
        return a + b;
    }
    
    // Méthode avec surcharge (même nom, paramètres différents)
    public static int additionner(int a, int b, int c) {
        return a + b + c;
    }
    
    // Méthode avec type de retour void
    public static void afficherInfo(String nom, int age) {
        System.out.println(nom + " a " + age + " ans");
    }
    
    // Méthode principale
    public static void main(String[] args) {
        direBonjour();  // Appel de la méthode
        String message = saluer("Java");
        System.out.println(message);  // "Bonjour, Java !"
        
        int resultat = additionner(5, 3);
        System.out.println(resultat);  // 8
        
        int resultat2 = additionner(1, 2, 3);
        System.out.println(resultat2);  // 6
        
        afficherInfo("Bassirou", 25);
    }
}</code></pre>
            </div>

            <h2 id="arrays">{{ trans('app.formations.java.arrays_title') }}</h2>
            <p>{{ trans('app.formations.java.arrays_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">// Création de tableaux
int[] nombres = {1, 2, 3, 4, 5};
String[] fruits = {"pomme", "banane", "orange"};

// Création avec taille
int[] tableau = new int[5];  // Tableau de 5 entiers (initialisés à 0)

// Accès aux éléments (index commence à 0)
System.out.println(fruits[0]);        // "pomme" (premier élément)
System.out.println(fruits[fruits.length - 1]);  // "orange" (dernier élément)

// Modification
fruits[1] = "mangue";    // Remplacer "banane" par "mangue"

// Longueur du tableau
System.out.println(fruits.length);  // 3

// Parcourir un tableau avec for classique
for (int i = 0; i < fruits.length; i++) {
    System.out.println(fruits[i]);
}

// Parcourir avec enhanced for (for-each)
for (String fruit : fruits) {
    System.out.println("J'aime les " + fruit);
}

// Tableaux multidimensionnels
int[][] matrice = {
    {1, 2, 3},
    {4, 5, 6},
    {7, 8, 9}
};

// Accès aux éléments
System.out.println(matrice[0][0]);  // 1
System.out.println(matrice[1][2]);  // 6

// Parcourir une matrice
for (int i = 0; i < matrice.length; i++) {
    for (int j = 0; j < matrice[i].length; j++) {
        System.out.print(matrice[i][j] + " ");
    }
    System.out.println();
}</code></pre>
            </div>

            <h2 id="oop">{{ trans('app.formations.java.oop_title') }}</h2>
            <p>{{ trans('app.formations.java.oop_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">// Définir une classe
class Personne {
    // Attributs d'instance
    private String nom;
    private int age;
    
    // Constructeur
    public Personne(String nom, int age) {
        this.nom = nom;
        this.age = age;
    }
    
    // Méthode d'instance
    public void sePresenter() {
        System.out.println("Je m'appelle " + nom + " et j'ai " + age + " ans");
    }
    
    // Getters et Setters
    public String getNom() {
        return nom;
    }
    
    public void setNom(String nom) {
        this.nom = nom;
    }
    
    public int getAge() {
        return age;
    }
    
    public void setAge(int age) {
        this.age = age;
    }
}

// Classe principale
public class ExemplePOO {
    public static void main(String[] args) {
        // Créer des objets (instances)
        Personne personne1 = new Personne("Bassirou", 25);
        Personne personne2 = new Personne("Aminata", 30);
        
        // Utiliser les méthodes
        personne1.sePresenter();
        personne2.sePresenter();
        
        // Accéder aux attributs via getters
        System.out.println(personne1.getNom());
        System.out.println(personne1.getAge());
    }
}</code></pre>
            </div>

            <h2 id="collections">{{ trans('app.formations.java.collections_title') }}</h2>
            <p>{{ trans('app.formations.java.collections_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;

// ArrayList (liste dynamique)
ArrayList&lt;String&gt; fruits = new ArrayList&lt;&gt;();
fruits.add("pomme");
fruits.add("banane");
fruits.add("orange");
fruits.add(1, "mangue");  // Insérer à l'index 1

System.out.println(fruits.get(0));  // "pomme"
System.out.println(fruits.size());  // 4

fruits.remove("banane");
fruits.remove(0);

// HashMap (dictionnaire clé-valeur)
HashMap&lt;String, Integer&gt; ages = new HashMap&lt;&gt;();
ages.put("Bassirou", 25);
ages.put("Aminata", 30);
ages.put("Ibrahima", 28);

System.out.println(ages.get("Bassirou"));  // 25
System.out.println(ages.containsKey("Aminata"));  // true

// Parcourir un HashMap
for (String nom : ages.keySet()) {
    System.out.println(nom + " a " + ages.get(nom) + " ans");
}

// HashSet (ensemble unique)
HashSet&lt;String&gt; villes = new HashSet&lt;&gt;();
villes.add("Dakar");
villes.add("Thiès");
villes.add("Dakar");  // Ignoré (déjà présent)

System.out.println(villes.size());  // 2</code></pre>
            </div>

            <h2 id="exceptions">{{ trans('app.formations.java.exceptions_title') }}</h2>
            <p>{{ trans('app.formations.java.exceptions_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">// Try-catch simple
try {
    int resultat = 10 / 0;  // Division par zéro
} catch (ArithmeticException e) {
    System.out.println("Erreur : Division par zéro !");
}

// Try-catch avec plusieurs catch
try {
    int[] tableau = {1, 2, 3};
    System.out.println(tableau[5]);  // Index hors limites
} catch (ArrayIndexOutOfBoundsException e) {
    System.out.println("Erreur : Index invalide !");
} catch (Exception e) {
    System.out.println("Erreur générale : " + e.getMessage());
}

// Try-catch-finally
try {
    // Code qui peut générer une exception
    int nombre = Integer.parseInt("abc");
} catch (NumberFormatException e) {
    System.out.println("Erreur : Format de nombre invalide !");
} finally {
    System.out.println("Ce code s'exécute toujours");
}

// Lancer une exception
public static void verifierAge(int age) throws IllegalArgumentException {
    if (age < 0) {
        throw new IllegalArgumentException("L'âge ne peut pas être négatif");
    }
    System.out.println("Âge valide : " + age);
}</code></pre>
            </div>

            <h2 id="files">{{ trans('app.formations.java.files_title') }}</h2>
            <p>{{ trans('app.formations.java.files_text') }}</p>

            <div class="code-box">
                <pre><code class="language-java">import java.io.FileWriter;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.IOException;

// Écrire dans un fichier
try {
    FileWriter writer = new FileWriter("fichier.txt");
    writer.write("Bonjour Java !");
    writer.close();
    System.out.println("Fichier écrit avec succès");
} catch (IOException e) {
    System.out.println("Erreur lors de l'écriture : " + e.getMessage());
}

// Lire un fichier
try {
    FileReader reader = new FileReader("fichier.txt");
    BufferedReader bufferedReader = new BufferedReader(reader);
    String ligne;
    while ((ligne = bufferedReader.readLine()) != null) {
        System.out.println(ligne);
    }
    bufferedReader.close();
} catch (IOException e) {
    System.out.println("Erreur lors de la lecture : " + e.getMessage());
}

// Utiliser try-with-resources (fermeture automatique)
try (FileWriter writer = new FileWriter("fichier.txt")) {
    writer.write("Bonjour Java !\n");
    writer.write("Ceci est la deuxième ligne\n");
    System.out.println("Fichier écrit avec succès");
} catch (IOException e) {
    System.out.println("Erreur : " + e.getMessage());
}

// Lire ligne par ligne avec try-with-resources
try (BufferedReader reader = new BufferedReader(new FileReader("fichier.txt"))) {
    String ligne;
    while ((ligne = reader.readLine()) != null) {
        System.out.println(ligne);
    }
} catch (IOException e) {
    System.out.println("Erreur lors de la lecture : " + e.getMessage());
}

// Ajouter à un fichier (mode append)
try (FileWriter writer = new FileWriter("fichier.txt", true)) {
    writer.write("Nouvelle ligne ajoutée\n");
    System.out.println("Ligne ajoutée avec succès");
} catch (IOException e) {
    System.out.println("Erreur : " + e.getMessage());
}</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.java.files_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.java.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.java.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.java.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.java.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.java.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.java.next_steps_further_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $icon = '';
                        $label = '';
                        $description = '';
                        if (preg_match('/^([📚🔧📦🌐📱☁️]) (.+)$/u', $item, $matches)) {
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
                <a href="{{ route('formations.python') }}" class="nav-btn">{{ trans('app.formations.java.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.java.nav_next') }}</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #ed8b00, #cc7700) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(237, 139, 0, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #ed8b00, #cc7700) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(237, 139, 0, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
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
    
    // Ajouter les boutons de copie à tous les blocs de code et réinitialiser Prism
    function addCopyButtonsAndHighlight() {
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
        
        // Réinitialiser Prism après l'ajout des boutons
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    }
    
    // Appeler après le chargement de la page
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(addCopyButtonsAndHighlight, 300);
        });
    } else {
        setTimeout(addCopyButtonsAndHighlight, 300);
    }
    
    // Réinitialiser après le chargement complet
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (typeof Prism !== 'undefined') {
                Prism.highlightAll();
            }
            addCopyButtonsAndHighlight();
        }, 500);
    });
    
    // Système de suivi automatique de progression
    @auth
    (function() {
        const formationSlug = 'java';
        const sections = [
            'intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 
            'loops', 'methods', 'arrays', 'oop', 'collections', 'exceptions', 'files'
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
                        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Félicitations ! Vous avez terminé la formation Java.</p>
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
