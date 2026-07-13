@extends('dashboard.layout')

@php
    $hideAds = true;
@endphp

@section('title', ($course->title ?? 'Cours') . ' | NiangProgrammeur')

@section('dashboard-content')
<div class="course-detail-container" data-course-id="{{ $course->id }}">
    @if($currentChapter)
    @php
        $currentIndex = $course->chapters->search(function($item) use ($currentChapter) { return $item->id === $currentChapter->id; });
        $prevChapter = $currentIndex > 0 ? $course->chapters[$currentIndex - 1] : null;
        $nextChapter = $currentIndex < $course->chapters->count() - 1 ? $course->chapters[$currentIndex + 1] : null;
        $totalChapters = $course->chapters->count();
        $completedChapters = $progress->completed_chapters ?? [];
        $isCurrentCompleted = in_array($currentChapter->id, $completedChapters);
        $progressPercentage = $progress->progress_percentage ?? 0;
        $isCourseCompleted = $progressPercentage >= 100;
    @endphp

    @if($isCourseCompleted)
    <div class="course-completed-banner">
        <div class="completed-banner-icon"><i class="fas fa-trophy"></i></div>
        <div class="completed-banner-text">
            <h3>Félicitations, vous avez terminé ce cours !</h3>
            <p>Récupérez votre certificat de réussite pour valoriser cette formation.</p>
        </div>
        @if($certificate)
        <a href="{{ route('dashboard.certificates.show', $certificate->id) }}" class="completed-banner-btn">
            <i class="fas fa-certificate"></i>
            <span>Voir mon certificat</span>
        </a>
        @else
        <form action="{{ route('dashboard.certificates.generate-paid-course', $course->id) }}" method="POST" class="completed-banner-form">
            @csrf
            <button type="submit" class="completed-banner-btn">
                <i class="fas fa-certificate"></i>
                <span>Obtenir mon certificat</span>
            </button>
        </form>
        @endif
    </div>
    @endif

    <article class="course-chapter-content">
        <header class="chapter-header-section">
            <nav class="chapter-breadcrumb-nav">
                <a href="{{ route('dashboard.paid-courses') }}">Mes cours</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $course->title }}</span>
            </nav>

            <div class="chapter-header-top">
                <div class="chapter-header-titles">
                    <h1 class="chapter-main-heading">{{ $currentChapter->title }}</h1>
                    @if($currentChapter->description)
                    <p class="chapter-description-text">{{ $currentChapter->description }}</p>
                    @endif
                </div>
                <button type="button"
                        class="chapter-complete-toggle {{ $isCurrentCompleted ? 'is-completed' : '' }}"
                        id="chapterCompleteBtn"
                        data-complete-url="{{ route('dashboard.paid-courses.chapter.complete', ['courseId' => $course->id, 'chapterId' => $currentChapter->id]) }}">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $isCurrentCompleted ? 'Chapitre terminé' : 'Marquer comme terminé' }}</span>
                </button>
            </div>

            <div class="chapter-metadata">
                @if($currentChapter->duration_minutes)
                <span class="meta-badge">
                    <i class="fas fa-clock"></i>
                    {{ $currentChapter->duration_minutes }} minutes
                </span>
                @endif
                <span class="meta-badge">
                    <i class="fas fa-list-ol"></i>
                    Chapitre {{ $currentIndex + 1 }} sur {{ $totalChapters }}
                </span>
                <span class="meta-badge">
                    <i class="fas fa-chart-line"></i>
                    <span id="chapterProgressBadgeText">{{ $progressPercentage }}</span>% du cours complété
                </span>
            </div>

            <div class="chapter-progress-track">
                <div class="chapter-progress-fill" id="chapterProgressFill" style="width: {{ $progressPercentage }}%"></div>
            </div>
        </header>

        <div class="chapter-body-section">
            @php
                $chapterContent = $currentChapter->processed_content ?? null;
                if (!$chapterContent) {
                    $chapterContent = $currentChapter->localized_content ?? $currentChapter->content_fr ?? $currentChapter->content;
                }
            @endphp
            @if($chapterContent)
            <div class="chapter-text-content" id="chapter-content-wrapper">
                {!! $chapterContent !!}
            </div>
            @else
            <div class="chapter-empty-state">
                <i class="fas fa-file-alt"></i>
                <p>Le contenu de ce chapitre sera disponible prochainement.</p>
            </div>
            @endif
        </div>

        <footer class="chapter-footer-section">
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
            <a href="{{ route('dashboard.paid-courses.show', ['courseId' => $course->id, 'chapter' => $nextChapter->id]) }}"
               class="chapter-nav-button next-button"
               id="nextChapterLink"
               data-complete-url="{{ route('dashboard.paid-courses.chapter.complete', ['courseId' => $course->id, 'chapterId' => $currentChapter->id]) }}">
                <div>
                    <span class="nav-label-text">Suivant</span>
                    <span class="nav-chapter-title">{{ $nextChapter->title }}</span>
                </div>
                <i class="fas fa-chevron-right"></i>
            </a>
            @else
            <a href="{{ route('dashboard.paid-courses') }}"
               class="chapter-nav-button next-button finish-button"
               id="nextChapterLink"
               data-complete-url="{{ route('dashboard.paid-courses.chapter.complete', ['courseId' => $course->id, 'chapterId' => $currentChapter->id]) }}">
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

.course-chapters-sidebar-section {
    margin-top: 0 !important;
    padding-top: 20px !important;
    border-top: none !important;
}

.course-detail-container {
    padding: 20px 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ============================================
   BANNIÈRE "COURS TERMINÉ"
   ============================================ */
.course-completed-banner {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px 30px;
    background: linear-gradient(135deg, #04AA6D 0%, #06b6d4 100%);
    border-radius: 16px;
    color: white;
    box-shadow: 0 10px 30px rgba(4, 170, 109, 0.3);
}

.completed-banner-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
}

.completed-banner-text {
    flex: 1;
    min-width: 0;
}

.completed-banner-text h3 {
    margin: 0 0 4px 0;
    font-size: 1.15rem;
    font-weight: 800;
}

.completed-banner-text p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.completed-banner-form {
    flex-shrink: 0;
}

.completed-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 22px;
    background: white;
    color: #04AA6D;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.25s ease;
}

.completed-banner-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
    .course-completed-banner {
        flex-direction: column;
        text-align: center;
    }
}

/* ============================================
   ARTICLE / CARTE PRINCIPALE
   ============================================ */
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
    padding-bottom: 24px;
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

.chapter-header-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 15px;
}

.chapter-header-titles {
    flex: 1;
    min-width: 0;
}

.chapter-main-heading {
    font-size: 2.25rem;
    font-weight: 900;
    color: #1e293b;
    margin: 0 0 12px 0;
    line-height: 1.2;
}

body.dark-mode .chapter-main-heading {
    color: white;
}

.chapter-description-text {
    font-size: 1.1rem;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
}

body.dark-mode .chapter-description-text {
    color: rgba(255, 255, 255, 0.7);
}

.chapter-complete-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: white;
    border: 2px solid #04AA6D;
    color: #04AA6D;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.25s ease;
}

body.dark-mode .chapter-complete-toggle {
    background: rgba(15, 23, 42, 0.6);
}

.chapter-complete-toggle:hover {
    background: rgba(4, 170, 109, 0.1);
    transform: translateY(-2px);
}

.chapter-complete-toggle.is-completed {
    background: linear-gradient(135deg, #04AA6D, #038f5a);
    color: white;
    border-color: transparent;
}

.chapter-complete-toggle.is-completed:hover {
    filter: brightness(1.05);
}

.chapter-metadata {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 18px;
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

.chapter-progress-track {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}

body.dark-mode .chapter-progress-track {
    background: rgba(255, 255, 255, 0.1);
}

.chapter-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #04AA6D, #06b6d4);
    border-radius: 999px;
    transition: width 0.6s ease;
}

.chapter-body-section {
    margin-bottom: 50px;
}

/* ============================================
   CONTENU DU CHAPITRE (texte enrichi)
   ============================================ */
.chapter-text-content {
    font-size: 1.05rem;
    line-height: 1.85;
    color: #334155;
}

body.dark-mode .chapter-text-content {
    color: rgba(255, 255, 255, 0.9);
}

.chapter-text-content h2,
.chapter-text-content h3,
.chapter-text-content h4 {
    color: #1e293b;
    margin-top: 34px;
    margin-bottom: 14px;
    font-weight: 800;
}

body.dark-mode .chapter-text-content h2,
body.dark-mode .chapter-text-content h3,
body.dark-mode .chapter-text-content h4 {
    color: white;
}

.chapter-text-content p {
    margin: 0 0 18px 0;
}

.chapter-text-content ul,
.chapter-text-content ol {
    margin: 0 0 20px 0;
    padding-left: 26px;
}

.chapter-text-content ul {
    list-style: disc;
}

.chapter-text-content ol {
    list-style: decimal;
}

.chapter-text-content li {
    margin-bottom: 8px;
    list-style: inherit;
}

.chapter-text-content strong {
    color: #1e293b;
    font-weight: 700;
}

body.dark-mode .chapter-text-content strong {
    color: white;
}

.chapter-divider {
    border: none;
    border-top: 2px dashed #e2e8f0;
    margin: 34px 0;
}

body.dark-mode .chapter-divider {
    border-top-color: rgba(255, 255, 255, 0.15);
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

/* ============================================
   CALLOUTS (encadrés emoji : Objectif, Explication, ...)
   ============================================ */
.content-callout {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 20px;
    border-radius: 12px;
    margin: 0 0 18px 0;
    border-left: 4px solid;
    font-size: 1rem;
}

.callout-icon {
    font-size: 1.35rem;
    line-height: 1;
    flex-shrink: 0;
}

.callout-body {
    flex: 1;
    color: #334155;
    line-height: 1.6;
}

body.dark-mode .callout-body {
    color: rgba(255, 255, 255, 0.9);
}

.callout-objective { background: rgba(6, 182, 212, 0.08); border-color: #06b6d4; }
.callout-info      { background: rgba(59, 130, 246, 0.08); border-color: #3b82f6; }
.callout-example   { background: rgba(99, 102, 241, 0.08); border-color: #6366f1; }
.callout-tip        { background: rgba(245, 158, 11, 0.1); border-color: #f59e0b; }
.callout-warning    { background: rgba(239, 68, 68, 0.08); border-color: #ef4444; }
.callout-summary    { background: rgba(4, 170, 109, 0.08); border-color: #04AA6D; }
.callout-exercise   { background: rgba(168, 85, 247, 0.08); border-color: #a855f7; }
.callout-success    { background: rgba(16, 185, 129, 0.1); border-color: #10b981; }
.callout-note       { background: #f8fafc; border-color: #94a3b8; }

body.dark-mode .callout-objective { background: rgba(6, 182, 212, 0.12); }
body.dark-mode .callout-info      { background: rgba(59, 130, 246, 0.12); }
body.dark-mode .callout-example   { background: rgba(99, 102, 241, 0.12); }
body.dark-mode .callout-tip        { background: rgba(245, 158, 11, 0.14); }
body.dark-mode .callout-warning    { background: rgba(239, 68, 68, 0.12); }
body.dark-mode .callout-summary    { background: rgba(4, 170, 109, 0.14); }
body.dark-mode .callout-exercise   { background: rgba(168, 85, 247, 0.14); }
body.dark-mode .callout-success    { background: rgba(16, 185, 129, 0.14); }
body.dark-mode .callout-note       { background: rgba(148, 163, 184, 0.1); }

/* ============================================
   QUIZ INTERACTIF
   ============================================ */
.quiz-card-modern {
    margin: 34px 0 10px 0;
    border-radius: 18px;
    border: 1px solid rgba(4, 170, 109, 0.25);
    background: linear-gradient(180deg, rgba(4, 170, 109, 0.04), transparent 120px);
    overflow: hidden;
}

body.dark-mode .quiz-card-modern {
    background: linear-gradient(180deg, rgba(4, 170, 109, 0.08), transparent 120px);
    border-color: rgba(4, 170, 109, 0.3);
}

.quiz-modern-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 22px 26px;
    border-bottom: 1px solid rgba(4, 170, 109, 0.15);
}

.quiz-modern-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #04AA6D, #06b6d4);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.quiz-modern-header h3 {
    margin: 0 0 2px 0 !important;
    font-size: 1.15rem !important;
}

.quiz-modern-header p {
    margin: 0;
    font-size: 0.85rem;
    color: #64748b;
}

body.dark-mode .quiz-modern-header p {
    color: rgba(255, 255, 255, 0.6);
}

.quiz-modern-body {
    padding: 10px 26px 26px 26px;
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.quiz-question-card {
    padding-top: 18px;
    border-top: 1px dashed rgba(4, 170, 109, 0.2);
}

.quiz-question-card:first-child {
    border-top: none;
    padding-top: 4px;
}

.quiz-question-head {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}

.quiz-question-number {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #04AA6D;
    color: white;
    font-size: 0.8rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}

.quiz-question-text {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.5;
}

body.dark-mode .quiz-question-text {
    color: white;
}

.quiz-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding-left: 38px;
}

.quiz-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    text-align: left;
    cursor: pointer;
    font-size: 0.925rem;
    color: #334155;
    transition: all 0.2s ease;
}

body.dark-mode .quiz-option {
    background: rgba(15, 23, 42, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.85);
}

.quiz-option:hover:not(:disabled) {
    border-color: #06b6d4;
    transform: translateX(3px);
}

.quiz-option-letter {
    width: 24px;
    height: 24px;
    border-radius: 7px;
    background: rgba(6, 182, 212, 0.12);
    color: #06b6d4;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.quiz-option-text {
    flex: 1;
}

.quiz-option-feedback {
    display: none;
    font-size: 0.9rem;
}

.quiz-option-feedback .fa-times { color: #ef4444; }
.quiz-option-feedback .fa-check { color: #10b981; }

.quiz-option.is-selected.is-correct {
    background: rgba(16, 185, 129, 0.12);
    border-color: #10b981;
}

.quiz-option.is-selected.is-incorrect {
    background: rgba(239, 68, 68, 0.1);
    border-color: #ef4444;
}

.quiz-option.reveal-correct {
    border-color: #10b981;
}

.quiz-option.is-selected .quiz-option-feedback,
.quiz-option.reveal-correct .quiz-option-feedback {
    display: inline-flex;
}

.quiz-option.is-selected.is-correct .quiz-option-feedback .fa-times,
.quiz-option.is-selected.is-incorrect .quiz-option-feedback .fa-check,
.quiz-option.reveal-correct .quiz-option-feedback .fa-times {
    display: none;
}

.quiz-option:disabled {
    cursor: default;
}

.quiz-tf-options {
    display: flex;
    gap: 10px;
    padding-left: 38px;
    margin-bottom: 12px;
}

.quiz-tf-btn {
    padding: 10px 24px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-weight: 700;
    font-size: 0.9rem;
    color: #334155;
    cursor: pointer;
    transition: all 0.2s ease;
}

body.dark-mode .quiz-tf-btn {
    background: rgba(15, 23, 42, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.85);
}

.quiz-tf-btn:hover:not(:disabled) {
    border-color: #06b6d4;
}

.quiz-tf-btn.is-correct {
    background: rgba(16, 185, 129, 0.15);
    border-color: #10b981;
    color: #059669;
}

.quiz-tf-btn.is-incorrect {
    background: rgba(239, 68, 68, 0.12);
    border-color: #ef4444;
    color: #dc2626;
}

.quiz-tf-reveal {
    display: none;
    margin-left: 38px;
    padding: 12px 16px;
    background: rgba(4, 170, 109, 0.08);
    border-radius: 10px;
    font-size: 0.9rem;
    color: #334155;
    line-height: 1.6;
}

body.dark-mode .quiz-tf-reveal {
    color: rgba(255, 255, 255, 0.85);
}

.quiz-truefalse.is-answered .quiz-tf-reveal {
    display: block;
}

.quiz-truefalse.is-answered .quiz-tf-btn:disabled {
    cursor: default;
}

/* ============================================
   BLOCS DE CODE
   ============================================ */
.code-box {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 20px;
    border-radius: 10px;
    font-family: 'Courier New', monospace;
    overflow-x: auto;
    word-wrap: break-word;
    margin: 15px 0 24px 0;
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
    color: #e2e8f0;
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

.copy-code-btn.copied {
    background: rgba(34, 197, 94, 0.9);
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

/* ============================================
   NAVIGATION PRÉCÉDENT / SUIVANT
   ============================================ */
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
    padding: 12px 18px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 300px;
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
    background: linear-gradient(135deg, rgba(4, 170, 109, 0.1), rgba(6, 182, 212, 0.1));
    border-color: rgba(4, 170, 109, 0.3);
}

.next-button.finish-button {
    background: linear-gradient(135deg, #04AA6D, #06b6d4);
    color: white;
    border-color: transparent;
}

.next-button.finish-button .nav-label-text,
.next-button.finish-button .nav-chapter-title {
    color: white !important;
}

.next-button.finish-button i {
    color: white !important;
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
    .chapter-header-top {
        flex-direction: column;
    }

    .chapter-complete-toggle {
        align-self: stretch;
        justify-content: center;
    }

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
        font-size: 1.75rem;
    }

    .course-chapter-content {
        padding: 22px;
    }

    .quiz-options,
    .quiz-tf-options,
    .quiz-tf-reveal {
        padding-left: 0;
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

        <div class="course-progress-ring" id="courseProgressRing" style="--progress: {{ $progressPercentage }}">
            <div class="progress-ring-inner">
                <span class="progress-ring-value" id="courseProgressValue">{{ $progressPercentage }}%</span>
            </div>
        </div>
        <p class="course-progress-caption" id="courseProgressCaption">
            {{ count($completedChapters) }} / {{ $totalChapters }} chapitres terminés
        </p>

        <h4 class="chapters-section-title">
            <i class="fas fa-list-ul"></i>
            Programme du cours
        </h4>
    </div>
    <div class="chapters-list-container" id="chaptersListContainer">
        @foreach($course->chapters as $index => $chapter)
        @php
            $isActive = $currentChapter && $chapter->id === $currentChapter->id;
            $isDone = in_array($chapter->id, $completedChapters);
        @endphp
        <a href="{{ route('dashboard.paid-courses.show', ['courseId' => $course->id, 'chapter' => $chapter->id]) }}"
           class="chapter-sidebar-item {{ $isActive ? 'active' : '' }} {{ $isDone ? 'is-done' : '' }}"
           data-chapter-id="{{ $chapter->id }}">
            <span class="chapter-index">
                <i class="fas fa-check chapter-index-check"></i>
                <span class="chapter-index-number">{{ $index + 1 }}</span>
            </span>
            <div class="chapter-sidebar-info">
                <span class="chapter-sidebar-title">{{ $chapter->title }}</span>
                @if($chapter->duration_minutes)
                <span class="chapter-sidebar-duration">
                    <i class="fas fa-clock"></i>
                    {{ $chapter->duration_minutes }} min
                </span>
                @endif
            </div>
            @if($isActive)
            <i class="fas fa-play chapter-active-icon"></i>
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
    padding-bottom: 18px;
    border-bottom: 1px solid rgba(4, 170, 109, 0.2);
    text-align: center;
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
    margin-bottom: 20px;
    transition: all 0.2s;
    align-self: flex-start;
}

.chapters-back-button:hover {
    background: #038f5a;
    transform: translateX(-2px);
}

.course-progress-ring {
    --progress: 0;
    width: 92px;
    height: 92px;
    border-radius: 50%;
    background: conic-gradient(#04AA6D calc(var(--progress) * 1%), rgba(4, 170, 109, 0.12) 0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px auto;
    transition: background 0.6s ease;
}

.progress-ring-inner {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.1rem;
    color: #1e293b;
}

body.dark-mode .progress-ring-inner {
    background: #0f172a;
    color: white;
}

.course-progress-caption {
    margin: 0 0 18px 0;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-align: center;
}

body.dark-mode .course-progress-caption {
    color: rgba(255, 255, 255, 0.6);
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
    justify-content: center;
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
    background: rgba(6, 182, 212, 0.12);
    border-color: #06b6d4;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
}

body.dark-mode .chapter-sidebar-item.active {
    background: rgba(6, 182, 212, 0.2);
}

.chapter-index {
    position: relative;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #cbd5e1;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

body.dark-mode .chapter-index {
    background: rgba(255, 255, 255, 0.15);
}

.chapter-sidebar-item.active .chapter-index {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    transform: scale(1.08);
}

.chapter-sidebar-item.is-done .chapter-index {
    background: linear-gradient(135deg, #04AA6D, #038f5a);
    box-shadow: 0 2px 8px rgba(4, 170, 109, 0.3);
}

.chapter-index-check {
    display: none;
}

.chapter-sidebar-item.is-done .chapter-index-check {
    display: block;
}

.chapter-sidebar-item.is-done .chapter-index-number {
    display: none;
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

.chapter-active-icon {
    color: #06b6d4;
    font-size: 0.8rem;
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<!-- Prism.js pour la coloration syntaxique -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

<style>
.code-box pre code[class*="language-"] {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function highlightCode() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    }
    highlightCode();
    setTimeout(highlightCode, 200);
    setTimeout(highlightCode, 500);
});

function copyCodeToClipboard(button, codeElement) {
    const codeText = codeElement.innerText || codeElement.textContent;
    navigator.clipboard.writeText(codeText).then(function() {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('copied');
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('copied');
        }, 2000);
    }).catch(function() {
        const textArea = document.createElement('textarea');
        textArea.value = codeText;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(textArea);
    });
}

(function() {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function updateProgressUI(data) {
        const ring = document.getElementById('courseProgressRing');
        const value = document.getElementById('courseProgressValue');
        const caption = document.getElementById('courseProgressCaption');
        const fill = document.getElementById('chapterProgressFill');
        const badgeText = document.getElementById('chapterProgressBadgeText');

        if (ring) ring.style.setProperty('--progress', data.progress_percentage);
        if (value) value.textContent = data.progress_percentage + '%';
        if (fill) fill.style.width = data.progress_percentage + '%';
        if (badgeText) badgeText.textContent = data.progress_percentage;

        const total = document.querySelectorAll('.chapter-sidebar-item').length;
        if (caption) caption.textContent = data.completed_chapters.length + ' / ' + total + ' chapitres terminés';

        (data.completed_chapters || []).forEach(function(chapterId) {
            const item = document.querySelector('.chapter-sidebar-item[data-chapter-id="' + chapterId + '"]');
            if (item) item.classList.add('is-done');
        });

        if (data.is_course_completed) {
            setTimeout(function() { window.location.reload(); }, 600);
        }
    }

    function markComplete(url) {
        if (!url || !csrfToken) return Promise.resolve(null);
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        }).then(function(res) { return res.ok ? res.json() : null; }).catch(function() { return null; });
    }

    const completeBtn = document.getElementById('chapterCompleteBtn');
    if (completeBtn) {
        completeBtn.addEventListener('click', function() {
            const url = completeBtn.getAttribute('data-complete-url');
            markComplete(url).then(function(data) {
                if (!data) return;
                completeBtn.classList.add('is-completed');
                completeBtn.querySelector('span').textContent = 'Chapitre terminé';
                updateProgressUI(data);
            });
        });
    }

    const nextLink = document.getElementById('nextChapterLink');
    if (nextLink) {
        nextLink.addEventListener('click', function() {
            const url = nextLink.getAttribute('data-complete-url');
            // Fire-and-forget : ne bloque pas la navigation vers le chapitre suivant.
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                keepalive: true,
            }).catch(function() {});
        });
    }

    // Interactivité du quiz : choix multiple
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.quiz-option');
        if (option && !option.disabled) {
            const card = option.closest('[data-quiz-question]');
            const options = card.querySelectorAll('.quiz-option');
            const isCorrect = option.getAttribute('data-correct') === '1';

            options.forEach(function(o) { o.disabled = true; });
            option.classList.add('is-selected', isCorrect ? 'is-correct' : 'is-incorrect');

            if (!isCorrect) {
                const correctOption = card.querySelector('.quiz-option[data-correct="1"]');
                if (correctOption) correctOption.classList.add('reveal-correct');
            }
            return;
        }

        const tfBtn = e.target.closest('.quiz-tf-btn');
        if (tfBtn && !tfBtn.disabled) {
            const card = tfBtn.closest('[data-quiz-truefalse]');
            const buttons = card.querySelectorAll('.quiz-tf-btn');
            const correctValue = card.getAttribute('data-answer');
            const chosenValue = tfBtn.getAttribute('data-value');

            buttons.forEach(function(b) {
                b.disabled = true;
                if (b.getAttribute('data-value') === correctValue) {
                    b.classList.add('is-correct');
                } else if (b === tfBtn) {
                    b.classList.add('is-incorrect');
                }
            });

            card.classList.add('is-answered');
        }
    });
})();
</script>
@endpush
@endsection
