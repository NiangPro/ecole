@extends('layouts.app')

@section('title', 'Formation Git | DevFormation')

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
        padding: 25px;
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
        .content-wrapper {
            flex-direction: column;
        }
        .sidebar {
            width: 100%;
            min-width: 100%;
            position: relative;
            top: 0;
            height: auto;
            max-height: none;
        }
        .main-content {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="tutorial-header">
    <h1 style="font-size: 48px; margin-bottom: 10px;">Tutoriel Git</h1>
    <p style="font-size: 20px;">Maîtrisez le contrôle de version avec Git</p>
</div>

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>Git Tutorial</h3>
            <a href="#intro" class="active">Introduction Git</a>
            <a href="#install">Installation</a>
            <a href="#config">Configuration</a>
            <a href="#init">Créer un dépôt</a>
            <a href="#status">Status & Add</a>
            <a href="#commit">Commit</a>
            <a href="#branches">Branches</a>
            <a href="#merge">Merge</a>
            <a href="#remote">Remote</a>
            <a href="#push">Push & Pull</a>
            <a href="#clone">Clone</a>
            <a href="#github">GitHub</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à Git</h1>
            <p>Git est un système de contrôle de version distribué gratuit et open-source. Il permet de suivre les modifications de votre code et de collaborer avec d'autres développeurs.</p>

            <h3>🚀 Pourquoi utiliser Git ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Historique complet</strong> - Suivez toutes les modifications</li>
                <li>✅ <strong>Collaboration</strong> - Travaillez en équipe efficacement</li>
                <li>✅ <strong>Branches</strong> - Développez des fonctionnalités en parallèle</li>
                <li>✅ <strong>Sauvegarde</strong> - Ne perdez jamais votre code</li>
                <li>✅ <strong>Standard</strong> - Utilisé par tous les développeurs</li>
            </ul>

            <h2 id="install">📦 Installation</h2>
            <p>Téléchargez et installez Git depuis le site officiel.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Vérifier l'installation</span><br>
                        git <span class="code-flag">--version</span>
                    </code>
                </div>
            </div>

            <h2 id="config">⚙️ Configuration</h2>
            <p>Configurez votre nom et email avant de commencer.</p>

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

            <h2 id="init">🆕 Créer un dépôt</h2>
            <p>Initialisez un nouveau dépôt Git dans votre projet.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment"># Créer un nouveau dépôt</span><br>
                        git <span class="code-command">init</span><br><br>
                        <span class="code-comment"># Cela crée un dossier .git caché</span>
                    </code>
                </div>
            </div>

            <h2 id="status">📊 Status & Add</h2>
            <p>Vérifiez l'état de vos fichiers et ajoutez-les à la zone de staging.</p>

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

            <h2 id="commit">💾 Commit</h2>
            <p>Enregistrez vos modifications avec un message descriptif.</p>

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

            <h2 id="branches">🌿 Branches</h2>
            <p>Les branches permettent de développer des fonctionnalités en parallèle.</p>

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

            <h2 id="merge">🔀 Merge</h2>
            <p>Fusionnez les modifications d'une branche dans une autre.</p>

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

            <h2 id="remote">🌐 Remote</h2>
            <p>Connectez votre dépôt local à un dépôt distant (GitHub, GitLab).</p>

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

            <h2 id="push">⬆️ Push & Pull</h2>
            <p>Envoyez et récupérez des modifications depuis le dépôt distant.</p>

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

            <h2 id="clone">📥 Clone</h2>
            <p>Clonez un dépôt existant sur votre machine.</p>

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

            <h2 id="github">🐙 GitHub</h2>
            <p>GitHub est la plateforme d'hébergement Git la plus populaire.</p>

            <div class="example-box">
                <h3>Workflow typique</h3>
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

            <h2>🎓 Commandes essentielles</h2>
            <p>Récapitulatif des commandes Git les plus utilisées.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Commandes à connaître :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li><code>git init</code> - Initialiser un dépôt</li>
                    <li><code>git status</code> - Voir l'état des fichiers</li>
                    <li><code>git add</code> - Ajouter à la zone de staging</li>
                    <li><code>git commit -m</code> - Créer un commit</li>
                    <li><code>git branch</code> - Gérer les branches</li>
                    <li><code>git checkout</code> - Changer de branche</li>
                    <li><code>git merge</code> - Fusionner des branches</li>
                    <li><code>git push</code> - Envoyer vers le distant</li>
                    <li><code>git pull</code> - Récupérer du distant</li>
                    <li><code>git clone</code> - Cloner un dépôt</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.php') }}" class="nav-btn">❮ Précédent: PHP</a>
                <a href="{{ route('formations.wordpress') }}" class="nav-btn">Suivant: WordPress ❯</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/sidebar-sticky.js') }}"></script>
<script src="{{ asset('js/sidebar-navigation.js') }}"></script>
@endsection
