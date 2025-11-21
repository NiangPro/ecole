@extends('layouts.app')

@section('title', 'Formation PHP | DevFormation')

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
        background-color: #777BB3;
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
        border: 1px solid rgba(119, 123, 179, 0.2);
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
        background: linear-gradient(180deg, #777BB3 0%, #5A5E8F 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #5A5E8F 0%, #484B72 100%);
    }
    .sidebar h3 {
        color: #777BB3;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(119, 123, 179, 0.2);
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
        background: #777BB3;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(119, 123, 179, 0.1) 0%, rgba(119, 123, 179, 0.05) 100%);
        color: #777BB3;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(119, 123, 179, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #777BB3 0%, #5A5E8F 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(119, 123, 179, 0.3);
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
        background: rgba(119, 123, 179, 0.1) !important;
        border: 2px solid rgba(119, 123, 179, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(119, 123, 179, 0.2) !important;
        border-color: rgba(119, 123, 179, 0.5) !important;
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
        background: linear-gradient(135deg, #777BB3, #5A5E8F);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(119, 123, 179, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(119, 123, 179, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #5A5E8F, #484B72);
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
        border-left: 4px solid #777BB3;
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
        border: 2px solid #777BB3;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(119, 123, 179, 0.1);
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
        content: 'PHP';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #777BB3;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
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
        background-color: #777BB3;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #5A5E8F;
        box-shadow: 0 4px 12px rgba(119, 123, 179, 0.3);
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
            background: linear-gradient(180deg, #777BB3 0%, #5A5E8F 100%);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #5A5E8F 0%, #484B72 100%);
        }
        .sidebar h3 {
            color: #777BB3;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(119, 123, 179, 0.2);
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
            background: #777BB3;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(119, 123, 179, 0.1) 0%, rgba(119, 123, 179, 0.05) 100%);
            color: #777BB3;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(119, 123, 179, 0.15);
        }
        .sidebar a:hover::before {
            transform: scaleY(1);
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #777BB3 0%, #5A5E8F 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(119, 123, 179, 0.3);
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
            border-left: 4px solid #777BB3;
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
            border: 2px solid #777BB3;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            word-wrap: break-word;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(119, 123, 179, 0.1);
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
            content: 'PHP';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #777BB3;
            color: white;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
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
            background-color: #777BB3;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .nav-btn:hover {
            background-color: #5A5E8F;
            box-shadow: 0 4px 12px rgba(119, 123, 179, 0.3);
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
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px;">Tutoriel PHP</h1>
    <p style="font-size: 20px;">Développez des applications web dynamiques côté serveur</p>
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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(119, 123, 179, 0.2);">
                <h3 style="margin: 0;">PHP Tutorial</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #777BB3; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="Fermer le menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">Introduction PHP</a>
            <a href="#syntax">Syntaxe</a>
            <a href="#variables">Variables</a>
            <a href="#datatypes">Types de données</a>
            <a href="#operators">Opérateurs</a>
            <a href="#conditions">Conditions</a>
            <a href="#loops">Boucles</a>
            <a href="#functions">Fonctions</a>
            <a href="#arrays">Tableaux</a>
            <a href="#forms">Formulaires</a>
            <a href="#sessions">Sessions</a>
            <a href="#mysql">MySQL</a>
            <a href="#pdo">PDO</a>
            <a href="#oop">POO</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à PHP</h1>
            <p>PHP (Hypertext Preprocessor) est un langage de script serveur open-source conçu spécifiquement pour le développement web. Créé en 1994 par Rasmus Lerdorf, PHP est aujourd'hui l'un des langages les plus utilisés pour créer des sites web dynamiques et interactifs.</p>

            <h3>🌐 Qu'est-ce que PHP ?</h3>
            <p>PHP est un langage de programmation qui s'exécute <strong>côté serveur</strong>, contrairement à JavaScript qui s'exécute dans le navigateur. Cela signifie que le code PHP est traité sur le serveur web avant que la page ne soit envoyée au navigateur de l'utilisateur.</p>

            <div class="example-box">
                <h3 style="color: #000;">💡 Comment fonctionne PHP ?</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li>L'utilisateur demande une page web (ex: <code>mon-site.com/page.php</code>)</li>
                    <li>Le serveur web (Apache/Nginx) reçoit la requête</li>
                    <li>Le serveur exécute le code PHP contenu dans le fichier</li>
                    <li>PHP génère du HTML dynamique</li>
                    <li>Le serveur envoie le HTML au navigateur de l'utilisateur</li>
                    <li>Le navigateur affiche la page web</li>
                </ol>
            </div>

            <h3>🚀 Pourquoi apprendre PHP ?</h3>
            <p>PHP est un choix excellent pour débuter en développement web pour plusieurs raisons :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Populaire</strong> - Utilisé par 77% des sites web dans le monde, y compris des géants comme WordPress, Facebook, Wikipedia, et Yahoo</li>
                <li>✅ <strong>Facile à apprendre</strong> - Syntaxe simple et intuitive, similaire à C et Java</li>
                <li>✅ <strong>Puissant</strong> - Permet de créer des applications web complètes : sites e-commerce, réseaux sociaux, systèmes de gestion de contenu</li>
                <li>✅ <strong>Gratuit et Open-Source</strong> - Aucun coût de licence, multiplateforme (Windows, Linux, macOS)</li>
                <li>✅ <strong>Vaste communauté</strong> - Des millions de développeurs, documentation complète, nombreuses ressources d'apprentissage</li>
                <li>✅ <strong>Intégration facile</strong> - Fonctionne parfaitement avec MySQL, HTML, CSS, JavaScript</li>
                <li>✅ <strong>Frameworks modernes</strong> - Laravel, Symfony, CodeIgniter pour développer rapidement</li>
            </ul>

            <h3>📋 Prérequis pour apprendre PHP</h3>
            <p>Avant de commencer avec PHP, il est recommandé d'avoir des connaissances de base en :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>HTML</strong> - Pour structurer le contenu des pages web</li>
                <li>✅ <strong>CSS</strong> - Pour styliser les pages web</li>
                <li>⚠️ <strong>JavaScript</strong> - Utile mais pas obligatoire au début</li>
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note importante :</strong> PHP nécessite un serveur web pour fonctionner. Vous pouvez installer un environnement de développement local comme <strong>XAMPP</strong>, <strong>WAMP</strong> (Windows), <strong>MAMP</strong> (Mac), ou <strong>LAMP</strong> (Linux) qui incluent Apache, MySQL et PHP.</p>
            </div>

            <h3>🎯 Cas d'usage de PHP</h3>
            <p>PHP est utilisé pour créer de nombreux types d'applications web :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>🌐 <strong>Sites web dynamiques</strong> - Pages qui changent selon les données</li>
                <li>🛒 <strong>E-commerce</strong> - Boutiques en ligne, paniers d'achat</li>
                <li>📝 <strong>Systèmes de gestion de contenu</strong> - WordPress, Drupal, Joomla</li>
                <li>👥 <strong>Réseaux sociaux</strong> - Forums, blogs, plateformes communautaires</li>
                <li>📊 <strong>Applications web</strong> - CRM, ERP, systèmes de gestion</li>
                <li>🔐 <strong>Authentification</strong> - Systèmes de connexion, gestion d'utilisateurs</li>
                <li>📧 <strong>Envoi d'emails</strong> - Formulaires de contact, newsletters</li>
            </ul>

            <h2 id="syntax">📝 Syntaxe de base</h2>
            <p>La syntaxe PHP est simple et intuitive. Tout code PHP doit être placé entre les balises d'ouverture <code>&lt;?php</code> et de fermeture <code>?&gt;</code>. Le code est ensuite exécuté sur le serveur et le résultat est envoyé au navigateur sous forme de HTML.</p>

            <h3>🔤 Balises PHP</h3>
            <p>PHP utilise des balises spéciales pour délimiter le code :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>&lt;?php ?&gt;</code> - Balises standard (recommandées)</li>
                <li><code>&lt;? ?&gt;</code> - Balises courtes (nécessitent une configuration)</li>
                <li><code>&lt;?= ?&gt;</code> - Équivalent à <code>echo</code> (depuis PHP 5.4)</li>
            </ul>

            <div class="example-box">
                <h3 style="color: #000;">Exemple basique :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        &nbsp;&nbsp;<span class="code-comment">// Ceci est un commentaire sur une ligne</span><br>
                        &nbsp;&nbsp;<span class="code-comment">/* Ceci est un commentaire<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;sur plusieurs lignes */</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Hello World!"</span>; <span class="code-comment">// Affiche "Hello World!"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📤 Affichage de contenu</h3>
            <p>PHP offre plusieurs façons d'afficher du contenu :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>echo</code> - Affiche une ou plusieurs chaînes (le plus utilisé)</li>
                <li><code>print</code> - Affiche une seule chaîne (retourne 1 en cas de succès)</li>
                <li><code>var_dump()</code> - Affiche des informations détaillées sur une variable (débogage)</li>
                <li><code>print_r()</code> - Affiche une variable de manière lisible (débogage)</li>
            </ul>

            <div class="example-box">
                <h3 style="color: #000;">Exemples d'affichage :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// echo - le plus utilisé</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour"</span>; <span class="code-comment">// Affiche: Bonjour</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour "</span>, <span class="code-string">"Monde"</span>; <span class="code-comment">// Affiche: Bonjour Monde</span><br><br>
                        <span class="code-comment">// print - similaire à echo</span><br>
                        <span class="code-keyword">print</span> <span class="code-string">"Hello"</span>; <span class="code-comment">// Affiche: Hello</span><br><br>
                        <span class="code-comment">// var_dump - pour déboguer</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>;<br>
                        <span class="code-function">var_dump</span>(<span class="code-variable">$age</span>); <span class="code-comment">// Affiche: int(25)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📝 Règles de syntaxe importantes</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ Les instructions se terminent par un point-virgule (<code>;</code>)</li>
                <li>✅ PHP est sensible à la casse pour les noms de variables (<code>$Nom</code> ≠ <code>$nom</code>)</li>
                <li>✅ Les noms de fonctions ne sont pas sensibles à la casse (<code>ECHO</code> = <code>echo</code>)</li>
                <li>✅ Les espaces et retours à la ligne sont généralement ignorés</li>
                <li>✅ Les commentaires ne sont pas exécutés</li>
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note importante :</strong> Les fichiers PHP doivent avoir l'extension <code>.php</code> et être exécutés sur un serveur web (Apache, Nginx). Vous ne pouvez pas simplement ouvrir un fichier PHP dans votre navigateur comme un fichier HTML. Il faut passer par un serveur web local ou distant.</p>
            </div>

            <h2 id="variables">📦 Variables</h2>
            <p>Les variables en PHP sont des conteneurs qui stockent des données. Elles sont essentielles pour créer des applications dynamiques. Contrairement à d'autres langages, PHP détermine automatiquement le type de variable selon la valeur assignée.</p>

            <h3>🔤 Règles de nommage des variables</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ Doivent commencer par le symbole <code>$</code> suivi d'une lettre ou d'un underscore</li>
                <li>✅ Peuvent contenir des lettres, chiffres et underscores</li>
                <li>✅ Sont sensibles à la casse (<code>$nom</code> ≠ <code>$Nom</code> ≠ <code>$NOM</code>)</li>
                <li>❌ Ne peuvent pas commencer par un chiffre</li>
                <li>❌ Ne peuvent pas contenir d'espaces ou de caractères spéciaux (sauf underscore)</li>
            </ul>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de variables valides et invalides :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// ✅ Variables valides</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean"</span>;<br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>;<br>
                        <span class="code-variable">$_prix</span> = <span class="code-string">19.99</span>;<br>
                        <span class="code-variable">$nomUtilisateur</span> = <span class="code-string">"admin"</span>; <span class="code-comment">// camelCase recommandé</span><br>
                        <span class="code-variable">$est_actif</span> = <span class="code-keyword">true</span>; <span class="code-comment">// snake_case aussi valide</span><br><br>
                        <span class="code-comment">// ❌ Variables invalides (généreraient des erreurs)</span><br>
                        <span class="code-comment">// $2nom = "Jean"; // Erreur: ne peut pas commencer par un chiffre</span><br>
                        <span class="code-comment">// $nom utilisateur = "admin"; // Erreur: espace interdit</span><br>
                        <span class="code-comment">// $nom-utilisateur = "admin"; // Erreur: tiret interdit</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>💾 Assignation de valeurs</h3>
            <p>L'assignation se fait avec l'opérateur <code>=</code>. PHP détermine automatiquement le type de la variable.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples pratiques :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Variables de différents types</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean"</span>; <span class="code-comment">// String (chaîne de caractères)</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>; <span class="code-comment">// Integer (entier)</span><br>
                        <span class="code-variable">$prix</span> = <span class="code-string">19.99</span>; <span class="code-comment">// Float (décimal)</span><br>
                        <span class="code-variable">$estActif</span> = <span class="code-keyword">true</span>; <span class="code-comment">// Boolean (booléen)</span><br>
                        <span class="code-variable">$ville</span> = <span class="code-keyword">NULL</span>; <span class="code-comment">// NULL (vide)</span><br><br>
                        <span class="code-comment">// Utilisation des variables</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$nom</span>; <span class="code-comment">// Affiche: Bonjour Jean</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Vous avez "</span> . <span class="code-variable">$age</span> . <span class="code-string">" ans"</span>; <span class="code-comment">// Affiche: Vous avez 25 ans</span><br><br>
                        <span class="code-comment">// Réassignation (changement de valeur)</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">26</span>; <span class="code-comment">// La variable $age vaut maintenant 26</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Variables de variables</h3>
            <p>PHP permet d'utiliser le contenu d'une variable comme nom d'une autre variable. C'est ce qu'on appelle des "variables de variables".</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de variable de variable :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"age"</span>; <span class="code-comment">// $nom contient la chaîne "age"</span><br>
                        <span class="code-variable">$$nom</span> = <span class="code-string">25</span>; <span class="code-comment">// Crée $age = 25</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$age</span>; <span class="code-comment">// Affiche: 25</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez des noms de variables descriptifs et cohérents. Préférez <code>$nomUtilisateur</code> plutôt que <code>$n</code> ou <code>$x</code>. Cela rend votre code plus lisible et maintenable.</p>
            </div>

            <h2 id="datatypes">🔢 Types de données</h2>
            <p>PHP est un langage à typage dynamique et faible. Cela signifie que vous n'avez pas besoin de déclarer le type d'une variable avant de l'utiliser, et PHP peut changer automatiquement le type d'une variable selon le contexte. PHP supporte 8 types de données primitifs.</p>

            <h3>📝 String (Chaîne de caractères)</h3>
            <p>Une chaîne est une séquence de caractères, comme du texte. Les chaînes peuvent être délimitées par des guillemets simples (<code>'</code>) ou doubles (<code>"</code>).</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de chaînes :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Guillemets simples (littéral)</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">'Jean'</span>; <span class="code-comment">// String</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">'Bonjour $nom'</span>; <span class="code-comment">// Affiche: Bonjour $nom (pas d'interpolation)</span><br><br>
                        <span class="code-comment">// Guillemets doubles (avec interpolation)</span><br>
                        <span class="code-variable">$prenom</span> = <span class="code-string">"Marie"</span>;<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour $prenom"</span>; <span class="code-comment">// Affiche: Bonjour Marie</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour {$prenom}"</span>; <span class="code-comment">// Même résultat (syntaxe recommandée)</span><br><br>
                        <span class="code-comment">// Concaténation</span><br>
                        <span class="code-variable">$message</span> = <span class="code-string">"Bonjour "</span> . <span class="code-variable">$prenom</span> . <span class="code-string">" !"</span>; <span class="code-comment">// Utilise le point (.)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔢 Integer (Entier)</h3>
            <p>Un entier est un nombre sans partie décimale. Il peut être positif, négatif ou zéro.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples d'entiers :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>; <span class="code-comment">// Integer positif</span><br>
                        <span class="code-variable">$temperature</span> = <span class="code-string">-10</span>; <span class="code-comment">// Integer négatif</span><br>
                        <span class="code-variable">$zero</span> = <span class="code-string">0</span>; <span class="code-comment">// Zéro</span><br>
                        <span class="code-variable">$grand</span> = <span class="code-string">2147483647</span>; <span class="code-comment">// Maximum sur 32 bits</span><br><br>
                        <span class="code-comment">// Opérations arithmétiques</span><br>
                        <span class="code-variable">$somme</span> = <span class="code-string">10</span> + <span class="code-string">5</span>; <span class="code-comment">// 15</span><br>
                        <span class="code-variable">$produit</span> = <span class="code-string">4</span> * <span class="code-string">7</span>; <span class="code-comment">// 28</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔢 Float (Nombre décimal)</h3>
            <p>Un float (ou double) est un nombre avec une partie décimale. Utilisé pour les calculs nécessitant une précision décimale.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de décimaux :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$prix</span> = <span class="code-string">19.99</span>; <span class="code-comment">// Float</span><br>
                        <span class="code-variable">$pi</span> = <span class="code-string">3.14159</span>; <span class="code-comment">// Float</span><br>
                        <span class="code-variable">$pourcentage</span> = <span class="code-string">0.15</span>; <span class="code-comment">// 15%</span><br>
                        <span class="code-variable">$scientifique</span> = <span class="code-string">1.5e3</span>; <span class="code-comment">// 1500 (notation scientifique)</span><br><br>
                        <span class="code-comment">// Calcul avec décimaux</span><br>
                        <span class="code-variable">$total</span> = <span class="code-string">19.99</span> + <span class="code-string">5.50</span>; <span class="code-comment">// 25.49</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>✅ Boolean (Booléen)</h3>
            <p>Un booléen représente une valeur de vérité. Il ne peut avoir que deux valeurs : <code>true</code> (vrai) ou <code>false</code> (faux).</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de booléens :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$estActif</span> = <span class="code-keyword">true</span>; <span class="code-comment">// Boolean true</span><br>
                        <span class="code-variable">$estConnecte</span> = <span class="code-keyword">false</span>; <span class="code-comment">// Boolean false</span><br><br>
                        <span class="code-comment">// Utilisation dans les conditions</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$estActif</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Le compte est actif"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Conversion en booléen</span><br>
                        <span class="code-variable">$valeur</span> = <span class="code-string">1</span>; <span class="code-comment">// true en contexte booléen</span><br>
                        <span class="code-variable">$valeur</span> = <span class="code-string">0</span>; <span class="code-comment">// false en contexte booléen</span><br>
                        <span class="code-variable">$valeur</span> = <span class="code-string">""</span>; <span class="code-comment">// false (chaîne vide)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📚 Array (Tableau)</h3>
            <p>Un tableau stocke plusieurs valeurs dans une seule variable. PHP supporte les tableaux indexés et associatifs.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de tableaux :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Tableau indexé (ancienne syntaxe)</span><br>
                        <span class="code-variable">$fruits</span> = <span class="code-function">array</span>(<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>);<br><br>
                        <span class="code-comment">// Tableau indexé (syntaxe courte PHP 5.4+)</span><br>
                        <span class="code-variable">$legumes</span> = [<span class="code-string">"Carotte"</span>, <span class="code-string">"Tomate"</span>, <span class="code-string">"Salade"</span>];<br><br>
                        <span class="code-comment">// Tableau associatif</span><br>
                        <span class="code-variable">$personne</span> = [<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Paris"</span><br>
                        ];<br><br>
                        <span class="code-comment">// Accès aux éléments</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-string">0</span>]; <span class="code-comment">// "Pomme"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne</span>[<span class="code-string">"nom"</span>]; <span class="code-comment">// "Jean"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Object (Objet)</h3>
            <p>Un objet est une instance d'une classe. Nous verrons les objets en détail dans la section POO.</p>

            <h3>❌ NULL</h3>
            <p><code>NULL</code> représente une variable sans valeur. Une variable est NULL si elle a été assignée à <code>NULL</code> ou n'a jamais été assignée.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples avec NULL :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$ville</span> = <span class="code-keyword">NULL</span>; <span class="code-comment">// NULL explicite</span><br>
                        <span class="code-variable">$nom</span>; <span class="code-comment">// NULL (non initialisée)</span><br><br>
                        <span class="code-comment">// Vérifier si une variable est NULL</span><br>
                        <span class="code-keyword">if</span> (<span class="code-function">is_null</span>(<span class="code-variable">$ville</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"La variable est NULL"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔍 Vérifier le type d'une variable</h3>
            <p>PHP fournit plusieurs fonctions pour vérifier le type d'une variable :</p>

            <div class="example-box">
                <h3 style="color: #000;">Fonctions de vérification de type :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$texte</span> = <span class="code-string">"Hello"</span>;<br>
                        <span class="code-variable">$nombre</span> = <span class="code-string">42</span>;<br>
                        <span class="code-variable">$decimal</span> = <span class="code-string">3.14</span>;<br>
                        <span class="code-variable">$tableau</span> = [<span class="code-string">1</span>, <span class="code-string">2</span>, <span class="code-string">3</span>];<br><br>
                        <span class="code-comment">// Vérifications</span><br>
                        <span class="code-function">is_string</span>(<span class="code-variable">$texte</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">is_int</span>(<span class="code-variable">$nombre</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">is_float</span>(<span class="code-variable">$decimal</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">is_array</span>(<span class="code-variable">$tableau</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">is_bool</span>(<span class="code-keyword">true</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">is_null</span>(<span class="code-keyword">NULL</span>); <span class="code-comment">// true</span><br><br>
                        <span class="code-comment">// Obtenir le type</span><br>
                        <span class="code-function">gettype</span>(<span class="code-variable">$texte</span>); <span class="code-comment">// "string"</span><br>
                        <span class="code-function">var_dump</span>(<span class="code-variable">$nombre</span>); <span class="code-comment">// int(42)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Conversion de types :</strong> PHP peut convertir automatiquement les types selon le contexte. Par exemple, <code>"5" + 3</code> donnera <code>8</code> (entier) car PHP convertit la chaîne en nombre. Vous pouvez aussi forcer une conversion avec <code>(int)</code>, <code>(string)</code>, <code>(float)</code>, <code>(bool)</code>, <code>(array)</code>.</p>
            </div>

            <h2 id="operators">➕ Opérateurs</h2>
            <p>Les opérateurs sont des symboles qui permettent d'effectuer des opérations sur des valeurs. PHP dispose de plusieurs catégories d'opérateurs : arithmétiques, de comparaison, logiques, d'affectation, et plus encore.</p>

            <h3>🔢 Opérateurs arithmétiques</h3>
            <p>Les opérateurs arithmétiques effectuent des opérations mathématiques de base.</p>

            <div class="example-box">
                <h3 style="color: #000;">Opérateurs arithmétiques :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$a</span> = <span class="code-string">10</span>;<br>
                        <span class="code-variable">$b</span> = <span class="code-string">3</span>;<br><br>
                        <span class="code-comment">// Addition</span><br>
                        <span class="code-variable">$somme</span> = <span class="code-variable">$a</span> + <span class="code-variable">$b</span>; <span class="code-comment">// 13</span><br><br>
                        <span class="code-comment">// Soustraction</span><br>
                        <span class="code-variable">$difference</span> = <span class="code-variable">$a</span> - <span class="code-variable">$b</span>; <span class="code-comment">// 7</span><br><br>
                        <span class="code-comment">// Multiplication</span><br>
                        <span class="code-variable">$produit</span> = <span class="code-variable">$a</span> * <span class="code-variable">$b</span>; <span class="code-comment">// 30</span><br><br>
                        <span class="code-comment">// Division</span><br>
                        <span class="code-variable">$quotient</span> = <span class="code-variable">$a</span> / <span class="code-variable">$b</span>; <span class="code-comment">// 3.333...</span><br><br>
                        <span class="code-comment">// Modulo (reste de la division)</span><br>
                        <span class="code-variable">$reste</span> = <span class="code-variable">$a</span> % <span class="code-variable">$b</span>; <span class="code-comment">// 1 (10 ÷ 3 = 3 reste 1)</span><br><br>
                        <span class="code-comment">// Exponentiation (PHP 5.6+)</span><br>
                        <span class="code-variable">$puissance</span> = <span class="code-variable">$a</span> ** <span class="code-variable">$b</span>; <span class="code-comment">// 1000 (10³)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔀 Opérateurs de comparaison</h3>
            <p>Les opérateurs de comparaison comparent deux valeurs et retournent <code>true</code> ou <code>false</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Opérateurs de comparaison :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$a</span> = <span class="code-string">5</span>;<br>
                        <span class="code-variable">$b</span> = <span class="code-string">"5"</span>; <span class="code-comment">// Chaîne "5"</span><br>
                        <span class="code-variable">$c</span> = <span class="code-string">10</span>;<br><br>
                        <span class="code-comment">// Égalité (valeur seulement)</span><br>
                        <span class="code-variable">$egal</span> = (<span class="code-variable">$a</span> == <span class="code-variable">$b</span>); <span class="code-comment">// true (5 == "5")</span><br><br>
                        <span class="code-comment">// Identité (valeur ET type)</span><br>
                        <span class="code-variable">$identique</span> = (<span class="code-variable">$a</span> === <span class="code-variable">$b</span>); <span class="code-comment">// false (int !== string)</span><br><br>
                        <span class="code-comment">// Différent</span><br>
                        <span class="code-variable">$different</span> = (<span class="code-variable">$a</span> != <span class="code-variable">$c</span>); <span class="code-comment">// true</span><br>
                        <span class="code-variable">$different</span> = (<span class="code-variable">$a</span> <> <span class="code-variable">$c</span>); <span class="code-comment">// true (même chose)</span><br><br>
                        <span class="code-comment">// Non identique</span><br>
                        <span class="code-variable">$nonIdentique</span> = (<span class="code-variable">$a</span> !== <span class="code-variable">$b</span>); <span class="code-comment">// true</span><br><br>
                        <span class="code-comment">// Supérieur / Inférieur</span><br>
                        <span class="code-variable">$superieur</span> = (<span class="code-variable">$c</span> > <span class="code-variable">$a</span>); <span class="code-comment">// true (10 > 5)</span><br>
                        <span class="code-variable">$inferieur</span> = (<span class="code-variable">$a</span> < <span class="code-variable">$c</span>); <span class="code-comment">// true (5 < 10)</span><br>
                        <span class="code-variable">$superieurEgal</span> = (<span class="code-variable">$a</span> >= <span class="code-string">5</span>); <span class="code-comment">// true</span><br>
                        <span class="code-variable">$inferieurEgal</span> = (<span class="code-variable">$a</span> <= <span class="code-string">5</span>); <span class="code-comment">// true</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Important :</strong> La différence entre <code>==</code> et <code>===</code> est cruciale. <code>==</code> compare seulement les valeurs (avec conversion de type), tandis que <code>===</code> compare les valeurs ET les types. Utilisez <code>===</code> pour éviter les bugs subtils !</p>
            </div>

            <h3>🔗 Opérateurs logiques</h3>
            <p>Les opérateurs logiques combinent des conditions booléennes.</p>

            <div class="example-box">
                <h3 style="color: #000;">Opérateurs logiques :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>;<br>
                        <span class="code-variable">$estActif</span> = <span class="code-keyword">true</span>;<br>
                        <span class="code-variable">$aPermis</span> = <span class="code-keyword">false</span>;<br><br>
                        <span class="code-comment">// ET logique (&& ou and)</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> >= <span class="code-string">18</span> && <span class="code-variable">$estActif</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Majeur et actif"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// OU logique (|| ou or)</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$estActif</span> || <span class="code-variable">$aPermis</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Actif ou a permis"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// NON logique (!)</span><br>
                        <span class="code-keyword">if</span> (!<span class="code-variable">$aPermis</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Pas de permis"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📝 Opérateurs d'affectation</h3>
            <p>Les opérateurs d'affectation assignent des valeurs aux variables. PHP offre des opérateurs d'affectation combinés pour simplifier le code.</p>

            <div class="example-box">
                <h3 style="color: #000;">Opérateurs d'affectation :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$x</span> = <span class="code-string">10</span>; <span class="code-comment">// Affectation simple</span><br><br>
                        <span class="code-comment">// Affectation combinée</span><br>
                        <span class="code-variable">$x</span> += <span class="code-string">5</span>; <span class="code-comment">// Équivaut à: $x = $x + 5 (résultat: 15)</span><br>
                        <span class="code-variable">$x</span> -= <span class="code-string">3</span>; <span class="code-comment">// Équivaut à: $x = $x - 3 (résultat: 12)</span><br>
                        <span class="code-variable">$x</span> *= <span class="code-string">2</span>; <span class="code-comment">// Équivaut à: $x = $x * 2 (résultat: 24)</span><br>
                        <span class="code-variable">$x</span> /= <span class="code-string">4</span>; <span class="code-comment">// Équivaut à: $x = $x / 4 (résultat: 6)</span><br>
                        <span class="code-variable">$x</span> %= <span class="code-string">5</span>; <span class="code-comment">// Équivaut à: $x = $x % 5 (résultat: 1)</span><br><br>
                        <span class="code-comment">// Concaténation avec affectation</span><br>
                        <span class="code-variable">$texte</span> = <span class="code-string">"Hello"</span>;<br>
                        <span class="code-variable">$texte</span> .= <span class="code-string">" World"</span>; <span class="code-comment">// Équivaut à: $texte = $texte . " World"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$texte</span>; <span class="code-comment">// "Hello World"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔗 Opérateur de concaténation</h3>
            <p>L'opérateur <code>.</code> (point) permet de concaténer (joindre) des chaînes de caractères.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de concaténation :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$prenom</span> = <span class="code-string">"Jean"</span>;<br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Dupont"</span>;<br>
                        <span class="code-variable">$age</span> = <span class="code-string">30</span>;<br><br>
                        <span class="code-comment">// Concaténation simple</span><br>
                        <span class="code-variable">$nomComplet</span> = <span class="code-variable">$prenom</span> . <span class="code-string">" "</span> . <span class="code-variable">$nom</span>; <span class="code-comment">// "Jean Dupont"</span><br><br>
                        <span class="code-comment">// Concaténation avec variables</span><br>
                        <span class="code-variable">$message</span> = <span class="code-string">"Bonjour "</span> . <span class="code-variable">$prenom</span> . <span class="code-string">", vous avez "</span> . <span class="code-variable">$age</span> . <span class="code-string">" ans"</span>;<br>
                        <span class="code-comment">// "Bonjour Jean, vous avez 30 ans"</span><br><br>
                        <span class="code-comment">// Avec guillemets doubles (interpolation)</span><br>
                        <span class="code-variable">$message2</span> = <span class="code-string">"Bonjour $prenom, vous avez $age ans"</span>; <span class="code-comment">// Plus simple !</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>➕ Opérateurs d'incrémentation et décrémentation</h3>
            <p>Ces opérateurs augmentent ou diminuent une variable de 1.</p>

            <div class="example-box">
                <h3 style="color: #000;">Incrémentation et décrémentation :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$x</span> = <span class="code-string">5</span>;<br><br>
                        <span class="code-comment">// Pré-incrémentation (incrémente puis retourne)</span><br>
                        <span class="code-keyword">echo</span> ++<span class="code-variable">$x</span>; <span class="code-comment">// Affiche: 6 (x vaut maintenant 6)</span><br><br>
                        <span class="code-comment">// Post-incrémentation (retourne puis incrémente)</span><br>
                        <span class="code-variable">$x</span> = <span class="code-string">5</span>;<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$x</span>++; <span class="code-comment">// Affiche: 5 (mais x vaut maintenant 6)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$x</span>; <span class="code-comment">// Affiche: 6</span><br><br>
                        <span class="code-comment">// Décrémentation</span><br>
                        <span class="code-variable">$x</span> = <span class="code-string">5</span>;<br>
                        <span class="code-keyword">echo</span> --<span class="code-variable">$x</span>; <span class="code-comment">// Affiche: 4</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$x</span>--; <span class="code-comment">// Affiche: 4 (mais x vaut maintenant 3)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="conditions">🔀 Conditions</h2>
            <p>Les structures conditionnelles permettent d'exécuter du code uniquement si certaines conditions sont remplies. C'est l'un des concepts fondamentaux de la programmation, permettant de créer des applications qui réagissent différemment selon les situations.</p>

            <h3>🔍 Structure if / elseif / else</h3>
            <p>La structure <code>if</code> est la plus courante. Elle permet d'exécuter du code si une condition est vraie.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple basique avec if :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">18</span>;<br><br>
                        <span class="code-comment">// Structure if simple</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> >= <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Vous êtes majeur"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Structure if / else</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> < <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Vous êtes mineur"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Vous êtes majeur"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Structure if / elseif / else (conditions multiples)</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> < <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Mineur"</span>;<br>
                        } <span class="code-keyword">elseif</span> (<span class="code-variable">$age</span> == <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Tout juste majeur"</span>;<br>
                        } <span class="code-keyword">elseif</span> (<span class="code-variable">$age</span> < <span class="code-string">65</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Majeur"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Senior"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Opérateur ternaire</h3>
            <p>L'opérateur ternaire est une façon concise d'écrire une condition if/else simple sur une seule ligne.</p>

            <div class="example-box">
                <h3 style="color: #000;">Syntaxe de l'opérateur ternaire :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">20</span>;<br><br>
                        <span class="code-comment">// Syntaxe: (condition) ? valeur_si_vrai : valeur_si_faux</span><br>
                        <span class="code-variable">$statut</span> = (<span class="code-variable">$age</span> >= <span class="code-string">18</span>) ? <span class="code-string">"Majeur"</span> : <span class="code-string">"Mineur"</span>;<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$statut</span>; <span class="code-comment">// "Majeur"</span><br><br>
                        <span class="code-comment">// Équivalent à:</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> >= <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$statut</span> = <span class="code-string">"Majeur"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-variable">$statut</span> = <span class="code-string">"Mineur"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Ternaire imbriqué (à utiliser avec modération)</span><br>
                        <span class="code-variable">$note</span> = <span class="code-string">85</span>;<br>
                        <span class="code-variable">$resultat</span> = (<span class="code-variable">$note</span> >= <span class="code-string">90</span>) ? <span class="code-string">"Excellent"</span> : (<span class="code-variable">$note</span> >= <span class="code-string">70</span>) ? <span class="code-string">"Bien"</span> : <span class="code-string">"À améliorer"</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Structure switch</h3>
            <p>La structure <code>switch</code> est utile quand vous avez plusieurs conditions à vérifier sur la même variable. Elle est souvent plus lisible qu'une série de <code>if/elseif</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple avec switch :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$jour</span> = <span class="code-string">"lundi"</span>;<br><br>
                        <span class="code-keyword">switch</span> (<span class="code-variable">$jour</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> <span class="code-string">"lundi"</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Premier jour de la semaine"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>; <span class="code-comment">// Important: arrête l'exécution</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> <span class="code-string">"vendredi"</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Dernier jour de travail"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> <span class="code-string">"samedi"</span>:<br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> <span class="code-string">"dimanche"</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Week-end !"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">default</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Jour de semaine"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Exemple avec nombres</span><br>
                        <span class="code-variable">$note</span> = <span class="code-string">85</span>;<br>
                        <span class="code-keyword">switch</span> (<span class="code-keyword">true</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> (<span class="code-variable">$note</span> >= <span class="code-string">90</span>):<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Excellent"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">case</span> (<span class="code-variable">$note</span> >= <span class="code-string">70</span>):<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bien"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">default</span>:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"À améliorer"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Important :</strong> N'oubliez jamais le <code>break</code> dans un <code>switch</code> ! Sans <code>break</code>, PHP continuera à exécuter les cases suivantes (c'est ce qu'on appelle "fall-through"). C'est parfois voulu (comme dans l'exemple samedi/dimanche), mais généralement c'est une erreur.</p>
            </div>

            <h3>✅ Conditions avec opérateurs logiques</h3>
            <p>Vous pouvez combiner plusieurs conditions avec les opérateurs logiques <code>&&</code> (ET), <code>||</code> (OU), et <code>!</code> (NON).</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples avec opérateurs logiques :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>;<br>
                        <span class="code-variable">$estActif</span> = <span class="code-keyword">true</span>;<br>
                        <span class="code-variable">$aPermis</span> = <span class="code-keyword">true</span>;<br><br>
                        <span class="code-comment">// ET logique (les deux conditions doivent être vraies)</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> >= <span class="code-string">18</span> && <span class="code-variable">$estActif</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Majeur et actif"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// OU logique (au moins une condition doit être vraie)</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> >= <span class="code-string">18</span> || <span class="code-variable">$aPermis</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Majeur ou a permis"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// NON logique (inverse la condition)</span><br>
                        <span class="code-keyword">if</span> (!<span class="code-variable">$estActif</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Compte inactif"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Conditions complexes</span><br>
                        <span class="code-keyword">if</span> ((<span class="code-variable">$age</span> >= <span class="code-string">18</span> && <span class="code-variable">$estActif</span>) || <span class="code-variable">$aPermis</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Accès autorisé"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Cas d'usage pratiques</h3>
            <p>Voici quelques exemples concrets d'utilisation des conditions dans des situations réelles :</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 1 : Vérification d'authentification</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$estConnecte</span> = <span class="code-keyword">true</span>;<br>
                        <span class="code-variable">$estAdmin</span> = <span class="code-keyword">false</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$estConnecte</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$estAdmin</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bienvenue administrateur"</span>;<br>
                        &nbsp;&nbsp;} <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bienvenue utilisateur"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Veuillez vous connecter"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 2 : Calcul de remise</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$montant</span> = <span class="code-string">150</span>; <span class="code-comment">// Montant d'achat</span><br>
                        <span class="code-variable">$remise</span> = <span class="code-string">0</span>; <span class="code-comment">// Remise en pourcentage</span><br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$montant</span> >= <span class="code-string">200</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$remise</span> = <span class="code-string">20</span>; <span class="code-comment">// 20% de remise</span><br>
                        } <span class="code-keyword">elseif</span> (<span class="code-variable">$montant</span> >= <span class="code-string">100</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$remise</span> = <span class="code-string">10</span>; <span class="code-comment">// 10% de remise</span><br>
                        } <span class="code-keyword">elseif</span> (<span class="code-variable">$montant</span> >= <span class="code-string">50</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$remise</span> = <span class="code-string">5</span>; <span class="code-comment">// 5% de remise</span><br>
                        }<br><br>
                        <span class="code-variable">$montantFinal</span> = <span class="code-variable">$montant</span> - (<span class="code-variable">$montant</span> * <span class="code-variable">$remise</span> / <span class="code-string">100</span>);<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Montant: $montant€, Remise: $remise%, Total: $montantFinal€"</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="loops">🔁 Boucles</h2>
            <p>Les boucles permettent de répéter des instructions plusieurs fois sans avoir à réécrire le même code. C'est un concept fondamental qui permet d'automatiser des tâches répétitives et de traiter des collections de données.</p>

            <h3>🔄 Boucle for</h3>
            <p>La boucle <code>for</code> est utilisée quand vous savez à l'avance combien de fois vous voulez répéter le code. Elle est composée de trois parties : initialisation, condition, et incrémentation.</p>

            <div class="example-box">
                <h3 style="color: #000;">Syntaxe de la boucle for :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Syntaxe: for (initialisation; condition; incrémentation)</span><br>
                        <span class="code-comment">// Exemple simple: compter de 0 à 4</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> < <span class="code-string">5</span>; <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>; <span class="code-comment">// Affiche: 0 1 2 3 4</span><br>
                        }<br><br>
                        <span class="code-comment">// Compter de 1 à 10</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">1</span>; <span class="code-variable">$i</span> <= <span class="code-string">10</span>; <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Compter à rebours de 10 à 1</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">10</span>; <span class="code-variable">$i</span> >= <span class="code-string">1</span>; <span class="code-variable">$i</span>--) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>; <span class="code-comment">// Affiche: 10 9 8 7 6 5 4 3 2 1</span><br>
                        }<br><br>
                        <span class="code-comment">// Compter par pas de 2</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> <= <span class="code-string">10</span>; <span class="code-variable">$i</span> += <span class="code-string">2</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>; <span class="code-comment">// Affiche: 0 2 4 6 8 10</span><br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Boucle while</h3>
            <p>La boucle <code>while</code> répète le code tant qu'une condition est vraie. Elle est utile quand vous ne savez pas à l'avance combien d'itérations seront nécessaires.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples avec while :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Exemple simple</span><br>
                        <span class="code-variable">$i</span> = <span class="code-string">0</span>;<br>
                        <span class="code-keyword">while</span> (<span class="code-variable">$i</span> < <span class="code-string">5</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>;<br>
                        &nbsp;&nbsp;<span class="code-variable">$i</span>++; <span class="code-comment">// Important: incrémenter pour éviter la boucle infinie</span><br>
                        }<br><br>
                        <span class="code-comment">// Exemple pratique: générer des nombres jusqu'à atteindre 100</span><br>
                        <span class="code-variable">$nombre</span> = <span class="code-string">1</span>;<br>
                        <span class="code-keyword">while</span> (<span class="code-variable">$nombre</span> <= <span class="code-string">100</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$nombre</span> . <span class="code-string">" "</span>;<br>
                        &nbsp;&nbsp;<span class="code-variable">$nombre</span> *= <span class="code-string">2</span>; <span class="code-comment">// Multiplier par 2 à chaque itération</span><br>
                        } <span class="code-comment">// Affiche: 1 2 4 8 16 32 64</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Boucle do...while</h3>
            <p>La boucle <code>do...while</code> est similaire à <code>while</code>, mais elle exécute le code au moins une fois avant de vérifier la condition.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple avec do...while :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$i</span> = <span class="code-string">0</span>;<br>
                        <span class="code-keyword">do</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>;<br>
                        &nbsp;&nbsp;<span class="code-variable">$i</span>++;<br>
                        } <span class="code-keyword">while</span> (<span class="code-variable">$i</span> < <span class="code-string">5</span>);<br><br>
                        <span class="code-comment">// Même si la condition est fausse au début, le code s'exécute une fois</span><br>
                        <span class="code-variable">$x</span> = <span class="code-string">10</span>;<br>
                        <span class="code-keyword">do</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Exécuté au moins une fois"</span>;<br>
                        } <span class="code-keyword">while</span> (<span class="code-variable">$x</span> < <span class="code-string">5</span>); <span class="code-comment">// Condition fausse, mais s'exécute quand même</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Boucle foreach</h3>
            <p>La boucle <code>foreach</code> est spécialement conçue pour parcourir les tableaux et les objets. C'est la boucle la plus utilisée en PHP pour traiter des collections de données.</p>

            <div class="example-box">
                <h3 style="color: #000;">foreach avec tableaux indexés :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$fruits</span> = [<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>];<br><br>
                        <span class="code-comment">// Syntaxe simple (valeur seulement)</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$fruits</span> <span class="code-keyword">as</span> <span class="code-variable">$fruit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$fruit</span> . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-comment">// Affiche: Pomme, Banane, Orange</span><br><br>
                        <span class="code-comment">// Syntaxe avec clé et valeur</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$fruits</span> <span class="code-keyword">as</span> <span class="code-variable">$index</span> => <span class="code-variable">$fruit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Index $index: $fruit&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-comment">// Affiche: Index 0: Pomme, Index 1: Banane, Index 2: Orange</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">foreach avec tableaux associatifs :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$personne</span> = [<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Paris"</span><br>
                        ];<br><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$personne</span> <span class="code-keyword">as</span> <span class="code-variable">$cle</span> => <span class="code-variable">$valeur</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"$cle: $valeur&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-comment">// Affiche: nom: Jean, age: 30, ville: Paris</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>⏹️ Contrôle des boucles : break et continue</h3>
            <p>Les mots-clés <code>break</code> et <code>continue</code> permettent de contrôler l'exécution des boucles.</p>

            <div class="example-box">
                <h3 style="color: #000;">break et continue :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// break - arrête complètement la boucle</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> < <span class="code-string">10</span>; <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$i</span> == <span class="code-string">5</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">break</span>; <span class="code-comment">// Arrête la boucle quand i = 5</span><br>
                        &nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>; <span class="code-comment">// Affiche: 0 1 2 3 4</span><br>
                        }<br><br>
                        <span class="code-comment">// continue - passe à l'itération suivante</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> < <span class="code-string">10</span>; <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$i</span> % <span class="code-string">2</span> == <span class="code-string">0</span>) { <span class="code-comment">// Si i est pair</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">continue</span>; <span class="code-comment">// Passe au suivant sans exécuter le reste</span><br>
                        &nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span> . <span class="code-string">" "</span>; <span class="code-comment">// Affiche seulement les impairs: 1 3 5 7 9</span><br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Cas d'usage pratiques</h3>
            <p>Voici des exemples concrets d'utilisation des boucles dans des situations réelles :</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 1 : Afficher une liste d'utilisateurs</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$utilisateurs</span> = [<span class="code-string">"Jean"</span>, <span class="code-string">"Marie"</span>, <span class="code-string">"Pierre"</span>, <span class="code-string">"Sophie"</span>];<br><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"&lt;ul&gt;"</span>;<br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$utilisateurs</span> <span class="code-keyword">as</span> <span class="code-variable">$utilisateur</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"&lt;li&gt;$utilisateur&lt;/li&gt;"</span>;<br>
                        }<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"&lt;/ul&gt;"</span>;<br>
                        <span class="code-comment">// Génère une liste HTML avec tous les utilisateurs</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 2 : Calculer la somme d'un tableau</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$nombres</span> = [<span class="code-string">10</span>, <span class="code-string">20</span>, <span class="code-string">30</span>, <span class="code-string">40</span>, <span class="code-string">50</span>];<br>
                        <span class="code-variable">$somme</span> = <span class="code-string">0</span>;<br><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$nombres</span> <span class="code-keyword">as</span> <span class="code-variable">$nombre</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$somme</span> += <span class="code-variable">$nombre</span>; <span class="code-comment">// Ajoute chaque nombre à la somme</span><br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"La somme est: $somme"</span>; <span class="code-comment">// Affiche: La somme est: 150</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez <code>foreach</code> pour parcourir les tableaux plutôt que <code>for</code> quand c'est possible. C'est plus lisible, plus sûr (pas de risque d'erreur d'index), et plus performant.</p>
            </div>

            <h2 id="functions">⚡ Fonctions</h2>
            <p>Les fonctions sont des blocs de code réutilisables qui effectuent une tâche spécifique. Elles permettent d'organiser votre code, d'éviter la répétition, et de faciliter la maintenance. PHP dispose de milliers de fonctions intégrées, mais vous pouvez aussi créer vos propres fonctions.</p>

            <h3>📝 Créer une fonction</h3>
            <p>Pour créer une fonction, utilisez le mot-clé <code>function</code> suivi du nom de la fonction et de parenthèses contenant les paramètres (optionnels).</p>

            <div class="example-box">
                <h3 style="color: #000;">Syntaxe de base :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Fonction simple sans paramètres</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">direBonjour</span>() {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bonjour !"</span>;<br>
                        }<br><br>
                        <span class="code-function">direBonjour</span>(); <span class="code-comment">// Appelle la fonction</span><br><br>
                        <span class="code-comment">// Fonction avec paramètres</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">saluer</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$nom</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">saluer</span>(<span class="code-string">"Marie"</span>); <span class="code-comment">// Affiche: "Bonjour Marie"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📤 return vs echo</h3>
            <p>Il y a une différence importante entre <code>return</code> et <code>echo</code> dans une fonction :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>echo</code> - Affiche directement le résultat (ne peut pas être récupéré)</li>
                <li><code>return</code> - Retourne une valeur que vous pouvez utiliser ailleurs (recommandé)</li>
            </ul>

            <div class="example-box">
                <h3 style="color: #000;">Différence entre return et echo :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Avec echo (affiche directement)</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">afficherNom</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$nom</span>; <span class="code-comment">// Affiche immédiatement</span><br>
                        }<br>
                        <span class="code-function">afficherNom</span>(<span class="code-string">"Jean"</span>); <span class="code-comment">// Affiche: Jean</span><br>
                        <span class="code-variable">$resultat</span> = <span class="code-function">afficherNom</span>(<span class="code-string">"Jean"</span>); <span class="code-comment">// $resultat vaut NULL</span><br><br>
                        <span class="code-comment">// Avec return (retourne une valeur)</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">obtenirNom</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$nom</span>; <span class="code-comment">// Retourne la valeur</span><br>
                        }<br>
                        <span class="code-variable">$resultat</span> = <span class="code-function">obtenirNom</span>(<span class="code-string">"Jean"</span>); <span class="code-comment">// $resultat vaut "Jean"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$resultat</span>; <span class="code-comment">// Affiche: Jean</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📥 Paramètres de fonction</h3>
            <p>Les fonctions peuvent accepter plusieurs paramètres, avec ou sans valeurs par défaut.</p>

            <div class="example-box">
                <h3 style="color: #000;">Fonctions avec plusieurs paramètres :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Fonction avec plusieurs paramètres</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">presenter</span>(<span class="code-variable">$prenom</span>, <span class="code-variable">$nom</span>, <span class="code-variable">$age</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Je m'appelle $prenom $nom et j'ai $age ans"</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">presenter</span>(<span class="code-string">"Jean"</span>, <span class="code-string">"Dupont"</span>, <span class="code-string">30</span>);<br>
                        <span class="code-comment">// Affiche: "Je m'appelle Jean Dupont et j'ai 30 ans"</span><br><br>
                        <span class="code-comment">// Paramètres avec valeurs par défaut</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">saluer</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$langue</span> = <span class="code-string">"fr"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$langue</span> == <span class="code-string">"en"</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Hello $nom"</span>;<br>
                        &nbsp;&nbsp;} <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour $nom"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">saluer</span>(<span class="code-string">"Marie"</span>); <span class="code-comment">// "Bonjour Marie" (utilise la valeur par défaut)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-function">saluer</span>(<span class="code-string">"John"</span>, <span class="code-string">"en"</span>); <span class="code-comment">// "Hello John"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔢 Type hints et return types</h3>
            <p>Depuis PHP 7, vous pouvez spécifier les types de paramètres et de retour pour une meilleure sécurité et lisibilité.</p>

            <div class="example-box">
                <h3 style="color: #000;">Fonctions avec types :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Spécifier le type des paramètres</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">additionner</span>(<span class="code-function">int</span> <span class="code-variable">$a</span>, <span class="code-function">int</span> <span class="code-variable">$b</span>): <span class="code-function">int</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$a</span> + <span class="code-variable">$b</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">additionner</span>(<span class="code-string">5</span>, <span class="code-string">3</span>); <span class="code-comment">// 8</span><br><br>
                        <span class="code-comment">// Fonction avec type string</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">majuscules</span>(<span class="code-function">string</span> <span class="code-variable">$texte</span>): <span class="code-function">string</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-function">strtoupper</span>(<span class="code-variable">$texte</span>);<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">majuscules</span>(<span class="code-string">"bonjour"</span>); <span class="code-comment">// "BONJOUR"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📚 Portée des variables (scope)</h3>
            <p>Les variables définies dans une fonction sont locales à cette fonction. Pour utiliser une variable globale, utilisez le mot-clé <code>global</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Portée des variables :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean"</span>; <span class="code-comment">// Variable globale</span><br><br>
                        <span class="code-keyword">function</span> <span class="code-function">afficherNom</span>() {<br>
                        &nbsp;&nbsp;<span class="code-comment">// $nom n'est pas accessible ici par défaut</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$nomLocal</span> = <span class="code-string">"Marie"</span>; <span class="code-comment">// Variable locale</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$nomLocal</span>; <span class="code-comment">// "Marie"</span><br>
                        }<br><br>
                        <span class="code-comment">// Utiliser une variable globale</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">afficherNomGlobal</span>() {<br>
                        &nbsp;&nbsp;<span class="code-keyword">global</span> <span class="code-variable">$nom</span>; <span class="code-comment">// Accède à la variable globale</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$nom</span>; <span class="code-comment">// "Jean"</span><br>
                        }<br><br>
                        <span class="code-comment">// Meilleure pratique: passer en paramètre</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">afficherNomParam</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$nom</span>; <span class="code-comment">// Utilise le paramètre</span><br>
                        }<br>
                        <span class="code-function">afficherNomParam</span>(<span class="code-variable">$nom</span>); <span class="code-comment">// "Jean"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Fonctions intégrées de PHP</h3>
            <p>PHP dispose de milliers de fonctions intégrées pour manipuler les chaînes, les tableaux, les dates, les fichiers, etc.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemples de fonctions intégrées :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Fonctions pour chaînes</span><br>
                        <span class="code-variable">$texte</span> = <span class="code-string">"  Bonjour Monde  "</span>;<br>
                        <span class="code-function">strlen</span>(<span class="code-variable">$texte</span>); <span class="code-comment">// Longueur: 15</span><br>
                        <span class="code-function">strtoupper</span>(<span class="code-variable">$texte</span>); <span class="code-comment">// "  BONJOUR MONDE  "</span><br>
                        <span class="code-function">trim</span>(<span class="code-variable">$texte</span>); <span class="code-comment">// "Bonjour Monde" (enlève espaces)</span><br>
                        <span class="code-function">substr</span>(<span class="code-variable">$texte</span>, <span class="code-string">0</span>, <span class="code-string">7</span>); <span class="code-comment">// "Bonjour"</span><br><br>
                        <span class="code-comment">// Fonctions pour tableaux</span><br>
                        <span class="code-variable">$tableau</span> = [<span class="code-string">3</span>, <span class="code-string">1</span>, <span class="code-string">4</span>, <span class="code-string">2</span>];<br>
                        <span class="code-function">count</span>(<span class="code-variable">$tableau</span>); <span class="code-comment">// Nombre d'éléments: 4</span><br>
                        <span class="code-function">sort</span>(<span class="code-variable">$tableau</span>); <span class="code-comment">// Trie le tableau</span><br>
                        <span class="code-function">array_reverse</span>(<span class="code-variable">$tableau</span>); <span class="code-comment">// Inverse l'ordre</span><br><br>
                        <span class="code-comment">// Fonctions mathématiques</span><br>
                        <span class="code-function">abs</span>(-<span class="code-string">5</span>); <span class="code-comment">// Valeur absolue: 5</span><br>
                        <span class="code-function">round</span>(<span class="code-string">3.7</span>); <span class="code-comment">// Arrondi: 4</span><br>
                        <span class="code-function">max</span>(<span class="code-string">1</span>, <span class="code-string">5</span>, <span class="code-string">3</span>); <span class="code-comment">// Maximum: 5</span><br>
                        <span class="code-function">min</span>(<span class="code-string">1</span>, <span class="code-string">5</span>, <span class="code-string">3</span>); <span class="code-comment">// Minimum: 1</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Cas d'usage pratiques</h3>
            <p>Voici des exemples de fonctions utiles dans des situations réelles :</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 1 : Calculer le prix TTC</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">calculerTTC</span>(<span class="code-function">float</span> <span class="code-variable">$prixHT</span>, <span class="code-function">float</span> <span class="code-variable">$tauxTVA</span> = <span class="code-string">20.0</span>): <span class="code-function">float</span> {<br>
                        &nbsp;&nbsp;<span class="code-variable">$montantTVA</span> = <span class="code-variable">$prixHT</span> * (<span class="code-variable">$tauxTVA</span> / <span class="code-string">100</span>);<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$prixHT</span> + <span class="code-variable">$montantTVA</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">calculerTTC</span>(<span class="code-string">100</span>); <span class="code-comment">// 120 (TVA 20% par défaut)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-function">calculerTTC</span>(<span class="code-string">100</span>, <span class="code-string">10</span>); <span class="code-comment">// 110 (TVA 10%)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 2 : Valider un email</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">validerEmail</span>(<span class="code-function">string</span> <span class="code-variable">$email</span>): <span class="code-function">bool</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-function">filter_var</span>(<span class="code-variable">$email</span>, <span class="code-function">FILTER_VALIDATE_EMAIL</span>) !== <span class="code-keyword">false</span>;<br>
                        }<br><br>
                        <span class="code-keyword">if</span> (<span class="code-function">validerEmail</span>(<span class="code-string">"test@example.com"</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Email valide"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Email invalide"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Donnez des noms descriptifs à vos fonctions. Préférez <code>calculerPrixTTC()</code> plutôt que <code>calc()</code>. Cela rend votre code auto-documenté et plus facile à comprendre.</p>
            </div>

            <h2 id="arrays">📚 Tableaux</h2>
            <p>Les tableaux (arrays) sont des structures de données qui permettent de stocker plusieurs valeurs dans une seule variable. Ils sont essentiels en PHP et sont utilisés partout : pour stocker des listes d'utilisateurs, des données de formulaires, des résultats de base de données, etc.</p>

            <h3>📋 Types de tableaux</h3>
            <p>PHP supporte deux types de tableaux :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Tableaux indexés</strong> - Les éléments sont accessibles par un index numérique (0, 1, 2, ...)</li>
                <li><strong>Tableaux associatifs</strong> - Les éléments sont accessibles par des clés nommées ("nom", "age", etc.)</li>
            </ul>

            <h3>🔢 Tableaux indexés</h3>
            <p>Les tableaux indexés utilisent des indices numériques commençant à 0.</p>

            <div class="example-box">
                <h3 style="color: #000;">Créer et utiliser un tableau indexé :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Syntaxe ancienne (PHP 5.3 et antérieur)</span><br>
                        <span class="code-variable">$fruits</span> = <span class="code-function">array</span>(<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>);<br><br>
                        <span class="code-comment">// Syntaxe courte (PHP 5.4+) - RECOMMANDÉE</span><br>
                        <span class="code-variable">$legumes</span> = [<span class="code-string">"Carotte"</span>, <span class="code-string">"Tomate"</span>, <span class="code-string">"Salade"</span>];<br><br>
                        <span class="code-comment">// Accéder aux éléments (indices commencent à 0)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-string">0</span>]; <span class="code-comment">// "Pomme"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-string">1</span>]; <span class="code-comment">// "Banane"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-string">2</span>]; <span class="code-comment">// "Orange"</span><br><br>
                        <span class="code-comment">// Modifier un élément</span><br>
                        <span class="code-variable">$fruits</span>[<span class="code-string">1</span>] = <span class="code-string">"Kiwi"</span>; <span class="code-comment">// Remplace "Banane" par "Kiwi"</span><br><br>
                        <span class="code-comment">// Ajouter un élément</span><br>
                        <span class="code-variable">$fruits</span>[] = <span class="code-string">"Fraise"</span>; <span class="code-comment">// Ajoute à la fin</span><br>
                        <span class="code-variable">$fruits</span>[<span class="code-string">10</span>] = <span class="code-string">"Mangue"</span>; <span class="code-comment">// Ajoute à l'index 10</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔑 Tableaux associatifs</h3>
            <p>Les tableaux associatifs utilisent des clés nommées au lieu d'indices numériques. C'est très utile pour représenter des données structurées.</p>

            <div class="example-box">
                <h3 style="color: #000;">Créer et utiliser un tableau associatif :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Syntaxe avec array()</span><br>
                        <span class="code-variable">$personne</span> = <span class="code-function">array</span>(<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"prenom"</span> => <span class="code-string">"Dupont"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Paris"</span><br>
                        );<br><br>
                        <span class="code-comment">// Syntaxe courte (recommandée)</span><br>
                        <span class="code-variable">$etudiant</span> = [<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Marie"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">25</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"note"</span> => <span class="code-string">18.5</span><br>
                        ];<br><br>
                        <span class="code-comment">// Accéder aux éléments</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne</span>[<span class="code-string">"nom"</span>]; <span class="code-comment">// "Jean"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne</span>[<span class="code-string">"age"</span>]; <span class="code-comment">// 30</span><br><br>
                        <span class="code-comment">// Modifier un élément</span><br>
                        <span class="code-variable">$personne</span>[<span class="code-string">"age"</span>] = <span class="code-string">31</span>; <span class="code-comment">// Met à jour l'âge</span><br><br>
                        <span class="code-comment">// Ajouter un élément</span><br>
                        <span class="code-variable">$personne</span>[<span class="code-string">"email"</span>] = <span class="code-string">"jean@example.com"</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Parcourir les tableaux</h3>
            <p>Il existe plusieurs façons de parcourir un tableau en PHP.</p>

            <div class="example-box">
                <h3 style="color: #000;">Méthodes pour parcourir un tableau :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$fruits</span> = [<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>];<br><br>
                        <span class="code-comment">// Méthode 1: foreach (RECOMMANDÉE)</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$fruits</span> <span class="code-keyword">as</span> <span class="code-variable">$fruit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$fruit</span> . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Méthode 2: foreach avec index</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$fruits</span> <span class="code-keyword">as</span> <span class="code-variable">$index</span> => <span class="code-variable">$fruit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"$index: $fruit&lt;br&gt;"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Méthode 3: for (pour tableaux indexés)</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> < <span class="code-function">count</span>(<span class="code-variable">$fruits</span>); <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-variable">$i</span>] . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🛠️ Fonctions utiles pour les tableaux</h3>
            <p>PHP offre de nombreuses fonctions pour manipuler les tableaux.</p>

            <div class="example-box">
                <h3 style="color: #000;">Fonctions courantes pour tableaux :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$nombres</span> = [<span class="code-string">3</span>, <span class="code-string">1</span>, <span class="code-string">4</span>, <span class="code-string">2</span>, <span class="code-string">5</span>];<br><br>
                        <span class="code-comment">// Compter les éléments</span><br>
                        <span class="code-function">count</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// 5</span><br>
                        <span class="code-function">sizeof</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// 5 (identique à count)</span><br><br>
                        <span class="code-comment">// Vérifier si un élément existe</span><br>
                        <span class="code-function">in_array</span>(<span class="code-string">3</span>, <span class="code-variable">$nombres</span>); <span class="code-comment">// true</span><br>
                        <span class="code-function">array_key_exists</span>(<span class="code-string">0</span>, <span class="code-variable">$nombres</span>); <span class="code-comment">// true</span><br><br>
                        <span class="code-comment">// Trier</span><br>
                        <span class="code-function">sort</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// [1, 2, 3, 4, 5] (modifie le tableau original)</span><br>
                        <span class="code-function">rsort</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// Tri décroissant</span><br>
                        <span class="code-function">asort</span>(<span class="code-variable">$personne</span>); <span class="code-comment">// Trie un tableau associatif par valeur</span><br>
                        <span class="code-function">ksort</span>(<span class="code-variable">$personne</span>); <span class="code-comment">// Trie par clé</span><br><br>
                        <span class="code-comment">// Ajouter/Supprimer des éléments</span><br>
                        <span class="code-function">array_push</span>(<span class="code-variable">$nombres</span>, <span class="code-string">6</span>); <span class="code-comment">// Ajoute à la fin</span><br>
                        <span class="code-function">array_pop</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// Retire le dernier</span><br>
                        <span class="code-function">array_unshift</span>(<span class="code-variable">$nombres</span>, <span class="code-string">0</span>); <span class="code-comment">// Ajoute au début</span><br>
                        <span class="code-function">array_shift</span>(<span class="code-variable">$nombres</span>); <span class="code-comment">// Retire le premier</span><br><br>
                        <span class="code-comment">// Rechercher</span><br>
                        <span class="code-function">array_search</span>(<span class="code-string">3</span>, <span class="code-variable">$nombres</span>); <span class="code-comment">// Retourne l'index de 3</span><br>
                        <span class="code-function">array_keys</span>(<span class="code-variable">$personne</span>); <span class="code-comment">// Retourne toutes les clés</span><br>
                        <span class="code-function">array_values</span>(<span class="code-variable">$personne</span>); <span class="code-comment">// Retourne toutes les valeurs</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📊 Tableaux multidimensionnels</h3>
            <p>Un tableau peut contenir d'autres tableaux, créant ainsi des tableaux multidimensionnels. C'est très utile pour représenter des structures de données complexes.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de tableau multidimensionnel :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Tableau de personnes</span><br>
                        <span class="code-variable">$personnes</span> = [<br>
                        &nbsp;&nbsp;[<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Paris"</span><br>
                        &nbsp;&nbsp;],<br>
                        &nbsp;&nbsp;[<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Marie"</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">25</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Lyon"</span><br>
                        &nbsp;&nbsp;],<br>
                        &nbsp;&nbsp;[<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Pierre"</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">35</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"ville"</span> => <span class="code-string">"Marseille"</span><br>
                        &nbsp;&nbsp;]<br>
                        ];<br><br>
                        <span class="code-comment">// Accéder aux éléments</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personnes</span>[<span class="code-string">0</span>][<span class="code-string">"nom"</span>]; <span class="code-comment">// "Jean"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personnes</span>[<span class="code-string">1</span>][<span class="code-string">"age"</span>]; <span class="code-comment">// 25</span><br><br>
                        <span class="code-comment">// Parcourir un tableau multidimensionnel</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$personnes</span> <span class="code-keyword">as</span> <span class="code-variable">$personne</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"{$personne['nom']} a {$personne['age']} ans et habite à {$personne['ville']}&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Cas d'usage pratiques</h3>
            <p>Voici des exemples concrets d'utilisation des tableaux :</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 1 : Stocker des données de formulaire</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Données d'un formulaire</span><br>
                        <span class="code-variable">$formulaire</span> = [<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"email"</span> => <span class="code-string">"jean@example.com"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"hobbies"</span> => [<span class="code-string">"Lecture"</span>, <span class="code-string">"Sport"</span>, <span class="code-string">"Musique"</span>]<br>
                        ];<br><br>
                        <span class="code-comment">// Traiter les données</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$formulaire</span> <span class="code-keyword">as</span> <span class="code-variable">$cle</span> => <span class="code-variable">$valeur</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-function">is_array</span>(<span class="code-variable">$valeur</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"$cle: "</span> . <span class="code-function">implode</span>(<span class="code-string">", "</span>, <span class="code-variable">$valeur</span>) . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;} <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"$cle: $valeur&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">Exemple 2 : Manipuler des listes</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$produits</span> = [<span class="code-string">"Laptop"</span>, <span class="code-string">"Souris"</span>, <span class="code-string">"Clavier"</span>];<br><br>
                        <span class="code-comment">// Ajouter un produit</span><br>
                        <span class="code-variable">$produits</span>[] = <span class="code-string">"Écran"</span>;<br><br>
                        <span class="code-comment">// Supprimer un produit</span><br>
                        <span class="code-variable">$index</span> = <span class="code-function">array_search</span>(<span class="code-string">"Souris"</span>, <span class="code-variable">$produits</span>);<br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$index</span> !== <span class="code-keyword">false</span>) {<br>
                        &nbsp;&nbsp;<span class="code-function">unset</span>(<span class="code-variable">$produits</span>[<span class="code-variable">$index</span>]);<br>
                        }<br><br>
                        <span class="code-comment">// Réindexer le tableau après suppression</span><br>
                        <span class="code-variable">$produits</span> = <span class="code-function">array_values</span>(<span class="code-variable">$produits</span>);<br><br>
                        <span class="code-comment">// Afficher tous les produits</span><br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$produits</span> <span class="code-keyword">as</span> <span class="code-variable">$produit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"- $produit&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez la syntaxe courte <code>[]</code> plutôt que <code>array()</code> pour créer des tableaux. C'est plus moderne, plus lisible, et c'est la syntaxe recommandée depuis PHP 5.4.</p>
            </div>

            <h2 id="forms">📝 Formulaires</h2>
            <p>Le traitement de formulaires est l'une des fonctionnalités les plus importantes de PHP. Les formulaires permettent aux utilisateurs d'envoyer des données au serveur, que PHP peut ensuite traiter, valider et stocker.</p>

            <h3>📤 Méthodes GET et POST</h3>
            <p>Il existe deux méthodes principales pour envoyer des données depuis un formulaire :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>GET</strong> - Les données sont visibles dans l'URL (limité à ~2000 caractères). Utilisé pour les recherches, filtres, etc.</li>
                <li><strong>POST</strong> - Les données sont envoyées de manière sécurisée (non visibles dans l'URL). Utilisé pour les formulaires de connexion, d'inscription, etc.</li>
            </ul>

            <h3>📥 Récupérer les données POST</h3>
            <p>Les données envoyées via POST sont accessibles via le superglobal <code>$_POST</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de formulaire avec POST :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Formulaire HTML --&gt;</span><br>
                        <span class="code-keyword">&lt;form</span> <span class="code-attr">method</span>=<span class="code-value">"POST"</span> <span class="code-attr">action</span>=<span class="code-value">"traitement.php"</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"text"</span> <span class="code-attr">name</span>=<span class="code-value">"nom"</span> <span class="code-attr">placeholder</span>=<span class="code-value">"Votre nom"</span> <span class="code-attr">required</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"email"</span> <span class="code-attr">name</span>=<span class="code-value">"email"</span> <span class="code-attr">placeholder</span>=<span class="code-value">"Votre email"</span> <span class="code-attr">required</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;button</span> <span class="code-attr">type</span>=<span class="code-value">"submit"</span><span class="code-keyword">&gt;</span>Envoyer<span class="code-keyword">&lt;/button&gt;</span><br>
                        <span class="code-keyword">&lt;/form&gt;</span><br><br>
                        <span class="code-comment">// traitement.php - Récupérer les données</span><br>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$_SERVER</span>[<span class="code-string">"REQUEST_METHOD"</span>] == <span class="code-string">"POST"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Récupérer les données</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$nom</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"nom"</span>] ?? <span class="code-string">''</span>; <span class="code-comment">// Utilise ?? pour valeur par défaut</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$email</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"email"</span>] ?? <span class="code-string">''</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Valider les données</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (!<span class="code-function">empty</span>(<span class="code-variable">$nom</span>) && !<span class="code-function">empty</span>(<span class="code-variable">$email</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bonjour $nom, votre email est $email"</span>;<br>
                        &nbsp;&nbsp;} <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Veuillez remplir tous les champs"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📥 Récupérer les données GET</h3>
            <p>Les données envoyées via GET sont accessibles via le superglobal <code>$_GET</code> et apparaissent dans l'URL.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple avec GET :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// URL: recherche.php?q=php&categorie=web</span><br>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Récupérer les paramètres GET</span><br>
                        <span class="code-variable">$recherche</span> = <span class="code-variable">$_GET</span>[<span class="code-string">"q"</span>] ?? <span class="code-string">''</span>; <span class="code-comment">// "php"</span><br>
                        <span class="code-variable">$categorie</span> = <span class="code-variable">$_GET</span>[<span class="code-string">"categorie"</span>] ?? <span class="code-string">''</span>; <span class="code-comment">// "web"</span><br><br>
                        <span class="code-keyword">if</span> (!<span class="code-function">empty</span>(<span class="code-variable">$recherche</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Résultats pour: $recherche dans la catégorie $categorie"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔒 Sécurité et validation</h3>
            <p>Il est crucial de valider et sécuriser les données des formulaires pour éviter les attaques (XSS, injection SQL, etc.).</p>

            <div class="example-box">
                <h3 style="color: #000;">Validation et sécurisation :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$_SERVER</span>[<span class="code-string">"REQUEST_METHOD"</span>] == <span class="code-string">"POST"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Récupérer et nettoyer les données</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$nom</span> = <span class="code-function">trim</span>(<span class="code-variable">$_POST</span>[<span class="code-string">"nom"</span>] ?? <span class="code-string">''</span>); <span class="code-comment">// Enlève les espaces</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$email</span> = <span class="code-function">filter_var</span>(<span class="code-variable">$_POST</span>[<span class="code-string">"email"</span>] ?? <span class="code-string">''</span>, <span class="code-function">FILTER_SANITIZE_EMAIL</span>);<br>
                        &nbsp;&nbsp;<span class="code-variable">$message</span> = <span class="code-function">htmlspecialchars</span>(<span class="code-variable">$_POST</span>[<span class="code-string">"message"</span>] ?? <span class="code-string">''</span>, <span class="code-function">ENT_QUOTES</span>, <span class="code-string">'UTF-8'</span>);<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Valider</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$erreurs</span> = [];<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-function">empty</span>(<span class="code-variable">$nom</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreurs</span>[] = <span class="code-string">"Le nom est requis"</span>;<br>
                        } <span class="code-keyword">elseif</span> (<span class="code-function">strlen</span>(<span class="code-variable">$nom</span>) < <span class="code-string">2</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreurs</span>[] = <span class="code-string">"Le nom doit contenir au moins 2 caractères"</span>;<br>
                        }<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (!<span class="code-function">filter_var</span>(<span class="code-variable">$email</span>, <span class="code-function">FILTER_VALIDATE_EMAIL</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreurs</span>[] = <span class="code-string">"Email invalide"</span>;<br>
                        }<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-function">empty</span>(<span class="code-variable">$erreurs</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Traiter les données (envoyer email, sauvegarder en BDD, etc.)</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Formulaire validé avec succès !"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Afficher les erreurs</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">foreach</span> (<span class="code-variable">$erreurs</span> <span class="code-keyword">as</span> <span class="code-variable">$erreur</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Erreur: $erreur&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📋 Exemple complet de formulaire</h3>
            <p>Voici un exemple complet avec formulaire HTML et traitement PHP sur la même page :</p>

            <div class="example-box">
                <h3 style="color: #000;">Formulaire complet :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$message</span> = <span class="code-string">''</span>;<br>
                        <span class="code-variable">$erreur</span> = <span class="code-string">''</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$_SERVER</span>[<span class="code-string">"REQUEST_METHOD"</span>] == <span class="code-string">"POST"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$nom</span> = <span class="code-function">trim</span>(<span class="code-variable">$_POST</span>[<span class="code-string">"nom"</span>] ?? <span class="code-string">''</span>);<br>
                        &nbsp;&nbsp;<span class="code-variable">$email</span> = <span class="code-function">filter_var</span>(<span class="code-variable">$_POST</span>[<span class="code-string">"email"</span>] ?? <span class="code-string">''</span>, <span class="code-function">FILTER_SANITIZE_EMAIL</span>);<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-function">empty</span>(<span class="code-variable">$nom</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreur</span> = <span class="code-string">"Le nom est requis"</span>;<br>
                        } <span class="code-keyword">elseif</span> (!<span class="code-function">filter_var</span>(<span class="code-variable">$email</span>, <span class="code-function">FILTER_VALIDATE_EMAIL</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreur</span> = <span class="code-string">"Email invalide"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$message</span> = <span class="code-string">"Merci $nom, votre formulaire a été envoyé !"</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Ici vous pourriez envoyer un email, sauvegarder en BDD, etc.</span><br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span><br><br>
                        <span class="code-comment">&lt;!DOCTYPE html&gt;</span><br>
                        <span class="code-keyword">&lt;html&gt;</span><br>
                        <span class="code-keyword">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;title&gt;</span>Formulaire de contact<span class="code-keyword">&lt;/title&gt;</span><br>
                        <span class="code-keyword">&lt;/head&gt;</span><br>
                        <span class="code-keyword">&lt;body&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;?php if</span> (<span class="code-variable">$message</span>): <span class="code-keyword">?&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: green;"</span><span class="code-keyword">&gt;</span><span class="code-keyword">&lt;?php echo</span> <span class="code-variable">$message</span>; <span class="code-keyword">?&gt;</span><span class="code-keyword">&lt;/p&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;?php endif; ?&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;?php if</span> (<span class="code-variable">$erreur</span>): <span class="code-keyword">?&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: red;"</span><span class="code-keyword">&gt;</span><span class="code-keyword">&lt;?php echo</span> <span class="code-variable">$erreur</span>; <span class="code-keyword">?&gt;</span><span class="code-keyword">&lt;/p&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;?php endif; ?&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;form</span> <span class="code-attr">method</span>=<span class="code-value">"POST"</span> <span class="code-attr">action</span>=<span class="code-value">""</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"text"</span> <span class="code-attr">name</span>=<span class="code-value">"nom"</span> <span class="code-attr">placeholder</span>=<span class="code-value">"Nom"</span> <span class="code-attr">required</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"email"</span> <span class="code-attr">name</span>=<span class="code-value">"email"</span> <span class="code-attr">placeholder</span>=<span class="code-value">"Email"</span> <span class="code-attr">required</span><span class="code-keyword">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">&lt;button</span> <span class="code-attr">type</span>=<span class="code-value">"submit"</span><span class="code-keyword">&gt;</span>Envoyer<span class="code-keyword">&lt;/button&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">&lt;/form&gt;</span><br>
                        <span class="code-keyword">&lt;/body&gt;</span><br>
                        <span class="code-keyword">&lt;/html&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Sécurité importante :</strong> Toujours valider et nettoyer les données des formulaires ! Utilisez <code>htmlspecialchars()</code> pour éviter les attaques XSS, <code>filter_var()</code> pour valider les emails, et des requêtes préparées pour les bases de données (voir section PDO).</p>
            </div>

            <h2 id="sessions">🔐 Sessions</h2>
            <p>Les sessions permettent de stocker des informations utilisateur entre les pages web. Contrairement aux cookies qui sont stockés côté client, les sessions sont stockées côté serveur, ce qui les rend plus sécurisées pour stocker des données sensibles.</p>

            <h3>🌐 Comment fonctionnent les sessions ?</h3>
            <p>Quand vous démarrez une session, PHP crée un identifiant unique (session ID) qui est envoyé au navigateur sous forme de cookie. Ce cookie permet au serveur de reconnaître l'utilisateur lors des requêtes suivantes et de récupérer ses données de session.</p>

            <h3>▶️ Démarrer une session</h3>
            <p>Avant d'utiliser les sessions, vous devez appeler <code>session_start()</code> au début de chaque page PHP qui utilise les sessions.</p>

            <div class="example-box">
                <h3 style="color: #000;">Démarrer et utiliser une session :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Démarrer la session (doit être appelé avant tout output HTML)</span><br>
                        <span class="code-function">session_start</span>();<br><br>
                        <span class="code-comment">// Stocker des données dans la session</span><br>
                        <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>] = <span class="code-string">"Jean"</span>;<br>
                        <span class="code-variable">$_SESSION</span>[<span class="code-string">"email"</span>] = <span class="code-string">"jean@example.com"</span>;<br>
                        <span class="code-variable">$_SESSION</span>[<span class="code-string">"role"</span>] = <span class="code-string">"admin"</span>;<br>
                        <span class="code-variable">$_SESSION</span>[<span class="code-string">"login_time"</span>] = <span class="code-function">time</span>(); <span class="code-comment">// Timestamp actuel</span><br><br>
                        <span class="code-comment">// Récupérer des données de la session</span><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>]; <span class="code-comment">// "Bonjour Jean"</span><br><br>
                        <span class="code-comment">// Vérifier si une variable de session existe</span><br>
                        <span class="code-keyword">if</span> (<span class="code-function">isset</span>(<span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>])) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Utilisateur connecté: "</span> . <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>];<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🗑️ Supprimer des données de session</h3>
            <p>Vous pouvez supprimer des variables individuelles ou détruire complètement la session.</p>

            <div class="example-box">
                <h3 style="color: #000;">Supprimer des données de session :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-function">session_start</span>();<br><br>
                        <span class="code-comment">// Supprimer une variable spécifique</span><br>
                        <span class="code-function">unset</span>(<span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>);<br><br>
                        <span class="code-comment">// Supprimer toutes les variables de session</span><br>
                        <span class="code-variable">$_SESSION</span> = []; <span class="code-comment">// Vide le tableau</span><br><br>
                        <span class="code-comment">// Détruire complètement la session</span><br>
                        <span class="code-function">session_destroy</span>(); <span class="code-comment">// Détruit la session côté serveur</span><br>
                        <span class="code-comment">// Note: session_destroy() ne supprime pas le cookie, il faut aussi:</span><br>
                        <span class="code-keyword">if</span> (<span class="code-function">ini_get</span>(<span class="code-string">"session.use_cookies"</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$params</span> = <span class="code-function">session_get_cookie_params</span>();<br>
                        &nbsp;&nbsp;<span class="code-function">setcookie</span>(<span class="code-function">session_name</span>(), <span class="code-string">''</span>, <span class="code-string">time()</span> - <span class="code-string">42000</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$params</span>[<span class="code-string">"path"</span>], <span class="code-variable">$params</span>[<span class="code-string">"domain"</span>],<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$params</span>[<span class="code-string">"secure"</span>], <span class="code-variable">$params</span>[<span class="code-string">"httponly"</span>]<br>
                        &nbsp;&nbsp;);<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔐 Exemple : Système de connexion</h3>
            <p>Voici un exemple complet d'utilisation des sessions pour un système de connexion :</p>

            <div class="example-box">
                <h3 style="color: #000;">Système de connexion avec sessions :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">// login.php - Page de connexion</span><br>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-function">session_start</span>();<br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$_SERVER</span>[<span class="code-string">"REQUEST_METHOD"</span>] == <span class="code-string">"POST"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$username</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"username"</span>] ?? <span class="code-string">''</span>;<br>
                        &nbsp;&nbsp;<span class="code-variable">$password</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"password"</span>] ?? <span class="code-string">''</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Vérifier les identifiants (exemple simplifié)</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$username</span> == <span class="code-string">"admin"</span> && <span class="code-variable">$password</span> == <span class="code-string">"secret123"</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Créer la session</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$_SESSION</span>[<span class="code-string">"logged_in"</span>] = <span class="code-keyword">true</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>] = <span class="code-variable">$username</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$_SESSION</span>[<span class="code-string">"login_time"</span>] = <span class="code-function">time</span>();<br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">// Rediriger vers la page d'accueil</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">header</span>(<span class="code-string">"Location: index.php"</span>);<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">exit</span>;<br>
                        &nbsp;&nbsp;} <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$erreur</span> = <span class="code-string">"Identifiants incorrects"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span><br><br>
                        <span class="code-comment">// index.php - Page protégée</span><br>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-function">session_start</span>();<br><br>
                        <span class="code-comment">// Vérifier si l'utilisateur est connecté</span><br>
                        <span class="code-keyword">if</span> (!<span class="code-function">isset</span>(<span class="code-variable">$_SESSION</span>[<span class="code-string">"logged_in"</span>]) || !<span class="code-variable">$_SESSION</span>[<span class="code-string">"logged_in"</span>]) {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Rediriger vers la page de connexion</span><br>
                        &nbsp;&nbsp;<span class="code-function">header</span>(<span class="code-string">"Location: login.php"</span>);<br>
                        &nbsp;&nbsp;<span class="code-keyword">exit</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bienvenue "</span> . <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>] . <span class="code-string">" !"</span>;<br>
                        <span class="code-keyword">?&gt;</span><br><br>
                        <span class="code-comment">// logout.php - Déconnexion</span><br>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-function">session_start</span>();<br>
                        <span class="code-variable">$_SESSION</span> = []; <span class="code-comment">// Vider la session</span><br>
                        <span class="code-function">session_destroy</span>(); <span class="code-comment">// Détruire la session</span><br>
                        <span class="code-function">header</span>(<span class="code-string">"Location: login.php"</span>);<br>
                        <span class="code-keyword">exit</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>⏱️ Durée de vie des sessions</h3>
            <p>Par défaut, une session PHP expire après 24 minutes d'inactivité. Vous pouvez modifier cette durée dans le fichier <code>php.ini</code> ou avec <code>ini_set()</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Configurer la durée de vie des sessions :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Modifier la durée de vie de la session (en secondes)</span><br>
                        <span class="code-function">ini_set</span>(<span class="code-string">'session.gc_maxlifetime'</span>, <span class="code-string">3600</span>); <span class="code-comment">// 1 heure</span><br>
                        <span class="code-function">session_set_cookie_params</span>(<span class="code-string">3600</span>); <span class="code-comment">// Cookie expire après 1 heure</span><br>
                        <span class="code-function">session_start</span>();<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Important :</strong> <code>session_start()</code> doit être appelé avant tout output HTML (même un espace ou une ligne vide). Sinon, vous obtiendrez l'erreur "Headers already sent".</p>
            </div>

            <h2 id="mysql">🗄️ MySQL</h2>
            <p>MySQL est l'un des systèmes de gestion de bases de données relationnelles (SGBDR) les plus populaires. PHP peut se connecter à MySQL pour stocker, récupérer, modifier et supprimer des données. Il existe deux extensions principales : <strong>MySQLi</strong> (améliorée) et <strong>PDO</strong> (plus moderne et recommandée).</p>

            <h3>🔌 Connexion à MySQL avec MySQLi</h3>
            <p>MySQLi (MySQL Improved) est l'extension améliorée de MySQL. Elle supporte les requêtes préparées et offre de meilleures performances.</p>

            <div class="example-box">
                <h3 style="color: #000;">Connexion et requêtes de base :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Connexion à la base de données</span><br>
                        <span class="code-variable">$host</span> = <span class="code-string">"localhost"</span>;<br>
                        <span class="code-variable">$username</span> = <span class="code-string">"root"</span>;<br>
                        <span class="code-variable">$password</span> = <span class="code-string">""</span>; <span class="code-comment">// Mot de passe vide par défaut (XAMPP)</span><br>
                        <span class="code-variable">$database</span> = <span class="code-string">"ma_base"</span>;<br><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-variable">$host</span>, <span class="code-variable">$username</span>, <span class="code-variable">$password</span>, <span class="code-variable">$database</span>);<br><br>
                        <span class="code-comment">// Vérifier la connexion</span><br>
                        <span class="code-keyword">if</span> (!<span class="code-variable">$conn</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">die</span>(<span class="code-string">"Connexion échouée: "</span> . <span class="code-function">mysqli_connect_error</span>());<br>
                        }<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Connexion réussie !"</span>;<br><br>
                        <span class="code-comment">// Fermer la connexion</span><br>
                        <span class="code-function">mysqli_close</span>(<span class="code-variable">$conn</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>📖 Requête SELECT (Lire des données)</h3>
            <p>La requête SELECT permet de récupérer des données de la base de données.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de requête SELECT :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-string">"localhost"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>, <span class="code-string">"ma_base"</span>);<br><br>
                        <span class="code-comment">// Requête SELECT</span><br>
                        <span class="code-variable">$sql</span> = <span class="code-string">"SELECT id, nom, email FROM users"</span>;<br>
                        <span class="code-variable">$result</span> = <span class="code-function">mysqli_query</span>(<span class="code-variable">$conn</span>, <span class="code-variable">$sql</span>);<br><br>
                        <span class="code-keyword">if</span> (<span class="code-function">mysqli_num_rows</span>(<span class="code-variable">$result</span>) > <span class="code-string">0</span>) {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Parcourir les résultats</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">while</span> (<span class="code-variable">$row</span> = <span class="code-function">mysqli_fetch_assoc</span>(<span class="code-variable">$result</span>)) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"ID: "</span> . <span class="code-variable">$row</span>[<span class="code-string">"id"</span>] . <span class="code-string">", Nom: "</span> . <span class="code-variable">$row</span>[<span class="code-string">"nom"</span>] . <span class="code-string">", Email: "</span> . <span class="code-variable">$row</span>[<span class="code-string">"email"</span>] . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Aucun résultat trouvé"</span>;<br>
                        }<br><br>
                        <span class="code-function">mysqli_close</span>(<span class="code-variable">$conn</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>➕ Requête INSERT (Insérer des données)</h3>
            <p>La requête INSERT permet d'ajouter de nouvelles données dans la base de données.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de requête INSERT :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-string">"localhost"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>, <span class="code-string">"ma_base"</span>);<br><br>
                        <span class="code-comment">// Données à insérer</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean Dupont"</span>;<br>
                        <span class="code-variable">$email</span> = <span class="code-string">"jean@example.com"</span>;<br>
                        <span class="code-variable">$age</span> = <span class="code-string">30</span>;<br><br>
                        <span class="code-comment">// Requête INSERT (⚠️ DANGEREUX - voir section PDO pour requêtes préparées)</span><br>
                        <span class="code-variable">$sql</span> = <span class="code-string">"INSERT INTO users (nom, email, age) VALUES ('$nom', '$email', $age)"</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-function">mysqli_query</span>(<span class="code-variable">$conn</span>, <span class="code-variable">$sql</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Nouvel utilisateur créé avec succès. ID: "</span> . <span class="code-function">mysqli_insert_id</span>(<span class="code-variable">$conn</span>);<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Erreur: "</span> . <span class="code-function">mysqli_error</span>(<span class="code-variable">$conn</span>);<br>
                        }<br><br>
                        <span class="code-function">mysqli_close</span>(<span class="code-variable">$conn</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>✏️ Requête UPDATE (Modifier des données)</h3>
            <p>La requête UPDATE permet de modifier des données existantes.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de requête UPDATE :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-string">"localhost"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>, <span class="code-string">"ma_base"</span>);<br><br>
                        <span class="code-variable">$id</span> = <span class="code-string">1</span>;<br>
                        <span class="code-variable">$nouveauNom</span> = <span class="code-string">"Marie Martin"</span>;<br>
                        <span class="code-variable">$nouvelAge</span> = <span class="code-string">25</span>;<br><br>
                        <span class="code-variable">$sql</span> = <span class="code-string">"UPDATE users SET nom='$nouveauNom', age=$nouvelAge WHERE id=$id"</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-function">mysqli_query</span>(<span class="code-variable">$conn</span>, <span class="code-variable">$sql</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Utilisateur mis à jour. Lignes affectées: "</span> . <span class="code-function">mysqli_affected_rows</span>(<span class="code-variable">$conn</span>);<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Erreur: "</span> . <span class="code-function">mysqli_error</span>(<span class="code-variable">$conn</span>);<br>
                        }<br><br>
                        <span class="code-function">mysqli_close</span>(<span class="code-variable">$conn</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🗑️ Requête DELETE (Supprimer des données)</h3>
            <p>La requête DELETE permet de supprimer des données de la base de données.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de requête DELETE :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-string">"localhost"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>, <span class="code-string">"ma_base"</span>);<br><br>
                        <span class="code-variable">$id</span> = <span class="code-string">1</span>;<br>
                        <span class="code-variable">$sql</span> = <span class="code-string">"DELETE FROM users WHERE id=$id"</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-function">mysqli_query</span>(<span class="code-variable">$conn</span>, <span class="code-variable">$sql</span>)) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Utilisateur supprimé avec succès"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Erreur: "</span> . <span class="code-function">mysqli_error</span>(<span class="code-variable">$conn</span>);<br>
                        }<br><br>
                        <span class="code-function">mysqli_close</span>(<span class="code-variable">$conn</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
                <p style="color: #000;"><strong>⚠️ Sécurité CRITIQUE :</strong> Les exemples ci-dessus utilisent des requêtes directes qui sont vulnérables aux attaques par injection SQL ! <strong>NE JAMAIS</strong> utiliser cette méthode en production. Utilisez toujours des <strong>requêtes préparées</strong> (voir section PDO ci-dessous) pour protéger votre application.</p>
            </div>

            <h2 id="pdo">🔗 PDO (PHP Data Objects)</h2>
            <p>PDO (PHP Data Objects) est une interface moderne et sécurisée pour accéder aux bases de données. C'est la méthode <strong>recommandée</strong> pour travailler avec des bases de données en PHP car elle offre :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Sécurité</strong> - Protection contre les injections SQL via requêtes préparées</li>
                <li>✅ <strong>Portabilité</strong> - Fonctionne avec MySQL, PostgreSQL, SQLite, etc.</li>
                <li>✅ <strong>Simplicité</strong> - API cohérente pour toutes les bases de données</li>
                <li>✅ <strong>Performance</strong> - Meilleures performances que MySQLi</li>
            </ul>

            <h3>🔌 Connexion avec PDO</h3>
            <p>La connexion PDO utilise un DSN (Data Source Name) pour spécifier la base de données.</p>

            <div class="example-box">
                <h3 style="color: #000;">Connexion PDO :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">try</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Connexion avec gestion d'erreurs</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"mysql:host=localhost;dbname=ma_base;charset=utf8mb4"</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">"root"</span>, <span class="code-comment">// Username</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-string">""</span>, <span class="code-comment">// Password</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;[<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">PDO</span>::<span class="code-function">ATTR_ERRMODE</span> => <span class="code-function">PDO</span>::<span class="code-function">ERRMODE_EXCEPTION</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">PDO</span>::<span class="code-function">ATTR_DEFAULT_FETCH_MODE</span> => <span class="code-function">PDO</span>::<span class="code-function">FETCH_ASSOC</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;]<br>
                        &nbsp;&nbsp;);<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Connexion réussie !"</span>;<br>
                        } <span class="code-keyword">catch</span> (<span class="code-function">PDOException</span> <span class="code-variable">$e</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Erreur de connexion: "</span> . <span class="code-variable">$e</span>-><span class="code-function">getMessage</span>();<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔒 Requêtes préparées (Sécurisées)</h3>
            <p>Les requêtes préparées sont la meilleure façon de protéger votre application contre les injections SQL. Les valeurs sont séparées de la requête SQL elle-même.</p>

            <div class="example-box">
                <h3 style="color: #000;">SELECT avec requête préparée :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<span class="code-string">"mysql:host=localhost;dbname=ma_base"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>);<br><br>
                        <span class="code-comment">// Requête préparée avec paramètres nommés (recommandé)</span><br>
                        <span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"SELECT * FROM users WHERE id = :id AND email = :email"</span>);<br>
                        <span class="code-variable">$stmt</span>-><span class="code-function">execute</span>([<br>
                        &nbsp;&nbsp;<span class="code-string">'id'</span> => <span class="code-string">1</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">'email'</span> => <span class="code-string">'jean@example.com'</span><br>
                        ]);<br><br>
                        <span class="code-comment">// Récupérer les résultats</span><br>
                        <span class="code-variable">$user</span> = <span class="code-variable">$stmt</span>-><span class="code-function">fetch</span>(); <span class="code-comment">// Un seul résultat</span><br>
                        <span class="code-comment">// ou</span><br>
                        <span class="code-variable">$users</span> = <span class="code-variable">$stmt</span>-><span class="code-function">fetchAll</span>(); <span class="code-comment">// Tous les résultats</span><br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$user</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Nom: "</span> . <span class="code-variable">$user</span>[<span class="code-string">'nom'</span>];<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">INSERT avec requête préparée :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<span class="code-string">"mysql:host=localhost;dbname=ma_base"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>);<br><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean Dupont"</span>;<br>
                        <span class="code-variable">$email</span> = <span class="code-string">"jean@example.com"</span>;<br>
                        <span class="code-variable">$age</span> = <span class="code-string">30</span>;<br><br>
                        <span class="code-comment">// Requête préparée INSERT</span><br>
                        <span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"INSERT INTO users (nom, email, age) VALUES (:nom, :email, :age)"</span>);<br>
                        <span class="code-variable">$stmt</span>-><span class="code-function">execute</span>([<br>
                        &nbsp;&nbsp;<span class="code-string">'nom'</span> => <span class="code-variable">$nom</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">'email'</span> => <span class="code-variable">$email</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">'age'</span> => <span class="code-variable">$age</span><br>
                        ]);<br><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Utilisateur créé. ID: "</span> . <span class="code-variable">$pdo</span>-><span class="code-function">lastInsertId</span>();<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">UPDATE et DELETE avec requêtes préparées :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<span class="code-string">"mysql:host=localhost;dbname=ma_base"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>);<br><br>
                        <span class="code-comment">// UPDATE</span><br>
                        <span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"UPDATE users SET nom = :nom, age = :age WHERE id = :id"</span>);<br>
                        <span class="code-variable">$stmt</span>-><span class="code-function">execute</span>([<br>
                        &nbsp;&nbsp;<span class="code-string">'id'</span> => <span class="code-string">1</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">'nom'</span> => <span class="code-string">"Marie Martin"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">'age'</span> => <span class="code-string">25</span><br>
                        ]);<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Lignes modifiées: "</span> . <span class="code-variable">$stmt</span>-><span class="code-function">rowCount</span>();<br><br>
                        <span class="code-comment">// DELETE</span><br>
                        <span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"DELETE FROM users WHERE id = :id"</span>);<br>
                        <span class="code-variable">$stmt</span>-><span class="code-function">execute</span>([<span class="code-string">'id'</span> => <span class="code-string">1</span>]);<br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Lignes supprimées: "</span> . <span class="code-variable">$stmt</span>-><span class="code-function">rowCount</span>();<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔄 Parcourir plusieurs résultats</h3>
            <p>Vous pouvez parcourir les résultats d'une requête de plusieurs façons.</p>

            <div class="example-box">
                <h3 style="color: #000;">Méthodes pour récupérer les résultats :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<span class="code-string">"mysql:host=localhost;dbname=ma_base"</span>, <span class="code-string">"root"</span>, <span class="code-string">""</span>);<br>
                        <span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"SELECT * FROM users"</span>);<br>
                        <span class="code-variable">$stmt</span>-><span class="code-function">execute</span>();<br><br>
                        <span class="code-comment">// Méthode 1: fetchAll() - Récupère tous les résultats</span><br>
                        <span class="code-variable">$users</span> = <span class="code-variable">$stmt</span>-><span class="code-function">fetchAll</span>();<br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$users</span> <span class="code-keyword">as</span> <span class="code-variable">$user</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$user</span>[<span class="code-string">'nom'</span>] . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Méthode 2: fetch() dans une boucle - Récupère un par un</span><br>
                        <span class="code-keyword">while</span> (<span class="code-variable">$user</span> = <span class="code-variable">$stmt</span>-><span class="code-function">fetch</span>()) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$user</span>[<span class="code-string">'nom'</span>] . <span class="code-string">"&lt;br&gt;"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <p style="color: #000;"><strong>✅ Avantage des requêtes préparées :</strong> Les requêtes préparées protègent automatiquement contre les injections SQL. Les valeurs sont traitées comme des données, pas comme du code SQL. C'est la méthode <strong>obligatoire</strong> à utiliser en production !</p>
            </div>

            <h2 id="oop">🎯 POO (Programmation Orientée Objet)</h2>
            <p>La Programmation Orientée Objet (POO) est un paradigme de programmation qui organise le code en <strong>classes</strong> et <strong>objets</strong>. Elle permet de créer des structures de code réutilisables, maintenables et modulaires. La POO est essentielle pour développer des applications PHP modernes et complexes.</p>

            <h3>📚 Concepts fondamentaux</h3>
            <p>La POO repose sur plusieurs concepts clés :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>Classe</strong> - Un modèle ou un plan pour créer des objets (comme un moule)</li>
                <li><strong>Objet</strong> - Une instance concrète d'une classe (comme un gâteau fait avec le moule)</li>
                <li><strong>Propriété</strong> - Une variable appartenant à une classe ou un objet</li>
                <li><strong>Méthode</strong> - Une fonction appartenant à une classe ou un objet</li>
                <li><strong>Encapsulation</strong> - Le principe de cacher les détails internes et exposer seulement ce qui est nécessaire</li>
                <li><strong>Héritage</strong> - La capacité d'une classe à hériter des propriétés et méthodes d'une autre classe</li>
            </ul>

            <h3>🏗️ Créer une classe</h3>
            <p>Une classe est définie avec le mot-clé <code>class</code> suivi du nom de la classe.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de classe simple :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Personne</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Propriétés (variables de la classe)</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">public</span> <span class="code-variable">$prenom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$age</span>; <span class="code-comment">// private = accessible uniquement dans la classe</span><br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Constructeur - appelé lors de la création d'un objet</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$prenom</span>, <span class="code-variable">$age</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->prenom = <span class="code-variable">$prenom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->age = <span class="code-variable">$age</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Méthode (fonction de la classe)</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">saluer</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour, je suis $this->prenom $this->nom"</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Méthode pour accéder à une propriété privée</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getAge</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->age;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">// Créer un objet (instance de la classe)</span><br>
                        <span class="code-variable">$personne1</span> = <span class="code-keyword">new</span> <span class="code-function">Personne</span>(<span class="code-string">"Dupont"</span>, <span class="code-string">"Jean"</span>, <span class="code-string">30</span>);<br>
                        <span class="code-variable">$personne2</span> = <span class="code-keyword">new</span> <span class="code-function">Personne</span>(<span class="code-string">"Martin"</span>, <span class="code-string">"Marie"</span>, <span class="code-string">25</span>);<br><br>
                        <span class="code-comment">// Utiliser les méthodes</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne1</span>-><span class="code-function">saluer</span>(); <span class="code-comment">// "Bonjour, je suis Jean Dupont"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne1</span>->nom; <span class="code-comment">// "Dupont" (propriété publique)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne1</span>-><span class="code-function">getAge</span>(); <span class="code-comment">// 30 (via méthode)</span><br>
                        <span class="code-comment">// echo $personne1->age; // ERREUR: propriété privée</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔒 Visibilité des propriétés et méthodes</h3>
            <p>PHP offre trois niveaux de visibilité pour contrôler l'accès aux propriétés et méthodes :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><strong>public</strong> - Accessible partout (dans la classe, dans les classes enfants, et depuis l'extérieur)</li>
                <li><strong>private</strong> - Accessible uniquement dans la classe elle-même</li>
                <li><strong>protected</strong> - Accessible dans la classe et ses classes enfants (héritage)</li>
            </ul>

            <div class="example-box">
                <h3 style="color: #000;">Exemple de visibilité :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Exemple</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">public</span> <span class="code-variable">$public</span> = <span class="code-string">"Accessible partout"</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$private</span> = <span class="code-string">"Accessible uniquement ici"</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">protected</span> <span class="code-variable">$protected</span> = <span class="code-string">"Accessible ici et dans les enfants"</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">afficherPrivate</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->private; <span class="code-comment">// OK: dans la classe</span><br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-variable">$obj</span> = <span class="code-keyword">new</span> <span class="code-function">Exemple</span>();<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$obj</span>->public; <span class="code-comment">// OK: public</span><br>
                        <span class="code-comment">// echo $obj->private; // ERREUR: private</span><br>
                        <span class="code-comment">// echo $obj->protected; // ERREUR: protected</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$obj</span>-><span class="code-function">afficherPrivate</span>(); <span class="code-comment">// OK: via méthode publique</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🏗️ Constructeur et destructeur</h3>
            <p>Le constructeur (<code>__construct</code>) est appelé automatiquement lors de la création d'un objet. Le destructeur (<code>__destruct</code>) est appelé quand l'objet est détruit.</p>

            <div class="example-box">
                <h3 style="color: #000;">Constructeur et destructeur :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Utilisateur</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$email</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Constructeur</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$email</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->email = <span class="code-variable">$email</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Utilisateur créé: $nom&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Destructeur</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__destruct</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Utilisateur détruit: {$this->nom}&lt;br&gt;"</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getNom</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->nom;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-variable">$user</span> = <span class="code-keyword">new</span> <span class="code-function">Utilisateur</span>(<span class="code-string">"Jean"</span>, <span class="code-string">"jean@example.com"</span>);<br>
                        <span class="code-comment">// Affiche: "Utilisateur créé: Jean"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$user</span>-><span class="code-function">getNom</span>(); <span class="code-comment">// "Jean"</span><br>
                        <span class="code-comment">// Quand $user est détruit, affiche: "Utilisateur détruit: Jean"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🔧 Getters et Setters</h3>
            <p>Les getters et setters sont des méthodes pour accéder et modifier les propriétés privées. C'est une bonne pratique qui permet de contrôler l'accès aux données.</p>

            <div class="example-box">
                <h3 style="color: #000;">Getters et Setters :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Produit</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$prix</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$prix</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>-><span class="code-function">setNom</span>(<span class="code-variable">$nom</span>);<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>-><span class="code-function">setPrix</span>(<span class="code-variable">$prix</span>);<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Getter pour nom</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getNom</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->nom;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Setter pour nom (avec validation)</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">setNom</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-function">strlen</span>(<span class="code-variable">$nom</span>) < <span class="code-string">2</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">throw new</span> <span class="code-function">Exception</span>(<span class="code-string">"Le nom doit contenir au moins 2 caractères"</span>);<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Getter pour prix</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getPrix</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->prix;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Setter pour prix (avec validation)</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">setPrix</span>(<span class="code-variable">$prix</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">if</span> (<span class="code-variable">$prix</span> < <span class="code-string">0</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">throw new</span> <span class="code-function">Exception</span>(<span class="code-string">"Le prix ne peut pas être négatif"</span>);<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->prix = <span class="code-variable">$prix</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-variable">$produit</span> = <span class="code-keyword">new</span> <span class="code-function">Produit</span>(<span class="code-string">"Laptop"</span>, <span class="code-string">999.99</span>);<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$produit</span>-><span class="code-function">getNom</span>(); <span class="code-comment">// "Laptop"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$produit</span>-><span class="code-function">getPrix</span>(); <span class="code-comment">// 999.99</span><br>
                        <span class="code-variable">$produit</span>-><span class="code-function">setPrix</span>(<span class="code-string">899.99</span>); <span class="code-comment">// Modifier le prix</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>👨‍👩‍👧‍👦 Héritage</h3>
            <p>L'héritage permet à une classe (enfant) d'hériter des propriétés et méthodes d'une autre classe (parent). C'est très utile pour éviter la duplication de code.</p>

            <div class="example-box">
                <h3 style="color: #000;">Exemple d'héritage :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Classe parent</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Animal</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">protected</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">protected</span> <span class="code-variable">$espece</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$espece</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->espece = <span class="code-variable">$espece</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">manger</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"{$this->nom} mange"</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">dormir</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"{$this->nom} dort"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">// Classe enfant (hérite de Animal)</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Chien</span> <span class="code-keyword">extends</span> <span class="code-function">Animal</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">parent::__construct</span>(<span class="code-variable">$nom</span>, <span class="code-string">"Chien"</span>); <span class="code-comment">// Appeler le constructeur parent</span><br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Méthode spécifique au chien</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">aboyer</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"{$this->nom} aboie: Wouf Wouf !"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">// Classe enfant (hérite de Animal)</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Chat</span> <span class="code-keyword">extends</span> <span class="code-function">Animal</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-function">parent::__construct</span>(<span class="code-variable">$nom</span>, <span class="code-string">"Chat"</span>);<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Méthode spécifique au chat</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">miauler</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"{$this->nom} miaule: Miaou !"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">// Utilisation</span><br>
                        <span class="code-variable">$chien</span> = <span class="code-keyword">new</span> <span class="code-function">Chien</span>(<span class="code-string">"Rex"</span>);<br>
                        <span class="code-variable">$chat</span> = <span class="code-keyword">new</span> <span class="code-function">Chat</span>(<span class="code-string">"Mimi"</span>);<br><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$chien</span>-><span class="code-function">manger</span>(); <span class="code-comment">// "Rex mange" (hérité)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$chien</span>-><span class="code-function">aboyer</span>(); <span class="code-comment">// "Rex aboie: Wouf Wouf !" (spécifique)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$chat</span>-><span class="code-function">dormir</span>(); <span class="code-comment">// "Mimi dort" (hérité)</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$chat</span>-><span class="code-function">miauler</span>(); <span class="code-comment">// "Mimi miaule: Miaou !" (spécifique)</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Méthodes statiques</h3>
            <p>Les méthodes statiques appartiennent à la classe elle-même, pas à une instance. Elles sont appelées avec <code>ClassName::methodName()</code>.</p>

            <div class="example-box">
                <h3 style="color: #000;">Méthodes statiques :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Calculatrice</span> {<br>
                        &nbsp;&nbsp;<span class="code-comment">// Méthode statique</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">public static function</span> <span class="code-function">additionner</span>(<span class="code-variable">$a</span>, <span class="code-variable">$b</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$a</span> + <span class="code-variable">$b</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public static function</span> <span class="code-function">multiplier</span>(<span class="code-variable">$a</span>, <span class="code-variable">$b</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$a</span> * <span class="code-variable">$b</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-comment">// Appeler sans créer d'objet</span><br>
                        <span class="code-keyword">echo</span> <span class="code-function">Calculatrice</span>::<span class="code-function">additionner</span>(<span class="code-string">5</span>, <span class="code-string">3</span>); <span class="code-comment">// 8</span><br>
                        <span class="code-keyword">echo</span> <span class="code-function">Calculatrice</span>::<span class="code-function">multiplier</span>(<span class="code-string">4</span>, <span class="code-string">7</span>); <span class="code-comment">// 28</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h3>🎯 Cas d'usage pratique : Gestion d'utilisateurs</h3>
            <p>Voici un exemple complet utilisant la POO pour gérer des utilisateurs :</p>

            <div class="example-box">
                <h3 style="color: #000;">Système de gestion d'utilisateurs :</h3>
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Utilisateur</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$id</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$email</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$role</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$email</span>, <span class="code-variable">$role</span> = <span class="code-string">"user"</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->email = <span class="code-variable">$email</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->role = <span class="code-variable">$role</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getNom</span>() { <span class="code-keyword">return</span> <span class="code-variable">$this</span>->nom; }<br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getEmail</span>() { <span class="code-keyword">return</span> <span class="code-variable">$this</span>->email; }<br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">getRole</span>() { <span class="code-keyword">return</span> <span class="code-variable">$this</span>->role; }<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">estAdmin</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-variable">$this</span>->role === <span class="code-string">"admin"</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">presenter</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Je suis {$this->nom} ({$this->email}), rôle: {$this->role}"</span>;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-variable">$user1</span> = <span class="code-keyword">new</span> <span class="code-function">Utilisateur</span>(<span class="code-string">"Jean"</span>, <span class="code-string">"jean@example.com"</span>);<br>
                        <span class="code-variable">$admin</span> = <span class="code-keyword">new</span> <span class="code-function">Utilisateur</span>(<span class="code-string">"Admin"</span>, <span class="code-string">"admin@example.com"</span>, <span class="code-string">"admin"</span>);<br><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$user1</span>-><span class="code-function">presenter</span>(); <span class="code-comment">// "Je suis Jean (jean@example.com), rôle: user"</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$admin</span>-><span class="code-function">estAdmin</span>(); <span class="code-comment">// true</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez la POO pour organiser votre code en structures logiques. Les classes permettent de regrouper des fonctionnalités liées, de réutiliser du code via l'héritage, et de protéger les données avec l'encapsulation. C'est essentiel pour développer des applications PHP professionnelles !</p>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous avez maintenant une solide base en PHP.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Syntaxe PHP et variables</li>
                    <li>Types de données</li>
                    <li>Opérateurs et expressions</li>
                    <li>Structures conditionnelles</li>
                    <li>Boucles</li>
                    <li>Fonctions</li>
                    <li>Tableaux</li>
                    <li>Traitement de formulaires</li>
                    <li>Sessions et cookies</li>
                    <li>Connexion MySQL</li>
                    <li>PDO et requêtes préparées</li>
                    <li>Programmation Orientée Objet</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.bootstrap') }}" class="nav-btn">❮ Précédent: Bootstrap</a>
                <a href="{{ route('formations.git') }}" class="nav-btn">Suivant: Git ❯</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #777BB3, #5A5E8F) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(119, 123, 179, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #777BB3, #5A5E8F) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(119, 123, 179, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
