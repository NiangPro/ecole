@extends('layouts.app')

@section('title', 'Formation Python | NiangProgrammeur')

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
        background-color: #3776ab;
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
        border: 1px solid rgba(55, 118, 171, 0.2);
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
        background: linear-gradient(180deg, #3776ab 0%, #2d5f8a 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2d5f8a 0%, #244a6f 100%);
    }
    .sidebar h3 {
        color: #3776ab;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(55, 118, 171, 0.2);
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
        background: #3776ab;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #3776ab;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.15);
    }
    .sidebar a:hover::before {
        transform: scaleY(1);
    }
    .sidebar a.active {
        background: linear-gradient(135deg, #3776ab 0%, #2d5f8a 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(55, 118, 171, 0.3);
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
        border-left: 4px solid #3776ab;
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
        border: 2px solid #3776ab;
        padding: 20px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        word-wrap: break-word;
        margin: 15px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 20px rgba(55, 118, 171, 0.1);
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
        content: 'Python';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #3776ab;
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
        background-color: #3776ab;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        font-weight: 600;
    }
    .nav-btn:hover {
        background-color: #2d5f8a;
        box-shadow: 0 4px 12px rgba(55, 118, 171, 0.3);
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
<div class="tutorial-header">
    <h1 style="margin: 0; font-size: 48px; font-weight: 800;">
        <i class="fab fa-python" style="margin-right: 15px;"></i>
        Formation Python
    </h1>
    <p style="font-size: 20px; margin-top: 15px; opacity: 0.9;">
        Apprenez Python, le langage de programmation polyvalent et puissant
    </p>
</div>

<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>Python Tutorial</h3>
            <a href="#intro" class="active">Introduction Python</a>
            <a href="#syntax">Syntaxe</a>
            <a href="#variables">Variables</a>
            <a href="#datatypes">Types de données</a>
            <a href="#operators">Opérateurs</a>
            <a href="#conditions">Conditions</a>
            <a href="#loops">Boucles</a>
            <a href="#functions">Fonctions</a>
            <a href="#lists">Listes & Dictionnaires</a>
            <a href="#modules">Modules</a>
            <a href="#oop">Programmation Orientée Objet</a>
            <a href="#files">Fichiers</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">Introduction à Python</h1>
            <p>Python est un langage de programmation de haut niveau, interprété et polyvalent, créé par Guido van Rossum et publié pour la première fois en 1991. Python est aujourd'hui l'un des langages les plus populaires au monde, utilisé dans de nombreux domaines : développement web, data science, intelligence artificielle, automatisation, et bien plus encore.</p>

            <h3>🐍 Qu'est-ce que Python ?</h3>
            <p>Python est un langage de programmation <strong>interprété</strong> et <strong>orienté objet</strong>. Contrairement aux langages compilés comme C++ ou Java, Python exécute le code ligne par ligne, ce qui le rend plus facile à déboguer et à tester. Sa syntaxe simple et lisible le rend idéal pour les débutants comme pour les experts.</p>

            <div class="example-box">
                <h3 style="color: #000;">💡 Pourquoi Python est si populaire ?</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li>Syntaxe simple et lisible - Le code Python ressemble presque à de l'anglais</li>
                    <li>Polyvalent - Utilisé pour le web, la data science, l'IA, l'automatisation</li>
                    <li>Vaste bibliothèque standard - Des milliers de modules disponibles</li>
                    <li>Communauté active - Des millions de développeurs dans le monde</li>
                    <li>Open-source et gratuit - Aucun coût de licence</li>
                    <li>Multi-plateforme - Fonctionne sur Windows, Linux, macOS</li>
                </ol>
            </div>

            <h3>🚀 Pourquoi apprendre Python ?</h3>
            <p>Python est un excellent choix pour débuter en programmation pour plusieurs raisons :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Facile à apprendre</strong> - Syntaxe claire et intuitive, parfaite pour les débutants</li>
                <li>✅ <strong>Polyvalent</strong> - Développement web (Django, Flask), data science (Pandas, NumPy), IA (TensorFlow, PyTorch), automatisation</li>
                <li>✅ <strong>Très demandé</strong> - L'un des langages les plus recherchés sur le marché du travail</li>
                <li>✅ <strong>Gratuit et Open-Source</strong> - Aucun coût, multiplateforme</li>
                <li>✅ <strong>Vaste écosystème</strong> - Des milliers de bibliothèques disponibles via pip</li>
                <li>✅ <strong>Grande communauté</strong> - Support et ressources abondantes</li>
                <li>✅ <strong>Utilisé par les géants</strong> - Google, Facebook, Netflix, Instagram, Spotify utilisent Python</li>
            </ul>

            <h3>📋 Prérequis pour apprendre Python</h3>
            <p>Python est si simple que vous pouvez commencer sans aucune expérience préalable ! Cependant, avoir des connaissances de base en :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Informatique de base</strong> - Savoir utiliser un ordinateur</li>
                <li>⚠️ <strong>Logique</strong> - Comprendre les concepts de base (variables, conditions, boucles) est utile mais pas obligatoire</li>
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note importante :</strong> Python est installé par défaut sur Linux et macOS. Pour Windows, vous pouvez télécharger Python depuis <a href="https://www.python.org/downloads/" target="_blank" style="color: #3776ab;">python.org</a>. Vous pouvez aussi utiliser un IDE comme PyCharm, VS Code, ou simplement l'interpréteur Python en ligne de commande.</p>
            </div>

            <h3>🎯 Cas d'usage de Python</h3>
            <p>Python est utilisé dans de nombreux domaines :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>🌐 <strong>Développement web</strong> - Django, Flask pour créer des sites web et API</li>
                <li>📊 <strong>Data Science</strong> - Analyse de données, visualisation avec Pandas, Matplotlib</li>
                <li>🤖 <strong>Intelligence Artificielle</strong> - Machine Learning, Deep Learning avec TensorFlow, PyTorch</li>
                <li>🔧 <strong>Automatisation</strong> - Scripts pour automatiser des tâches répétitives</li>
                <li>📱 <strong>Applications desktop</strong> - Tkinter, PyQt pour créer des interfaces graphiques</li>
                <li>🎮 <strong>Développement de jeux</strong> - Pygame pour créer des jeux vidéo</li>
                <li>🌐 <strong>Scraping web</strong> - BeautifulSoup, Scrapy pour extraire des données de sites web</li>
            </ul>

            <h2 id="syntax">📝 Syntaxe de base</h2>
            <p>La syntaxe Python est simple et lisible. Python utilise l'indentation (espaces ou tabulations) pour définir les blocs de code, contrairement à d'autres langages qui utilisent des accolades.</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Premier programme Python</span>
<span class="code-function">print</span>(<span class="code-string">"Bonjour, monde !"</span>)

<span class="code-comment"># Variables</span>
<span class="code-variable">nom</span> = <span class="code-string">"NiangProgrammeur"</span>
<span class="code-variable">age</span> = <span class="code-number">25</span>

<span class="code-function">print</span>(<span class="code-string">f"Je m'appelle {nom} et j'ai {age} ans"</span>)
                </code>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">💡 Points importants sur la syntaxe Python :</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li>Python est <strong>sensible à l'indentation</strong> - Utilisez 4 espaces (recommandé) ou des tabulations de manière cohérente</li>
                    <li>Les commentaires commencent par <code>#</code></li>
                    <li>Pas besoin de point-virgule à la fin des lignes</li>
                    <li>Les chaînes de caractères peuvent utiliser des guillemets simples <code>'</code> ou doubles <code>"</code></li>
                </ul>
            </div>

            <h2 id="variables">🔤 Variables</h2>
            <p>En Python, les variables sont créées simplement en leur assignant une valeur. Vous n'avez pas besoin de déclarer le type de variable.</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Déclaration de variables</span>
<span class="code-variable">nom</span> = <span class="code-string">"Python"</span>  <span class="code-comment"># String (chaîne de caractères)</span>
<span class="code-variable">age</span> = <span class="code-number">30</span>  <span class="code-comment"># Integer (entier)</span>
<span class="code-variable">prix</span> = <span class="code-number">19.99</span>  <span class="code-comment"># Float (nombre décimal)</span>
<span class="code-variable">est_actif</span> = <span class="code-keyword">True</span>  <span class="code-comment"># Boolean (booléen)</span>

<span class="code-comment"># Affichage</span>
<span class="code-function">print</span>(<span class="code-variable">nom</span>)
<span class="code-function">print</span>(<span class="code-variable">age</span>)
<span class="code-function">print</span>(<span class="code-variable">prix</span>)
<span class="code-function">print</span>(<span class="code-variable">est_actif</span>)
                </code>
            </div>

            <h2 id="datatypes">📊 Types de données</h2>
            <p>Python a plusieurs types de données intégrés :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Types de base</span>
<span class="code-variable">texte</span> = <span class="code-string">"Hello"</span>  <span class="code-comment"># str</span>
<span class="code-variable">nombre</span> = <span class="code-number">42</span>  <span class="code-comment"># int</span>
<span class="code-variable">decimal</span> = <span class="code-number">3.14</span>  <span class="code-comment"># float</span>
<span class="code-variable">booleen</span> = <span class="code-keyword">True</span>  <span class="code-comment"># bool</span>

<span class="code-comment"># Collections</span>
<span class="code-variable">liste</span> = [<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>]  <span class="code-comment"># list</span>
<span class="code-variable">dictionnaire</span> = {<span class="code-string">"nom"</span>: <span class="code-string">"Python"</span>, <span class="code-string">"age"</span>: <span class="code-number">30</span>}  <span class="code-comment"># dict</span>
<span class="code-variable">tuple</span> = (<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>)  <span class="code-comment"># tuple</span>
<span class="code-variable">ensemble</span> = {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>}  <span class="code-comment"># set</span>
                </code>
            </div>

            <h2 id="operators">🔢 Opérateurs</h2>
            <p>Python supporte les opérateurs arithmétiques, de comparaison et logiques :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Opérateurs arithmétiques</span>
<span class="code-variable">a</span> = <span class="code-number">10</span>
<span class="code-variable">b</span> = <span class="code-number">3</span>

<span class="code-function">print</span>(<span class="code-variable">a</span> + <span class="code-variable">b</span>)  <span class="code-comment"># Addition: 13</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> - <span class="code-variable">b</span>)  <span class="code-comment"># Soustraction: 7</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> * <span class="code-variable">b</span>)  <span class="code-comment"># Multiplication: 30</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> / <span class="code-variable">b</span>)  <span class="code-comment"># Division: 3.333...</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> // <span class="code-variable">b</span>)  <span class="code-comment"># Division entière: 3</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> % <span class="code-variable">b</span>)  <span class="code-comment"># Modulo: 1</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> ** <span class="code-variable">b</span>)  <span class="code-comment"># Puissance: 1000</span>

<span class="code-comment"># Opérateurs de comparaison</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> > <span class="code-variable">b</span>)  <span class="code-comment"># True</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> == <span class="code-variable">b</span>)  <span class="code-comment"># False</span>
<span class="code-function">print</span>(<span class="code-variable">a</span> != <span class="code-variable">b</span>)  <span class="code-comment"># True</span>
                </code>
            </div>

            <h2 id="conditions">🔀 Structures conditionnelles</h2>
            <p>Python utilise <code>if</code>, <code>elif</code> et <code>else</code> pour les conditions :</p>

            <div class="code-box">
                <code>
<span class="code-variable">age</span> = <span class="code-number">20</span>

<span class="code-keyword">if</span> <span class="code-variable">age</span> >= <span class="code-number">18</span>:
    <span class="code-function">print</span>(<span class="code-string">"Vous êtes majeur"</span>)
<span class="code-keyword">elif</span> <span class="code-variable">age</span> >= <span class="code-number">13</span>:
    <span class="code-function">print</span>(<span class="code-string">"Vous êtes adolescent"</span>)
<span class="code-keyword">else</span>:
    <span class="code-function">print</span>(<span class="code-string">"Vous êtes mineur"</span>)
                </code>
            </div>

            <h2 id="loops">🔄 Boucles</h2>
            <p>Python propose deux types de boucles : <code>for</code> et <code>while</code> :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Boucle for</span>
<span class="code-keyword">for</span> <span class="code-variable">i</span> <span class="code-keyword">in</span> <span class="code-function">range</span>(<span class="code-number">5</span>):
    <span class="code-function">print</span>(<span class="code-variable">i</span>)  <span class="code-comment"># Affiche 0, 1, 2, 3, 4</span>

<span class="code-comment"># Boucle for avec liste</span>
<span class="code-variable">fruits</span> = [<span class="code-string">"pomme"</span>, <span class="code-string">"banane"</span>, <span class="code-string">"orange"</span>]
<span class="code-keyword">for</span> <span class="code-variable">fruit</span> <span class="code-keyword">in</span> <span class="code-variable">fruits</span>:
    <span class="code-function">print</span>(<span class="code-variable">fruit</span>)

<span class="code-comment"># Boucle while</span>
<span class="code-variable">compteur</span> = <span class="code-number">0</span>
<span class="code-keyword">while</span> <span class="code-variable">compteur</span> < <span class="code-number">5</span>:
    <span class="code-function">print</span>(<span class="code-variable">compteur</span>)
    <span class="code-variable">compteur</span> += <span class="code-number">1</span>
                </code>
            </div>

            <h2 id="functions">⚙️ Fonctions</h2>
            <p>Les fonctions permettent de réutiliser du code. En Python, on définit une fonction avec <code>def</code> :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Fonction simple</span>
<span class="code-keyword">def</span> <span class="code-function">dire_bonjour</span>():
    <span class="code-function">print</span>(<span class="code-string">"Bonjour !"</span>)

<span class="code-function">dire_bonjour</span>()  <span class="code-comment"># Appel de la fonction</span>

<span class="code-comment"># Fonction avec paramètres</span>
<span class="code-keyword">def</span> <span class="code-function">saluer</span>(<span class="code-variable">nom</span>):
    <span class="code-keyword">return</span> <span class="code-string">f"Bonjour, {nom} !"</span>

<span class="code-variable">message</span> = <span class="code-function">saluer</span>(<span class="code-string">"Python"</span>)
<span class="code-function">print</span>(<span class="code-variable">message</span>)  <span class="code-comment"># "Bonjour, Python !"</span>

<span class="code-comment"># Fonction avec plusieurs paramètres</span>
<span class="code-keyword">def</span> <span class="code-function">additionner</span>(<span class="code-variable">a</span>, <span class="code-variable">b</span>):
    <span class="code-keyword">return</span> <span class="code-variable">a</span> + <span class="code-variable">b</span>

<span class="code-function">print</span>(<span class="code-function">additionner</span>(<span class="code-number">5</span>, <span class="code-number">3</span>))  <span class="code-comment"># 8</span>
                </code>
            </div>

            <h2 id="lists">📋 Listes et Dictionnaires</h2>
            <p>Les listes et dictionnaires sont des structures de données très utiles en Python :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Listes</span>
<span class="code-variable">nombres</span> = [<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>]
<span class="code-variable">nombres</span>.<span class="code-function">append</span>(<span class="code-number">6</span>)  <span class="code-comment"># Ajouter un élément</span>
<span class="code-function">print</span>(<span class="code-variable">nombres</span>[<span class="code-number">0</span>])  <span class="code-comment"># Premier élément: 1</span>
<span class="code-function">print</span>(<span class="code-function">len</span>(<span class="code-variable">nombres</span>))  <span class="code-comment"># Longueur: 6</span>

<span class="code-comment"># Dictionnaires</span>
<span class="code-variable">personne</span> = {
    <span class="code-string">"nom"</span>: <span class="code-string">"Bassirou"</span>,
    <span class="code-string">"age"</span>: <span class="code-number">25</span>,
    <span class="code-string">"ville"</span>: <span class="code-string">"Dakar"</span>
}
<span class="code-function">print</span>(<span class="code-variable">personne</span>[<span class="code-string">"nom"</span>])  <span class="code-comment"># "Bassirou"</span>
<span class="code-variable">personne</span>[<span class="code-string">"metier"</span>] = <span class="code-string">"Développeur"</span>  <span class="code-comment"># Ajouter une clé</span>
                </code>
            </div>

            <h2 id="modules">📦 Modules</h2>
            <p>Python permet d'importer des modules pour étendre ses fonctionnalités :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Importer un module</span>
<span class="code-keyword">import</span> math

<span class="code-function">print</span>(math.<span class="code-function">sqrt</span>(<span class="code-number">16</span>))  <span class="code-comment"># 4.0</span>
<span class="code-function">print</span>(math.<span class="code-function">pi</span>)  <span class="code-comment"># 3.14159...</span>

<span class="code-comment"># Importer une fonction spécifique</span>
<span class="code-keyword">from</span> datetime <span class="code-keyword">import</span> datetime

<span class="code-variable">maintenant</span> = datetime.<span class="code-function">now</span>()
<span class="code-function">print</span>(<span class="code-variable">maintenant</span>)
                </code>
            </div>

            <h2 id="oop">🏗️ Programmation Orientée Objet</h2>
            <p>Python supporte la programmation orientée objet (POO) :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Définir une classe</span>
<span class="code-keyword">class</span> <span class="code-function">Personne</span>:
    <span class="code-keyword">def</span> <span class="code-function">__init__</span>(<span class="code-keyword">self</span>, <span class="code-variable">nom</span>, <span class="code-variable">age</span>):
        <span class="code-keyword">self</span>.<span class="code-variable">nom</span> = <span class="code-variable">nom</span>
        <span class="code-keyword">self</span>.<span class="code-variable">age</span> = <span class="code-variable">age</span>
    
    <span class="code-keyword">def</span> <span class="code-function">se_presenter</span>(<span class="code-keyword">self</span>):
        <span class="code-keyword">return</span> <span class="code-string">f"Je m'appelle {self.nom} et j'ai {self.age} ans"</span>

<span class="code-comment"># Créer un objet</span>
<span class="code-variable">personne1</span> = <span class="code-function">Personne</span>(<span class="code-string">"Bassirou"</span>, <span class="code-number">25</span>)
<span class="code-function">print</span>(<span class="code-variable">personne1</span>.<span class="code-function">se_presenter</span>())
                </code>
            </div>

            <h2 id="files">📁 Manipulation de fichiers</h2>
            <p>Python permet de lire et écrire dans des fichiers facilement :</p>

            <div class="code-box">
                <code>
<span class="code-comment"># Écrire dans un fichier</span>
<span class="code-keyword">with</span> <span class="code-function">open</span>(<span class="code-string">"fichier.txt"</span>, <span class="code-string">"w"</span>) <span class="code-keyword">as</span> <span class="code-variable">f</span>:
    <span class="code-variable">f</span>.<span class="code-function">write</span>(<span class="code-string">"Bonjour Python !"</span>)

<span class="code-comment"># Lire un fichier</span>
<span class="code-keyword">with</span> <span class="code-function">open</span>(<span class="code-string">"fichier.txt"</span>, <span class="code-string">"r"</span>) <span class="code-keyword">as</span> <span class="code-variable">f</span>:
    <span class="code-variable">contenu</span> = <span class="code-variable">f</span>.<span class="code-function">read</span>()
    <span class="code-function">print</span>(<span class="code-variable">contenu</span>)
                </code>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez <code>with</code> pour ouvrir les fichiers, cela garantit que le fichier sera fermé automatiquement même en cas d'erreur. C'est la méthode recommandée en Python !</p>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous avez maintenant une solide base en Python.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Syntaxe Python et variables</li>
                    <li>Types de données</li>
                    <li>Opérateurs et expressions</li>
                    <li>Structures conditionnelles</li>
                    <li>Boucles (for et while)</li>
                    <li>Fonctions</li>
                    <li>Listes et dictionnaires</li>
                    <li>Modules</li>
                    <li>Programmation Orientée Objet</li>
                    <li>Manipulation de fichiers</li>
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.ia') }}" class="nav-btn">❮ Précédent: IA</a>
                <a href="{{ route('exercices') }}" class="nav-btn">Pratiquer avec des exercices ❯</a>
            </div>
        </main>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/sidebar-sticky.js') }}"></script>
<script src="{{ asset('js/sidebar-navigation.js') }}"></script>
@endsection

