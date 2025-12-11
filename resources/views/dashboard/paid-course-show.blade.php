@extends('dashboard.layout')

@section('title', ($course->title ?? 'Cours') . ' | NiangProgrammeur')

@section('dashboard-content')
<div class="course-detail-container">
    @php
        $currentChapterId = request('chapter', $course->chapters->first()->id ?? null);
        $currentChapter = $course->chapters->firstWhere('id', $currentChapterId) ?? $course->chapters->first();
    @endphp

    @if($currentChapter)
    <article class="course-chapter-content">
        <header class="chapter-header-section">
            <nav class="chapter-breadcrumb-nav">
                <a href="{{ route('dashboard.paid-courses') }}">Mes cours</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $course->title }}</span>
            </nav>
            <h1 class="chapter-main-heading">{{ $currentChapter->title }}</h1>
            @if($currentChapter->description)
            <p class="chapter-description-text">{{ $currentChapter->description }}</p>
            @endif
            <div class="chapter-metadata">
                @if($currentChapter->duration_minutes)
                <span class="meta-badge">
                    <i class="fas fa-clock"></i>
                    {{ $currentChapter->duration_minutes }} minutes
                </span>
                @endif
                <span class="meta-badge">
                    <i class="fas fa-list-ol"></i>
                    Chapitre {{ $course->chapters->search(function($item) use ($currentChapter) { return $item->id === $currentChapter->id; }) + 1 }} sur {{ $course->chapters->count() }}
                </span>
            </div>
        </header>

        <div class="chapter-body-section">
            @if($currentChapter->content)
            <div class="chapter-text-content" id="chapter-content-wrapper">
                {!! $currentChapter->content !!}
            </div>
            @else
            <div class="chapter-empty-state">
                <i class="fas fa-file-alt"></i>
                <p>Le contenu de ce chapitre sera disponible prochainement.</p>
            </div>
            @endif
        </div>

        <footer class="chapter-footer-section">
            @php
                $currentIndex = $course->chapters->search(function($item) use ($currentChapter) { return $item->id === $currentChapter->id; });
                $prevChapter = $currentIndex > 0 ? $course->chapters[$currentIndex - 1] : null;
                $nextChapter = $currentIndex < $course->chapters->count() - 1 ? $course->chapters[$currentIndex + 1] : null;
            @endphp

            @if($prevChapter)
            <a href="{{ route('dashboard.paid-courses.show', ['courseId' => $course->id, 'chapter' => $prevChapter->id]) }}" class="chapter-nav-button prev-button">
                <i class="fas fa-chevron-left"></i>
                <div>
                    <span class="nav-label-text">Précédent</span>
                    <span class="nav-chapter-title">{{ $prevChapter->title }}</span>
                </div>
            </a>
            @else
            <div></div>
            @endif

            @if($nextChapter)
            <a href="{{ route('dashboard.paid-courses.show', ['courseId' => $course->id, 'chapter' => $nextChapter->id]) }}" class="chapter-nav-button next-button">
                <div>
                    <span class="nav-label-text">Suivant</span>
                    <span class="nav-chapter-title">{{ $nextChapter->title }}</span>
                </div>
                <i class="fas fa-chevron-right"></i>
            </a>
            @else
            <a href="{{ route('dashboard.paid-courses') }}" class="chapter-nav-button next-button">
                <div>
                    <span class="nav-label-text">Terminé</span>
                    <span class="nav-chapter-title">Retour aux cours</span>
                </div>
                <i class="fas fa-check"></i>
            </a>
            @endif
        </footer>
    </article>
    @else
    <div class="course-empty-state">
        <i class="fas fa-exclamation-circle"></i>
        <h3>Aucun chapitre disponible</h3>
        <p>Ce cours n'a pas encore de contenu.</p>
    </div>
    @endif
</div>

@push('styles')
<style>
/* Masquer la navigation principale du sidebar sur cette page */
.dashboard-sidebar .sidebar-nav {
    display: none !important;
}

.dashboard-sidebar .sidebar-header {
    display: none !important;
}

/* Afficher uniquement la section Programme du cours */
.course-chapters-sidebar-section {
    margin-top: 0 !important;
    padding-top: 20px !important;
    border-top: none !important;
}

.course-detail-container {
    padding: 20px 0;
}

.course-chapter-content {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

body.dark-mode .course-chapter-content {
    background: rgba(30, 41, 59, 0.8);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.chapter-header-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid #e2e8f0;
}

body.dark-mode .chapter-header-section {
    border-bottom-color: rgba(6, 182, 212, 0.2);
}

.chapter-breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 20px;
}

body.dark-mode .chapter-breadcrumb-nav {
    color: rgba(255, 255, 255, 0.6);
}

.chapter-breadcrumb-nav a {
    color: #06b6d4;
    text-decoration: none;
}

.chapter-breadcrumb-nav i {
    font-size: 0.7rem;
}

.chapter-main-heading {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1e293b;
    margin: 0 0 15px 0;
    line-height: 1.2;
}

body.dark-mode .chapter-main-heading {
    color: white;
}

.chapter-description-text {
    font-size: 1.2rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 20px;
}

body.dark-mode .chapter-description-text {
    color: rgba(255, 255, 255, 0.7);
}

.chapter-metadata {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.meta-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 600;
}

body.dark-mode .meta-badge {
    color: rgba(255, 255, 255, 0.6);
}

.meta-badge i {
    color: #06b6d4;
}

.chapter-body-section {
    margin-bottom: 50px;
}

.chapter-text-content {
    font-size: 1.1rem;
    line-height: 1.9;
    color: #334155;
    white-space: pre-line;
}

body.dark-mode .chapter-text-content {
    color: rgba(255, 255, 255, 0.9);
}

.chapter-text-content h2,
.chapter-text-content h3 {
    color: #1e293b;
    margin-top: 30px;
    margin-bottom: 15px;
}

body.dark-mode .chapter-text-content h2,
body.dark-mode .chapter-text-content h3 {
    color: white;
}

.chapter-text-content p {
    margin-bottom: 20px;
}

.chapter-text-content ul,
.chapter-text-content ol {
    margin: 20px 0;
    padding-left: 30px;
}

.chapter-text-content code:not(.code-box code) {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

body.dark-mode .chapter-text-content code:not(.code-box code) {
    background: rgba(15, 23, 42, 0.6);
    color: #06b6d4;
}

/* Styles pour les blocs de code (comme dans les formations html5) */
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

.code-box pre {
    margin: 0;
    padding: 0;
    background: transparent;
    overflow: visible;
}

.code-box pre {
    margin: 0;
    padding: 0;
    background: transparent;
    overflow: visible;
}

.code-box pre code {
    display: block;
    max-width: 100%;
    overflow-wrap: break-word;
    line-height: 1.6;
    font-size: 0.95rem;
    white-space: pre;
    overflow-x: auto;
    margin: 0;
    padding: 0;
    background: transparent;
    /* Couleur par défaut visible si Prism.js ne fonctionne pas */
    color: #e2e8f0;
}

/* Styles pour Prism.js - permettre la coloration */
.code-box pre code[class*="language-"] {
    color: #e2e8f0;
    font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
    background: transparent !important;
}

/* Styles Prism.js pour les tokens - Style VS Code Dark (comme dans les autres formations) */
/* Styles généraux pour tous les tokens Prism - DOIT être avant les styles spécifiques */
.code-box .token {
    text-shadow: none !important;
    font-size: 0.95rem !important;
    line-height: 1.6 !important;
}

/* Styles spécifiques pour chaque type de token */
.code-box .token.comment,
.code-box .token.prolog,
.code-box .token.doctype,
.code-box .token.cdata,
.code-box code[class*="language-"] .token.comment,
.code-box code[class*="language-"] .token.prolog,
.code-box code[class*="language-"] .token.doctype,
.code-box code[class*="language-"] .token.cdata {
    color: #6a9955 !important;
    font-style: italic;
}

.code-box .token.string,
.code-box .token.attr-value,
.code-box code[class*="language-"] .token.string,
.code-box code[class*="language-"] .token.attr-value {
    color: #ce9178 !important;
}

.code-box .token.keyword,
.code-box .token.boolean,
.code-box code[class*="language-"] .token.keyword,
.code-box code[class*="language-"] .token.boolean {
    color: #569cd6 !important;
    font-weight: 500;
}

.code-box .token.operator,
.code-box code[class*="language-"] .token.operator {
    color: #d4d4d4 !important;
}

.code-box .token.function,
.code-box code[class*="language-"] .token.function {
    color: #dcdcaa !important;
}

.code-box .token.class-name,
.code-box code[class*="language-"] .token.class-name {
    color: #4ec9b0 !important;
}

.code-box .token.number,
.code-box code[class*="language-"] .token.number {
    color: #b5cea8 !important;
}

.code-box .token.punctuation,
.code-box code[class*="language-"] .token.punctuation {
    color: #d4d4d4 !important;
}

.code-box .token.variable,
.code-box .token.property,
.code-box code[class*="language-"] .token.variable,
.code-box code[class*="language-"] .token.property {
    color: #9cdcfe !important;
}

.code-box .token.tag,
.code-box code[class*="language-"] .token.tag {
    color: #569cd6 !important;
}

.code-box .token.attr-name,
.code-box code[class*="language-"] .token.attr-name {
    color: #9cdcfe !important;
}

.code-box .token.selector,
.code-box code[class*="language-"] .token.selector {
    color: #d7ba7d !important;
}

/* S'assurer que les tokens Prism héritent correctement */
.code-box pre code .token {
    font-size: 0.95rem !important;
    line-height: 1.6 !important;
    font-weight: 400 !important;
    text-shadow: none !important;
}

/* Si Prism.js n'est pas chargé, garder la couleur par défaut */
.code-box pre code:not(.token) {
    color: #e2e8f0;
}

/* FORCER l'application des styles Prism - Surcharge agressive */
.code-box pre code[class*="language-"] *,
.code-box pre code[class*="language-"] .token {
    /* Les styles spécifiques ci-dessus s'appliqueront avec !important */
}

/* S'assurer que le thème Prism.js peut s'appliquer */
.code-box pre[class*="language-"],
.code-box pre code[class*="language-"] {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
}

.code-box::before {
    content: attr(data-language);
    position: absolute;
    top: 10px;
    right: 15px;
    background: #04AA6D;
    color: white;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    z-index: 1;
}

.copy-code-btn {
    position: absolute;
    top: 10px;
    right: 80px;
    background: #04AA6D;
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
    gap: 5px;
}

.copy-code-btn:hover {
    background: #038f5a;
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

/* Styles pour le dark mode des blocs de code */
body.dark-mode .code-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

body.dark-mode .code-box code {
    color: #e2e8f0;
}

.chapter-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

body.dark-mode .chapter-empty-state {
    color: rgba(255, 255, 255, 0.6);
}

.chapter-empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #06b6d4;
}

.chapter-footer-section {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding-top: 30px;
    border-top: 2px solid #e2e8f0;
}

body.dark-mode .chapter-footer-section {
    border-top-color: rgba(6, 182, 212, 0.2);
}

.chapter-nav-button {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 280px;
}

body.dark-mode .chapter-nav-button {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.2);
}

.chapter-nav-button:hover {
    background: rgba(6, 182, 212, 0.1);
    border-color: #06b6d4;
    transform: translateY(-2px);
}

.next-button {
    flex-direction: row-reverse;
    margin-left: auto;
}

.nav-label-text {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 3px;
}

body.dark-mode .nav-label-text {
    color: rgba(255, 255, 255, 0.6);
}

.nav-chapter-title {
    display: block;
    font-weight: 600;
    color: #1e293b;
    font-size: 0.85rem;
    line-height: 1.3;
}

body.dark-mode .nav-chapter-title {
    color: white;
}

.chapter-nav-button i {
    color: #06b6d4;
    font-size: 1rem;
}

.course-empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #64748b;
}

body.dark-mode .course-empty-state {
    color: rgba(255, 255, 255, 0.6);
}

.course-empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #06b6d4;
}

@media (max-width: 768px) {
    .chapter-footer-section {
        flex-direction: column;
    }

    .chapter-nav-button {
        max-width: 100%;
    }

    .next-button {
        margin-left: 0;
    }

    .chapter-main-heading {
        font-size: 2rem;
    }

    .course-chapter-content {
        padding: 25px;
    }
}
</style>
@endpush

@push('sidebar-extra')
<div class="course-chapters-sidebar-section">
    <div class="chapters-section-header">
        <a href="{{ route('dashboard.paid-courses') }}" class="chapters-back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Retour aux cours</span>
        </a>
        <h4 class="chapters-section-title">
            <i class="fas fa-list-ul"></i>
            Programme du cours
        </h4>
    </div>
    <div class="chapters-list-container">
        @foreach($course->chapters as $index => $chapter)
        <a href="{{ route('dashboard.paid-courses.show', ['courseId' => $course->id, 'chapter' => $chapter->id]) }}" 
           class="chapter-sidebar-item {{ request('chapter') == $chapter->id || (!request()->has('chapter') && $index === 0) ? 'active' : '' }}">
            <span class="chapter-index">{{ $index + 1 }}</span>
            <div class="chapter-sidebar-info">
                <span class="chapter-sidebar-title">{{ $chapter->title }}</span>
                @if($chapter->duration_minutes)
                <span class="chapter-sidebar-duration">
                    <i class="fas fa-clock"></i>
                    {{ $chapter->duration_minutes }} min
                </span>
                @endif
            </div>
            @if(request('chapter') == $chapter->id || (!request()->has('chapter') && $index === 0))
            <i class="fas fa-check-circle chapter-check-icon"></i>
            @endif
        </a>
        @endforeach
    </div>
</div>

<style>
.course-chapters-sidebar-section {
    margin-top: 0 !important;
    padding-top: 20px !important;
    border-top: none !important;
    padding: 20px !important;
}

.chapters-section-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(4, 170, 109, 0.2);
}

.chapters-back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #04AA6D;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 15px;
    transition: all 0.2s;
}

.chapters-back-button:hover {
    background: #038f5a;
    transform: translateX(-2px);
}

.chapters-section-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #04AA6D;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

body.dark-mode .chapters-section-title {
    color: #04AA6D;
}

.chapters-list-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.chapter-sidebar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    background: rgba(4, 170, 109, 0.05);
}

body.dark-mode .chapter-sidebar-item {
    background: rgba(4, 170, 109, 0.08);
}

.chapter-sidebar-item:hover {
    background: rgba(4, 170, 109, 0.1);
    border-color: rgba(4, 170, 109, 0.3);
    transform: translateX(5px);
}

body.dark-mode .chapter-sidebar-item:hover {
    background: rgba(4, 170, 109, 0.15);
}

.chapter-sidebar-item.active {
    background: rgba(4, 170, 109, 0.15);
    border-color: #04AA6D;
    box-shadow: 0 4px 12px rgba(4, 170, 109, 0.2);
}

body.dark-mode .chapter-sidebar-item.active {
    background: rgba(4, 170, 109, 0.2);
}

.chapter-index {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #04AA6D, #038f5a);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(4, 170, 109, 0.3);
}

.chapter-sidebar-item.active .chapter-index {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 12px rgba(4, 170, 109, 0.4);
    transform: scale(1.1);
}

.chapter-sidebar-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.chapter-sidebar-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

body.dark-mode .chapter-sidebar-title {
    color: rgba(255, 255, 255, 0.9);
}

.chapter-sidebar-duration {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

body.dark-mode .chapter-sidebar-duration {
    color: rgba(255, 255, 255, 0.6);
}

.chapter-check-icon {
    color: #10b981;
    font-size: 1rem;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.chapter-sidebar-item.active .chapter-check-icon {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<!-- Highlight.js pour la coloration syntaxique - Alternative plus fiable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/json.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/java.min.js"></script>

<style>
/* Styles pour Highlight.js */
.code-box pre {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
}

.code-box pre code {
    display: block;
    padding: 15px;
    background: #1e293b;
    border-radius: 8px;
    overflow-x: auto;
    color: #e2e8f0;
    font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* S'assurer que Highlight.js peut appliquer ses styles */
.code-box pre code.hljs {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}

/* Styles pour les tokens Highlight.js */
.code-box pre code .hljs-keyword,
.code-box pre code .hljs-selector-tag,
.code-box pre code .hljs-literal,
.code-box pre code .hljs-title,
.code-box pre code .hljs-section,
.code-box pre code .hljs-doctag,
.code-box pre code .hljs-type,
.code-box pre code .hljs-name,
.code-box pre code .hljs-strong {
    color: #c792ea !important;
    font-weight: 600 !important;
}

.code-box pre code .hljs-comment,
.code-box pre code .hljs-quote {
    color: #5c6370 !important;
    font-style: italic !important;
}

.code-box pre code .hljs-string,
.code-box pre code .hljs-attr,
.code-box pre code .hljs-template-variable,
.code-box pre code .hljs-attribute {
    color: #c3e88d !important;
}

.code-box pre code .hljs-number,
.code-box pre code .hljs-literal {
    color: #f78c6c !important;
}

.code-box pre code .hljs-function,
.code-box pre code .hljs-title.function_ {
    color: #82aaff !important;
}

.code-box pre code .hljs-variable,
.code-box pre code .hljs-template-variable {
    color: #eeffff !important;
}

.code-box pre code .hljs-tag,
.code-box pre code .hljs-name {
    color: #f07178 !important;
}

.code-box pre code .hljs-attr {
    color: #ffcb6b !important;
}

.code-box pre code .hljs-built_in,
.code-box pre code .hljs-builtin-name {
    color: #ffcb6b !important;
}
</style>

<script>
// SOLUTION DÉFINITIVE - Highlight.js avec décodage HTML correct
(function() {
    'use strict';
    
    // Fonction pour décoder les entités HTML
    function decodeHtmlEntities(text) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }
    
    // Fonction pour obtenir le texte brut du code (décodé)
    function getRawCodeText(codeElement) {
        // Essayer d'abord textContent (décode automatiquement)
        let text = codeElement.textContent || codeElement.innerText || '';
        
        // Si le texte contient encore des entités HTML, les décoder
        if (text.includes('&lt;') || text.includes('&gt;') || text.includes('&amp;') || text.includes('&quot;')) {
            text = decodeHtmlEntities(text);
        }
        
        return text;
    }
    
    function highlightAllCode() {
        if (typeof hljs === 'undefined') {
            console.warn('Highlight.js non chargé, nouvelle tentative...');
            setTimeout(highlightAllCode, 200);
            return;
        }
        
        try {
            // Sélectionner TOUS les blocs de code possibles
            const codeBlocks = document.querySelectorAll('.code-box pre code[class*="language-"], pre code[class*="language-"], code[class*="language-"]');
            console.log('Blocs de code trouvés:', codeBlocks.length);
            
            if (codeBlocks.length === 0) {
                console.warn('Aucun bloc de code trouvé');
                return;
            }
            
            let successCount = 0;
            
            codeBlocks.forEach(function(code, index) {
                try {
                    // Ignorer si déjà traité
                    if (code.classList.contains('hljs') && code.innerHTML !== code.textContent) {
                        return;
                    }
                    
                    // Extraire le langage de la classe
                    const langMatch = code.className.match(/language-(\w+)/);
                    const lang = langMatch ? langMatch[1] : null;
                    
                    if (!lang) {
                        console.warn('Bloc', index + 1, 'sans langage détecté');
                        return;
                    }
                    
                    // Mapper les langages si nécessaire
                    const langMap = {
                        'js': 'javascript',
                        'py': 'python',
                        'rb': 'ruby',
                        'sh': 'bash',
                        'yml': 'yaml',
                        'md': 'markdown',
                    };
                    
                    const highlightLang = langMap[lang.toLowerCase()] || lang.toLowerCase();
                    
                    // Obtenir le texte brut décodé
                    const rawCode = getRawCodeText(code);
                    
                    if (!rawCode || rawCode.trim().length === 0) {
                        console.warn('Bloc', index + 1, 'vide');
                        return;
                    }
                    
                    // Vérifier si le langage est supporté
                    if (hljs.getLanguage(highlightLang)) {
                        // Appliquer Highlight.js avec le texte brut décodé
                        const result = hljs.highlight(rawCode, { language: highlightLang });
                        code.innerHTML = result.value;
                        code.classList.add('hljs');
                        successCount++;
                        console.log('✓ Bloc', index + 1, 'coloré (lang:', highlightLang + ')');
                    } else {
                        // Langage non supporté, essayer auto-détection
                        code.textContent = rawCode; // Réinitialiser avec le texte brut
                        hljs.highlightElement(code);
                        code.classList.add('hljs');
                        successCount++;
                        console.log('✓ Bloc', index + 1, 'coloré avec auto-détection');
                    }
                } catch (e) {
                    console.error('✗ Erreur sur le bloc', index + 1, ':', e);
                    // En cas d'erreur, au moins s'assurer que le code est visible
                    const codeElement = code;
                    if (codeElement.textContent) {
                        codeElement.style.color = '#e2e8f0';
                    }
                }
            });
            
            console.log('✅ Highlight.js appliqué:', successCount, '/', codeBlocks.length, 'blocs');
            
            // Si certains blocs n'ont pas été colorés, réessayer
            if (successCount < codeBlocks.length) {
                console.log('Réessai pour les blocs non colorés...');
                setTimeout(highlightAllCode, 500);
            }
        } catch (error) {
            console.error('✗ Erreur lors de l\'application de Highlight.js:', error);
        }
    }
    
    // Fonction d'initialisation avec multiples tentatives
    function initHighlighting() {
        highlightAllCode();
        setTimeout(highlightAllCode, 200);
        setTimeout(highlightAllCode, 500);
        setTimeout(highlightAllCode, 1000);
        setTimeout(highlightAllCode, 2000);
    }
    
    // Appliquer dès que possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHighlighting);
    } else {
        initHighlighting();
    }
    
    // Réappliquer après le chargement complet
    window.addEventListener('load', function() {
        console.log('🔄 Window load - Réapplication de Highlight.js');
        setTimeout(highlightAllCode, 300);
        setTimeout(highlightAllCode, 1000);
    });
    
    // Observer les changements dans le DOM
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            let shouldHighlight = false;
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    const hasCodeBlocks = Array.from(mutation.addedNodes).some(function(node) {
                        if (node.nodeType !== 1) return false;
                        return (
                            (node.querySelector && node.querySelector('code[class*="language-"]')) ||
                            (node.matches && node.matches('code[class*="language-"]')) ||
                            (node.classList && node.classList.contains('code-box'))
                        );
                    });
                    if (hasCodeBlocks) shouldHighlight = true;
                }
            });
            if (shouldHighlight) {
                console.log('🔄 Nouveaux blocs détectés, réapplication...');
                setTimeout(highlightAllCode, 200);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // Tentative finale après 3 secondes
    setTimeout(function() {
        console.log('🔄 Tentative finale de coloration');
        highlightAllCode();
    }, 3000);
})();

// Fonction pour copier le code (conservée)
window.copyCodeToClipboard = function(button, codeElement) {
                // Coloration PHP basique
                content = content
                    .replace(/(\/\/.*$|\/\*[\s\S]*?\*\/)/gm, '<span class="token comment">$1</span>')
                    .replace(/(&lt;\?php|\?&gt;)/g, '<span class="token keyword">$1</span>')
                    .replace(/\b(function|class|namespace|use|return|if|else|foreach|for|while|public|private|protected|extends|implements)\b/g, 
                        '<span class="token keyword">$1</span>')
                    .replace(/(&quot;[^&]*?&quot;|'[^']*?')/g, '<span class="token string">$1</span>');
                
                if (content !== originalContent) {
                    code.innerHTML = content;
                }
            } else if (lang === 'javascript' || lang === 'js') {
                // Coloration JavaScript basique
                content = content
                    .replace(/(\/\/.*$|\/\*[\s\S]*?\*\/)/gm, '<span class="token comment">$1</span>')
                    .replace(/\b(function|const|let|var|return|if|else|for|while|class|extends|import|export)\b/g, 
                        '<span class="token keyword">$1</span>')
                    .replace(/(&quot;[^&]*?&quot;|'[^']*?')/g, '<span class="token string">$1</span>');
                
                if (content !== originalContent) {
                    code.innerHTML = content;
                }
            }
        });
        
        console.log('Coloration de fallback appliquée');
    }
    
    // Fonction pour vérifier et forcer la coloration
    function forceHighlight() {
        const result = highlightCode();
        
        if (!result && highlightAttempts < maxAttempts) {
            // Réessayer après un délai
            setTimeout(forceHighlight, 300);
        } else if (!result) {
            // Après plusieurs tentatives, utiliser le fallback
            applyFallbackColoration();
        }
    }
    
    // Appliquer dès que possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            forceHighlight();
            setTimeout(forceHighlight, 200);
            setTimeout(forceHighlight, 500);
            setTimeout(forceHighlight, 1000);
        });
    } else {
        // DOM déjà chargé
        forceHighlight();
        setTimeout(forceHighlight, 200);
        setTimeout(forceHighlight, 500);
        setTimeout(forceHighlight, 1000);
    }
    
    // Réinitialiser après le chargement complet
    window.addEventListener('load', function() {
        console.log('Window load - Réapplication de Prism.js');
        highlightAttempts = 0; // Réinitialiser le compteur
        forceHighlight();
    });
    
    // Observer les changements dans le DOM (pour le contenu chargé dynamiquement)
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    const hasCodeBlocks = Array.from(mutation.addedNodes).some(function(node) {
                        return node.nodeType === 1 && (
                            node.querySelector && node.querySelector('code[class*="language-"]') ||
                            node.matches && node.matches('code[class*="language-"]')
                        );
                    });
                    
                    if (hasCodeBlocks) {
                        console.log('Nouveaux blocs de code détectés, réapplication de Prism.js');
                        setTimeout(forceHighlight, 100);
                    }
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();

    // Fonction pour copier le code
    window.copyCodeToClipboard = function(button, codeElement) {
        const codeText = codeElement.innerText || codeElement.textContent;
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(codeText).then(function() {
                const originalText = button.querySelector('span').textContent;
                button.classList.add('copied');
                button.querySelector('span').textContent = 'Copié !';
                button.setAttribute('title', 'Code copié !');
                
                setTimeout(function() {
                    button.classList.remove('copied');
                    button.querySelector('span').textContent = originalText;
                    button.setAttribute('title', 'Copier le code');
                }, 2000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie:', err);
                fallbackCopyTextToClipboard(codeText, button);
            });
        } else {
            fallbackCopyTextToClipboard(codeText, button);
        }
    };

    // Méthode de fallback pour copier
    function fallbackCopyTextToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                const originalText = button.querySelector('span').textContent;
                button.classList.add('copied');
                button.querySelector('span').textContent = 'Copié !';
                button.setAttribute('title', 'Code copié !');
                
                setTimeout(function() {
                    button.classList.remove('copied');
                    button.querySelector('span').textContent = originalText;
                    button.setAttribute('title', 'Copier le code');
                }, 2000);
            } else {
                alert('Impossible de copier le code. Veuillez le sélectionner manuellement.');
            }
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
            alert('Impossible de copier le code. Veuillez le sélectionner manuellement.');
        }
        
        document.body.removeChild(textArea);
    }
});
</script>
@endpush
@endsection
