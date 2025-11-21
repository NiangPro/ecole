@extends('layouts.app')

@section('title', 'Formation JavaScript | DevFormation')

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
        background-color: #F7DF1E;
        color: #000;
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
        border: 1px solid rgba(247, 223, 30, 0.2);
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
        background: linear-gradient(180deg, #F7DF1E 0%, #D4B91E 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #D4B91E 0%, #B39A1A 100%);
    }
    .sidebar h3 {
        color: #F7DF1E;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(247, 223, 30, 0.2);
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
        background: #F7DF1E;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(247, 223, 30, 0.1) 0%, rgba(247, 223, 30, 0.05) 100%);
        color: #D4B91E;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(247, 223, 30, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #F7DF1E 0%, #D4B91E 100%);
        color: #000;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(247, 223, 30, 0.3);
        transform: translateX(5px);
    }
    .sidebar a.active::before {
        transform: scaleY(1);
        background: #000;
    }
    
    .sidebar-close-btn {
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(247, 223, 30, 0.1) !important;
        border: 2px solid rgba(247, 223, 30, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(247, 223, 30, 0.2) !important;
        border-color: rgba(247, 223, 30, 0.5) !important;
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
        background: linear-gradient(135deg, #F7DF1E, #D4B91E);
        border: none;
        border-radius: 50%;
        color: #000;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(247, 223, 30, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(247, 223, 30, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #D4B91E, #B39A1A);
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
        border-left: 4px solid #F7DF1E;
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
        border: 2px solid #F7DF1E;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(247, 223, 30, 0.1);
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
        content: 'JS';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #F7DF1E;
        color: #000;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    /* Bouton de copie - Même taille que le label JS */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 80px;
        background: #F7DF1E;
        color: #000;
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
        background: #D4C017;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }
    
    .copy-code-btn:active {
        transform: translateY(0);
    }
    
    .copy-code-btn.copied {
        background: rgba(34, 197, 94, 0.9);
        color: white;
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
    .code-keyword {
        color: #c678dd;
    }
    .code-function {
        color: #61afef;
    }
    .code-string {
        color: #98c379;
    }
    .code-number {
        color: #d19a66;
    }
    .code-comment {
        color: #5c6370;
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
        background-color: #F7DF1E;
        color: #000;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #D4B91E;
        box-shadow: 0 4px 12px rgba(247, 223, 30, 0.3);
    }
    @media (max-width: 992px) {
        }
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #F7DF1E 0%, #D4B91E 100%);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #D4B91E 0%, #B39A1A 100%);
        }
        .sidebar h3 {
            color: #F7DF1E;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(247, 223, 30, 0.2);
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
            background: #F7DF1E;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(247, 223, 30, 0.1) 0%, rgba(247, 223, 30, 0.05) 100%);
            color: #D4B91E;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(247, 223, 30, 0.15);
        }
        .sidebar a:hover::before {
            transform: scaleY(1);
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #F7DF1E 0%, #D4B91E 100%);
            color: #000;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(247, 223, 30, 0.3);
            transform: translateX(5px);
        }
        .sidebar a.active::before {
            transform: scaleY(1);
            background: #000;
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
            border-left: 4px solid #F7DF1E;
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
            border: 2px solid #F7DF1E;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            word-wrap: break-word;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(247, 223, 30, 0.1);
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
            content: 'JS';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #F7DF1E;
            color: #000;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .code-box code {
            color: #e2e8f0;
            line-height: 1.6;
        }
        .code-keyword {
            color: #c678dd;
        }
        .code-function {
            color: #61afef;
        }
        .code-string {
            color: #98c379;
        }
        .code-number {
            color: #d19a66;
        }
        .code-comment {
            color: #5c6370;
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
            background-color: #F7DF1E;
            color: #000;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .nav-btn:hover {
            background-color: #D4B91E;
            box-shadow: 0 4px 12px rgba(247, 223, 30, 0.3);
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
        
        @media (max-width: 992px) {
            .content-wrapper {
                flex-direction: column;
            }
            .main-content {
                max-width: 100%;
            }
        }
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px; color: #000;">Tutoriel JavaScript</h1>
    <p style="font-size: 20px; color: #000;">Apprenez à rendre vos sites web interactifs avec JavaScript</p>
</div>

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Ouvrir le menu">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(247, 223, 30, 0.2);">
                <h3 style="margin: 0;">JavaScript Tutorial</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #F7DF1E; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="Fermer le menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">Introduction JS</a>
            <a href="#variables">Variables</a>
            <a href="#datatypes">Types de données</a>
            <a href="#operators">Opérateurs</a>
            <a href="#conditions">Conditions</a>
            <a href="#loops">Boucles</a>
            <a href="#functions">Fonctions</a>
            <a href="#arrays">Tableaux</a>
            <a href="#objects">Objets</a>
            <a href="#dom">DOM</a>
            <a href="#events">Événements</a>
            <a href="#es6">ES6+</a>
            <a href="#async">Asynchrone</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à JavaScript</h1>
            <p>JavaScript est le langage de programmation du web. Il permet de rendre vos pages web interactives et dynamiques.</p>

            <h3>🚀 Pourquoi apprendre JavaScript ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Langage incontournable</strong> - Utilisé par 98% des sites web</li>
                <li>✅ <strong>Polyvalent</strong> - Frontend, Backend (Node.js), Mobile, Desktop</li>
                <li>✅ <strong>Interactivité</strong> - Créez des expériences utilisateur riches</li>
                <li>✅ <strong>Écosystème riche</strong> - Milliers de bibliothèques et frameworks</li>
                <li>✅ <strong>Opportunités</strong> - Forte demande sur le marché du travail</li>
            </ul>

            <h2 id="variables">📦 Variables</h2>
            <p>Les variables permettent de stocker des données. JavaScript offre trois façons de déclarer des variables.</p>

            <div class="example-box">
                <h3>Déclaration de variables</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// var - Ancienne méthode (éviter)</span><br>
                        <span class="code-keyword">var</span> nom = <span class="code-string">"Jean"</span>;<br><br>
                        <span class="code-comment">// let - Variable modifiable</span><br>
                        <span class="code-keyword">let</span> age = <span class="code-number">25</span>;<br>
                        age = <span class="code-number">26</span>; <span class="code-comment">// OK</span><br><br>
                        <span class="code-comment">// const - Constante (non modifiable)</span><br>
                        <span class="code-keyword">const</span> PI = <span class="code-number">3.14159</span>;<br>
                        <span class="code-comment">// PI = 3; // Erreur!</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez <code>const</code> par défaut, <code>let</code> si la valeur doit changer, et évitez <code>var</code>.</p>
            </div>

            <h2 id="datatypes">🔢 Types de données</h2>
            <p>JavaScript est un langage à typage dynamique avec plusieurs types de données primitifs et complexes.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Types primitifs</span><br>
                        <span class="code-keyword">let</span> texte = <span class="code-string">"Hello"</span>; <span class="code-comment">// String</span><br>
                        <span class="code-keyword">let</span> nombre = <span class="code-number">42</span>; <span class="code-comment">// Number</span><br>
                        <span class="code-keyword">let</span> decimal = <span class="code-number">3.14</span>; <span class="code-comment">// Number</span><br>
                        <span class="code-keyword">let</span> vrai = <span class="code-keyword">true</span>; <span class="code-comment">// Boolean</span><br>
                        <span class="code-keyword">let</span> vide = <span class="code-keyword">null</span>; <span class="code-comment">// Null</span><br>
                        <span class="code-keyword">let</span> indefini; <span class="code-comment">// Undefined</span><br><br>
                        <span class="code-comment">// Types complexes</span><br>
                        <span class="code-keyword">let</span> tableau = [<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>]; <span class="code-comment">// Array</span><br>
                        <span class="code-keyword">let</span> objet = {nom: <span class="code-string">"Jean"</span>, age: <span class="code-number">25</span>}; <span class="code-comment">// Object</span>
                    </code>
                </div>
            </div>

            <h2 id="operators">➕ Opérateurs</h2>
            <p>Les opérateurs permettent d'effectuer des opérations sur les variables et valeurs.</p>

            <div class="example-box">
                <h3>Opérateurs arithmétiques</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> a = <span class="code-number">10</span>, b = <span class="code-number">3</span>;<br><br>
                        <span class="code-function">console.log</span>(a + b); <span class="code-comment">// 13 - Addition</span><br>
                        <span class="code-function">console.log</span>(a - b); <span class="code-comment">// 7 - Soustraction</span><br>
                        <span class="code-function">console.log</span>(a * b); <span class="code-comment">// 30 - Multiplication</span><br>
                        <span class="code-function">console.log</span>(a / b); <span class="code-comment">// 3.333... - Division</span><br>
                        <span class="code-function">console.log</span>(a % b); <span class="code-comment">// 1 - Modulo (reste)</span><br>
                        <span class="code-function">console.log</span>(a ** b); <span class="code-comment">// 1000 - Puissance</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Opérateurs de comparaison</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Égalité (== compare valeur, === compare valeur ET type)</span><br>
                        <span class="code-function">console.log</span>(<span class="code-number">5</span> == <span class="code-string">"5"</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">console.log</span>(<span class="code-number">5</span> === <span class="code-string">"5"</span>); <span class="code-comment">// false</span><br><br>
                        <span class="code-comment">// Autres comparaisons</span><br>
                        <span class="code-function">console.log</span>(<span class="code-number">5</span> != <span class="code-number">3</span>); <span class="code-comment">// true - Différent</span><br>
                        <span class="code-function">console.log</span>(<span class="code-number">5</span> > <span class="code-number">3</span>); <span class="code-comment">// true - Supérieur</span><br>
                        <span class="code-function">console.log</span>(<span class="code-number">5</span> >= <span class="code-number">5</span>); <span class="code-comment">// true - Supérieur ou égal</span>
                    </code>
                </div>
            </div>

            <h2 id="conditions">🔀 Conditions</h2>
            <p>Les structures conditionnelles permettent d'exécuter du code selon des conditions.</p>

            <div class="example-box">
                <h3>if / else if / else</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> age = <span class="code-number">18</span>;<br><br>
                        <span class="code-keyword">if</span> (age < <span class="code-number">13</span>) {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(<span class="code-string">"Enfant"</span>);<br>
                        } <span class="code-keyword">else if</span> (age < <span class="code-number">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(<span class="code-string">"Adolescent"</span>);<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(<span class="code-string">"Adulte"</span>);<br>
                        }
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Opérateur ternaire</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> age = <span class="code-number">20</span>;<br>
                        <span class="code-keyword">let</span> statut = age >= <span class="code-number">18</span> ? <span class="code-string">"Majeur"</span> : <span class="code-string">"Mineur"</span>;<br>
                        <span class="code-function">console.log</span>(statut); <span class="code-comment">// "Majeur"</span>
                    </code>
                </div>
            </div>

            <h2 id="loops">🔁 Boucles</h2>
            <p>Les boucles permettent de répéter des instructions plusieurs fois.</p>

            <div class="example-box">
                <h3>Boucle for</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">for</span> (<span class="code-keyword">let</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(<span class="code-string">"Itération "</span> + i);<br>
                        }<br>
                        <span class="code-comment">// Affiche: Itération 0, 1, 2, 3, 4</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Boucle while</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> i = <span class="code-number">0</span>;<br>
                        <span class="code-keyword">while</span> (i < <span class="code-number">3</span>) {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(i);<br>
                        &nbsp;&nbsp;i++;<br>
                        }
                    </code>
                </div>
            </div>

            <h2 id="functions">⚡ Fonctions</h2>
            <p>Les fonctions sont des blocs de code réutilisables qui effectuent une tâche spécifique.</p>

            <div class="example-box">
                <h3>Déclaration de fonction</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Fonction classique</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">saluer</span>(nom) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour "</span> + nom;<br>
                        }<br><br>
                        <span class="code-function">console.log</span>(<span class="code-function">saluer</span>(<span class="code-string">"Marie"</span>)); <span class="code-comment">// "Bonjour Marie"</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Arrow function (ES6)</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Syntaxe moderne et concise</span><br>
                        <span class="code-keyword">const</span> <span class="code-function">multiplier</span> = (a, b) => a * b;<br><br>
                        <span class="code-function">console.log</span>(<span class="code-function">multiplier</span>(<span class="code-number">5</span>, <span class="code-number">3</span>)); <span class="code-comment">// 15</span>
                    </code>
                </div>
            </div>

            <h2 id="arrays">📚 Tableaux</h2>
            <p>Les tableaux permettent de stocker plusieurs valeurs dans une seule variable.</p>

            <div class="example-box">
                <h3>Création et manipulation</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> fruits = [<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>];<br><br>
                        <span class="code-comment">// Accès aux éléments</span><br>
                        <span class="code-function">console.log</span>(fruits[<span class="code-number">0</span>]); <span class="code-comment">// "Pomme"</span><br><br>
                        <span class="code-comment">// Ajouter un élément</span><br>
                        fruits.<span class="code-function">push</span>(<span class="code-string">"Mangue"</span>);<br><br>
                        <span class="code-comment">// Longueur du tableau</span><br>
                        <span class="code-function">console.log</span>(fruits.length); <span class="code-comment">// 4</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Méthodes de tableau</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> nombres = [<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>];<br><br>
                        <span class="code-comment">// map - Transformer chaque élément</span><br>
                        <span class="code-keyword">let</span> doubles = nombres.<span class="code-function">map</span>(n => n * <span class="code-number">2</span>);<br><br>
                        <span class="code-comment">// filter - Filtrer les éléments</span><br>
                        <span class="code-keyword">let</span> pairs = nombres.<span class="code-function">filter</span>(n => n % <span class="code-number">2</span> === <span class="code-number">0</span>);<br><br>
                        <span class="code-comment">// reduce - Réduire à une valeur</span><br>
                        <span class="code-keyword">let</span> somme = nombres.<span class="code-function">reduce</span>((acc, n) => acc + n, <span class="code-number">0</span>);
                    </code>
                </div>
            </div>

            <h2 id="objects">🎯 Objets</h2>
            <p>Les objets permettent de regrouper des données et fonctionnalités liées.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> personne = {<br>
                        &nbsp;&nbsp;nom: <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;age: <span class="code-number">30</span>,<br>
                        &nbsp;&nbsp;<span class="code-function">saluer</span>: <span class="code-keyword">function</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour, je suis "</span> + <span class="code-keyword">this</span>.nom;<br>
                        &nbsp;&nbsp;}<br>
                        };<br><br>
                        <span class="code-function">console.log</span>(personne.nom); <span class="code-comment">// "Jean"</span><br>
                        <span class="code-function">console.log</span>(personne.<span class="code-function">saluer</span>()); <span class="code-comment">// "Bonjour, je suis Jean"</span>
                    </code>
                </div>
            </div>

            <h2 id="dom">🌐 Manipulation du DOM</h2>
            <p>Le DOM (Document Object Model) permet d'interagir avec les éléments HTML.</p>

            <div class="example-box">
                <h3>Sélection d'éléments</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Sélectionner par ID</span><br>
                        <span class="code-keyword">let</span> element = document.<span class="code-function">getElementById</span>(<span class="code-string">"monId"</span>);<br><br>
                        <span class="code-comment">// Sélectionner par classe</span><br>
                        <span class="code-keyword">let</span> elements = document.<span class="code-function">getElementsByClassName</span>(<span class="code-string">"maClasse"</span>);<br><br>
                        <span class="code-comment">// Sélecteur CSS (moderne)</span><br>
                        <span class="code-keyword">let</span> elem = document.<span class="code-function">querySelector</span>(<span class="code-string">".maClasse"</span>);<br>
                        <span class="code-keyword">let</span> elems = document.<span class="code-function">querySelectorAll</span>(<span class="code-string">"p"</span>);
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Modification du contenu</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> titre = document.<span class="code-function">querySelector</span>(<span class="code-string">"h1"</span>);<br><br>
                        <span class="code-comment">// Modifier le texte</span><br>
                        titre.textContent = <span class="code-string">"Nouveau titre"</span>;<br><br>
                        <span class="code-comment">// Modifier le HTML</span><br>
                        titre.innerHTML = <span class="code-string">"&lt;span&gt;Titre stylé&lt;/span&gt;"</span>;<br><br>
                        <span class="code-comment">// Modifier les styles</span><br>
                        titre.style.color = <span class="code-string">"blue"</span>;
                    </code>
                </div>
            </div>

            <h2 id="events">🖱️ Événements</h2>
            <p>Les événements permettent de réagir aux actions de l'utilisateur.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> bouton = document.<span class="code-function">querySelector</span>(<span class="code-string">"button"</span>);<br><br>
                        <span class="code-comment">// Écouter un clic</span><br>
                        bouton.<span class="code-function">addEventListener</span>(<span class="code-string">"click"</span>, <span class="code-keyword">function</span>() {<br>
                        &nbsp;&nbsp;<span class="code-function">alert</span>(<span class="code-string">"Bouton cliqué!"</span>);<br>
                        });<br><br>
                        <span class="code-comment">// Avec arrow function</span><br>
                        bouton.<span class="code-function">addEventListener</span>(<span class="code-string">"click"</span>, () => {<br>
                        &nbsp;&nbsp;<span class="code-function">console.log</span>(<span class="code-string">"Clic détecté"</span>);<br>
                        });
                    </code>
                </div>
            </div>

            <h2 id="es6">🚀 ES6+ Fonctionnalités modernes</h2>
            <p>ES6 (ECMAScript 2015) a introduit de nombreuses fonctionnalités modernes.</p>

            <div class="example-box">
                <h3>Destructuring</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// Destructuring d'objets</span><br>
                        <span class="code-keyword">let</span> personne = {nom: <span class="code-string">"Jean"</span>, age: <span class="code-number">30</span>};<br>
                        <span class="code-keyword">let</span> {nom, age} = personne;<br><br>
                        <span class="code-comment">// Destructuring de tableaux</span><br>
                        <span class="code-keyword">let</span> [a, b] = [<span class="code-number">1</span>, <span class="code-number">2</span>];
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Spread operator</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> arr1 = [<span class="code-number">1</span>, <span class="code-number">2</span>];<br>
                        <span class="code-keyword">let</span> arr2 = [...arr1, <span class="code-number">3</span>, <span class="code-number">4</span>]; <span class="code-comment">// [1, 2, 3, 4]</span><br><br>
                        <span class="code-keyword">let</span> obj1 = {a: <span class="code-number">1</span>};<br>
                        <span class="code-keyword">let</span> obj2 = {...obj1, b: <span class="code-number">2</span>}; <span class="code-comment">// {a: 1, b: 2}</span>
                    </code>
                </div>
            </div>

            <h2 id="async">⏱️ Programmation asynchrone</h2>
            <p>JavaScript permet d'exécuter du code de manière asynchrone pour ne pas bloquer l'exécution.</p>

            <div class="example-box">
                <h3>Promises</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">let</span> promesse = <span class="code-keyword">new</span> <span class="code-function">Promise</span>((resolve, reject) => {<br>
                        &nbsp;&nbsp;<span class="code-function">setTimeout</span>(() => {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">resolve</span>(<span class="code-string">"Succès!"</span>);<br>
                        &nbsp;&nbsp;}, <span class="code-number">1000</span>);<br>
                        });<br><br>
                        promesse.<span class="code-function">then</span>(resultat => <span class="code-function">console.log</span>(resultat));
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Async/Await</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">async function</span> <span class="code-function">chargerDonnees</span>() {<br>
                        &nbsp;&nbsp;<span class="code-keyword">try</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">let</span> response = <span class="code-keyword">await</span> <span class="code-function">fetch</span>(<span class="code-string">"api/data"</span>);<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">let</span> data = <span class="code-keyword">await</span> response.<span class="code-function">json</span>();<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">console.log</span>(data);<br>
                        &nbsp;&nbsp;} <span class="code-keyword">catch</span> (error) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">console.error</span>(error);<br>
                        &nbsp;&nbsp;}<br>
                        }
                    </code>
                </div>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous avez maintenant une solide base en JavaScript.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Variables et types de données</li>
                    <li>Opérateurs et expressions</li>
                    <li>Structures conditionnelles</li>
                    <li>Boucles et itérations</li>
                    <li>Fonctions et arrow functions</li>
                    <li>Tableaux et méthodes</li>
                    <li>Objets et propriétés</li>
                    <li>Manipulation du DOM</li>
                    <li>Gestion des événements</li>
                    <li>Fonctionnalités ES6+</li>
                    <li>Programmation asynchrone</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.css3') }}" class="nav-btn">❮ Précédent: CSS3</a>
                <a href="{{ route('formations.bootstrap') }}" class="nav-btn">Suivant: Bootstrap ❯</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #F7DF1E, #D4B91E) !important; border: none !important; border-radius: 50% !important; color: #000 !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(247, 223, 30, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #F7DF1E, #D4B91E) !important; border: none !important; border-radius: 50% !important; color: #000 !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(247, 223, 30, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
