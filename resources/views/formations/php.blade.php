@extends('layouts.app')

@section('title', 'Formation PHP | DevFormation')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    html {
        overflow-x: hidden;
    }
    body {
        background-color: #fff !important;
        color: #000 !important;
        padding-top: 80px !important;
        overflow-x: hidden !important;
        margin: 0;
        padding: 0;
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
        overflow-x: hidden;
    }
    .content-wrapper {
        display: flex;
        gap: 20px;
        padding: 20px;
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
    .sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 25px;
        border-radius: 15px;
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        align-self: flex-start;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
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
        .content-wrapper {
            flex-direction: column;
        }
        .sidebar {
            width: 100%;
            flex-shrink: 0;
            position: relative;
            top: 0;
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
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3>PHP Tutorial</h3>
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
            <p>PHP (Hypertext Preprocessor) est un langage de script serveur open-source conçu pour le développement web. Il est exécuté côté serveur et génère du HTML dynamique.</p>

            <h3>🚀 Pourquoi apprendre PHP ?</h3>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Populaire</strong> - Utilisé par 77% des sites web (WordPress, Facebook, Wikipedia)</li>
                <li>✅ <strong>Facile à apprendre</strong> - Syntaxe simple et intuitive</li>
                <li>✅ <strong>Puissant</strong> - Créez des applications web complètes</li>
                <li>✅ <strong>Gratuit</strong> - Open-source et multiplateforme</li>
                <li>✅ <strong>Communauté</strong> - Vaste écosystème et support</li>
            </ul>

            <h2 id="syntax">📝 Syntaxe de base</h2>
            <p>Le code PHP est exécuté sur le serveur et le résultat est envoyé au navigateur en HTML.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        &nbsp;&nbsp;<span class="code-comment">// Ceci est un commentaire</span><br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Hello World!"</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note :</strong> Les fichiers PHP doivent avoir l'extension <code>.php</code> et être exécutés sur un serveur web (Apache, Nginx).</p>
            </div>

            <h2 id="variables">📦 Variables</h2>
            <p>Les variables en PHP commencent par le symbole <code>$</code> et sont sensibles à la casse.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$nom</span> = <span class="code-string">"Jean"</span>;<br>
                        <span class="code-variable">$age</span> = <span class="code-string">25</span>;<br>
                        <span class="code-variable">$prix</span> = <span class="code-string">19.99</span>;<br>
                        <span class="code-variable">$estActif</span> = <span class="code-keyword">true</span>;<br><br>
                        <span class="code-keyword">echo</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$nom</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="datatypes">🔢 Types de données</h2>
            <p>PHP supporte plusieurs types de données : string, integer, float, boolean, array, object, NULL.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$texte</span> = <span class="code-string">"Hello"</span>; <span class="code-comment">// String</span><br>
                        <span class="code-variable">$nombre</span> = <span class="code-string">42</span>; <span class="code-comment">// Integer</span><br>
                        <span class="code-variable">$decimal</span> = <span class="code-string">3.14</span>; <span class="code-comment">// Float</span><br>
                        <span class="code-variable">$vrai</span> = <span class="code-keyword">true</span>; <span class="code-comment">// Boolean</span><br>
                        <span class="code-variable">$tableau</span> = <span class="code-function">array</span>(<span class="code-string">1</span>, <span class="code-string">2</span>, <span class="code-string">3</span>); <span class="code-comment">// Array</span><br>
                        <span class="code-variable">$vide</span> = <span class="code-keyword">NULL</span>; <span class="code-comment">// NULL</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="operators">➕ Opérateurs</h2>
            <p>PHP dispose d'opérateurs arithmétiques, de comparaison, logiques et d'affectation.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Arithmétiques</span><br>
                        <span class="code-variable">$somme</span> = <span class="code-string">10</span> + <span class="code-string">5</span>; <span class="code-comment">// 15</span><br>
                        <span class="code-variable">$produit</span> = <span class="code-string">10</span> * <span class="code-string">5</span>; <span class="code-comment">// 50</span><br><br>
                        <span class="code-comment">// Comparaison</span><br>
                        <span class="code-variable">$egal</span> = (<span class="code-string">5</span> == <span class="code-string">"5"</span>); <span class="code-comment">// true</span><br>
                        <span class="code-variable">$identique</span> = (<span class="code-string">5</span> === <span class="code-string">"5"</span>); <span class="code-comment">// false</span><br><br>
                        <span class="code-comment">// Concaténation</span><br>
                        <span class="code-variable">$texte</span> = <span class="code-string">"Hello"</span> . <span class="code-string">" World"</span>;<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="conditions">🔀 Conditions</h2>
            <p>Les structures conditionnelles permettent d'exécuter du code selon des conditions.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-variable">$age</span> = <span class="code-string">18</span>;<br><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$age</span> < <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Mineur"</span>;<br>
                        } <span class="code-keyword">elseif</span> (<span class="code-variable">$age</span> == <span class="code-string">18</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Tout juste majeur"</span>;<br>
                        } <span class="code-keyword">else</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Majeur"</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="loops">🔁 Boucles</h2>
            <p>Les boucles permettent de répéter des instructions.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Boucle for</span><br>
                        <span class="code-keyword">for</span> (<span class="code-variable">$i</span> = <span class="code-string">0</span>; <span class="code-variable">$i</span> < <span class="code-string">5</span>; <span class="code-variable">$i</span>++) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$i</span>;<br>
                        }<br><br>
                        <span class="code-comment">// Boucle foreach (pour tableaux)</span><br>
                        <span class="code-variable">$fruits</span> = <span class="code-function">array</span>(<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>);<br>
                        <span class="code-keyword">foreach</span> (<span class="code-variable">$fruits</span> <span class="code-keyword">as</span> <span class="code-variable">$fruit</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$fruit</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="functions">⚡ Fonctions</h2>
            <p>Les fonctions permettent de réutiliser du code.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">function</span> <span class="code-function">saluer</span>(<span class="code-variable">$nom</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$nom</span>;<br>
                        }<br><br>
                        <span class="code-keyword">echo</span> <span class="code-function">saluer</span>(<span class="code-string">"Marie"</span>); <span class="code-comment">// "Bonjour Marie"</span><br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="arrays">📚 Tableaux</h2>
            <p>Les tableaux stockent plusieurs valeurs dans une seule variable.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Tableau indexé</span><br>
                        <span class="code-variable">$fruits</span> = <span class="code-function">array</span>(<span class="code-string">"Pomme"</span>, <span class="code-string">"Banane"</span>, <span class="code-string">"Orange"</span>);<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$fruits</span>[<span class="code-string">0</span>]; <span class="code-comment">// "Pomme"</span><br><br>
                        <span class="code-comment">// Tableau associatif</span><br>
                        <span class="code-variable">$personne</span> = <span class="code-function">array</span>(<br>
                        &nbsp;&nbsp;<span class="code-string">"nom"</span> => <span class="code-string">"Jean"</span>,<br>
                        &nbsp;&nbsp;<span class="code-string">"age"</span> => <span class="code-string">30</span><br>
                        );<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$personne</span>[<span class="code-string">"nom"</span>];<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="forms">📝 Formulaires</h2>
            <p>PHP peut traiter les données de formulaires avec $_GET et $_POST.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Récupérer données POST</span><br>
                        <span class="code-keyword">if</span> (<span class="code-variable">$_SERVER</span>[<span class="code-string">"REQUEST_METHOD"</span>] == <span class="code-string">"POST"</span>) {<br>
                        &nbsp;&nbsp;<span class="code-variable">$nom</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"nom"</span>];<br>
                        &nbsp;&nbsp;<span class="code-variable">$email</span> = <span class="code-variable">$_POST</span>[<span class="code-string">"email"</span>];<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$nom</span>;<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="sessions">🔐 Sessions</h2>
            <p>Les sessions permettent de stocker des informations utilisateur entre les pages.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Démarrer la session</span><br>
                        <span class="code-function">session_start</span>();<br><br>
                        <span class="code-comment">// Stocker des données</span><br>
                        <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>] = <span class="code-string">"Jean"</span>;<br><br>
                        <span class="code-comment">// Récupérer des données</span><br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$_SESSION</span>[<span class="code-string">"username"</span>];<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="mysql">🗄️ MySQL</h2>
            <p>PHP peut se connecter à des bases de données MySQL pour stocker et récupérer des données.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-comment">// Connexion mysqli</span><br>
                        <span class="code-variable">$conn</span> = <span class="code-function">mysqli_connect</span>(<span class="code-string">"localhost"</span>, <span class="code-string">"user"</span>, <span class="code-string">"pass"</span>, <span class="code-string">"database"</span>);<br><br>
                        <span class="code-comment">// Requête SELECT</span><br>
                        <span class="code-variable">$sql</span> = <span class="code-string">"SELECT * FROM users"</span>;<br>
                        <span class="code-variable">$result</span> = <span class="code-function">mysqli_query</span>(<span class="code-variable">$conn</span>, <span class="code-variable">$sql</span>);<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="pdo">🔗 PDO</h2>
            <p>PDO (PHP Data Objects) est une interface moderne et sécurisée pour accéder aux bases de données.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">try</span> {<br>
                        &nbsp;&nbsp;<span class="code-variable">$pdo</span> = <span class="code-keyword">new</span> <span class="code-function">PDO</span>(<span class="code-string">"mysql:host=localhost;dbname=test"</span>, <span class="code-string">"user"</span>, <span class="code-string">"pass"</span>);<br><br>
                        &nbsp;&nbsp;<span class="code-comment">// Requête préparée (sécurisée)</span><br>
                        &nbsp;&nbsp;<span class="code-variable">$stmt</span> = <span class="code-variable">$pdo</span>-><span class="code-function">prepare</span>(<span class="code-string">"SELECT * FROM users WHERE id = :id"</span>);<br>
                        &nbsp;&nbsp;<span class="code-variable">$stmt</span>-><span class="code-function">execute</span>([<span class="code-string">'id'</span> => <span class="code-string">1</span>]);<br>
                        } <span class="code-keyword">catch</span> (<span class="code-function">PDOException</span> <span class="code-variable">$e</span>) {<br>
                        &nbsp;&nbsp;<span class="code-keyword">echo</span> <span class="code-variable">$e</span>-><span class="code-function">getMessage</span>();<br>
                        }<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
            </div>

            <h2 id="oop">🎯 POO (Programmation Orientée Objet)</h2>
            <p>La POO permet d'organiser le code en classes et objets.</p>

            <div class="example-box">
                <div class="code-box">
                    <code>
                        <span class="code-keyword">&lt;?php</span><br>
                        <span class="code-keyword">class</span> <span class="code-function">Personne</span> {<br>
                        &nbsp;&nbsp;<span class="code-keyword">public</span> <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;<span class="code-keyword">private</span> <span class="code-variable">$age</span>;<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">__construct</span>(<span class="code-variable">$nom</span>, <span class="code-variable">$age</span>) {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->nom = <span class="code-variable">$nom</span>;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-variable">$this</span>->age = <span class="code-variable">$age</span>;<br>
                        &nbsp;&nbsp;}<br><br>
                        &nbsp;&nbsp;<span class="code-keyword">public function</span> <span class="code-function">saluer</span>() {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="code-keyword">return</span> <span class="code-string">"Bonjour "</span> . <span class="code-variable">$this</span>->nom;<br>
                        &nbsp;&nbsp;}<br>
                        }<br><br>
                        <span class="code-variable">$p</span> = <span class="code-keyword">new</span> <span class="code-function">Personne</span>(<span class="code-string">"Jean"</span>, <span class="code-string">30</span>);<br>
                        <span class="code-keyword">echo</span> <span class="code-variable">$p</span>-><span class="code-function">saluer</span>();<br>
                        <span class="code-keyword">?&gt;</span>
                    </code>
                </div>
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
