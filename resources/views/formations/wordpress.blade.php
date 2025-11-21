@extends('layouts.app')

@section('title', 'Formation WordPress | DevFormation')

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
        background-color: #21759B;
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
        border: 1px solid rgba(33, 117, 155, 0.2);
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
        background: linear-gradient(180deg, #21759B 0%, #1A5F7A 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #1A5F7A 0%, #134A5F 100%);
    }
    .sidebar h3 {
        color: #21759B;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(33, 117, 155, 0.2);
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
        background: #21759B;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(33, 117, 155, 0.1) 0%, rgba(33, 117, 155, 0.05) 100%);
        color: #21759B;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(33, 117, 155, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #21759B 0%, #1A5F7A 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(33, 117, 155, 0.3);
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
        background: rgba(33, 117, 155, 0.1) !important;
        border: 2px solid rgba(33, 117, 155, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(33, 117, 155, 0.2) !important;
        border-color: rgba(33, 117, 155, 0.5) !important;
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
        background: linear-gradient(135deg, #21759B, #1A5F7A);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(33, 117, 155, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(33, 117, 155, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #1A5F7A, #154A5F);
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
        border-left: 4px solid #21759B;
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
        border: 2px solid #21759B;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(33, 117, 155, 0.1);
        position: relative;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    .code-box code {
        display: block;
        max-width: 100%;
        overflow-wrap: break-word;
        color: #e2e8f0;
        line-height: 1.6;
    }
    .code-box::before {
        content: 'WordPress';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #21759B;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .code-function {
        color: #61afef;
    }
    .code-tag {
        color: #c678dd;
    }
    .code-string {
        color: #98c379;
    }
    .code-variable {
        color: #e5c07b;
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
        background-color: #21759B;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #1A5F7A;
        box-shadow: 0 4px 12px rgba(33, 117, 155, 0.3);
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
            background: linear-gradient(180deg, #21759B 0%, #1A5F7A 100%);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1A5F7A 0%, #134A5F 100%);
        }
        .sidebar h3 {
            color: #21759B;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(33, 117, 155, 0.2);
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
            background: #21759B;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(33, 117, 155, 0.1) 0%, rgba(33, 117, 155, 0.05) 100%);
            color: #21759B;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(33, 117, 155, 0.15);
        }
        .sidebar a:hover::before {
            transform: scaleY(1);
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #21759B 0%, #1A5F7A 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(33, 117, 155, 0.3);
            transform: translateX(5px);
        }
        .sidebar a.active::before {
            transform: scaleY(1);
            background: white;
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
            border-left: 4px solid #21759B;
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
            border: 2px solid #21759B;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            word-wrap: break-word;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(33, 117, 155, 0.1);
            position: relative;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }
        .code-box code {
            display: block;
            max-width: 100%;
            overflow-wrap: break-word;
            color: #e2e8f0;
            line-height: 1.6;
        }
        .code-box::before {
            content: 'WordPress';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #21759B;
            color: white;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .code-function {
            color: #61afef;
        }
        .code-tag {
            color: #c678dd;
        }
        .code-string {
            color: #98c379;
        }
        .code-variable {
            color: #e5c07b;
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
            background-color: #21759B;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .nav-btn:hover {
            background-color: #1A5F7A;
            box-shadow: 0 4px 12px rgba(33, 117, 155, 0.3);
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
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px;">Tutoriel WordPress</h1>
    <p style="font-size: 20px;">Créez des sites web professionnels avec WordPress</p>
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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(33, 117, 155, 0.2);">
                <h3 style="margin: 0;">WordPress Tutorial</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #21759B; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="Fermer le menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">Introduction WordPress</a>
            <a href="#install">Installation</a>
            <a href="#dashboard">Tableau de bord</a>
            <a href="#pages">Pages</a>
            <a href="#posts">Articles</a>
            <a href="#media">Médias</a>
            <a href="#themes">Thèmes</a>
            <a href="#plugins">Plugins</a>
            <a href="#menus">Menus</a>
            <a href="#widgets">Widgets</a>
            <a href="#users">Utilisateurs</a>
            <a href="#seo">SEO</a>
            <a href="#security">Sécurité</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à WordPress</h1>
            <p>WordPress est le système de gestion de contenu (CMS) le plus populaire au monde, alimentant plus de 43% des sites web. Il permet de créer des sites web professionnels sans connaître le code.</p>

            <h3>🚀 Pourquoi choisir WordPress ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Facile à utiliser</strong> - Interface intuitive pour tous</li>
                <li>✅ <strong>Flexible</strong> - Des milliers de thèmes et plugins</li>
                <li>✅ <strong>SEO-friendly</strong> - Optimisé pour les moteurs de recherche</li>
                <li>✅ <strong>Gratuit</strong> - Open-source et communauté active</li>
                <li>✅ <strong>Scalable</strong> - Du blog au site e-commerce</li>
            </ul>

            <h2 id="install">📦 Installation</h2>
            <p>WordPress peut être installé localement ou sur un hébergeur web.</p>

            <div class="example-box">
                <h3>Installation locale (XAMPP/WAMP)</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>1. Télécharger WordPress depuis wordpress.org</li>
                    <li>2. Extraire dans le dossier htdocs</li>
                    <li>3. Créer une base de données MySQL</li>
                    <li>4. Lancer l'installation via navigateur</li>
                </ul>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note :</strong> La plupart des hébergeurs proposent une installation WordPress en un clic.</p>
            </div>

            <h2 id="dashboard">🎛️ Tableau de bord</h2>
            <p>Le tableau de bord WordPress est votre centre de contrôle pour gérer votre site.</p>

            <div class="example-box">
                <h3>Éléments principaux</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Articles</strong> - Gérer vos articles de blog</li>
                    <li><strong>Médias</strong> - Bibliothèque d'images et fichiers</li>
                    <li><strong>Pages</strong> - Créer des pages statiques</li>
                    <li><strong>Apparence</strong> - Thèmes, menus, widgets</li>
                    <li><strong>Extensions</strong> - Ajouter des fonctionnalités</li>
                    <li><strong>Réglages</strong> - Configuration du site</li>
                </ul>
            </div>

            <h2 id="pages">📄 Pages</h2>
            <p>Les pages sont utilisées pour le contenu statique (À propos, Contact, Services).</p>

            <div class="example-box">
                <h3>Créer une page</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>1. Cliquer sur <strong>Pages → Ajouter</strong></li>
                    <li>2. Saisir le titre et le contenu</li>
                    <li>3. Choisir un modèle de page</li>
                    <li>4. Définir l'image à la une</li>
                    <li>5. Publier ou enregistrer comme brouillon</li>
                </ul>
            </div>

            <h2 id="posts">📝 Articles</h2>
            <p>Les articles sont pour le contenu dynamique et chronologique (blog).</p>

            <div class="example-box">
                <h3>Créer un article</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>1. Aller dans <strong>Articles → Ajouter</strong></li>
                    <li>2. Rédiger le contenu avec l'éditeur Gutenberg</li>
                    <li>3. Ajouter des catégories et étiquettes</li>
                    <li>4. Définir l'image à la une</li>
                    <li>5. Publier immédiatement ou programmer</li>
                </ul>
            </div>

            <h2 id="media">🖼️ Médias</h2>
            <p>La bibliothèque de médias stocke toutes vos images, vidéos et fichiers.</p>

            <div class="example-box">
                <h3>Formats supportés</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Images</strong> - JPG, PNG, GIF, WebP</li>
                    <li><strong>Documents</strong> - PDF, DOC, XLS</li>
                    <li><strong>Audio</strong> - MP3, WAV, OGG</li>
                    <li><strong>Vidéo</strong> - MP4, MOV, AVI</li>
                </ul>
            </div>

            <h2 id="themes">🎨 Thèmes</h2>
            <p>Les thèmes contrôlent l'apparence visuelle de votre site.</p>

            <div class="example-box">
                <h3>Installer un thème</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>1. <strong>Apparence → Thèmes → Ajouter</strong></li>
                    <li>2. Rechercher un thème gratuit</li>
                    <li>3. Cliquer sur <strong>Installer</strong></li>
                    <li>4. Activer le thème</li>
                    <li>5. Personnaliser via <strong>Personnaliser</strong></li>
                </ul>
            </div>

            <h2 id="plugins">🔌 Plugins</h2>
            <p>Les plugins ajoutent des fonctionnalités à votre site WordPress.</p>

            <div class="example-box">
                <h3>Plugins essentiels</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Yoast SEO</strong> - Optimisation SEO</li>
                    <li><strong>Contact Form 7</strong> - Formulaires de contact</li>
                    <li><strong>Wordfence</strong> - Sécurité</li>
                    <li><strong>WP Super Cache</strong> - Performance</li>
                    <li><strong>Elementor</strong> - Constructeur de pages</li>
                </ul>
            </div>

            <h2 id="menus">🧭 Menus</h2>
            <p>Les menus permettent de créer la navigation de votre site.</p>

            <div class="example-box">
                <h3>Créer un menu</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>1. <strong>Apparence → Menus</strong></li>
                    <li>2. Créer un nouveau menu</li>
                    <li>3. Ajouter des pages, articles, liens personnalisés</li>
                    <li>4. Organiser par glisser-déposer</li>
                    <li>5. Assigner à un emplacement (Header, Footer)</li>
                </ul>
            </div>

            <h2 id="widgets">📦 Widgets</h2>
            <p>Les widgets ajoutent du contenu dans les zones comme la sidebar ou le footer.</p>

            <div class="example-box">
                <h3>Widgets populaires</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Recherche</strong> - Barre de recherche</li>
                    <li><strong>Articles récents</strong> - Derniers articles</li>
                    <li><strong>Catégories</strong> - Liste des catégories</li>
                    <li><strong>Texte</strong> - Contenu personnalisé</li>
                    <li><strong>Réseaux sociaux</strong> - Liens sociaux</li>
                </ul>
            </div>

            <h2 id="users">👥 Utilisateurs</h2>
            <p>WordPress permet de gérer plusieurs utilisateurs avec différents rôles.</p>

            <div class="example-box">
                <h3>Rôles utilisateurs</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Administrateur</strong> - Accès complet</li>
                    <li><strong>Éditeur</strong> - Gérer tous les contenus</li>
                    <li><strong>Auteur</strong> - Publier ses propres articles</li>
                    <li><strong>Contributeur</strong> - Écrire des articles</li>
                    <li><strong>Abonné</strong> - Lecture seule</li>
                </ul>
            </div>

            <h2 id="seo">🔍 SEO</h2>
            <p>Optimisez votre site pour les moteurs de recherche.</p>

            <div class="example-box">
                <h3>Bonnes pratiques SEO</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>Installer <strong>Yoast SEO</strong> ou <strong>Rank Math</strong></li>
                    <li>Optimiser les titres et méta-descriptions</li>
                    <li>Utiliser des URLs propres (permaliens)</li>
                    <li>Ajouter du texte alt aux images</li>
                    <li>Créer un sitemap XML</li>
                    <li>Améliorer la vitesse du site</li>
                </ul>
            </div>

            <h2 id="security">🔒 Sécurité</h2>
            <p>Protégez votre site WordPress contre les menaces.</p>

            <div class="example-box">
                <h3>Mesures de sécurité</h3>
                <ul style="line-height: 2; color: #000;">
                    <li>Garder WordPress, thèmes et plugins à jour</li>
                    <li>Utiliser des mots de passe forts</li>
                    <li>Installer un plugin de sécurité (Wordfence)</li>
                    <li>Activer l'authentification à deux facteurs</li>
                    <li>Faire des sauvegardes régulières</li>
                    <li>Utiliser un certificat SSL (HTTPS)</li>
                </ul>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous savez maintenant utiliser WordPress.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Installation de WordPress</li>
                    <li>Navigation dans le tableau de bord</li>
                    <li>Création de pages et articles</li>
                    <li>Gestion des médias</li>
                    <li>Installation de thèmes</li>
                    <li>Utilisation des plugins</li>
                    <li>Configuration des menus</li>
                    <li>Ajout de widgets</li>
                    <li>Gestion des utilisateurs</li>
                    <li>Optimisation SEO</li>
                    <li>Sécurisation du site</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.git') }}" class="nav-btn">❮ Précédent: Git</a>
                <a href="{{ route('formations.ia') }}" class="nav-btn">Suivant: IA ❯</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #21759B, #1A5F7A) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(33, 117, 155, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #21759B, #1A5F7A) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(33, 117, 155, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
@endsection
