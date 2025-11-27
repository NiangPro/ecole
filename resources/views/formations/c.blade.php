@extends('layouts.app')

@section('title', trans('app.formations.c.title') . ' | NiangProgrammeur')

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
        background-color: #a8b9cc;
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
        background: linear-gradient(180deg, #a8b9cc 0%, #8fa0b3 100%);
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2d5f8a 0%, #244a6f 100%);
    }
    .sidebar h3 {
        color: #a8b9cc;
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(168, 185, 204, 0.2);
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
        background: #a8b9cc;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .sidebar a:hover {
        background: linear-gradient(135deg, rgba(55, 118, 171, 0.1) 0%, rgba(55, 118, 171, 0.05) 100%);
        color: #a8b9cc;
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
        /* backdrop-filter: blur(5px); */ /* Désactivé pour éviter le flou */
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
        border-top: 2px solid rgba(168, 185, 204, 0.2);
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
        border-left: 4px solid #a8b9cc;
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
        border: 2px solid #a8b9cc;
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
        content: 'C';
        position: absolute;
        top: 10px;
        right: 15px;
        background: #a8b9cc;
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 1;
    }
    
    /* Bouton de copie - Même taille que le label Python */
    .copy-code-btn {
        position: absolute;
        top: 10px;
        right: 100px;
        background: #a8b9cc;
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
        background: #2A5A87;
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
        text-shadow: none !important; /* Pas de flou */
    }
    
    /* Supprimer tout flou sur le code */
    .code-box pre,
    .code-box code,
    .code-box pre code,
    .code-box pre code .token {
        text-shadow: none !important;
        filter: none !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Styles Prism.js pour la coloration syntaxique C */
    .code-box code[class*="language-"],
    .code-box pre[class*="language-"] {
        color: #e2e8f0;
        text-shadow: none;
        font-family: 'Courier New', 'Consolas', 'Monaco', 'Fira Code', monospace;
        background: transparent !important;
    }
    
    /* Couleurs pour les tokens C - Style VS Code Dark */
    .code-box code[class*="language-"] .token.comment,
    .code-box code[class*="language-"] .token.prolog,
    .code-box code[class*="language-"] .token.doctype,
    .code-box code[class*="language-"] .token.cdata {
        color: #6a9955 !important;
        font-style: italic;
    }
    .code-box code[class*="language-"] .token.string,
    .code-box code[class*="language-"] .token.attr-value {
        color: #ce9178 !important;
    }
    .code-box code[class*="language-"] .token.keyword,
    .code-box code[class*="language-"] .token.boolean {
        color: #569cd6 !important;
        font-weight: 500;
    }
    .code-box code[class*="language-"] .token.operator {
        color: #d4d4d4 !important;
    }
    .code-box code[class*="language-"] .token.function {
        color: #dcdcaa !important;
    }
    .code-box code[class*="language-"] .token.class-name {
        color: #4ec9b0 !important;
    }
    .code-box code[class*="language-"] .token.number {
        color: #b5cea8 !important;
    }
    .code-box code[class*="language-"] .token.punctuation {
        color: #d4d4d4 !important;
    }
    .code-box code[class*="language-"] .token.variable,
    .code-box code[class*="language-"] .token.property {
        color: #9cdcfe !important;
    }
    
    /* S'assurer que les tokens Prism héritent correctement */
    .code-box pre code .token {
        font-size: 14px;
        line-height: 1.6;
        font-weight: 400;
        text-shadow: none !important; /* Pas de flou sur les tokens */
    }
    
    /* Styles généraux pour tous les tokens Prism */
    .code-box .token {
        text-shadow: none !important;
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
        background-color: #a8b9cc;
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
        <i class="fab fa-c" style="margin-right: 15px;"></i>
        {{ trans('app.formations.c.title') }}
    </h1>
    <p style="font-size: 20px; margin-top: 15px; opacity: 0.9;">
        {{ trans('app.formations.c.subtitle') }}
    </p>
</div>

<div class="tutorial-content">
    <div class="content-wrapper">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="{{ trans('app.formations.c.menu_open') }}">
            <i class="fas fa-bars" id="sidebarToggleIcon"></i>
        </button>
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="tutorialSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(55, 118, 171, 0.2);">
                <h3 style="margin: 0;">{{ trans('app.formations.c.sidebar_title') }}</h3>
                <button class="sidebar-close-btn" id="sidebarClose" style="display: none; background: none; border: none; color: #3776ab; font-size: 24px; cursor: pointer; padding: 5px; width: 35px; height: 35px; border-radius: 50%; transition: all 0.3s ease;" aria-label="{{ trans('app.formations.c.menu_close') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @php
                $anchors = ['intro', 'syntax', 'variables', 'datatypes', 'operators', 'conditions', 'loops', 'functions', 'pointers', 'arrays', 'structs', 'memory'];
            @endphp
            @foreach(trans('app.formations.c.sidebar_menu') as $index => $menuItem)
            <a href="#{{ $anchors[$index] ?? 'intro' }}" class="{{ $index === 0 ? 'active' : '' }}">{{ $menuItem }}</a>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="intro">{{ trans('app.formations.c.intro_title') }}</h1>
            <p>{{ trans('app.formations.c.intro_text') }}</p>

            <h3>{{ trans('app.formations.c.what_is_title') }}</h3>
            <p>{!! trans('app.formations.c.what_is_text') !!}</p>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.c.why_important_title') }}</h3>
                <ol style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.c.why_important_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ol>
            </div>

            <h3>{{ trans('app.formations.c.why_learn_title') }}</h3>
            <p>{{ trans('app.formations.c.why_learn_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.c.why_learn_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h3>{{ trans('app.formations.c.prerequisites_title') }}</h3>
            <p>{{ trans('app.formations.c.prerequisites_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.c.prerequisites_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $label = $parts[0] ?? '';
                    $description = $parts[1] ?? '';
                @endphp
                <li>✅ <strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.c.prerequisites_note') !!}</p>
            </div>

            <h3>{{ trans('app.formations.c.use_cases_title') }}</h3>
            <p>{{ trans('app.formations.c.use_cases_text') }}</p>
            <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                @foreach(trans('app.formations.c.use_cases_items') as $item)
                @php
                    $parts = explode(' - ', $item);
                    $icon = '';
                    $label = '';
                    $description = '';
                    if (preg_match('/^([🖥️🔧🚗🎮📡🔬💾🌐]) (.+)$/u', $item, $matches)) {
                        $icon = $matches[1];
                        $rest = $matches[2];
                        $labelParts = explode(' - ', $rest);
                        $label = $labelParts[0] ?? '';
                        $description = $labelParts[1] ?? '';
                    } else {
                        $labelParts = explode(' - ', $item);
                        $label = $labelParts[0] ?? '';
                        $description = $labelParts[1] ?? '';
                    }
                @endphp
                <li>@if($icon){{ $icon }} @endif<strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                @endforeach
            </ul>

            <h2 id="syntax">{{ trans('app.formations.c.syntax_title') }}</h2>
            <p>{!! trans('app.formations.c.syntax_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c">// Premier programme C
#include <stdio.h>

int main() {
    printf("Bonjour, monde !\n");
    return 0;
}

// Variables
int age = 25;
char nom[] = "NiangProgrammeur";

// Affichage formaté
printf("Je m'appelle %s et j'ai %d ans\n", nom, age);

// Opérations simples
int resultat = 10 + 5;
printf("10 + 5 = %d\n", resultat);
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.c.syntax_points_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.c.syntax_points_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h3>{{ trans('app.formations.c.syntax_example_title') }}</h3>
            <p>{{ trans('app.formations.c.syntax_example_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>

// Définition d'une fonction
int calculer_moyenne(int nombres[], int taille) {
    if (taille == 0) {
        return 0;
    }
    int somme = 0;
    for (int i = 0; i < taille; i++) {
        somme += nombres[i];
    }
    return somme / taille;
}

// Fonction principale
int main() {
    int notes[] = {15, 18, 12, 20, 16};
    int moyenne = calculer_moyenne(notes, 5);
    printf("La moyenne est : %d\n", moyenne);
    return 0;
}</code></pre>
            </div>

            <h2 id="variables">{{ trans('app.formations.c.variables_title') }}</h2>
            <p>{!! trans('app.formations.c.variables_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>

int main() {
    // Déclaration de variables
    int age = 30;                    // Entier
    float prix = 19.99f;             // Nombre décimal (simple précision)
    double decimal = 3.14159;         // Nombre décimal (double précision)
    char lettre = 'A';                // Caractère unique
    char nom[] = "C";                // Chaîne de caractères (tableau)
    
    // Affichage avec printf
    printf("Age: %d\n", age);
    printf("Prix: %.2f\n", prix);
    printf("Decimal: %lf\n", decimal);
    printf("Lettre: %c\n", lettre);
    printf("Nom: %s\n", nom);
    
    // Déclaration puis assignation
    int nombre;
    nombre = 10;
    printf("Nombre: %d\n", nombre);
    
    // Constantes avec #define
    #define PI 3.14159
    #define MAX_SIZE 100
    
    // Constantes avec const
    const int TAILLE = 50;
    const char* MESSAGE = "Bonjour";
    
    // Noms de variables valides
    int age_utilisateur = 25;
    char nom_utilisateur[] = "Bassirou";
    float _prive = 3.14;  // Possible mais non recommandé
    
    return 0;
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.c.variables_rules_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.c.variables_rules_items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="datatypes">{{ trans('app.formations.c.datatypes_title') }}</h2>
            <p>{{ trans('app.formations.c.datatypes_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>
#include <stdbool.h>  // Pour le type bool

int main() {
    // Types entiers
    char c = 'A';              // 1 octet (-128 à 127 ou 0 à 255)
    short s = 100;            // 2 octets (-32768 à 32767)
    int i = 1000;             // 4 octets (généralement)
    long l = 100000L;         // 4 ou 8 octets
    long long ll = 1000000LL; // 8 octets
    
    // Types entiers non signés
    unsigned char uc = 200;
    unsigned int ui = 5000;
    unsigned long ul = 100000UL;
    
    // Types décimaux
    float f = 3.14f;          // 4 octets (simple précision)
    double d = 3.14159;       // 8 octets (double précision)
    long double ld = 3.141592653589793L;  // 10 ou 16 octets
    
    // Type booléen (C99+)
    bool est_vrai = true;
    bool est_faux = false;
    
    // Chaînes de caractères (tableaux de char)
    char chaine[] = "Hello";  // Tableau de caractères
    char* pointeur = "World"; // Pointeur vers une chaîne
    
    // Affichage avec printf
    printf("char: %c\n", c);
    printf("int: %d\n", i);
    printf("float: %.2f\n", f);
    printf("double: %lf\n", d);
    printf("bool: %d\n", est_vrai);
    printf("chaine: %s\n", chaine);
    
    // Taille des types (en octets)
    printf("Taille de int: %zu octets\n", sizeof(int));
    printf("Taille de float: %zu octets\n", sizeof(float));
    printf("Taille de double: %zu octets\n", sizeof(double));
    
    return 0;
}</code></pre>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.c.datatypes_list_title') }}</h3>
                <ul style="line-height: 2; font-size: 16px; margin-left: 20px; color: #000;">
                    @foreach(trans('app.formations.c.datatypes_list_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $label = $parts[0] ?? '';
                        $description = $parts[1] ?? '';
                    @endphp
                    <li><strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <h2 id="operators">{{ trans('app.formations.c.operators_title') }}</h2>
            <p>{{ trans('app.formations.c.operators_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>
#include <math.h>  // Pour pow()

int main() {
    int a = 10, b = 3;
    
    // Opérateurs arithmétiques
    printf("%d + %d = %d\n", a, b, a + b);      // Addition: 13
    printf("%d - %d = %d\n", a, b, a - b);      // Soustraction: 7
    printf("%d * %d = %d\n", a, b, a * b);      // Multiplication: 30
    printf("%d / %d = %d\n", a, b, a / b);      // Division entière: 3
    printf("%d %% %d = %d\n", a, b, a % b);      // Modulo (reste): 1
    
    // Division avec float
    float resultat = (float)a / b;
    printf("%d / %d = %.2f\n", a, b, resultat); // Division: 3.33
    
    // Puissance (nécessite math.h)
    double puissance = pow(a, b);
    printf("%d^%d = %.0f\n", a, b, puissance);  // Puissance: 1000
    
    // Opérateurs de comparaison
    printf("%d > %d = %d\n", a, b, a > b);      // 1 (true)
    printf("%d < %d = %d\n", a, b, a < b);      // 0 (false)
    printf("%d >= %d = %d\n", a, b, a >= b);    // 1 (true)
    printf("%d <= %d = %d\n", a, b, a <= b);    // 0 (false)
    printf("%d == %d = %d\n", a, b, a == b);    // 0 (false)
    printf("%d != %d = %d\n", a, b, a != b);    // 1 (true)
    
    // Opérateurs logiques
    int x = 1, y = 0;  // 1 = true, 0 = false
    printf("%d && %d = %d\n", x, y, x && y);    // ET logique: 0
    printf("%d || %d = %d\n", x, y, x || y);    // OU logique: 1
    printf("!%d = %d\n", x, !x);                // NON logique: 0
    
    // Opérateurs d'assignation
    int c = 5;
    c += 3;  // Équivalent à c = c + 3 (c devient 8)
    c -= 2;  // Équivalent à c = c - 2 (c devient 6)
    c *= 2;  // Équivalent à c = c * 2 (c devient 12)
    c /= 3;  // Équivalent à c = c / 3 (c devient 4)
    c %= 3;  // Équivalent à c = c % 3 (c devient 1)
    
    // Opérateurs d'incrémentation/décrémentation
    int i = 5;
    printf("i = %d\n", i++);  // Post-incrémentation: affiche 5, puis i = 6
    printf("i = %d\n", ++i);  // Pré-incrémentation: i = 7, puis affiche 7
    
    return 0;
}</code></pre>
            </div>

            <h2 id="conditions">{{ trans('app.formations.c.conditions_title') }}</h2>
            <p>{!! trans('app.formations.c.conditions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c"># Structure if simple
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

            <h2 id="loops">{{ trans('app.formations.c.loops_title') }}</h2>
            <p>{!! trans('app.formations.c.loops_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c"># Boucle for avec range()
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

            <h2 id="functions">{{ trans('app.formations.c.functions_title') }}</h2>
            <p>{!! trans('app.formations.c.functions_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c"># Fonction simple (sans paramètres)
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

            <h2 id="pointers">{{ trans('app.formations.c.pointers_title') }}</h2>
            <p>{{ trans('app.formations.c.pointers_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>

int main() {
    int nombre = 42;
    int* ptr = &nombre;  // ptr pointe vers nombre
    
    printf("Valeur de nombre: %d\n", nombre);      // 42
    printf("Adresse de nombre: %p\n", &nombre);    // Adresse mémoire
    printf("Valeur de ptr: %p\n", ptr);            // Même adresse
    printf("Valeur pointée par ptr: %d\n", *ptr);  // 42 (déréférencement)
    
    // Modifier via le pointeur
    *ptr = 100;
    printf("Nouvelle valeur de nombre: %d\n", nombre);  // 100
    
    // Pointeur NULL
    int* ptr_null = NULL;
    if (ptr_null == NULL) {
        printf("Pointeur NULL\n");
    }
    
    // Pointeurs et tableaux
    int tableau[] = {1, 2, 3, 4, 5};
    int* ptr_tableau = tableau;  // tableau est déjà un pointeur
    
    printf("Premier élément: %d\n", *ptr_tableau);        // 1
    printf("Deuxième élément: %d\n", *(ptr_tableau + 1)); // 2
    printf("Troisième élément: %d\n", ptr_tableau[2]);    // 3
    
    return 0;
}</code></pre>
            </div>

            <h2 id="arrays">{{ trans('app.formations.c.arrays_title') }}</h2>
            <p>{{ trans('app.formations.c.arrays_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>

int main() {
    // Déclaration et initialisation
    int nombres[5] = {1, 2, 3, 4, 5};
    int tableau[10];  // Tableau non initialisé
    
    // Accès aux éléments (index commence à 0)
    printf("Premier élément: %d\n", nombres[0]);  // 1
    printf("Dernier élément: %d\n", nombres[4]);  // 5
    
    // Modification
    nombres[1] = 10;
    printf("nombres[1] = %d\n", nombres[1]);  // 10
    
    // Parcourir un tableau
    for (int i = 0; i < 5; i++) {
        printf("nombres[%d] = %d\n", i, nombres[i]);
    }
    
    // Tableaux multidimensionnels
    int matrice[3][3] = {
        {1, 2, 3},
        {4, 5, 6},
        {7, 8, 9}
    };
    
    for (int i = 0; i < 3; i++) {
        for (int j = 0; j < 3; j++) {
            printf("%d ", matrice[i][j]);
        }
        printf("\n");
    }
    
    // Tableaux de caractères (chaînes)
    char chaine[] = "Bonjour";
    char nom[20] = "C";
    
    printf("Chaine: %s\n", chaine);
    printf("Nom: %s\n", nom);
    
    return 0;
}</code></pre>
            </div>

            <h2 id="structs">{{ trans('app.formations.c.structs_title') }}</h2>
            <p>{{ trans('app.formations.c.structs_text') }}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>
#include <string.h>

// Définition d'une structure
struct Personne {
    char nom[50];
    int age;
    float taille;
};

int main() {
    // Création d'une variable de type Personne
    struct Personne p1;
    strcpy(p1.nom, "Bassirou");
    p1.age = 25;
    p1.taille = 1.75;
    
    printf("Nom: %s\n", p1.nom);
    printf("Age: %d\n", p1.age);
    printf("Taille: %.2f\n", p1.taille);
    
    // Initialisation lors de la déclaration
    struct Personne p2 = {"Aminata", 30, 1.65};
    printf("\nNom: %s, Age: %d\n", p2.nom, p2.age);
    
    // Tableau de structures
    struct Personne personnes[3] = {
        {"Bassirou", 25, 1.75},
        {"Aminata", 30, 1.65},
        {"Ibrahima", 28, 1.80}
    };
    
    for (int i = 0; i < 3; i++) {
        printf("%s a %d ans\n", personnes[i].nom, personnes[i].age);
    }
    
    // Pointeurs vers structures
    struct Personne* ptr = &p1;
    printf("\nNom via pointeur: %s\n", ptr->nom);  // Opérateur ->
    printf("Age via pointeur: %d\n", (*ptr).age);  // Ou (*ptr).age
    
    return 0;
}</code></pre>
            </div>

            <h2 id="memory">{{ trans('app.formations.c.memory_title') }}</h2>
            <p>{!! trans('app.formations.c.memory_text') !!}</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>
#include <stdlib.h>  // Pour malloc, calloc, realloc, free

int main() {
    // Allocation dynamique avec malloc
    int* ptr = (int*)malloc(5 * sizeof(int));  // Tableau de 5 entiers
    
    if (ptr == NULL) {
        printf("Erreur d'allocation mémoire\n");
        return 1;
    }
    
    // Utilisation de la mémoire allouée
    for (int i = 0; i < 5; i++) {
        ptr[i] = i * 10;
    }
    
    for (int i = 0; i < 5; i++) {
        printf("ptr[%d] = %d\n", i, ptr[i]);
    }
    
    // Libération de la mémoire
    free(ptr);
    ptr = NULL;  // Bonne pratique : mettre le pointeur à NULL
    
    // Allocation avec calloc (initialise à zéro)
    int* ptr2 = (int*)calloc(5, sizeof(int));
    printf("\nTableau initialisé à zéro:\n");
    for (int i = 0; i < 5; i++) {
        printf("ptr2[%d] = %d\n", i, ptr2[i]);
    }
    
    // Réallocation avec realloc
    ptr2 = (int*)realloc(ptr2, 10 * sizeof(int));  // Agrandir à 10 éléments
    printf("\nAprès réallocation:\n");
    for (int i = 5; i < 10; i++) {
        ptr2[i] = i * 10;
    }
    
    for (int i = 0; i < 10; i++) {
        printf("ptr2[%d] = %d\n", i, ptr2[i]);
    }
    
    free(ptr2);
    
    // Allocation pour une chaîne de caractères
    char* chaine = (char*)malloc(50 * sizeof(char));
    if (chaine != NULL) {
        strcpy(chaine, "Bonjour C !");
        printf("\nChaine: %s\n", chaine);
        free(chaine);
    }
    
    return 0;
}</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;"><strong>💡 Note importante :</strong> En C, vous devez toujours libérer la mémoire allouée avec <code>free()</code> pour éviter les fuites mémoire. N'oubliez jamais de vérifier si l'allocation a réussi (pointeur != NULL) avant d'utiliser la mémoire !</p>
            </div>

            <h2 id="files">📁 Manipulation de fichiers</h2>
            <p>En C, la manipulation de fichiers se fait avec les fonctions de la bibliothèque standard : <code>fopen()</code>, <code>fclose()</code>, <code>fprintf()</code>, <code>fscanf()</code>, <code>fread()</code>, <code>fwrite()</code>, etc.</p>

            <div class="code-box">
                <pre><code class="language-c">#include <stdio.h>

int main() {
    FILE* fichier;
    
    // Écrire dans un fichier (mode "w" = write)
    fichier = fopen("fichier.txt", "w");
    if (fichier == NULL) {
        printf("Erreur lors de l'ouverture du fichier\n");
        return 1;
    }
    
    fprintf(fichier, "Bonjour C !\n");
    fprintf(fichier, "Ceci est la deuxième ligne\n");
    fclose(fichier);
    
    // Lire un fichier (mode "r" = read)
    fichier = fopen("fichier.txt", "r");
    if (fichier == NULL) {
        printf("Erreur lors de l'ouverture du fichier\n");
        return 1;
    }
    
    char ligne[100];
    while (fgets(ligne, sizeof(ligne), fichier) != NULL) {
        printf("%s", ligne);
    }
    fclose(fichier);
    
    // Ajouter à un fichier (mode "a" = append)
    fichier = fopen("fichier.txt", "a");
    if (fichier != NULL) {
        fprintf(fichier, "Nouvelle ligne ajoutée\n");
        fclose(fichier);
    }
    
    // Modes de fichier
    // "r"  - Lecture (le fichier doit exister)
    // "w"  - Écriture (crée ou écrase le fichier)
    // "a"  - Ajout (ajoute à la fin, crée si n'existe pas)
    // "r+" - Lecture et écriture
    // "w+" - Lecture et écriture (crée ou écrase)
    // "a+" - Lecture et ajout
    
    return 0;
}</code></pre>
            </div>

            <div class="note-box">
                <p style="color: #000;">{!! trans('app.formations.c.memory_note') !!}</p>
            </div>

            <h2>{{ trans('app.formations.c.next_steps_title') }}</h2>
            <p>{{ trans('app.formations.c.next_steps_text') }}</p>
            
            <div class="example-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <h3 style="color: #000;">{{ trans('app.formations.c.next_steps_learned_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.c.next_steps_learned_items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="example-box">
                <h3 style="color: #000;">{{ trans('app.formations.c.next_steps_further_title') }}</h3>
                <ul style="margin-left: 20px; line-height: 2; color: #000;">
                    @foreach(trans('app.formations.c.next_steps_further_items') as $item)
                    @php
                        $parts = explode(' - ', $item);
                        $icon = '';
                        $label = '';
                        $description = '';
                        if (preg_match('/^([📚🔧📦🌐📊🤖]) (.+)$/u', $item, $matches)) {
                            $icon = $matches[1];
                            $rest = $matches[2];
                            $labelParts = explode(' - ', $rest);
                            $label = $labelParts[0] ?? '';
                            $description = $labelParts[1] ?? '';
                        } else {
                            $labelParts = explode(' - ', $item);
                            $label = $labelParts[0] ?? '';
                            $description = $labelParts[1] ?? '';
                        }
                    @endphp
                    <li>@if($icon){{ $icon }} @endif<strong>{{ $label }}</strong>@if($description) - {{ $description }}@endif</li>
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="{{ route('formations.ia') }}" class="nav-btn">{{ trans('app.formations.c.nav_previous') }}</a>
                <a href="{{ route('exercices') }}" class="nav-btn">{{ trans('app.formations.c.nav_next') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-c.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>
    // Initialiser Prism.js après le chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        function highlightCode() {
            if (typeof Prism !== 'undefined') {
                // Forcer la coloration de tous les blocs de code
                const codeElements = document.querySelectorAll('code[class*="language-"]');
                codeElements.forEach(function(code) {
                    try {
                        Prism.highlightElement(code);
                    } catch (e) {
                        console.error('Erreur Prism:', e);
                    }
                });
                Prism.highlightAll();
            }
        }
        
        highlightCode();
        setTimeout(highlightCode, 200);
        setTimeout(highlightCode, 500);
    });
    
    // Réinitialiser après le chargement complet
    window.addEventListener('load', function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
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
