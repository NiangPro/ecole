@extends('layouts.app')

@section('title', 'Formation IA | DevFormation')

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
        background: linear-gradient(135deg, #14B8A6 0%, #06B6D4 100%);
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
        border: 1px solid rgba(20, 184, 166, 0.2);
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
        background: linear-gradient(180deg, #14B8A6 0%, #0D9488 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #0D9488 0%, #0F766E 100%);
    }
    .sidebar h3 {
        color: #14B8A6;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(20, 184, 166, 0.2);
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
        background: #14B8A6;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(20, 184, 166, 0.05) 100%);
        color: #14B8A6;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(20, 184, 166, 0.3);
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
        background: rgba(20, 184, 166, 0.1) !important;
        border: 2px solid rgba(20, 184, 166, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(20, 184, 166, 0.2) !important;
        border-color: rgba(20, 184, 166, 0.5) !important;
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
        background: linear-gradient(135deg, #14B8A6, #0D9488);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(20, 184, 166, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #0D9488, #0F766E);
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
        border-left: 4px solid #14B8A6;
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
        border: 2px solid #14B8A6;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(20, 184, 166, 0.1);
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
        content: 'AI';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #14B8A6;
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
        background: linear-gradient(135deg, #14B8A6 0%, #06B6D4 100%);
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background: linear-gradient(135deg, #0D9488 0%, #0891B2 100%);
        box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
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
            background: linear-gradient(180deg, #14B8A6 0%, #0D9488 100%);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #0D9488 0%, #0F766E 100%);
        }
        .sidebar h3 {
            color: #14B8A6;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(20, 184, 166, 0.2);
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
            background: #14B8A6;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(20, 184, 166, 0.05) 100%);
            color: #14B8A6;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
        }
        .sidebar a:hover::before {
            transform: scaleY(1);
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(20, 184, 166, 0.3);
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
            border-left: 4px solid #14B8A6;
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
            border: 2px solid #14B8A6;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            word-wrap: break-word;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(20, 184, 166, 0.1);
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
            content: 'AI';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #14B8A6;
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
            background: linear-gradient(135deg, #14B8A6 0%, #06B6D4 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .nav-btn:hover {
            background: linear-gradient(135deg, #0D9488 0%, #0891B2 100%);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
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
    <h1 style="font-size: 48px; margin-bottom: 10px;">Tutoriel Intelligence Artificielle</h1>
    <p style="font-size: 20px;">Découvrez l'IA et ses applications</p>
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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(20, 184, 166, 0.2);">
                <h3 style="margin: 0;">IA Tutorial</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #14B8A6; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="Fermer le menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="#intro" class="active">Introduction IA</a>
            <a href="#concepts">Concepts de base</a>
            <a href="#ml">Machine Learning</a>
            <a href="#dl">Deep Learning</a>
            <a href="#nlp">NLP</a>
            <a href="#cv">Computer Vision</a>
            <a href="#python">Python pour l'IA</a>
            <a href="#tensorflow">TensorFlow</a>
            <a href="#pytorch">PyTorch</a>
            <a href="#models">Modèles pré-entraînés</a>
            <a href="#apis">APIs IA</a>
            <a href="#ethics">Éthique</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à l'Intelligence Artificielle</h1>
            <p>L'Intelligence Artificielle (IA) est la simulation de l'intelligence humaine par des machines. Elle transforme tous les secteurs, du développement web à la santé.</p>

            <h3>🚀 Pourquoi apprendre l'IA ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Demande croissante</strong> - Marché en pleine expansion</li>
                <li>✅ <strong>Automatisation</strong> - Automatisez des tâches complexes</li>
                <li>✅ <strong>Innovation</strong> - Créez des solutions intelligentes</li>
                <li>✅ <strong>Salaires élevés</strong> - Compétences très recherchées</li>
                <li>✅ <strong>Futur</strong> - Technologie du futur</li>
            </ul>

            <h2 id="concepts">🧠 Concepts de base</h2>
            <p>Comprendre les concepts fondamentaux de l'IA.</p>

            <div class="example-box">
                <h3>Types d'IA</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>IA faible</strong> - Spécialisée dans une tâche (Siri, Alexa)</li>
                    <li><strong>IA forte</strong> - Intelligence générale (théorique)</li>
                    <li><strong>IA super</strong> - Dépasse l'intelligence humaine (futur)</li>
                </ul>
            </div>

            <h2 id="ml">🤖 Machine Learning</h2>
            <p>Le Machine Learning permet aux machines d'apprendre à partir de données sans être explicitement programmées.</p>

            <div class="example-box">
                <h3>Types d'apprentissage</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Supervisé</strong> - Apprendre avec des données étiquetées</li>
                    <li><strong>Non supervisé</strong> - Trouver des patterns dans les données</li>
                    <li><strong>Par renforcement</strong> - Apprendre par essai-erreur</li>
                </ul>
            </div>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">from</span> sklearn.linear_model <span class="code-keyword">import</span> <span class="code-function">LinearRegression</span><br><br>
                        <span class="code-comment"># Créer un modèle</span><br>
                        <span class="code-variable">model</span> = <span class="code-function">LinearRegression</span>()<br><br>
                        <span class="code-comment"># Entraîner le modèle</span><br>
                        <span class="code-variable">model</span>.<span class="code-function">fit</span>(<span class="code-variable">X_train</span>, <span class="code-variable">y_train</span>)<br><br>
                        <span class="code-comment"># Faire des prédictions</span><br>
                        <span class="code-variable">predictions</span> = <span class="code-variable">model</span>.<span class="code-function">predict</span>(<span class="code-variable">X_test</span>)
                    </code>
                </div>
            </div>

            <h2 id="dl">🧬 Deep Learning</h2>
            <p>Le Deep Learning utilise des réseaux de neurones profonds pour résoudre des problèmes complexes.</p>

            <div class="example-box">
                <h3>Architectures populaires</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>CNN</strong> - Réseaux convolutifs (images)</li>
                    <li><strong>RNN</strong> - Réseaux récurrents (séquences)</li>
                    <li><strong>Transformers</strong> - Attention mechanism (NLP)</li>
                    <li><strong>GAN</strong> - Réseaux génératifs adverses</li>
                </ul>
            </div>

            <h2 id="nlp">💬 NLP (Natural Language Processing)</h2>
            <p>Le traitement du langage naturel permet aux machines de comprendre et générer du texte.</p>

            <div class="example-box">
                <h3>Applications NLP</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Chatbots</strong> - Assistants conversationnels</li>
                    <li><strong>Traduction</strong> - Google Translate</li>
                    <li><strong>Sentiment Analysis</strong> - Analyser les émotions</li>
                    <li><strong>Résumé automatique</strong> - Synthétiser du texte</li>
                    <li><strong>GPT</strong> - Génération de texte</li>
                </ul>
            </div>

            <h2 id="cv">👁️ Computer Vision</h2>
            <p>La vision par ordinateur permet aux machines de "voir" et interpréter les images.</p>

            <div class="example-box">
                <h3>Applications</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Reconnaissance faciale</strong> - Déverrouillage de téléphone</li>
                    <li><strong>Détection d'objets</strong> - Voitures autonomes</li>
                    <li><strong>OCR</strong> - Lecture de texte dans les images</li>
                    <li><strong>Segmentation</strong> - Isoler des objets</li>
                </ul>
            </div>

            <h2 id="python">🐍 Python pour l'IA</h2>
            <p>Python est le langage de prédilection pour l'IA grâce à ses bibliothèques puissantes.</p>

            <div class="example-box">
                <h3>Bibliothèques essentielles</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>NumPy</strong> - Calcul numérique</li>
                    <li><strong>Pandas</strong> - Manipulation de données</li>
                    <li><strong>Matplotlib</strong> - Visualisation</li>
                    <li><strong>Scikit-learn</strong> - Machine Learning</li>
                    <li><strong>TensorFlow/PyTorch</strong> - Deep Learning</li>
                </ul>
            </div>

            <h2 id="tensorflow">🔥 TensorFlow</h2>
            <p>TensorFlow est une bibliothèque open-source de Google pour le Deep Learning.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">import</span> tensorflow <span class="code-keyword">as</span> tf<br><br>
                        <span class="code-comment"># Créer un modèle séquentiel</span><br>
                        <span class="code-variable">model</span> = tf.keras.<span class="code-function">Sequential</span>([<br>
                        &nbsp;&nbsp;tf.keras.layers.<span class="code-function">Dense</span>(<span class="code-string">128</span>, activation=<span class="code-string">'relu'</span>),<br>
                        &nbsp;&nbsp;tf.keras.layers.<span class="code-function">Dense</span>(<span class="code-string">10</span>, activation=<span class="code-string">'softmax'</span>)<br>
                        ])<br><br>
                        <span class="code-comment"># Compiler</span><br>
                        <span class="code-variable">model</span>.<span class="code-function">compile</span>(optimizer=<span class="code-string">'adam'</span>, loss=<span class="code-string">'sparse_categorical_crossentropy'</span>)
                    </code>
                </div>
            </div>

            <h2 id="pytorch">⚡ PyTorch</h2>
            <p>PyTorch est une bibliothèque de Facebook, très populaire en recherche.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">import</span> torch<br>
                        <span class="code-keyword">import</span> torch.nn <span class="code-keyword">as</span> nn<br><br>
                        <span class="code-comment"># Définir un réseau</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Net</span>(nn.Module):<br>
                        &nbsp;&nbsp;<span class="code-keyword">def</span> <span class="code-function">__init__</span>(<span class="code-variable">self</span>):<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">self</span>.fc1 = nn.<span class="code-function">Linear</span>(<span class="code-string">784</span>, <span class="code-string">128</span>)<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">self</span>.fc2 = nn.<span class="code-function">Linear</span>(<span class="code-string">128</span>, <span class="code-string">10</span>)
                    </code>
                </div>
            </div>

            <h2 id="models">📦 Modèles pré-entraînés</h2>
            <p>Utilisez des modèles déjà entraînés pour gagner du temps.</p>

            <div class="example-box">
                <h3>Modèles populaires</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>BERT</strong> - Compréhension du langage</li>
                    <li><strong>GPT</strong> - Génération de texte</li>
                    <li><strong>ResNet</strong> - Classification d'images</li>
                    <li><strong>YOLO</strong> - Détection d'objets</li>
                    <li><strong>Stable Diffusion</strong> - Génération d'images</li>
                </ul>
            </div>

            <h2 id="apis">🌐 APIs IA</h2>
            <p>Intégrez l'IA dans vos applications via des APIs.</p>

            <div class="example-box">
                <h3>APIs populaires</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>OpenAI API</strong> - GPT, DALL-E</li>
                    <li><strong>Google Cloud AI</strong> - Vision, NLP</li>
                    <li><strong>AWS AI</strong> - Rekognition, Comprehend</li>
                    <li><strong>Hugging Face</strong> - Modèles transformers</li>
                </ul>
            </div>

            <h2 id="ethics">⚖️ Éthique de l'IA</h2>
            <p>L'IA soulève des questions éthiques importantes.</p>

            <div class="example-box">
                <h3>Considérations éthiques</h3>
                <ul style="line-height: 2; color: #000;">
                    <li><strong>Biais</strong> - Éviter la discrimination</li>
                    <li><strong>Transparence</strong> - Expliquer les décisions</li>
                    <li><strong>Vie privée</strong> - Protéger les données</li>
                    <li><strong>Responsabilité</strong> - Qui est responsable ?</li>
                    <li><strong>Impact social</strong> - Emploi et société</li>
                </ul>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous avez découvert les bases de l'IA.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Introduction à l'IA</li>
                    <li>Concepts fondamentaux</li>
                    <li>Machine Learning</li>
                    <li>Deep Learning</li>
                    <li>NLP (traitement du langage)</li>
                    <li>Computer Vision</li>
                    <li>Python pour l'IA</li>
                    <li>TensorFlow et PyTorch</li>
                    <li>Modèles pré-entraînés</li>
                    <li>APIs IA</li>
                    <li>Éthique de l'IA</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.wordpress') }}" class="nav-btn">❮ Précédent: WordPress</a>
                <a href="{{ route('home') }}" class="nav-btn">Accueil ❯</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #14B8A6, #0D9488) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #14B8A6, #0D9488) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
