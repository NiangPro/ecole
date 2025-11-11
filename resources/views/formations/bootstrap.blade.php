@extends('layouts.app')

@section('title', 'Formation Bootstrap | DevFormation')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    html {
        overflow-x: hidden;
        scroll-behavior: smooth;
    }
    body {
        background-color: #fff !important;
        color: #000 !important;
        overflow-x: hidden !important;
    }
    .tutorial-header {
        background-color: #7952B3;
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
    }
    .content-wrapper {
        display: flex;
        gap: 20px;
        padding: 20px;
        width: 100%;
        margin: 0;
        position: relative;
    }
    .sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 25px;
        border-radius: 15px;
        min-width: 280px;
        position: sticky;
        top: 90px;
        height: fit-content;
        max-height: calc(100vh - 110px);
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(121, 82, 179, 0.2);
        z-index: 100;
        will-change: transform;
    }
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #7952B3 0%, #5A3D8A 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #5A3D8A 0%, #4A2F6F 100%);
    }
    .sidebar h3 {
        color: #7952B3;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(121, 82, 179, 0.2);
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
        background: #7952B3;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(121, 82, 179, 0.1) 0%, rgba(121, 82, 179, 0.05) 100%);
        color: #7952B3;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(121, 82, 179, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #7952B3 0%, #5A3D8A 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(121, 82, 179, 0.3);
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
        border-left: 4px solid #7952B3;
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
        border: 2px solid #7952B3;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(121, 82, 179, 0.1);
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
        content: 'Bootstrap';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #7952B3;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .code-tag {
        color: #61afef;
    }
    .code-attr {
        color: #d19a66;
    }
    .code-value {
        color: #98c379;
    }
    .code-class {
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
        background-color: #7952B3;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #5A3D8A;
        box-shadow: 0 4px 12px rgba(121, 82, 179, 0.3);
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
            background: linear-gradient(180deg, #7952B3 0%, #5A3D8A 100%);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #5A3D8A 0%, #4A2F6F 100%);
        }
        .sidebar h3 {
            color: #7952B3;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(121, 82, 179, 0.2);
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
            background: #7952B3;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            background: linear-gradient(135deg, rgba(121, 82, 179, 0.1) 0%, rgba(121, 82, 179, 0.05) 100%);
            color: #7952B3;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(121, 82, 179, 0.15);
        }
        .sidebar a:hover::before {
            transform: scaleY(1);
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #7952B3 0%, #5A3D8A 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(121, 82, 179, 0.3);
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
            border-left: 4px solid #7952B3;
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
            border: 2px solid #7952B3;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            word-wrap: break-word;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(121, 82, 179, 0.1);
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
            content: 'Bootstrap';
            position: absolute;
            top: 10px;
            right: 15px;
            background: #7952B3;
            color: white;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .code-tag {
            color: #61afef;
        }
        .code-attr {
            color: #d19a66;
        }
        .code-value {
            color: #98c379;
        }
        .code-class {
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
            background-color: #7952B3;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 600;
        }
        .nav-btn:hover {
            background-color: #5A3D8A;
            box-shadow: 0 4px 12px rgba(121, 82, 179, 0.3);
        }
        @media (max-width: 992px) {
            .content-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                min-width: 100%;
                position: static;
                top: auto;
                max-height: none;
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
    <h1 style="font-size: 48px; margin-bottom: 10px;">Tutoriel Bootstrap</h1>
    <p style="font-size: 20px;">Créez des sites responsive rapidement avec Bootstrap</p>
</div>

<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>Bootstrap Tutorial</h3>
            <a href="#intro" class="active">Introduction</a>
            <a href="#installation">Installation</a>
            <a href="#grid">Système de grille</a>
            <a href="#containers">Containers</a>
            <a href="#typography">Typographie</a>
            <a href="#colors">Couleurs</a>
            <a href="#buttons">Boutons</a>
            <a href="#navbar">Navbar</a>
            <a href="#cards">Cards</a>
            <a href="#forms">Formulaires</a>
            <a href="#modals">Modals</a>
            <a href="#utilities">Utilitaires</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à Bootstrap</h1>
            <p>Bootstrap est le framework CSS le plus populaire au monde pour créer des sites web responsive et mobile-first rapidement.</p>

            <h3>🚀 Pourquoi utiliser Bootstrap ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Gain de temps</strong> - Composants prêts à l'emploi</li>
                <li>✅ <strong>Responsive</strong> - Mobile-first par défaut</li>
                <li>✅ <strong>Cohérence</strong> - Design uniforme</li>
                <li>✅ <strong>Communauté</strong> - Documentation complète et support actif</li>
                <li>✅ <strong>Personnalisable</strong> - Facile à adapter à votre marque</li>
            </ul>

            <h2 id="installation">📦 Installation</h2>
            <p>Il existe plusieurs façons d'intégrer Bootstrap dans votre projet.</p>

            <div class="example-box">
                <h3>Via CDN (le plus simple)</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- CSS --&gt;</span><br>
                        <span class="code-tag">&lt;link</span> <span class="code-attr">href</span>=<span class="code-value">"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"</span> <span class="code-attr">rel</span>=<span class="code-value">"stylesheet"</span><span class="code-tag">&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- JavaScript Bundle --&gt;</span><br>
                        <span class="code-tag">&lt;script</span> <span class="code-attr">src</span>=<span class="code-value">"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"</span><span class="code-tag">&gt;&lt;/script&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="grid">📐 Système de grille</h2>
            <p>Le système de grille Bootstrap utilise Flexbox et permet de créer des layouts responsive avec 12 colonnes.</p>

            <div class="example-box">
                <h3>Structure de base</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"container"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"row"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"col-md-6"</span><span class="code-tag">&gt;</span>Colonne 1<span class="code-tag">&lt;/div&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"col-md-6"</span><span class="code-tag">&gt;</span>Colonne 2<span class="code-tag">&lt;/div&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Breakpoints</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>col-</code> - Extra small (&lt;576px)</li>
                <li><code>col-sm-</code> - Small (≥576px)</li>
                <li><code>col-md-</code> - Medium (≥768px)</li>
                <li><code>col-lg-</code> - Large (≥992px)</li>
                <li><code>col-xl-</code> - Extra large (≥1200px)</li>
                <li><code>col-xxl-</code> - Extra extra large (≥1400px)</li>
            </ul>

            <h2 id="containers">📦 Containers</h2>
            <p>Les containers sont les éléments de base pour contenir et aligner votre contenu.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Container fixe --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"container"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/div&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Container fluide (100% largeur) --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"container-fluid"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/div&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Container responsive --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"container-md"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="typography">✍️ Typographie</h2>
            <p>Bootstrap fournit des styles par défaut pour tous les éléments typographiques.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;h1</span> <span class="code-attr">class</span>=<span class="code-value">"display-1"</span><span class="code-tag">&gt;</span>Display 1<span class="code-tag">&lt;/h1&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"lead"</span><span class="code-tag">&gt;</span>Texte important<span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"text-muted"</span><span class="code-tag">&gt;</span>Texte atténué<span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;mark&gt;</span>Texte surligné<span class="code-tag">&lt;/mark&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="colors">🎨 Couleurs</h2>
            <p>Bootstrap propose des classes de couleurs contextuelles pour le texte et les arrière-plans.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Couleurs de texte --&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"text-primary"</span><span class="code-tag">&gt;</span>Texte primaire<span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"text-success"</span><span class="code-tag">&gt;</span>Texte succès<span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"text-danger"</span><span class="code-tag">&gt;</span>Texte danger<span class="code-tag">&lt;/p&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Arrière-plans --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"bg-primary text-white"</span><span class="code-tag">&gt;</span>Fond primaire<span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="buttons">🔘 Boutons</h2>
            <p>Bootstrap propose de nombreux styles de boutons prêts à l'emploi.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-primary"</span><span class="code-tag">&gt;</span>Primaire<span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-success"</span><span class="code-tag">&gt;</span>Succès<span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-outline-primary"</span><span class="code-tag">&gt;</span>Outline<span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-lg"</span><span class="code-tag">&gt;</span>Grand<span class="code-tag">&lt;/button&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="navbar">🧭 Navbar</h2>
            <p>La navbar Bootstrap est responsive et personnalisable.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;nav</span> <span class="code-attr">class</span>=<span class="code-value">"navbar navbar-expand-lg navbar-dark bg-dark"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"container-fluid"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;a</span> <span class="code-attr">class</span>=<span class="code-value">"navbar-brand"</span> <span class="code-attr">href</span>=<span class="code-value">"#"</span><span class="code-tag">&gt;</span>Logo<span class="code-tag">&lt;/a&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"navbar-toggler"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/button&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;/nav&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="cards">🃏 Cards</h2>
            <p>Les cards sont des conteneurs flexibles pour afficher du contenu.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"card"</span> <span class="code-attr">style</span>=<span class="code-value">"width: 18rem;"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"..."</span> <span class="code-attr">class</span>=<span class="code-value">"card-img-top"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"card-body"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;h5</span> <span class="code-attr">class</span>=<span class="code-value">"card-title"</span><span class="code-tag">&gt;</span>Titre<span class="code-tag">&lt;/h5&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;p</span> <span class="code-attr">class</span>=<span class="code-value">"card-text"</span><span class="code-tag">&gt;</span>Contenu...<span class="code-tag">&lt;/p&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"#"</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-primary"</span><span class="code-tag">&gt;</span>Bouton<span class="code-tag">&lt;/a&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="forms">📝 Formulaires</h2>
            <p>Bootstrap stylise automatiquement les éléments de formulaire.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;form&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"mb-3"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;label</span> <span class="code-attr">class</span>=<span class="code-value">"form-label"</span><span class="code-tag">&gt;</span>Email<span class="code-tag">&lt;/label&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"email"</span> <span class="code-attr">class</span>=<span class="code-value">"form-control"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/div&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span class="code-value">"btn btn-primary"</span><span class="code-tag">&gt;</span>Envoyer<span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;/form&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="modals">🪟 Modals</h2>
            <p>Les modals sont des fenêtres de dialogue qui s'affichent au-dessus du contenu.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Bouton déclencheur --&gt;</span><br>
                        <span class="code-tag">&lt;button</span> <span class="code-attr">data-bs-toggle</span>=<span class="code-value">"modal"</span> <span class="code-attr">data-bs-target</span>=<span class="code-value">"#myModal"</span><span class="code-tag">&gt;</span>Ouvrir<span class="code-tag">&lt;/button&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Modal --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"modal fade"</span> <span class="code-attr">id</span>=<span class="code-value">"myModal"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"modal-dialog"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"modal-content"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/div&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="utilities">🛠️ Classes utilitaires</h2>
            <p>Bootstrap offre des classes utilitaires pour le spacing, display, flexbox, etc.</p>

            <div class="example-box">
                <h3>Spacing (margin et padding)</h3>
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- m = margin, p = padding --&gt;</span><br>
                        <span class="code-comment">&lt;!-- t/b/s/e = top/bottom/start/end --&gt;</span><br>
                        <span class="code-comment">&lt;!-- 0-5 = taille (0 à 3rem) --&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"mt-3"</span><span class="code-tag">&gt;</span>Margin top 3<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"p-4"</span><span class="code-tag">&gt;</span>Padding 4<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"mx-auto"</span><span class="code-tag">&gt;</span>Centré<span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <div class="example-box">
                <h3>Display et Flexbox</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"d-flex justify-content-between"</span><span class="code-tag">&gt;</span>...<span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">class</span>=<span class="code-value">"d-none d-md-block"</span><span class="code-tag">&gt;</span>Visible sur MD+<span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous maîtrisez maintenant les bases de Bootstrap.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Installation et configuration de Bootstrap</li>
                    <li>Système de grille responsive</li>
                    <li>Containers et breakpoints</li>
                    <li>Typographie et couleurs</li>
                    <li>Boutons et composants</li>
                    <li>Navbar et navigation</li>
                    <li>Cards pour le contenu</li>
                    <li>Formulaires stylisés</li>
                    <li>Modals interactifs</li>
                    <li>Classes utilitaires</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.javascript') }}" class="nav-btn">❮ Précédent: JavaScript</a>
                <a href="{{ route('formations.php') }}" class="nav-btn">Suivant: PHP ❯</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('h1[id], h2[id]');
    const navLinks = document.querySelectorAll('.sidebar a');
    
    function highlightActiveSection() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - 100)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', highlightActiveSection);
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 90,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endsection
