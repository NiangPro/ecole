@extends('layouts.app')

@section('title', 'Formation HTML5 | DevFormation')

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
        background-color: #04AA6D;
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
        border: 1px solid rgba(4, 170, 109, 0.1);
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
        background: linear-gradient(180deg, #04AA6D 0%, #038f5a 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #038f5a 0%, #027049 100%);
    }
    .sidebar h3 {
        color: #04AA6D;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(4, 170, 109, 0.2);
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
        background: #04AA6D;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.1) 0%, rgba(4, 170, 109, 0.05) 100%);
        color: #04AA6D;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(4, 170, 109, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #04AA6D 0%, #038f5a 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(4, 170, 109, 0.3);
        transform: translateX(5px);
    }
    .sidebar a.active::before {
        transform: scaleY(1);
        background: white;
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
        border-left: 4px solid #04AA6D;
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
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(4, 170, 109, 0.1);
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
        content: 'HTML';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #04AA6D;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .code-box code {
        color: #e2e8f0;
        line-height: 1.6;
    }
    .code-tag {
        color: #f472b6;
    }
    .code-attr {
        color: #60a5fa;
    }
    .code-value {
        color: #fbbf24;
    }
    .code-text {
        color: #a3e635;
    }
    .code-comment {
        color: #94a3b8;
        font-style: italic;
    }
    .try-it-btn {
        background-color: #04AA6D;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: all 0.3s;
    }
    .try-it-btn:hover {
        background-color: #059862;
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
        background-color: #04AA6D;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
    }
    .nav-btn:hover {
        background-color: #059862;
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
    }
</style>
@endsection

@section('content')
<!-- Content -->
<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>HTML5 Tutorial</h3>
            <a href="#intro" class="active">Introduction HTML5</a>
            <a href="#editors">Éditeurs HTML</a>
            <a href="#basic">HTML Basique</a>
            <a href="#elements">Éléments HTML</a>
            <a href="#attributes">Attributs HTML</a>
            <a href="#headings">Titres HTML</a>
            <a href="#paragraphs">Paragraphes</a>
            <a href="#styles">Styles CSS</a>
            <a href="#formatting">Formatage Texte</a>
            <a href="#quotations">Citations</a>
            <a href="#comments">Commentaires</a>
            <a href="#colors">Couleurs</a>
            <a href="#links">Liens Hypertextes</a>
            <a href="#images">Images</a>
            <a href="#tables">Tableaux</a>
            <a href="#lists">Listes</a>
            <a href="#forms">Formulaires</a>
            <a href="#semantic">HTML Sémantique</a>
            <a href="#media">Audio & Vidéo</a>
            <a href="#canvas">Canvas</a>
            <a href="#svg">SVG</a>
            <a href="#apis">APIs HTML5</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à HTML5</h1>
            <p>HTML5 est la dernière version du langage HTML (HyperText Markup Language). C'est le langage standard pour créer et structurer le contenu des pages web.</p>

            <h3>🚀 Pourquoi apprendre HTML5 ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Essentiel pour le développement web</strong> - C'est la base de tous les sites web</li>
                <li>✅ <strong>Facile à apprendre</strong> - Syntaxe simple et intuitive</li>
                <li>✅ <strong>Très demandé</strong> - Compétence recherchée par les employeurs</li>
                <li>✅ <strong>Compatible</strong> - Fonctionne sur tous les navigateurs modernes</li>
                <li>✅ <strong>Gratuit</strong> - Aucun logiciel payant nécessaire</li>
            </ul>

            <h2 id="editors">💻 Éditeurs HTML</h2>
            <p>Pour écrire du code HTML, vous avez besoin d'un éditeur de texte. Voici les meilleurs éditeurs pour débuter :</p>
            
            <h3>Éditeurs recommandés :</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Visual Studio Code</strong> - Gratuit, puissant, extensions nombreuses</li>
                <li>✅ <strong>Sublime Text</strong> - Rapide et léger</li>
                <li>✅ <strong>Atom</strong> - Open source et personnalisable</li>
                <li>✅ <strong>Notepad++</strong> - Simple et efficace (Windows)</li>
                <li>✅ <strong>Brackets</strong> - Spécialisé pour le web</li>
            </ul>

            <div class="example-box">
                <h3>Installation de VS Code</h3>
                <p>1. Téléchargez VS Code depuis <a href="https://code.visualstudio.com" target="_blank" class="text-cyan-600 hover:underline">code.visualstudio.com</a></p>
                <p>2. Installez l'extension "Live Server" pour prévisualiser vos pages</p>
                <p>3. Créez un fichier avec l'extension <code>.html</code></p>
                <p>4. Tapez <code>!</code> puis <code>Tab</code> pour générer une structure HTML5</p>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Astuce :</strong> VS Code offre l'auto-complétion, la coloration syntaxique et le débogage intégré. C'est l'éditeur le plus populaire chez les développeurs web !</p>
            </div>

            <h2 id="basic">📚 HTML Basique</h2>
            <p>Comprendre les concepts de base est essentiel pour maîtriser HTML5.</p>

            <h3>Structure minimale d'une page HTML</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;!DOCTYPE html&gt;</span><br>
                        <span class="code-tag">&lt;html</span> <span class="code-attr">lang</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">charset</span>=<span class="code-value">"UTF-8"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;title&gt;</span><span class="code-text">Ma Page</span><span class="code-tag">&lt;/title&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span><br>
                        <span class="code-tag">&lt;body&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-comment">&lt;!-- Votre contenu ici --&gt;</span><br>
                        <span class="code-tag">&lt;/body&gt;</span><br>
                        <span class="code-tag">&lt;/html&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Les balises de base</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>&lt;!DOCTYPE html&gt;</code> - Déclare le type de document</li>
                <li><code>&lt;html&gt;</code> - Conteneur principal</li>
                <li><code>&lt;head&gt;</code> - Métadonnées (non visibles)</li>
                <li><code>&lt;body&gt;</code> - Contenu visible de la page</li>
                <li><code>&lt;meta&gt;</code> - Informations sur la page</li>
                <li><code>&lt;title&gt;</code> - Titre dans l'onglet du navigateur</li>
            </ul>

            <h2>📝 Votre premier document HTML5</h2>
            <p>Voici la structure de base d'un document HTML5 :</p>
            
            <div class="example-box">
                <h3>Exemple - Structure HTML5</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;!DOCTYPE html&gt;</span><br>
                        <span class="code-tag">&lt;html</span> <span class="code-attr">lang</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">charset</span>=<span class="code-value">"UTF-8"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;meta</span> <span class="code-attr">name</span>=<span class="code-value">"viewport"</span> <span class="code-attr">content</span>=<span class="code-value">"width=device-width, initial-scale=1.0"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;title&gt;</span><span class="code-text">Ma Première Page HTML5</span><span class="code-tag">&lt;/title&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span><br>
                        <span class="code-tag">&lt;body&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;h1&gt;</span><span class="code-text">Bienvenue sur ma page !</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est mon premier paragraphe HTML5.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;/body&gt;</span><br>
                        <span class="code-tag">&lt;/html&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>💡 Explication :</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li><code>&lt;!DOCTYPE html&gt;</code> - Déclare que c'est un document HTML5</li>
                    <li><code>&lt;html&gt;</code> - Élément racine de la page</li>
                    <li><code>&lt;head&gt;</code> - Contient les métadonnées (titre, encodage, etc.)</li>
                    <li><code>&lt;body&gt;</code> - Contient le contenu visible de la page</li>
                </ul>
            </div>

            <h2 id="elements">🧩 Les Éléments HTML</h2>
            <p>Un élément HTML est défini par une <strong>balise d'ouverture</strong>, du <strong>contenu</strong>, et une <strong>balise de fermeture</strong> :</p>
            
            <div class="code-box">
                <code>
                    <span class="code-tag">&lt;nombalise&gt;</span><span class="code-text">Le contenu va ici...</span><span class="code-tag">&lt;/nombalise&gt;</span>
                </code>
            </div>

            <h3>Éléments HTML courants :</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;h1&gt;</span><span class="code-text">Titre principal</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        <span class="code-tag">&lt;h2&gt;</span><span class="code-text">Sous-titre</span><span class="code-tag">&lt;/h2&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Un paragraphe de texte.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://example.com"</span><span class="code-tag">&gt;</span><span class="code-text">Un lien</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"image.jpg"</span> <span class="code-attr">alt</span>=<span class="code-value">"Description"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;div&gt;</span><span class="code-text">Un conteneur</span><span class="code-tag">&lt;/div&gt;</span><br>
                        <span class="code-tag">&lt;span&gt;</span><span class="code-text">Un élément en ligne</span><span class="code-tag">&lt;/span&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="attributes">⚙️ Les Attributs HTML</h2>
            <p>Les attributs fournissent des informations supplémentaires sur les éléments HTML. Ils sont toujours spécifiés dans la balise d'ouverture.</p>
            
            <div class="example-box">
                <h3>Exemple d'attributs</h3>
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://devformation.com"</span> <span class="code-attr">target</span>=<span class="code-value">"_blank"</span> <span class="code-attr">title</span>=<span class="code-value">"Visitez DevFormation"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">Cliquez ici</span><br>
                        <span class="code-tag">&lt;/a&gt;</span><br><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"logo.png"</span> <span class="code-attr">alt</span>=<span class="code-value">"Logo DevFormation"</span> <span class="code-attr">width</span>=<span class="code-value">"200"</span> <span class="code-attr">height</span>=<span class="code-value">"100"</span><span class="code-tag">&gt;</span><br><br>
                        <span class="code-tag">&lt;div</span> <span class="code-attr">id</span>=<span class="code-value">"header"</span> <span class="code-attr">class</span>=<span class="code-value">"container"</span><span class="code-tag">&gt;</span><span class="code-text">Contenu</span><span class="code-tag">&lt;/div&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Attributs les plus utilisés :</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>href</code> - Spécifie l'URL d'un lien</li>
                <li><code>src</code> - Spécifie la source d'une image</li>
                <li><code>alt</code> - Texte alternatif pour les images</li>
                <li><code>id</code> - Identifiant unique d'un élément</li>
                <li><code>class</code> - Classe CSS pour styliser l'élément</li>
                <li><code>style</code> - Style CSS inline</li>
                <li><code>title</code> - Information supplémentaire (tooltip)</li>
            </ul>

            <h2 id="headings">📌 Les Titres HTML</h2>
            <p>HTML propose 6 niveaux de titres, de <code>&lt;h1&gt;</code> à <code>&lt;h6&gt;</code> :</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;h1&gt;</span><span class="code-text">Titre de niveau 1 (le plus important)</span><span class="code-tag">&lt;/h1&gt;</span><br>
                        <span class="code-tag">&lt;h2&gt;</span><span class="code-text">Titre de niveau 2</span><span class="code-tag">&lt;/h2&gt;</span><br>
                        <span class="code-tag">&lt;h3&gt;</span><span class="code-text">Titre de niveau 3</span><span class="code-tag">&lt;/h3&gt;</span><br>
                        <span class="code-tag">&lt;h4&gt;</span><span class="code-text">Titre de niveau 4</span><span class="code-tag">&lt;/h4&gt;</span><br>
                        <span class="code-tag">&lt;h5&gt;</span><span class="code-text">Titre de niveau 5</span><span class="code-tag">&lt;/h5&gt;</span><br>
                        <span class="code-tag">&lt;h6&gt;</span><span class="code-text">Titre de niveau 6 (le moins important)</span><span class="code-tag">&lt;/h6&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>⚠️ Important :</strong> Utilisez <code>&lt;h1&gt;</code> pour le titre principal de la page (un seul par page). Les moteurs de recherche utilisent les titres pour indexer le contenu.</p>
            </div>

            <h2 id="paragraphs">📄 Les Paragraphes</h2>
            <p>L'élément <code>&lt;p&gt;</code> définit un paragraphe. Les navigateurs ajoutent automatiquement un espace avant et après chaque paragraphe.</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est un paragraphe.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Ceci est un autre paragraphe.</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Les paragraphes peuvent contenir </span><span class="code-tag">&lt;strong&gt;</span><span class="code-text">du texte en gras</span><span class="code-tag">&lt;/strong&gt;</span><span class="code-text"> et </span><span class="code-tag">&lt;em&gt;</span><span class="code-text">en italique</span><span class="code-tag">&lt;/em&gt;</span><span class="code-text">.</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="styles">🎨 Styles CSS</h2>
            <p>Vous pouvez ajouter du style à vos éléments HTML de trois façons différentes :</p>

            <h3>1. Style inline (dans la balise)</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: blue; font-size: 20px;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>2. Style interne (dans le &lt;head&gt;)</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;style&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">p { color: red; }</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/style&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span>
                    </code>
                </div>
            </div>

            <h3>3. Style externe (fichier CSS séparé)</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;head&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;link</span> <span class="code-attr">rel</span>=<span class="code-value">"stylesheet"</span> <span class="code-attr">href</span>=<span class="code-value">"styles.css"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;/head&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>💡 Bonne pratique :</strong> Utilisez toujours un fichier CSS externe pour faciliter la maintenance et la réutilisation du code.</p>
            </div>

            <h2 id="formatting">✨ Formatage du Texte</h2>
            <p>HTML propose plusieurs balises pour formater le texte :</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;b&gt;</span><span class="code-text">Texte en gras</span><span class="code-tag">&lt;/b&gt;</span><br>
                        <span class="code-tag">&lt;strong&gt;</span><span class="code-text">Texte important (gras)</span><span class="code-tag">&lt;/strong&gt;</span><br>
                        <span class="code-tag">&lt;i&gt;</span><span class="code-text">Texte en italique</span><span class="code-tag">&lt;/i&gt;</span><br>
                        <span class="code-tag">&lt;em&gt;</span><span class="code-text">Texte accentué (italique)</span><span class="code-tag">&lt;/em&gt;</span><br>
                        <span class="code-tag">&lt;mark&gt;</span><span class="code-text">Texte surligné</span><span class="code-tag">&lt;/mark&gt;</span><br>
                        <span class="code-tag">&lt;small&gt;</span><span class="code-text">Texte plus petit</span><span class="code-tag">&lt;/small&gt;</span><br>
                        <span class="code-tag">&lt;del&gt;</span><span class="code-text">Texte supprimé</span><span class="code-tag">&lt;/del&gt;</span><br>
                        <span class="code-tag">&lt;ins&gt;</span><span class="code-text">Texte inséré</span><span class="code-tag">&lt;/ins&gt;</span><br>
                        <span class="code-tag">&lt;sub&gt;</span><span class="code-text">Texte en indice</span><span class="code-tag">&lt;/sub&gt;</span><br>
                        <span class="code-tag">&lt;sup&gt;</span><span class="code-text">Texte en exposant</span><span class="code-tag">&lt;/sup&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="quotations">💬 Citations et Quotations</h2>
            <p>HTML propose plusieurs balises pour afficher des citations et des références.</p>

            <h3>Citation courte (&lt;q&gt;)</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Einstein a dit : </span><span class="code-tag">&lt;q&gt;</span><span class="code-text">L'imagination est plus importante que le savoir</span><span class="code-tag">&lt;/q&gt;</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Citation longue (&lt;blockquote&gt;)</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;blockquote</span> <span class="code-attr">cite</span>=<span class="code-value">"https://source.com"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">Ceci est une citation longue qui sera affichée avec une indentation.</span><br>
                        <span class="code-tag">&lt;/blockquote&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Autres balises utiles</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;abbr</span> <span class="code-attr">title</span>=<span class="code-value">"HyperText Markup Language"</span><span class="code-tag">&gt;</span><span class="code-text">HTML</span><span class="code-tag">&lt;/abbr&gt;</span><br>
                        <span class="code-tag">&lt;cite&gt;</span><span class="code-text">Le Petit Prince</span><span class="code-tag">&lt;/cite&gt;</span><br>
                        <span class="code-tag">&lt;address&gt;</span><span class="code-text">123 Rue Example, Dakar</span><span class="code-tag">&lt;/address&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="comments">📝 Commentaires HTML</h2>
            <p>Les commentaires permettent d'ajouter des notes dans votre code qui ne seront pas affichées dans le navigateur.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Ceci est un commentaire sur une ligne --&gt;</span><br><br>
                        <span class="code-comment">&lt;!--</span><br>
                        <span class="code-comment">&nbsp;&nbsp;Ceci est un commentaire</span><br>
                        <span class="code-comment">&nbsp;&nbsp;sur plusieurs lignes</span><br>
                        <span class="code-comment">--&gt;</span><br><br>
                        <span class="code-tag">&lt;p&gt;</span><span class="code-text">Contenu visible</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-comment">&lt;!-- &lt;p&gt;Contenu commenté (caché)&lt;/p&gt; --&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p><strong>💡 Utilité des commentaires :</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li>Expliquer le code complexe</li>
                    <li>Désactiver temporairement du code</li>
                    <li>Marquer des sections importantes</li>
                    <li>Collaborer avec d'autres développeurs</li>
                </ul>
            </div>

            <h2 id="colors">🌈 Couleurs HTML</h2>
            <p>HTML supporte plusieurs formats pour définir les couleurs.</p>

            <h3>1. Noms de couleurs</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: red;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte rouge</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: blue;"</span><span class="code-tag">&gt;</span><span class="code-text">Texte bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>2. Code hexadécimal</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #FF0000;"</span><span class="code-tag">&gt;</span><span class="code-text">Rouge</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #00FF00;"</span><span class="code-tag">&gt;</span><span class="code-text">Vert</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: #0000FF;"</span><span class="code-tag">&gt;</span><span class="code-text">Bleu</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h3>3. RGB et RGBA</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: rgb(255, 0, 0);"</span><span class="code-tag">&gt;</span><span class="code-text">Rouge RGB</span><span class="code-tag">&lt;/p&gt;</span><br>
                        <span class="code-tag">&lt;p</span> <span class="code-attr">style</span>=<span class="code-value">"color: rgba(0, 0, 255, 0.5);"</span><span class="code-tag">&gt;</span><span class="code-text">Bleu transparent</span><span class="code-tag">&lt;/p&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="links">🔗 Les Liens Hypertextes</h2>
            <p>Les liens permettent de naviguer entre les pages. Utilisez la balise <code>&lt;a&gt;</code> avec l'attribut <code>href</code> :</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"https://devformation.com"</span><span class="code-tag">&gt;</span><span class="code-text">Visitez DevFormation</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"contact.html"</span><span class="code-tag">&gt;</span><span class="code-text">Page de contact</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"#section1"</span><span class="code-tag">&gt;</span><span class="code-text">Aller à la section 1</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"mailto:contact@devformation.com"</span><span class="code-tag">&gt;</span><span class="code-text">Envoyez un email</span><span class="code-tag">&lt;/a&gt;</span><br>
                        <span class="code-tag">&lt;a</span> <span class="code-attr">href</span>=<span class="code-value">"tel:+221783123657"</span><span class="code-tag">&gt;</span><span class="code-text">Appelez-nous</span><span class="code-tag">&lt;/a&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="images">🖼️ Les Images HTML</h2>
            <p>L'élément <code>&lt;img&gt;</code> permet d'insérer des images dans une page web. L'attribut <code>src</code> spécifie le chemin de l'image et <code>alt</code> fournit un texte alternatif.</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"photo.jpg"</span> <span class="code-attr">alt</span>=<span class="code-value">"Description de l'image"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;img</span> <span class="code-attr">src</span>=<span class="code-value">"logo.png"</span> <span class="code-attr">alt</span>=<span class="code-value">"Logo"</span> <span class="code-attr">width</span>=<span class="code-value">"300"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="tables">📊 Les Tableaux HTML</h2>
            <p>Les tableaux HTML permettent d'organiser et d'afficher des données structurées en lignes et colonnes. Ils sont essentiels pour présenter des informations tabulaires de manière claire et organisée.</p>

            <h3>Structure d'un tableau</h3>
            <p>Un tableau HTML est composé de plusieurs éléments :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>&lt;table&gt;</code> - Conteneur principal du tableau</li>
                <li><code>&lt;thead&gt;</code> - En-tête du tableau (optionnel mais recommandé)</li>
                <li><code>&lt;tbody&gt;</code> - Corps du tableau contenant les données</li>
                <li><code>&lt;tfoot&gt;</code> - Pied du tableau (optionnel)</li>
                <li><code>&lt;tr&gt;</code> - Ligne du tableau (Table Row)</li>
                <li><code>&lt;th&gt;</code> - Cellule d'en-tête (Table Header)</li>
                <li><code>&lt;td&gt;</code> - Cellule de données (Table Data)</li>
            </ul>

            <h3>Exemple de tableau complet</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;table</span> <span class="code-attr">border</span>=<span class="code-value">"1"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;thead&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Nom</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Âge</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;th&gt;</span><span class="code-text">Ville</span><span class="code-tag">&lt;/th&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/thead&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;tbody&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Jean Dupont</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">25</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Dakar</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;tr&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Marie Martin</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">30</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;td&gt;</span><span class="code-text">Thiès</span><span class="code-tag">&lt;/td&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/tr&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/tbody&gt;</span><br>
                        <span class="code-tag">&lt;/table&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Attributs importants des tableaux</h3>
            <div class="example-box">
                <h4 style="color: #000;">Fusion de cellules</h4>
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Fusion horizontale (colspan) --&gt;</span><br>
                        <span class="code-tag">&lt;td</span> <span class="code-attr">colspan</span>=<span class="code-value">"2"</span><span class="code-tag">&gt;</span><span class="code-text">Cette cellule occupe 2 colonnes</span><span class="code-tag">&lt;/td&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Fusion verticale (rowspan) --&gt;</span><br>
                        <span class="code-tag">&lt;td</span> <span class="code-attr">rowspan</span>=<span class="code-value">"3"</span><span class="code-tag">&gt;</span><span class="code-text">Cette cellule occupe 3 lignes</span><span class="code-tag">&lt;/td&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonnes pratiques :</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li>Utilisez toujours <code>&lt;thead&gt;</code> et <code>&lt;tbody&gt;</code> pour une meilleure structure</li>
                    <li>Les <code>&lt;th&gt;</code> rendent le tableau plus accessible aux lecteurs d'écran</li>
                    <li>Ajoutez un attribut <code>caption</code> pour décrire le tableau</li>
                    <li>Utilisez CSS pour le style plutôt que les attributs HTML obsolètes</li>
                </ul>
            </div>

            <h2 id="lists">📝 Les Listes HTML</h2>
            <p>HTML propose trois types de listes pour organiser et présenter des informations de manière structurée. Chaque type de liste a son utilité spécifique.</p>

            <h3>1. Liste non ordonnée (&lt;ul&gt;)</h3>
            <p>Utilisée pour des éléments sans ordre particulier. Par défaut, les éléments sont affichés avec des puces.</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">HTML5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">CSS3</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">JavaScript</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ul&gt;</span>
                    </code>
                </div>
                <p style="color: #000; margin-top: 10px;"><strong>Résultat :</strong> • HTML5 • CSS3 • JavaScript</p>
            </div>

            <h3>2. Liste ordonnée (&lt;ol&gt;)</h3>
            <p>Utilisée pour des éléments qui suivent un ordre spécifique. Les éléments sont numérotés automatiquement.</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ol&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Ouvrir l'éditeur</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Écrire le code HTML</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Enregistrer le fichier</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Ouvrir dans le navigateur</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ol&gt;</span>
                    </code>
                </div>
                <p style="color: #000; margin-top: 10px;"><strong>Résultat :</strong> 1. Ouvrir l'éditeur 2. Écrire le code HTML 3. Enregistrer...</p>
            </div>

            <h3>Attributs des listes ordonnées</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Type de numérotation --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"A"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- A, B, C... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"a"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- a, b, c... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"I"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- I, II, III... --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">type</span>=<span class="code-value">"i"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- i, ii, iii... --&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Commencer à un numéro spécifique --&gt;</span><br>
                        <span class="code-tag">&lt;ol</span> <span class="code-attr">start</span>=<span class="code-value">"5"</span><span class="code-tag">&gt;</span><span class="code-text"> &lt;!-- Commence à 5 --&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Élément 5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Élément 6</span><span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ol&gt;</span>
                    </code>
                </div>
            </div>

            <h3>3. Liste de définition (&lt;dl&gt;)</h3>
            <p>Utilisée pour afficher des termes et leurs définitions, comme un glossaire.</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;dl&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dt&gt;</span><span class="code-text">HTML</span><span class="code-tag">&lt;/dt&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dd&gt;</span><span class="code-text">Langage de balisage pour créer des pages web</span><span class="code-tag">&lt;/dd&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dt&gt;</span><span class="code-text">CSS</span><span class="code-tag">&lt;/dt&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;dd&gt;</span><span class="code-text">Langage de style pour la présentation des pages web</span><span class="code-tag">&lt;/dd&gt;</span><br>
                        <span class="code-tag">&lt;/dl&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Listes imbriquées</h3>
            <p>Vous pouvez créer des listes à plusieurs niveaux en imbriquant des listes :</p>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Technologies Front-end</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">HTML5</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">CSS3</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">JavaScript</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Technologies Back-end</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;ul&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">PHP</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;li&gt;</span><span class="code-text">Python</span><span class="code-tag">&lt;/li&gt;</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;/ul&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;/li&gt;</span><br>
                        <span class="code-tag">&lt;/ul&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Quand utiliser chaque type :</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li><strong>&lt;ul&gt;</strong> - Liste de courses, caractéristiques d'un produit, menu de navigation</li>
                    <li><strong>&lt;ol&gt;</strong> - Instructions étape par étape, classements, recettes de cuisine</li>
                    <li><strong>&lt;dl&gt;</strong> - Glossaires, FAQ, métadonnées</li>
                </ul>
            </div>

            <h2 id="forms">📋 Les Formulaires HTML</h2>
            <p>Les formulaires HTML permettent aux utilisateurs d'interagir avec votre site en saisissant et soumettant des données. Essentiels pour inscriptions, connexions, recherches et commentaires.</p>

            <h3>Structure de base</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;form</span> <span class="code-attr">action</span>=<span class="code-value">"/traitement.php"</span> <span class="code-attr">method</span>=<span class="code-value">"post"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;label</span> <span class="code-attr">for</span>=<span class="code-value">"nom"</span><span class="code-tag">&gt;</span><span class="code-text">Nom:</span><span class="code-tag">&lt;/label&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"text"</span> <span class="code-attr">id</span>=<span class="code-value">"nom"</span> <span class="code-attr">name</span>=<span class="code-value">"nom"</span> <span class="code-attr">required</span><span class="code-tag">&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;label</span> <span class="code-attr">for</span>=<span class="code-value">"email"</span><span class="code-tag">&gt;</span><span class="code-text">Email:</span><span class="code-tag">&lt;/label&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;input</span> <span class="code-attr">type</span>=<span class="code-value">"email"</span> <span class="code-attr">id</span>=<span class="code-value">"email"</span> <span class="code-attr">name</span>=<span class="code-value">"email"</span><span class="code-tag">&gt;</span><br><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;button</span> <span class="code-attr">type</span>=<span class="code-value">"submit"</span><span class="code-tag">&gt;</span><span class="code-text">Envoyer</span><span class="code-tag">&lt;/button&gt;</span><br>
                        <span class="code-tag">&lt;/form&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Types de champs input</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li><code>text</code> - Texte simple</li>
                <li><code>email</code> - Adresse email (validation automatique)</li>
                <li><code>password</code> - Mot de passe (masqué)</li>
                <li><code>number</code> - Nombres uniquement</li>
                <li><code>tel</code> - Numéro de téléphone</li>
                <li><code>date</code> - Sélecteur de date</li>
                <li><code>checkbox</code> - Cases à cocher</li>
                <li><code>radio</code> - Boutons radio</li>
                <li><code>file</code> - Upload de fichiers</li>
            </ul>

            <h3>Autres éléments de formulaire</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-comment">&lt;!-- Zone de texte multiligne --&gt;</span><br>
                        <span class="code-tag">&lt;textarea</span> <span class="code-attr">name</span>=<span class="code-value">"message"</span> <span class="code-attr">rows</span>=<span class="code-value">"5"</span><span class="code-tag">&gt;&lt;/textarea&gt;</span><br><br>
                        <span class="code-comment">&lt;!-- Liste déroulante --&gt;</span><br>
                        <span class="code-tag">&lt;select</span> <span class="code-attr">name</span>=<span class="code-value">"pays"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;option</span> <span class="code-attr">value</span>=<span class="code-value">"sn"</span><span class="code-tag">&gt;</span><span class="code-text">Sénégal</span><span class="code-tag">&lt;/option&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;option</span> <span class="code-attr">value</span>=<span class="code-value">"fr"</span><span class="code-tag">&gt;</span><span class="code-text">France</span><span class="code-tag">&lt;/option&gt;</span><br>
                        <span class="code-tag">&lt;/select&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonnes pratiques :</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; color: #000;">
                    <li>Toujours associer un <code>&lt;label&gt;</code> à chaque champ avec l'attribut <code>for</code></li>
                    <li>Utiliser <code>placeholder</code> pour des exemples de saisie</li>
                    <li>Ajouter <code>required</code> pour les champs obligatoires</li>
                    <li>Utiliser les bons types d'input pour la validation automatique</li>
                </ul>
            </div>

            <h2 id="media">🎬 Audio et Vidéo HTML5</h2>
            <p>HTML5 permet d'intégrer facilement des médias audio et vidéo sans plugins externes.</p>

            <h3>Vidéo HTML5</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;video</span> <span class="code-attr">width</span>=<span class="code-value">"640"</span> <span class="code-attr">height</span>=<span class="code-value">"360"</span> <span class="code-attr">controls</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;source</span> <span class="code-attr">src</span>=<span class="code-value">"video.mp4"</span> <span class="code-attr">type</span>=<span class="code-value">"video/mp4"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">Votre navigateur ne supporte pas la vidéo.</span><br>
                        <span class="code-tag">&lt;/video&gt;</span>
                    </code>
                </div>
            </div>

            <h3>Audio HTML5</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;audio</span> <span class="code-attr">controls</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;source</span> <span class="code-attr">src</span>=<span class="code-value">"audio.mp3"</span> <span class="code-attr">type</span>=<span class="code-value">"audio/mpeg"</span><span class="code-tag">&gt;</span><br>
                        <span class="code-tag">&lt;/audio&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="canvas">🎨 Canvas HTML5</h2>
            <p>L'élément <code>&lt;canvas&gt;</code> permet de dessiner des graphiques dynamiques avec JavaScript.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;canvas</span> <span class="code-attr">id</span>=<span class="code-value">"monCanvas"</span> <span class="code-attr">width</span>=<span class="code-value">"400"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;&lt;/canvas&gt;</span><br>
                        <span class="code-tag">&lt;script&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">const ctx = document.getElementById('monCanvas').getContext('2d');</span><br>
                        &nbsp;&nbsp;<span class="code-text">ctx.fillStyle = '#04AA6D';</span><br>
                        &nbsp;&nbsp;<span class="code-text">ctx.fillRect(20, 20, 150, 100);</span><br>
                        <span class="code-tag">&lt;/script&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="svg">🖌️ SVG</h2>
            <p>SVG permet de créer des graphiques vectoriels qui s'adaptent à toutes les tailles.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;svg</span> <span class="code-attr">width</span>=<span class="code-value">"200"</span> <span class="code-attr">height</span>=<span class="code-value">"200"</span><span class="code-tag">&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;circle</span> <span class="code-attr">cx</span>=<span class="code-value">"100"</span> <span class="code-attr">cy</span>=<span class="code-value">"100"</span> <span class="code-attr">r</span>=<span class="code-value">"80"</span> <span class="code-attr">fill</span>=<span class="code-value">"#04AA6D"</span><span class="code-tag">/&gt;</span><br>
                        <span class="code-tag">&lt;/svg&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="apis">⚡ APIs HTML5</h2>
            <p>HTML5 introduit de nombreuses APIs JavaScript pour créer des applications web puissantes.</p>

            <h3>Geolocation API</h3>
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;script&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-text">navigator.geolocation.getCurrentPosition(function(pos) {</span><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-text">console.log(pos.coords.latitude);</span><br>
                        &nbsp;&nbsp;<span class="code-text">});</span><br>
                        <span class="code-tag">&lt;/script&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="semantic">🏗️ HTML Sémantique</h2>
            <p>HTML5 introduit des balises sémantiques qui donnent du sens au contenu.</p>
            
            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-tag">&lt;header&gt;</span><span class="code-text">En-tête de la page</span><span class="code-tag">&lt;/header&gt;</span><br>
                        <span class="code-tag">&lt;nav&gt;</span><span class="code-text">Menu de navigation</span><span class="code-tag">&lt;/nav&gt;</span><br>
                        <span class="code-tag">&lt;main&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;article&gt;</span><span class="code-text">Contenu principal</span><span class="code-tag">&lt;/article&gt;</span><br>
                        &nbsp;&nbsp;<span class="code-tag">&lt;aside&gt;</span><span class="code-text">Contenu secondaire</span><span class="code-tag">&lt;/aside&gt;</span><br>
                        <span class="code-tag">&lt;/main&gt;</span><br>
                        <span class="code-tag">&lt;footer&gt;</span><span class="code-text">Pied de page</span><span class="code-tag">&lt;/footer&gt;</span>
                    </code>
                </div>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Maintenant que vous connaissez les bases, explorez les autres chapitres dans le menu de gauche pour approfondir vos connaissances en HTML5 !</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>La structure de base d'un document HTML5</li>
                    <li>Les éléments et balises HTML</li>
                    <li>Les attributs HTML</li>
                    <li>Les titres et paragraphes</li>
                    <li>Le formatage du texte</li>
                    <li>Les liens hypertextes</li>
                    <li>Les images</li>
                    <li>Les tableaux avec fusion de cellules</li>
                    <li>Les listes (ordonnées, non ordonnées, définition)</li>
                    <li>Les formulaires et leurs éléments</li>
                    <li>HTML Sémantique</li>
                    <li>Audio et Vidéo HTML5</li>
                    <li>Canvas et SVG</li>
                    <li>Les APIs HTML5</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('home') }}" class="nav-btn">❮ Accueil</a>
                <a href="{{ route('formations.css3') }}" class="nav-btn">Suivant: CSS3 ❯</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/sidebar-sticky.js') }}"></script>
<script src="{{ asset('js/sidebar-navigation.js') }}"></script>
@endsection
