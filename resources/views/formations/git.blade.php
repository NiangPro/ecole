@extends('layouts.app')

@section('title', trans('app.formations.git.title') . ' | NiangProgrammeur')

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
        background-color: #F05032;
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
        border: 1px solid rgba(240, 80, 50, 0.2);
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
        background: linear-gradient(180deg, #F05032 0%, #C73D26 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #C73D26 0%, #A32E1D 100%);
    }
    .sidebar h3 {
        color: #F05032;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(240, 80, 50, 0.2);
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
        background: #F05032;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(240, 80, 50, 0.1) 0%, rgba(240, 80, 50, 0.05) 100%);
        color: #F05032;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(240, 80, 50, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #F05032 0%, #C73D26 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(240, 80, 50, 0.3);
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
        background: rgba(240, 80, 50, 0.1) !important;
        border: 2px solid rgba(240, 80, 50, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(240, 80, 50, 0.2) !important;
        border-color: rgba(240, 80, 50, 0.5) !important;
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
        background: linear-gradient(135deg, #F05032, #C73D26);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(240, 80, 50, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(240, 80, 50, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #C73D26, #A32E1D);
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
        border-left: 4px solid #F05032;
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
        border: 2px solid #F05032;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(240, 80, 50, 0.1);
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
        content: 'Git';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #F05032;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    /* Bouton de copie - Même taille que le label Git */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 80px;
        background: #F05032;
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
        background: #D43A1F;
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
    .code-command {
        color: #61afef;
    }
    .code-flag {
        color: #c678dd;
    }
    .code-string {
        color: #98c379;
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
        background-color: #F05032;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #C73D26;
        box-shadow: 0 4px 12px rgba(240, 80, 50, 0.3);
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
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px;">{{ trans('app.formations.git.title') }}</h1>
    <p style="font-size: 20px;">{{ trans('app.formations.git.subtitle') }}</p>
</div>

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.git.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(240, 80, 50, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.git.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #F05032; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.git.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'install', 'config', 'init', 'status', 'commit', 'branches', 'merge', 'remote', 'push', 'clone', 'github'];
            @endphp
            @foreach(trans('app.formations.git.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.git.intro_title') }}</h1>
            <p>{{ trans('app.formations.git.intro_text') }}</p>

            <h3>{{ trans('app.formations.git.why_title') }}</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.git.why_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h2 id="install">{{ trans('app.formations.git.install_title') }}</h2>
            <p>{{ trans('app.formations.git.install_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Vérifier l'installation</span><br>
                        git <span class="code-flag">--version</span>
                    </code>
                </div>
            </div>

            <h2 id="config">{{ trans('app.formations.git.config_title') }}</h2>
            <p>{{ trans('app.formations.git.config_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Configurer votre identité</span><br>
                        git <span class="code-command">config</span> <span class="code-flag">--global</span> user.name <span class="code-string">"Votre Nom"</span><br>
                        git <span class="code-command">config</span> <span class="code-flag">--global</span> user.email <span class="code-string">"email@example.com"</span><br><br>
                        <span class="code-comment"># Vérifier la configuration</span><br>
                        git <span class="code-command">config</span> <span class="code-flag">--list</span>
                    </code>
                </div>
            </div>

            <h2 id="init">{{ trans('app.formations.git.init_title') }}</h2>
            <p>{{ trans('app.formations.git.init_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Créer un nouveau dépôt</span><br>
                        git <span class="code-command">init</span><br><br>
                        <span class="code-comment"># Cela crée un dossier .git caché</span>
                    </code>
                </div>
            </div>

            <h2 id="status">{{ trans('app.formations.git.status_title') }}</h2>
            <p>{{ trans('app.formations.git.status_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Voir l'état des fichiers</span><br>
                        git <span class="code-command">status</span><br><br>
                        <span class="code-comment"># Ajouter un fichier spécifique</span><br>
                        git <span class="code-command">add</span> <span class="code-string">fichier.txt</span><br><br>
                        <span class="code-comment"># Ajouter tous les fichiers</span><br>
                        git <span class="code-command">add</span> <span class="code-flag">.</span>
                    </code>
                </div>
            </div>

            <h2 id="commit">{{ trans('app.formations.git.commit_title') }}</h2>
            <p>{{ trans('app.formations.git.commit_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Créer un commit</span><br>
                        git <span class="code-command">commit</span> <span class="code-flag">-m</span> <span class="code-string">"Message du commit"</span><br><br>
                        <span class="code-comment"># Voir l'historique des commits</span><br>
                        git <span class="code-command">log</span><br><br>
                        <span class="code-comment"># Historique condensé</span><br>
                        git <span class="code-command">log</span> <span class="code-flag">--oneline</span>
                    </code>
                </div>
            </div>

            <h2 id="branches">{{ trans('app.formations.git.branches_title') }}</h2>
            <p>{{ trans('app.formations.git.branches_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Lister les branches</span><br>
                        git <span class="code-command">branch</span><br><br>
                        <span class="code-comment"># Créer une nouvelle branche</span><br>
                        git <span class="code-command">branch</span> <span class="code-string">nouvelle-feature</span><br><br>
                        <span class="code-comment"># Changer de branche</span><br>
                        git <span class="code-command">checkout</span> <span class="code-string">nouvelle-feature</span><br><br>
                        <span class="code-comment"># Créer et changer en une seule commande</span><br>
                        git <span class="code-command">checkout</span> <span class="code-flag">-b</span> <span class="code-string">nouvelle-feature</span>
                    </code>
                </div>
            </div>

            <h2 id="merge">{{ trans('app.formations.git.merge_title') }}</h2>
            <p>{{ trans('app.formations.git.merge_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Se placer sur la branche de destination</span><br>
                        git <span class="code-command">checkout</span> <span class="code-string">main</span><br><br>
                        <span class="code-comment"># Fusionner une branche</span><br>
                        git <span class="code-command">merge</span> <span class="code-string">nouvelle-feature</span><br><br>
                        <span class="code-comment"># Supprimer une branche</span><br>
                        git <span class="code-command">branch</span> <span class="code-flag">-d</span> <span class="code-string">nouvelle-feature</span>
                    </code>
                </div>
            </div>

            <h2 id="remote">{{ trans('app.formations.git.remote_title') }}</h2>
            <p>{{ trans('app.formations.git.remote_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Ajouter un dépôt distant</span><br>
                        git <span class="code-command">remote add</span> <span class="code-string">origin</span> https://github.com/user/repo.git<br><br>
                        <span class="code-comment"># Voir les dépôts distants</span><br>
                        git <span class="code-command">remote</span> <span class="code-flag">-v</span>
                    </code>
                </div>
            </div>

            <h2 id="push">{{ trans('app.formations.git.push_title') }}</h2>
            <p>{{ trans('app.formations.git.push_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Envoyer vers le dépôt distant</span><br>
                        git <span class="code-command">push</span> <span class="code-string">origin</span> <span class="code-string">main</span><br><br>
                        <span class="code-comment"># Première fois (définir upstream)</span><br>
                        git <span class="code-command">push</span> <span class="code-flag">-u</span> <span class="code-string">origin</span> <span class="code-string">main</span><br><br>
                        <span class="code-comment"># Récupérer les modifications</span><br>
                        git <span class="code-command">pull</span> <span class="code-string">origin</span> <span class="code-string">main</span>
                    </code>
                </div>
            </div>

            <h2 id="clone">{{ trans('app.formations.git.clone_title') }}</h2>
            <p>{{ trans('app.formations.git.clone_text') }}</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Cloner un dépôt</span><br>
                        git <span class="code-command">clone</span> https://github.com/user/repo.git<br><br>
                        <span class="code-comment"># Cloner dans un dossier spécifique</span><br>
                        git <span class="code-command">clone</span> https://github.com/user/repo.git <span class="code-string">mon-dossier</span>
                    </code>
                </div>
            </div>

            <h2 id="github">{{ trans('app.formations.git.github_title') }}</h2>
            <p>{{ trans('app.formations.git.github_text') }}</p>

            <div class="example-box">
                <h3>{{ trans('app.formations.git.github_workflow_title') }}</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment"># 1. Cloner le projet</span><br>
                        git <span class="code-command">clone</span> https://github.com/user/repo.git<br><br>
                        <span class="code-comment"># 2. Créer une branche</span><br>
                        git <span class="code-command">checkout</span> <span class="code-flag">-b</span> <span class="code-string">ma-feature</span><br><br>
                        <span class="code-comment"># 3. Faire des modifications et commit</span><br>
                        git <span class="code-command">add</span> <span class="code-flag">.</span><br>
                        git <span class="code-command">commit</span> <span class="code-flag">-m</span> <span class="code-string">"Ajout de ma feature"</span><br><br>
                        <span class="code-comment"># 4. Pousser vers GitHub</span><br>
                        git <span class="code-command">push</span> <span class="code-string">origin</span> <span class="code-string">ma-feature</span><br><br>
                        <span class="code-comment"># 5. Créer une Pull Request sur GitHub</span>
                    </code>
                </div>
            </div>

            <h2>{{ trans('app.formations.git.commands_title') }}</h2>
            <p>{{ trans('app.formations.git.commands_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.git.commands_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.git.commands_learned_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $command = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><code>{{ $command }}</code>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.php') }}" class="nav-btn">{{ trans('app.formations.git.nav_previous') }}</a>
                <a href="{{ route('formations.wordpress') }}" class="nav-btn">{{ trans('app.formations.git.nav_next') }}</a>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #F05032, #C73D26) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(240, 80, 50, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #F05032, #C73D26) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(240, 80, 50, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
