@extends('layouts.app')

@section('title', trans('app.formations.css3.title') . ' | NiangProgrammeur')

@section('styles')
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
        background-color: #1E90FF;
        color: white;
        padding: 80px 20px 40px;
        text-align: center;
        width: 100%;
        margin: 0;
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
        border: 1px solid rgba(30, 144, 255, 0.1);
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
        background: linear-gradient(180deg, #1E90FF 0%, #1873CC 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #1873CC 0%, #1560B0 100%);
    }
    .sidebar h3 {
        color: #1E90FF;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(30, 144, 255, 0.2);
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
        background: #1E90FF;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(30, 144, 255, 0.1) 0%, rgba(30, 144, 255, 0.05) 100%);
        color: #1E90FF;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(30, 144, 255, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #1E90FF 0%, #1873CC 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(30, 144, 255, 0.3);
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
        background: rgba(30, 144, 255, 0.1) !important;
        border: 2px solid rgba(30, 144, 255, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(30, 144, 255, 0.2) !important;
        border-color: rgba(30, 144, 255, 0.5) !important;
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
        background: linear-gradient(135deg, #1E90FF, #1873CC);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(30, 144, 255, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(30, 144, 255, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #1873CC, #1560B0);
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
        flex: 1 1 auto;
        min-width: 0;
        background: white;
        padding: 30px;
        border-radius: 5px;
        overflow-x: hidden;
        max-width: 100%;
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
    }
    .main-content p {
        color: #000;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 16px;
    }
    .example-box {
        background-color: #E7E9EB;
        border-left: 4px solid #1E90FF;
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
        border: 2px solid #1E90FF;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(30, 144, 255, 0.1);
        position: relative;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    .code-box code {
        display: block;
        max-width: 100%;
        overflow-wrap: break-word;
    }
    .code-box::before {
        content: 'CSS';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #1E90FF;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    /* Bouton de copie - Même taille que le label CSS */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 80px;
        background: #1E90FF;
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
        background: #1873CC;
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
    .code-box code {
        color: #e2e8f0;
        line-height: 1.6;
    }
    .code-selector {
        color: #fbbf24;
    }
    .code-property {
        color: #60a5fa;
    }
    .code-value {
        color: #a3e635;
    }
    .code-comment {
        color: #94a3b8;
        font-style: italic;
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
        background-color: #1E90FF;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
    }
    .nav-btn:hover {
        background-color: #1873CC;
    }
    @media (max-width: 992px) {
        .content-wrapper {
            flex-direction: column;
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
            width: 100%;
            padding: 20px;
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
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px;">{{ trans('app.formations.css3.title') }}</h1>
    <p style="font-size: 20px;">{{ trans('app.formations.css3.subtitle') }}</p>
</div>

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.css3.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(30, 144, 255, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.css3.title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #1E90FF; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.css3.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">{{ trans('app.formations.css3.sidebar_menu')[0] }}</a>
            <a href="#syntax">{{ trans('app.formations.css3.sidebar_menu')[1] }}</a>
            <a href="#selectors">{{ trans('app.formations.css3.sidebar_menu')[2] }}</a>
            <a href="#colors">{{ trans('app.formations.css3.sidebar_menu')[3] }}</a>
            <a href="#backgrounds">{{ trans('app.formations.css3.sidebar_menu')[4] }}</a>
            <a href="#borders">{{ trans('app.formations.css3.sidebar_menu')[5] }}</a>
            <a href="#margins">{{ trans('app.formations.css3.sidebar_menu')[6] }}</a>
            <a href="#text">{{ trans('app.formations.css3.sidebar_menu')[7] }}</a>
            <a href="#fonts">{{ trans('app.formations.css3.sidebar_menu')[8] }}</a>
            <a href="#flexbox">{{ trans('app.formations.css3.sidebar_menu')[9] }}</a>
            <a href="#grid">{{ trans('app.formations.css3.sidebar_menu')[10] }}</a>
            <a href="#transitions">{{ trans('app.formations.css3.sidebar_menu')[11] }}</a>
            <a href="#animations">{{ trans('app.formations.css3.sidebar_menu')[12] }}</a>
            <a href="#responsive">{{ trans('app.formations.css3.sidebar_menu')[13] }}</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.css3.intro_title') }}</h1>
            <p>{{ trans('app.formations.css3.intro_text') }}</p>

            <h3>{{ trans('app.formations.css3.why_learn_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.css3.why_learn_items') as $item)
                <li>✅ <strong>{{ explode(' - ', $item)[0] }}</strong>@if(isset(explode(' - ', $item)[1])) - {{ explode(' - ', $item)[1] }}@endif</li>
                @endforeach
            </ul>

            <h2 id="syntax">{{ trans('app.formations.css3.syntax_title') }}</h2>
            <p>{{ trans('app.formations.css3.syntax_text') }}</p>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.syntax_structure_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">sélecteur</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">propriété</span>: <span class="code-value">valeur</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">propriété</span>: <span class="code-value">valeur</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.syntax_example_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">h1</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">color</span>: <span class="code-value">blue</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">font-size</span>: <span class="code-value">24px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">text-align</span>: <span class="code-value">center</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.syntax_ways_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.css3.syntax_ways_list') as $way)
                <li><strong>{{ explode(' - ', $way)[0] }}</strong>@if(isset(explode(' - ', $way)[1])) - {!! explode(' - ', $way)[1] !!}@endif</li>
                @endforeach
            </ul>

            <h2 id="selectors">{{ trans('app.formations.css3.selectors_title') }}</h2>
            <p>{{ trans('app.formations.css3.selectors_text') }}</p>

            <h3>{{ trans('app.formations.css3.selectors_basic_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Sélecteur d'élément - Cible tous les éléments du type */</span><br>
                        <span class="code-selector">p</span> { <span class="code-property">color</span>: <span class="code-value">red</span>; }<br><br>
                        <span class="code-comment">/* Sélecteur de classe - Cible les éléments avec class="ma-classe" */</span><br>
                        <span class="code-selector">.ma-classe</span> { <span class="code-property">color</span>: <span class="code-value">blue</span>; }<br><br>
                        <span class="code-comment">/* Sélecteur d'ID - Cible l'élément avec id="mon-id" (unique) */</span><br>
                        <span class="code-selector">#mon-id</span> { <span class="code-property">color</span>: <span class="code-value">green</span>; }<br><br>
                        <span class="code-comment">/* Sélecteur universel - Cible TOUS les éléments */</span><br>
                        <span class="code-selector">*</span> { <span class="code-property">margin</span>: <span class="code-value">0</span>; }<br><br>
                        <span class="code-comment">/* Sélecteur de groupe - Cible plusieurs éléments */</span><br>
                        <span class="code-selector">h1, h2, h3</span> { <span class="code-property">font-family</span>: <span class="code-value">Arial</span>; }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.selectors_combinators_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Descendant - Tous les p dans div */</span><br>
                        <span class="code-selector">div p</span> { <span class="code-property">color</span>: <span class="code-value">blue</span>; }<br><br>
                        <span class="code-comment">/* Enfant direct - p directement dans div */</span><br>
                        <span class="code-selector">div > p</span> { <span class="code-property">color</span>: <span class="code-value">red</span>; }<br><br>
                        <span class="code-comment">/* Adjacent - p immédiatement après div */</span><br>
                        <span class="code-selector">div + p</span> { <span class="code-property">margin-top</span>: <span class="code-value">20px</span>; }<br><br>
                        <span class="code-comment">/* Frères suivants - Tous les p après div */</span><br>
                        <span class="code-selector">div ~ p</span> { <span class="code-property">color</span>: <span class="code-value">gray</span>; }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.selectors_pseudo_classes_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* États interactifs */</span><br>
                        <span class="code-selector">a:hover</span> { <span class="code-property">color</span>: <span class="code-value">red</span>; } <span class="code-comment">/* Survol */</span><br>
                        <span class="code-selector">a:active</span> { <span class="code-property">color</span>: <span class="code-value">green</span>; } <span class="code-comment">/* Clic */</span><br>
                        <span class="code-selector">input:focus</span> { <span class="code-property">border</span>: <span class="code-value">2px solid blue</span>; } <span class="code-comment">/* Focus */</span><br><br>
                        <span class="code-comment">/* Sélection structurelle */</span><br>
                        <span class="code-selector">li:first-child</span> { <span class="code-property">font-weight</span>: <span class="code-value">bold</span>; }<br>
                        <span class="code-selector">li:last-child</span> { <span class="code-property">color</span>: <span class="code-value">red</span>; }<br>
                        <span class="code-selector">li:nth-child(2n)</span> { <span class="code-property">background</span>: <span class="code-value">#f0f0f0</span>; } <span class="code-comment">/* Pairs */</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.selectors_pseudo_elements_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Ajouter du contenu avant/après */</span><br>
                        <span class="code-selector">p::before</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">content</span>: <span class="code-value">"→ "</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">color</span>: <span class="code-value">blue</span>;<br>
                        }<br><br>
                        <span class="code-selector">p::after</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">content</span>: <span class="code-value">" ✓"</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Première lettre/ligne */</span><br>
                        <span class="code-selector">p::first-letter</span> { <span class="code-property">font-size</span>: <span class="code-value">2em</span>; }<br>
                        <span class="code-selector">p::first-line</span> { <span class="code-property">font-weight</span>: <span class="code-value">bold</span>; }
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.selectors_specificity') }}</strong></p>
            </div>

            <h2 id="colors">{{ trans('app.formations.css3.colors_title') }}</h2>
            <p>{{ trans('app.formations.css3.colors_text') }}</p>

            <h3>{{ trans('app.formations.css3.colors_formats_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* 1. Noms de couleurs (140 noms prédéfinis) */</span><br>
                        <span class="code-selector">.texte1</span> { <span class="code-property">color</span>: <span class="code-value">red</span>; }<br>
                        <span class="code-selector">.texte2</span> { <span class="code-property">color</span>: <span class="code-value">dodgerblue</span>; }<br><br>
                        <span class="code-comment">/* 2. Hexadécimal (#RRGGBB ou #RGB) */</span><br>
                        <span class="code-selector">.texte3</span> { <span class="code-property">color</span>: <span class="code-value">#FF0000</span>; } <span class="code-comment">/* Rouge */</span><br>
                        <span class="code-selector">.texte4</span> { <span class="code-property">color</span>: <span class="code-value">#F00</span>; } <span class="code-comment">/* Raccourci */</span><br><br>
                        <span class="code-comment">/* 3. RGB (Red, Green, Blue: 0-255) */</span><br>
                        <span class="code-selector">.texte5</span> { <span class="code-property">color</span>: <span class="code-value">rgb(255, 0, 0)</span>; }<br><br>
                        <span class="code-comment">/* 4. RGBA (avec Alpha: 0-1 pour transparence) */</span><br>
                        <span class="code-selector">.texte6</span> { <span class="code-property">color</span>: <span class="code-value">rgba(255, 0, 0, 0.5)</span>; } <span class="code-comment">/* 50% transparent */</span><br><br>
                        <span class="code-comment">/* 5. HSL (Teinte, Saturation, Luminosité) */</span><br>
                        <span class="code-selector">.texte7</span> { <span class="code-property">color</span>: <span class="code-value">hsl(0, 100%, 50%)</span>; } <span class="code-comment">/* Rouge */</span><br><br>
                        <span class="code-comment">/* 6. HSLA (HSL avec transparence) */</span><br>
                        <span class="code-selector">.texte8</span> { <span class="code-property">color</span>: <span class="code-value">hsla(120, 100%, 50%, 0.3)</span>; } <span class="code-comment">/* Vert 30% */</span>
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.colors_properties_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(['color', 'background-color', 'border-color', 'outline-color', 'opacity'] as $index => $prop)
                <li><code>{{ $prop }}</code> - {{ trans('app.formations.css3.colors_properties_list')[$index] }}</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.colors_tip') }}</strong></p>
            </div>

            <h2 id="backgrounds">{{ trans('app.formations.css3.backgrounds_title') }}</h2>
            <p>{{ trans('app.formations.css3.backgrounds_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">background-color</span>: <span class="code-value">#f0f0f0</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">background-image</span>: <span class="code-value">url('image.jpg')</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">background-size</span>: <span class="code-value">cover</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">background-position</span>: <span class="code-value">center</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">background-repeat</span>: <span class="code-value">no-repeat</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.backgrounds_gradients_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Dégradé linéaire */</span><br>
                        <span class="code-selector">.gradient1</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">background</span>: <span class="code-value">linear-gradient(to right, #1E90FF, #00CED1)</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Dégradé radial */</span><br>
                        <span class="code-selector">.gradient2</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">background</span>: <span class="code-value">radial-gradient(circle, #1E90FF, white)</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="borders">{{ trans('app.formations.css3.borders_title') }}</h2>
            <p>{{ trans('app.formations.css3.borders_text') }}</p>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.borders_properties_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">.box</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">border</span>: <span class="code-value">2px solid black</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">border-radius</span>: <span class="code-value">10px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">border-top</span>: <span class="code-value">5px dashed red</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">border-left-color</span>: <span class="code-value">blue</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.borders_shadow_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.card</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">box-shadow</span>: <span class="code-value">0 4px 6px rgba(0,0,0,0.1)</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Ombre multiple */</span><br>
                        <span class="code-selector">.card-hover</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">box-shadow</span>: <span class="code-value">0 10px 20px rgba(0,0,0,0.2),<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0 6px 6px rgba(0,0,0,0.1)</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="margins">{{ trans('app.formations.css3.margins_title') }}</h2>
            <p>Le Box Model est fondamental en CSS. Il définit comment les éléments occupent l'espace : contenu, padding, bordure et marge.</p>

            <h3>Comprendre le Box Model</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Content</strong> - Le contenu de l'élément (texte, image)</li>
                <li><strong>Padding</strong> - Espace entre le contenu et la bordure (intérieur)</li>
                <li><strong>Border</strong> - La bordure autour du padding</li>
                <li><strong>Margin</strong> - Espace à l'extérieur de la bordure (sépare des autres éléments)</li>
            </ul>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.margins_syntax_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">.element</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">/* 1 valeur - Tous les côtés */</span><br>
                        &nbsp;&nbsp;<span class="code-property">margin</span>: <span class="code-value">20px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* 2 valeurs - Vertical | Horizontal */</span><br>
                        &nbsp;&nbsp;<span class="code-property">margin</span>: <span class="code-value">10px 20px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* 3 valeurs - Top | Horizontal | Bottom */</span><br>
                        &nbsp;&nbsp;<span class="code-property">margin</span>: <span class="code-value">10px 20px 15px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* 4 valeurs - Top | Right | Bottom | Left (sens horaire) */</span><br>
                        &nbsp;&nbsp;<span class="code-property">margin</span>: <span class="code-value">10px 20px 15px 5px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Propriétés individuelles */</span><br>
                        &nbsp;&nbsp;<span class="code-property">margin-top</span>: <span class="code-value">10px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">margin-right</span>: <span class="code-value">20px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">margin-bottom</span>: <span class="code-value">15px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">margin-left</span>: <span class="code-value">5px</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.margins_techniques_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Centrer horizontalement */</span><br>
                        <span class="code-selector">.center</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">width</span>: <span class="code-value">800px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">margin</span>: <span class="code-value">0 auto</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Marges négatives (superposition) */</span><br>
                        <span class="code-selector">.overlap</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">margin-top</span>: <span class="code-value">-20px</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Box-sizing pour inclure padding dans la largeur */</span><br>
                        <span class="code-selector">*</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">box-sizing</span>: <span class="code-value">border-box</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.margins_collapse') }}</strong></p>
            </div>

            <h2 id="text">{{ trans('app.formations.css3.text_title') }}</h2>
            <p>{{ trans('app.formations.css3.text_text') }}</p>

            <h3>{{ trans('app.formations.css3.text_properties_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.text</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">/* Couleur du texte */</span><br>
                        &nbsp;&nbsp;<span class="code-property">color</span>: <span class="code-value">#333</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Alignement: left, right, center, justify */</span><br>
                        &nbsp;&nbsp;<span class="code-property">text-align</span>: <span class="code-value">center</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Décoration: none, underline, overline, line-through */</span><br>
                        &nbsp;&nbsp;<span class="code-property">text-decoration</span>: <span class="code-value">underline</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Transformation: uppercase, lowercase, capitalize */</span><br>
                        &nbsp;&nbsp;<span class="code-property">text-transform</span>: <span class="code-value">uppercase</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Espacement entre les lettres */</span><br>
                        &nbsp;&nbsp;<span class="code-property">letter-spacing</span>: <span class="code-value">2px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Espacement entre les mots */</span><br>
                        &nbsp;&nbsp;<span class="code-property">word-spacing</span>: <span class="code-value">5px</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Hauteur de ligne (1.5 = 150% de la taille de police) */</span><br>
                        &nbsp;&nbsp;<span class="code-property">line-height</span>: <span class="code-value">1.6</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Ombre du texte: x y flou couleur */</span><br>
                        &nbsp;&nbsp;<span class="code-property">text-shadow</span>: <span class="code-value">2px 2px 4px rgba(0,0,0,0.3)</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.text_overflow_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.truncate</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">white-space</span>: <span class="code-value">nowrap</span>; <span class="code-comment">/* Pas de retour à la ligne */</span><br>
                        &nbsp;&nbsp;<span class="code-property">overflow</span>: <span class="code-value">hidden</span>; <span class="code-comment">/* Cache le débordement */</span><br>
                        &nbsp;&nbsp;<span class="code-property">text-overflow</span>: <span class="code-value">ellipsis</span>; <span class="code-comment">/* Ajoute ... */</span><br>
                        }<br><br>
                        <span class="code-selector">.word-break</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">word-wrap</span>: <span class="code-value">break-word</span>; <span class="code-comment">/* Coupe les mots longs */</span><br>
                        &nbsp;&nbsp;<span class="code-property">word-break</span>: <span class="code-value">break-all</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="fonts">{{ trans('app.formations.css3.fonts_title') }}</h2>
            <p>{{ trans('app.formations.css3.fonts_text') }}</p>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.fonts_properties_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">.titre</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">font-family</span>: <span class="code-value">'Arial', sans-serif</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">font-size</span>: <span class="code-value">24px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">font-weight</span>: <span class="code-value">bold</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">font-style</span>: <span class="code-value">italic</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.fonts_google_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Dans le HTML */</span><br>
                        <span class="code-selector">&lt;link</span> <span class="code-property">href</span>=<span class="code-value">"https://fonts.googleapis.com/css2?family=Roboto"</span> <span class="code-property">rel</span>=<span class="code-value">"stylesheet"</span><span class="code-selector">&gt;</span><br><br>
                        <span class="code-comment">/* Dans le CSS */</span><br>
                        <span class="code-selector">body</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">font-family</span>: <span class="code-value">'Roboto', sans-serif</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="flexbox">{{ trans('app.formations.css3.flexbox_title') }}</h2>
            <p>{{ trans('app.formations.css3.flexbox_text') }}</p>

            <h3>{{ trans('app.formations.css3.flexbox_container_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>flex-direction</code> - Direction des éléments (row, column, row-reverse, column-reverse)</li>
                <li><code>justify-content</code> - Alignement sur l'axe principal (flex-start, center, space-between, space-around)</li>
                <li><code>align-items</code> - Alignement sur l'axe secondaire (flex-start, center, stretch)</li>
                <li><code>flex-wrap</code> - Retour à la ligne (nowrap, wrap, wrap-reverse)</li>
                <li><code>gap</code> - Espacement entre les éléments</li>
            </ul>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">display</span>: <span class="code-value">flex</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">flex-direction</span>: <span class="code-value">row</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">justify-content</span>: <span class="code-value">space-between</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">align-items</span>: <span class="code-value">center</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">gap</span>: <span class="code-value">20px</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">flex-wrap</span>: <span class="code-value">wrap</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.flexbox_items_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>flex-grow</code> - Facteur d'agrandissement (0 = ne grandit pas, 1+ = grandit)</li>
                <li><code>flex-shrink</code> - Facteur de rétrécissement (0 = ne rétrécit pas)</li>
                <li><code>flex-basis</code> - Taille de base avant distribution de l'espace</li>
                <li><code>flex</code> - Raccourci pour grow shrink basis (ex: flex: 1 0 200px)</li>
                <li><code>align-self</code> - Alignement individuel (override align-items)</li>
            </ul>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.item</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">flex</span>: <span class="code-value">1</span>; <span class="code-comment">/* Équivalent à 1 1 0 */</span><br>
                        }<br><br>
                        <span class="code-selector">.item-fixed</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">flex</span>: <span class="code-value">0 0 200px</span>; <span class="code-comment">/* Largeur fixe */</span><br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="grid">🎯 CSS Grid</h2>
            <p>CSS Grid est un système de mise en page bidimensionnel puissant pour créer des grilles complexes avec un contrôle précis sur les lignes et colonnes.</p>

            <h3>Propriétés du conteneur Grid</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>display: grid</code> - Active le mode Grid</li>
                <li><code>grid-template-columns</code> - Définit les colonnes (px, %, fr, auto, repeat())</li>
                <li><code>grid-template-rows</code> - Définit les lignes</li>
                <li><code>gap</code> - Espacement entre cellules (row-gap, column-gap)</li>
                <li><code>grid-auto-flow</code> - Direction de placement (row, column, dense)</li>
                <li><code>justify-items</code> - Alignement horizontal des items (start, center, end, stretch)</li>
                <li><code>align-items</code> - Alignement vertical des items</li>
            </ul>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.grid_define_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">.grid-container</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">display</span>: <span class="code-value">grid</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* 3 colonnes de taille égale (fr = fraction) */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-template-columns</span>: <span class="code-value">1fr 1fr 1fr</span>;<br>
                        &nbsp;&nbsp;<span class="code-comment">/* Raccourci avec repeat() */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-template-columns</span>: <span class="code-value">repeat(3, 1fr)</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Colonnes de tailles différentes */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-template-columns</span>: <span class="code-value">200px 1fr 2fr</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Lignes automatiques */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-template-rows</span>: <span class="code-value">auto</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Espacement */</span><br>
                        &nbsp;&nbsp;<span class="code-property">gap</span>: <span class="code-value">20px</span>; <span class="code-comment">/* ou row-gap: 20px; column-gap: 10px; */</span><br>
                        }
                    </code>
                </div>
            </div>

            <h3>Placement des éléments</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>grid-column</code> - Position sur les colonnes (start / end ou span nombre)</li>
                <li><code>grid-row</code> - Position sur les lignes</li>
                <li><code>grid-area</code> - Raccourci pour row-start / column-start / row-end / column-end</li>
                <li><code>justify-self</code> - Alignement horizontal individuel</li>
                <li><code>align-self</code> - Alignement vertical individuel</li>
            </ul>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Placement avec lignes de début/fin */</span><br>
                        <span class="code-selector">.item1</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">grid-column</span>: <span class="code-value">1 / 3</span>; <span class="code-comment">/* De la ligne 1 à 3 (2 colonnes) */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-row</span>: <span class="code-value">1 / 2</span>;<br>
                        }<br><br>
                        <span class="code-comment">/* Placement avec span */</span><br>
                        <span class="code-selector">.item2</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">grid-column</span>: <span class="code-value">span 2</span>; <span class="code-comment">/* Occupe 2 colonnes */</span><br>
                        &nbsp;&nbsp;<span class="code-property">grid-row</span>: <span class="code-value">span 2</span>; <span class="code-comment">/* Occupe 2 lignes */</span><br>
                        }<br><br>
                        <span class="code-comment">/* Raccourci grid-area */</span><br>
                        <span class="code-selector">.item3</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">grid-area</span>: <span class="code-value">1 / 1 / 3 / 3</span>; <span class="code-comment">/* row-start/col-start/row-end/col-end */</span><br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.grid_areas_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">display</span>: <span class="code-value">grid</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">grid-template-areas</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-value">"header header header"</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-value">"sidebar main main"</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-value">"footer footer footer"</span>;<br>
                        }<br><br>
                        <span class="code-selector">.header</span> { <span class="code-property">grid-area</span>: <span class="code-value">header</span>; }<br>
                        <span class="code-selector">.sidebar</span> { <span class="code-property">grid-area</span>: <span class="code-value">sidebar</span>; }<br>
                        <span class="code-selector">.main</span> { <span class="code-property">grid-area</span>: <span class="code-value">main</span>; }
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 fr vs % :</strong> L'unité <code>fr</code> distribue l'espace disponible APRÈS avoir soustrait les tailles fixes. Plus flexible que les pourcentages !</p>
            </div>

            <h2 id="transitions">{{ trans('app.formations.css3.transitions_title') }}</h2>
            <p>{{ trans('app.formations.css3.transitions_text') }}</p>

            <h3>{{ trans('app.formations.css3.transitions_properties_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>transition-property</code> - Propriété(s) à animer (all, background, transform, etc.)</li>
                <li><code>transition-duration</code> - Durée de la transition (en s ou ms)</li>
                <li><code>transition-timing-function</code> - Courbe d'accélération (ease, linear, ease-in, ease-out, ease-in-out, cubic-bezier)</li>
                <li><code>transition-delay</code> - Délai avant le début (en s ou ms)</li>
                <li><code>transition</code> - Raccourci : property duration timing-function delay</li>
            </ul>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.transitions_shorthand_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-selector">.button</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">background-color</span>: <span class="code-value">#1E90FF</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">transform</span>: <span class="code-value">scale(1)</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Transition sur toutes les propriétés */</span><br>
                        &nbsp;&nbsp;<span class="code-property">transition</span>: <span class="code-value">all 0.3s ease</span>;<br>
                        }<br><br>
                        <span class="code-selector">.button:hover</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">background-color</span>: <span class="code-value">#1873CC</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">transform</span>: <span class="code-value">scale(1.05)</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.transitions_multiple_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.card</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">/* Propriétés individuelles */</span><br>
                        &nbsp;&nbsp;<span class="code-property">transition-property</span>: <span class="code-value">background-color, transform, box-shadow</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">transition-duration</span>: <span class="code-value">0.3s, 0.5s, 0.3s</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">transition-timing-function</span>: <span class="code-value">ease, ease-out, ease</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Ou en raccourci */</span><br>
                        &nbsp;&nbsp;<span class="code-property">transition</span>: <span class="code-value">background-color 0.3s ease,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;transform 0.5s ease-out,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;box-shadow 0.3s ease</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.transitions_timing_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(['linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'cubic-bezier(n,n,n,n)'] as $index => $func)
                <li><code>{{ $func }}</code> - {{ trans('app.formations.css3.transitions_timing_list')[$index] }}</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.transitions_performance') }}</strong></p>
            </div>

            <h2 id="animations">{{ trans('app.formations.css3.animations_title') }}</h2>
            <p>{{ trans('app.formations.css3.animations_text') }}</p>

            <h3>{{ trans('app.formations.css3.animations_properties_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>animation-name</code> - Nom de l'animation @keyframes</li>
                <li><code>animation-duration</code> - Durée (en s ou ms)</li>
                <li><code>animation-timing-function</code> - Courbe d'accélération (ease, linear, etc.)</li>
                <li><code>animation-delay</code> - Délai avant le début</li>
                <li><code>animation-iteration-count</code> - Nombre de répétitions (nombre ou infinite)</li>
                <li><code>animation-direction</code> - Direction (normal, reverse, alternate, alternate-reverse)</li>
                <li><code>animation-fill-mode</code> - État avant/après (none, forwards, backwards, both)</li>
                <li><code>animation-play-state</code> - État de lecture (running, paused)</li>
            </ul>

            <div class="example-box">
                <h3>{{ trans('app.formations.css3.animations_keyframes_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Avec pourcentages */</span><br>
                        <span class="code-selector">@keyframes</span> <span class="code-value">slideIn</span> {<br>
                        &nbsp;&nbsp;<span class="code-selector">0%</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">transform</span>: <span class="code-value">translateX(-100%)</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">opacity</span>: <span class="code-value">0</span>;<br>
                        &nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;<span class="code-selector">50%</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">transform</span>: <span class="code-value">translateX(-50%)</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">opacity</span>: <span class="code-value">0.5</span>;<br>
                        &nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;<span class="code-selector">100%</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">transform</span>: <span class="code-value">translateX(0)</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">opacity</span>: <span class="code-value">1</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">/* Avec from/to */</span><br>
                        <span class="code-selector">@keyframes</span> <span class="code-value">fadeIn</span> {<br>
                        &nbsp;&nbsp;<span class="code-selector">from</span> { <span class="code-property">opacity</span>: <span class="code-value">0</span>; }<br>
                        &nbsp;&nbsp;<span class="code-selector">to</span> { <span class="code-property">opacity</span>: <span class="code-value">1</span>; }<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.animations_apply_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Syntaxe complète */</span><br>
                        <span class="code-selector">.box</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">animation-name</span>: <span class="code-value">slideIn</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">animation-duration</span>: <span class="code-value">1s</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">animation-timing-function</span>: <span class="code-value">ease-out</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">animation-delay</span>: <span class="code-value">0.5s</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">animation-iteration-count</span>: <span class="code-value">3</span>; <span class="code-comment">/* ou infinite */</span><br>
                        &nbsp;&nbsp;<span class="code-property">animation-direction</span>: <span class="code-value">alternate</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">animation-fill-mode</span>: <span class="code-value">forwards</span>; <span class="code-comment">/* Garde l'état final */</span><br>
                        }<br><br>
                        <span class="code-comment">/* Raccourci */</span><br>
                        <span class="code-selector">.box</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">animation</span>: <span class="code-value">slideIn 1s ease-out 0.5s 3 alternate forwards</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.animations_multiple_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.element</span> {<br>
                        &nbsp;&nbsp;<span class="code-property">animation</span>: <span class="code-value">slideIn 1s ease-out,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fadeIn 0.5s ease,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;rotate 2s linear infinite</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.animations_fill_mode') }}</strong></p>
            </div>

            <h2 id="responsive">{{ trans('app.formations.css3.responsive_title') }}</h2>
            <p>{{ trans('app.formations.css3.responsive_text') }}</p>

            <h3>{{ trans('app.formations.css3.responsive_syntax_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(['@media (condition)', 'min-width', 'max-width', 'orientation', 'and'] as $index => $item)
                <li><code>{{ $item }}</code> - {{ trans('app.formations.css3.responsive_syntax_list')[$index] }}</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.css3.responsive_breakpoints_title') }}</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">/* Mobile (0-768px) */</span><br>
                        <span class="code-selector">@media (max-width: 768px)</span> {<br>
                        &nbsp;&nbsp;<span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">flex-direction</span>: <span class="code-value">column</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">/* Tablette (769px-1024px) */</span><br>
                        <span class="code-selector">@media (min-width: 769px) and (max-width: 1024px)</span> {<br>
                        &nbsp;&nbsp;<span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">grid-template-columns</span>: <span class="code-value">repeat(2, 1fr)</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">/* Desktop (1025px+) */</span><br>
                        <span class="code-selector">@media (min-width: 1025px)</span> {<br>
                        &nbsp;&nbsp;<span class="code-selector">.container</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-property">grid-template-columns</span>: <span class="code-value">repeat(3, 1fr)</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }
                    </code>
                </div>
            </div>

            <h3>{{ trans('app.formations.css3.responsive_units_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(['vw', 'vh', 'vmin', 'vmax', '%', 'em', 'rem', 'clamp(min, préféré, max)'] as $index => $unit)
                <li><code>{{ $unit }}</code> - {{ trans('app.formations.css3.responsive_units_list')[$index] }}</li>
                @endforeach
            </ul>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-selector">.responsive</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">/* Taille fluide avec limites */</span><br>
                        &nbsp;&nbsp;<span class="code-property">font-size</span>: <span class="code-value">clamp(16px, 2vw, 24px)</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">width</span>: <span class="code-value">90vw</span>;<br>
                        &nbsp;&nbsp;<span class="code-property">max-width</span>: <span class="code-value">1200px</span>;<br>
                        }
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>{{ trans('app.formations.css3.responsive_mobile_first') }}</strong></p>
            </div>

            <h2>{{ trans('app.formations.css3.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.css3.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.css3.learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.css3.learned_list') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.html5') }}" class="nav-btn">❮ {{ trans('app.formations.css3.previous') }}: HTML5</a>
                <a href="{{ route('formations.javascript') }}" class="nav-btn">{{ trans('app.formations.css3.next') }}: JavaScript ❯</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #1E90FF, #1873CC) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(30, 144, 255, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #1E90FF, #1873CC) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(30, 144, 255, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
