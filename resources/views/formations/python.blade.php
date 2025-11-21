@extends('layouts.app')

@section('title', 'Formation Python | NiangProgrammeur')

@section('styles')
<!-- Prism.js pour la coloration syntaxique -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
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
    
    .sidebar-close-btn {
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(55, 118, 171, 0.1) !important;
        border: 2px solid rgba(55, 118, 171, 0.3) !important;
        transition: all 0.3s ease;
    }
    
    .sidebar-close-btn:hover {
        background: rgba(55, 118, 171, 0.2) !important;
        border-color: rgba(55, 118, 171, 0.5) !important;
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
        background: linear-gradient(135deg, #3776ab, #2d5f8a);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4);
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(55, 118, 171, 0.6);
    }
    
    .sidebar-toggle-btn.active {
        background: linear-gradient(135deg, #2d5f8a, #244a6f);
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
        padding-top: 20px;
        border-top: 2px solid rgba(55, 118, 171, 0.2);
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
        z-index: 1;
    }
    .code-box pre {
        margin: 0;
        padding: 0;
        background: transparent !important;
        overflow-x: auto;
    }
    .code-box pre code {
        display: block;
        padding: 0;
        color: #e2e8f0;
        line-height: 1.6;
        font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
        font-size: 14px;
        white-space: pre;
        overflow-x: auto;
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
    /* Styles Prism.js pour mode sombre */
    body.dark-mode .code-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    body.dark-mode .code-box pre code {
        color: #e2e8f0;
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
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Ouvrir le menu">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">Python Tutorial</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #3776ab; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="Fermer le menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
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
                    <li><strong>Syntaxe simple et lisible</strong> - Le code Python ressemble presque à de l'anglais, ce qui le rend facile à apprendre et à comprendre</li>
                    <li><strong>Polyvalent</strong> - Utilisé pour le web (Django, Flask), la data science (Pandas, NumPy), l'IA (TensorFlow, PyTorch), l'automatisation</li>
                    <li><strong>Vaste bibliothèque standard</strong> - Des milliers de modules disponibles pour presque tous les besoins</li>
                    <li><strong>Communauté active</strong> - Des millions de développeurs dans le monde, documentation complète, nombreuses ressources d'apprentissage</li>
                    <li><strong>Open-source et gratuit</strong> - Aucun coût de licence, multiplateforme (Windows, Linux, macOS)</li>
                    <li><strong>Multi-paradigme</strong> - Supporte la programmation procédurale, orientée objet et fonctionnelle</li>
                </ol>
            </div>

            <h3>🚀 Pourquoi apprendre Python ?</h3>
            <p>Python est un excellent choix pour débuter en programmation pour plusieurs raisons :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Facile à apprendre</strong> - Syntaxe claire et intuitive, parfaite pour les débutants. La courbe d'apprentissage est douce comparée à d'autres langages</li>
                <li>✅ <strong>Polyvalent</strong> - Développement web (Django, Flask), data science (Pandas, NumPy), IA (TensorFlow, PyTorch), automatisation, scripts système</li>
                <li>✅ <strong>Très demandé</strong> - L'un des langages les plus recherchés sur le marché du travail. Utilisé par Google, Facebook, Netflix, Instagram, Spotify</li>
                <li>✅ <strong>Gratuit et Open-Source</strong> - Aucun coût, multiplateforme, communauté active</li>
                <li>✅ <strong>Vaste écosystème</strong> - Des milliers de bibliothèques disponibles via pip (gestionnaire de paquets Python)</li>
                <li>✅ <strong>Grande communauté</strong> - Support et ressources abondantes, forums actifs, tutoriels gratuits</li>
                <li>✅ <strong>Rapidité de développement</strong> - Permet de développer rapidement des prototypes et applications</li>
            </ul>

            <h3>📋 Prérequis pour apprendre Python</h3>
            <p>Python est si simple que vous pouvez commencer sans aucune expérience préalable ! Cependant, avoir des connaissances de base en :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>✅ <strong>Informatique de base</strong> - Savoir utiliser un ordinateur, créer et éditer des fichiers</li>
                <li>⚠️ <strong>Logique</strong> - Comprendre les concepts de base (variables, conditions, boucles) est utile mais pas obligatoire, vous les apprendrez avec Python</li>
            </ul>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note importante :</strong> Python est installé par défaut sur Linux et macOS. Pour Windows, vous pouvez télécharger Python depuis <a href="https://www.python.org/downloads/" target="_blank" style="color: #3776ab; font-weight: bold;">python.org</a>. Vous pouvez aussi utiliser un IDE comme PyCharm, VS Code, ou simplement l'interpréteur Python en ligne de commande. Pour tester rapidement, vous pouvez utiliser des environnements en ligne comme Repl.it ou Python.org/shell.</p>
            </div>

            <h3>🎯 Cas d'usage de Python</h3>
            <p>Python est utilisé dans de nombreux domaines :</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                <li>🌐 <strong>Développement web</strong> - Django, Flask pour créer des sites web et API REST. Frameworks modernes et puissants</li>
                <li>📊 <strong>Data Science</strong> - Analyse de données, visualisation avec Pandas, NumPy, Matplotlib. Très utilisé dans la finance et la recherche</li>
                <li>🤖 <strong>Intelligence Artificielle</strong> - Machine Learning, Deep Learning avec TensorFlow, PyTorch, Scikit-learn</li>
                <li>🔧 <strong>Automatisation</strong> - Scripts pour automatiser des tâches répétitives, traitement de fichiers, web scraping</li>
                <li>📱 <strong>Applications desktop</strong> - Tkinter, PyQt pour créer des interfaces graphiques multiplateformes</li>
                <li>🎮 <strong>Développement de jeux</strong> - Pygame pour créer des jeux vidéo 2D</li>
                <li>🌐 <strong>Scraping web</strong> - BeautifulSoup, Scrapy pour extraire des données de sites web</li>
                <li>🔬 <strong>Calcul scientifique</strong> - NumPy, SciPy pour les calculs mathématiques et scientifiques</li>
            </ul>

            <h2 id="syntax">📝 Syntaxe de base</h2>
            <p>La syntaxe Python est simple et lisible. Python utilise l'<strong>indentation</strong> (espaces ou tabulations) pour définir les blocs de code, contrairement à d'autres langages qui utilisent des accolades <code>{}</code> ou des mots-clés comme <code>begin/end</code>.</p>

            <div class="code-box">
                <pre><code class="language-python"># Premier programme Python
print("Bonjour, monde !")

# Variables
nom = "NiangProgrammeur"
age = 25

# F-strings pour formater les chaînes (Python 3.6+)
print(f"Je m'appelle {nom} et j'ai {age} ans")

# Opérations simples
resultat = 10 + 5
print(f"10 + 5 = {resultat}")</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">💡 Points importants sur la syntaxe Python :</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>Python est sensible à l'indentation</strong> - Utilisez 4 espaces (recommandé par PEP 8) ou des tabulations de manière cohérente. L'indentation définit les blocs de code</li>
                    <li><strong>Les commentaires</strong> commencent par <code>#</code> pour une ligne, ou <code>"""</code> pour plusieurs lignes (docstrings)</li>
                    <li><strong>Pas besoin de point-virgule</strong> à la fin des lignes (contrairement à C, Java, PHP)</li>
                    <li><strong>Les chaînes de caractères</strong> peuvent utiliser des guillemets simples <code>'</code> ou doubles <code>"</code>. Les f-strings (f"...") permettent l'interpolation</li>
                    <li><strong>Les deux-points</strong> <code>:</code> marquent le début d'un bloc (après if, for, def, class, etc.)</li>
                    <li><strong>PEP 8</strong> est le guide de style officiel pour écrire du code Python lisible</li>
                </ul>
            </div>

            <h3>🔍 Exemple détaillé de syntaxe</h3>
            <p>Voici un exemple complet montrant plusieurs aspects de la syntaxe Python :</p>

            <div class="code-box">
                <pre><code class="language-python"># Définition d'une fonction
def calculer_moyenne(nombres):
    """Calcule la moyenne d'une liste de nombres."""
    if len(nombres) == 0:
        return 0
    somme = sum(nombres)
    moyenne = somme / len(nombres)
    return moyenne

# Utilisation
notes = [15, 18, 12, 20, 16]
moyenne = calculer_moyenne(notes)
print(f"La moyenne est : {moyenne}")</code></pre>
            </div>

            <h2 id="variables">🔤 Variables</h2>
            <p>En Python, les variables sont créées simplement en leur assignant une valeur. Vous n'avez <strong>pas besoin de déclarer le type</strong> de variable explicitement. Python est un langage à <strong>typage dynamique</strong>, ce qui signifie que le type est déterminé automatiquement à l'exécution.</p>

            <div class="code-box">
                <pre><code class="language-python"># Déclaration de variables
nom = "Python"          # String (chaîne de caractères)
age = 30                # Integer (entier)
prix = 19.99            # Float (nombre décimal)
est_actif = True        # Boolean (booléen)
valeur_nulle = None     # NoneType (valeur nulle)

# Affichage
print(nom)
print(age)
print(prix)
print(est_actif)
print(valeur_nulle)

# Réassignation (changement de type)
variable = 10
print(type(variable))   # <class 'int'>

variable = "Dix"
print(type(variable))   # <class 'str'>

# Noms de variables valides
nom_utilisateur = "Bassirou"
age_utilisateur = 25
_privé = "variable privée"
CONSTANTE = 3.14159     # Convention pour les constantes</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">📌 Règles pour les noms de variables :</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li>Doivent commencer par une lettre ou un underscore <code>_</code></li>
                    <li>Peuvent contenir des lettres, chiffres et underscores</li>
                    <li>Ne peuvent pas contenir d'espaces (utilisez <code>_</code> à la place)</li>
                    <li>Sont sensibles à la casse (<code>age</code> ≠ <code>Age</code>)</li>
                    <li>Ne peuvent pas être des mots-clés Python (<code>if</code>, <code>for</code>, <code>def</code>, etc.)</li>
                    <li>Convention : utilisez <code>snake_case</code> pour les variables (PEP 8)</li>
                </ul>
            </div>

            <h2 id="datatypes">📊 Types de données</h2>
            <p>Python a plusieurs types de données intégrés (built-in types). Voici les principaux :</p>

            <div class="code-box">
                <pre><code class="language-python"># Types de base (scalaires)
texte = "Hello"                    # str (string)
nombre = 42                        # int (integer)
decimal = 3.14                     # float (floating point)
booleen = True                     # bool (boolean)
valeur_nulle = None                # NoneType

# Collections (structures de données)
liste = [1, 2, 3, 4, 5]           # list (liste ordonnée, modifiable)
tuple = (1, 2, 3)                 # tuple (liste ordonnée, immuable)
dictionnaire = {"nom": "Python", "age": 30}  # dict (paires clé-valeur)
ensemble = {1, 2, 3, 4}           # set (ensemble unique, non ordonné)

# Vérifier le type
print(type(texte))                # <class 'str'>
print(type(nombre))               # <class 'int'>
print(type(liste))                # <class 'list'>
print(type(dictionnaire))         # <class 'dict'>

# Conversion de types
age_str = str(25)                 # Convertir en string
age_int = int("25")               # Convertir en entier
prix_float = float("19.99")       # Convertir en décimal</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">📚 Types de données Python :</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    <li><strong>str</strong> - Chaînes de caractères (texte)</li>
                    <li><strong>int</strong> - Nombres entiers (positifs, négatifs, zéro)</li>
                    <li><strong>float</strong> - Nombres décimaux (virgule flottante)</li>
                    <li><strong>bool</strong> - Booléens (True ou False)</li>
                    <li><strong>list</strong> - Listes ordonnées et modifiables</li>
                    <li><strong>tuple</strong> - Tuples ordonnés et immuables</li>
                    <li><strong>dict</strong> - Dictionnaires (paires clé-valeur)</li>
                    <li><strong>set</strong> - Ensembles (éléments uniques, non ordonnés)</li>
                    <li><strong>NoneType</strong> - Type pour la valeur None (équivalent à null)</li>
                </ul>
            </div>

            <h2 id="operators">🔢 Opérateurs</h2>
            <p>Python supporte les opérateurs arithmétiques, de comparaison, logiques, d'assignation et d'identité :</p>

            <div class="code-box">
                <pre><code class="language-python"># Opérateurs arithmétiques
a = 10
b = 3

print(a + b)    # Addition: 13
print(a - b)    # Soustraction: 7
print(a * b)    # Multiplication: 30
print(a / b)    # Division: 3.3333333333333335
print(a // b)   # Division entière: 3
print(a % b)    # Modulo (reste): 1
print(a ** b)   # Puissance: 1000

# Opérateurs de comparaison
print(a > b)    # True (supérieur à)
print(a < b)    # False (inférieur à)
print(a >= b)   # True (supérieur ou égal)
print(a <= b)   # False (inférieur ou égal)
print(a == b)   # False (égalité)
print(a != b)   # True (différent)

# Opérateurs logiques
x = True
y = False
print(x and y)  # False (ET logique)
print(x or y)   # True (OU logique)
print(not x)    # False (NON logique)

# Opérateurs d'assignation
c = 5
c += 3          # Équivalent à c = c + 3 (c devient 8)
c -= 2          # Équivalent à c = c - 2 (c devient 6)
c *= 2          # Équivalent à c = c * 2 (c devient 12)
c /= 3          # Équivalent à c = c / 3 (c devient 4.0)

# Opérateurs d'identité
liste1 = [1, 2, 3]
liste2 = [1, 2, 3]
liste3 = liste1

print(liste1 is liste2)    # False (objets différents)
print(liste1 is liste3)    # True (même objet)
print(liste1 == liste2)    # True (valeurs égales)</code></pre>
            </div>

            <h2 id="conditions">🔀 Structures conditionnelles</h2>
            <p>Python utilise <code>if</code>, <code>elif</code> (else if) et <code>else</code> pour les conditions. L'indentation est cruciale pour définir les blocs de code.</p>

            <div class="code-box">
                <pre><code class="language-python"># Structure if simple
age = 20

if age >= 18:
    print("Vous êtes majeur")
else:
    print("Vous êtes mineur")

# Structure if/elif/else
age = 15

if age >= 18:
    print("Vous êtes majeur")
    print("Vous pouvez voter")
elif age >= 13:
    print("Vous êtes adolescent")
elif age >= 6:
    print("Vous êtes enfant")
else:
    print("Vous êtes un bébé")

# Conditions multiples
note = 85

if note >= 90:
    mention = "Excellent"
elif note >= 80:
    mention = "Très bien"
elif note >= 70:
    mention = "Bien"
elif note >= 60:
    mention = "Assez bien"
else:
    mention = "Insuffisant"

print(f"Votre mention : {mention}")

# Opérateur ternaire (expression conditionnelle)
age = 20
statut = "Majeur" if age >= 18 else "Mineur"
print(statut)

# Conditions avec and/or
age = 25
permis = True

if age >= 18 and permis:
    print("Vous pouvez conduire")
else:
    print("Vous ne pouvez pas conduire")</code></pre>
            </div>

            <h2 id="loops">🔄 Boucles</h2>
            <p>Python propose deux types de boucles : <code>for</code> (pour itérer sur une séquence) et <code>while</code> (pour répéter tant qu'une condition est vraie) :</p>

            <div class="code-box">
                <pre><code class="language-python"># Boucle for avec range()
for i in range(5):
    print(i)  # Affiche 0, 1, 2, 3, 4

# range() avec début et fin
for i in range(1, 6):
    print(i)  # Affiche 1, 2, 3, 4, 5

# range() avec pas
for i in range(0, 10, 2):
    print(i)  # Affiche 0, 2, 4, 6, 8

# Boucle for avec liste
fruits = ["pomme", "banane", "orange"]
for fruit in fruits:
    print(f"J'aime les {fruit}")

# Boucle for avec index (enumerate)
fruits = ["pomme", "banane", "orange"]
for index, fruit in enumerate(fruits):
    print(f"{index}: {fruit}")

# Boucle while
compteur = 0
while compteur < 5:
    print(compteur)
    compteur += 1

# Boucle while avec break
compteur = 0
while True:
    print(compteur)
    compteur += 1
    if compteur >= 5:
        break  # Sortir de la boucle

# continue (passer à l'itération suivante)
for i in range(10):
    if i % 2 == 0:  # Si i est pair
        continue    # Passer au suivant
    print(i)       # Affiche seulement les impairs: 1, 3, 5, 7, 9

# Boucle for avec else
for i in range(5):
    print(i)
else:
    print("Boucle terminée")  # Exécuté si la boucle se termine normalement</code></pre>
            </div>

            <h2 id="functions">⚙️ Fonctions</h2>
            <p>Les fonctions permettent de réutiliser du code. En Python, on définit une fonction avec <code>def</code>. Les fonctions peuvent prendre des paramètres et retourner des valeurs avec <code>return</code>.</p>

            <div class="code-box">
                <pre><code class="language-python"># Fonction simple (sans paramètres)
def dire_bonjour():
    print("Bonjour !")

dire_bonjour()  # Appel de la fonction

# Fonction avec paramètres
def saluer(nom):
    return f"Bonjour, {nom} !"

message = saluer("Python")
print(message)  # "Bonjour, Python !"

# Fonction avec plusieurs paramètres
def additionner(a, b):
    return a + b

resultat = additionner(5, 3)
print(resultat)  # 8

# Fonction avec paramètres par défaut
def saluer_personne(nom, message="Bonjour"):
    return f"{message}, {nom} !"

print(saluer_personne("Bassirou"))              # "Bonjour, Bassirou !"
print(saluer_personne("Bassirou", "Salut"))     # "Salut, Bassirou !"

# Fonction avec arguments nommés
def creer_personne(nom, age, ville="Dakar"):
    return f"{nom}, {age} ans, habite à {ville}"

print(creer_personne("Bassirou", 25))
print(creer_personne(age=25, nom="Bassirou", ville="Thiès"))

# Fonction avec *args (arguments variables)
def additionner_nombres(*args):
    return sum(args)

print(additionner_nombres(1, 2, 3, 4, 5))  # 15

# Fonction avec **kwargs (arguments nommés variables)
def afficher_info(**kwargs):
    for cle, valeur in kwargs.items():
        print(f"{cle}: {valeur}")

afficher_info(nom="Bassirou", age=25, ville="Dakar")

# Fonction lambda (fonction anonyme)
carre = lambda x: x ** 2
print(carre(5))  # 25

# Utilisation de lambda avec map()
nombres = [1, 2, 3, 4, 5]
carres = list(map(lambda x: x ** 2, nombres))
print(carres)  # [1, 4, 9, 16, 25]</code></pre>
            </div>

            <h2 id="lists">📋 Listes et Dictionnaires</h2>
            <p>Les listes et dictionnaires sont des structures de données très utiles en Python. Les listes sont ordonnées et modifiables, les dictionnaires stockent des paires clé-valeur.</p>

            <div class="code-box">
                <pre><code class="language-python"># ========== LISTES ==========
# Création de listes
nombres = [1, 2, 3, 4, 5]
fruits = ["pomme", "banane", "orange"]
liste_mixte = [1, "deux", 3.0, True]

# Accès aux éléments (index commence à 0)
print(fruits[0])        # "pomme" (premier élément)
print(fruits[-1])      # "orange" (dernier élément)

# Modification
fruits[1] = "mangue"    # Remplacer "banane" par "mangue"

# Méthodes des listes
fruits.append("kiwi")           # Ajouter à la fin
fruits.insert(1, "ananas")      # Insérer à l'index 1
fruits.remove("pomme")          # Supprimer un élément
fruits.pop()                    # Supprimer le dernier élément
fruits.pop(0)                   # Supprimer l'élément à l'index 0

# Autres méthodes utiles
print(len(fruits))              # Longueur de la liste
print(fruits.count("banane"))   # Compter les occurrences
fruits.sort()                   # Trier la liste
fruits.reverse()                # Inverser la liste

# Slicing (tranches)
nombres = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
print(nombres[2:5])     # [2, 3, 4] (de l'index 2 à 4)
print(nombres[:3])      # [0, 1, 2] (du début à l'index 2)
print(nombres[3:])      # [3, 4, 5, 6, 7, 8, 9] (de l'index 3 à la fin)
print(nombres[::2])     # [0, 2, 4, 6, 8] (tous les 2 éléments)

# ========== DICTIONNAIRES ==========
# Création de dictionnaires
personne = {
    "nom": "Bassirou",
    "age": 25,
    "ville": "Dakar"
}

# Accès aux valeurs
print(personne["nom"])          # "Bassirou"
print(personne.get("age"))      # 25 (méthode get() plus sûre)
print(personne.get("email", "Non renseigné"))  # Valeur par défaut

# Modification et ajout
personne["age"] = 26            # Modifier
personne["email"] = "bassirou@example.com"  # Ajouter

# Méthodes des dictionnaires
print(personne.keys())          # Toutes les clés
print(personne.values())        # Toutes les valeurs
print(personne.items())         # Toutes les paires clé-valeur

# Parcourir un dictionnaire
for cle, valeur in personne.items():
    print(f"{cle}: {valeur}")

# Supprimer
del personne["email"]           # Supprimer une clé
personne.pop("ville")           # Supprimer et retourner la valeur</code></pre>
            </div>

            <h2 id="modules">📦 Modules</h2>
            <p>Python permet d'importer des modules pour étendre ses fonctionnalités. Un module est un fichier contenant des fonctions, classes et variables que vous pouvez réutiliser.</p>

            <div class="code-box">
                <pre><code class="language-python"># Importer un module complet
import math

print(math.sqrt(16))        # 4.0 (racine carrée)
print(math.pi)              # 3.141592653589793
print(math.cos(0))          # 1.0

# Importer avec un alias
import datetime as dt
maintenant = dt.datetime.now()
print(maintenant)

# Importer des fonctions spécifiques
from math import sqrt, pi
print(sqrt(25))             # 5.0
print(pi)                   # 3.141592653589793

# Importer tout d'un module (non recommandé)
from math import *
print(sin(0))               # 0.0

# Modules standards utiles
import random
print(random.randint(1, 100))  # Nombre aléatoire entre 1 et 100

import os
print(os.getcwd())          # Répertoire courant

import sys
print(sys.version)          # Version de Python

# Créer son propre module
# Créer un fichier mon_module.py avec :
# def ma_fonction():
#     return "Hello from module"
#
# Puis l'importer :
# import mon_module
# print(mon_module.ma_fonction())</code></pre>
            </div>

            <h2 id="oop">🏗️ Programmation Orientée Objet</h2>
            <p>Python supporte la programmation orientée objet (POO). Une classe est un modèle pour créer des objets. Les objets ont des attributs (données) et des méthodes (fonctions).</p>

            <div class="code-box">
                <pre><code class="language-python"># Définir une classe
class Personne:
    # Constructeur (méthode spéciale __init__)
    def __init__(self, nom, age):
        self.nom = nom      # Attribut d'instance
        self.age = age
    
    # Méthode d'instance
    def se_presenter(self):
        return f"Je m'appelle {self.nom} et j'ai {self.age} ans"
    
    def avoir_ans(self, annees):
        self.age += annees
        return f"Dans {annees} ans, j'aurai {self.age} ans"

# Créer des objets (instances)
personne1 = Personne("Bassirou", 25)
personne2 = Personne("Aminata", 30)

# Utiliser les méthodes
print(personne1.se_presenter())
print(personne2.se_presenter())
print(personne1.avoir_ans(5))

# Accéder aux attributs
print(personne1.nom)
print(personne1.age)

# Classe avec attributs de classe
class Voiture:
    # Attribut de classe (partagé par toutes les instances)
    nombre_voitures = 0
    
    def __init__(self, marque, modele):
        self.marque = marque
        self.modele = modele
        Voiture.nombre_voitures += 1
    
    def __str__(self):
        return f"{self.marque} {self.modele}"

voiture1 = Voiture("Toyota", "Corolla")
voiture2 = Voiture("Honda", "Civic")
print(f"Nombre de voitures créées : {Voiture.nombre_voitures}")

# Héritage
class Etudiant(Personne):
    def __init__(self, nom, age, ecole):
        super().__init__(nom, age)  # Appeler le constructeur parent
        self.ecole = ecole
    
    def etudier(self):
        return f"{self.nom} étudie à {self.ecole}"

etudiant = Etudiant("Bassirou", 25, "UCAD")
print(etudiant.se_presenter())  # Méthode héritée
print(etudiant.etudier())       # Méthode spécifique</code></pre>
            </div>

            <h2 id="files">📁 Manipulation de fichiers</h2>
            <p>Python permet de lire et écrire dans des fichiers facilement. Il est recommandé d'utiliser <code>with</code> pour garantir la fermeture automatique du fichier.</p>

            <div class="code-box">
                <pre><code class="language-python"># Écrire dans un fichier (mode 'w' = write)
with open("fichier.txt", "w", encoding="utf-8") as f:
    f.write("Bonjour Python !\n")
    f.write("Ceci est la deuxième ligne\n")

# Lire un fichier (mode 'r' = read)
with open("fichier.txt", "r", encoding="utf-8") as f:
    contenu = f.read()
    print(contenu)

# Lire ligne par ligne
with open("fichier.txt", "r", encoding="utf-8") as f:
    for ligne in f:
        print(ligne.strip())  # strip() enlève les sauts de ligne

# Lire toutes les lignes dans une liste
with open("fichier.txt", "r", encoding="utf-8") as f:
    lignes = f.readlines()
    print(lignes)

# Ajouter à un fichier (mode 'a' = append)
with open("fichier.txt", "a", encoding="utf-8") as f:
    f.write("Nouvelle ligne ajoutée\n")

# Modes de fichier
# 'r'  - Lecture (défaut)
# 'w'  - Écriture (écrase le fichier existant)
# 'a'  - Ajout (ajoute à la fin)
# 'x'  - Création exclusive (erreur si existe)
# 'b'  - Mode binaire (rb, wb)
# 't'  - Mode texte (défaut, rt, wt)
# '+'  - Lecture et écriture (r+, w+, a+)

# Gestion d'erreurs
try:
    with open("fichier_inexistant.txt", "r") as f:
        contenu = f.read()
except FileNotFoundError:
    print("Le fichier n'existe pas")
except PermissionError:
    print("Permission refusée")
except Exception as e:
    print(f"Erreur : {e}")</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Bonne pratique :</strong> Utilisez toujours <code>with</code> pour ouvrir les fichiers. Cela garantit que le fichier sera fermé automatiquement même en cas d'erreur. C'est la méthode recommandée en Python et cela évite les fuites de ressources !</p>
            </div>

            <h2>🎓 Prochaines étapes</h2>
            <p>Félicitations ! Vous avez maintenant une solide base en Python.</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">✅ Ce que vous avez appris :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>Syntaxe Python et variables</li>
                    <li>Types de données (str, int, float, bool, list, dict, tuple, set)</li>
                    <li>Opérateurs (arithmétiques, comparaison, logiques)</li>
                    <li>Structures conditionnelles (if, elif, else)</li>
                    <li>Boucles (for et while)</li>
                    <li>Fonctions (définition, paramètres, return, lambda)</li>
                    <li>Listes et dictionnaires (méthodes, slicing)</li>
                    <li>Modules (import, création)</li>
                    <li>Programmation Orientée Objet (classes, objets, héritage)</li>
                    <li>Manipulation de fichiers (lecture, écriture, gestion d'erreurs)</li>
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">🚀 Pour aller plus loin :</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    <li>📚 <strong>Compréhensions de listes</strong> - Syntaxe concise pour créer des listes</li>
                    <li>🔧 <strong>Gestion des exceptions</strong> - try/except pour gérer les erreurs</li>
                    <li>📦 <strong>Packages et pip</strong> - Installer des bibliothèques externes</li>
                    <li>🌐 <strong>Développement web</strong> - Django ou Flask pour créer des sites web</li>
                    <li>📊 <strong>Data Science</strong> - Pandas, NumPy pour l'analyse de données</li>
                    <li>🤖 <strong>Intelligence Artificielle</strong> - TensorFlow, PyTorch pour le Machine Learning</li>
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
                        sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3776ab, #2d5f8a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
                    sidebarToggle.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; bottom: 20px !important; left: 20px !important; width: 60px !important; height: 60px !important; background: linear-gradient(135deg, #3776ab, #2d5f8a) !important; border: none !important; border-radius: 50% !important; color: white !important; font-size: 24px !important; cursor: pointer !important; z-index: 10000 !important; box-shadow: 0 8px 25px rgba(55, 118, 171, 0.4) !important; align-items: center !important; justify-content: center !important;';
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
<!-- Prism.js pour la coloration syntaxique -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
<script>
    // Initialiser Prism.js après le chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    });
</script>
@endsection
