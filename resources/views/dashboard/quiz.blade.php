@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.quiz.title');
    $pageDescription = trans('app.profile.dashboard.quiz.description');
@endphp

@if(isset($quizResults) && $quizResults->count() > 0)
    <div style="display: grid; gap: 1.25rem;">
        @foreach($quizResults as $result)
        <div class="content-card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.25rem;">
                <div style="flex: 1;">
                    <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">
                        {{ trans('app.nav.quiz') }} {{ ucfirst($result->language ?? $result->quiz_id) }}
                    </h3>
                    <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.875rem;">
                        <i class="fas fa-calendar"></i> {{ trans('app.profile.dashboard.quiz.completed_on') }} {{ $result->completed_at->format('d/m/Y') }} {{ app()->getLocale() === 'fr' ? 'à' : 'at' }} {{ $result->completed_at->format('H:i') }}
                    </div>
                </div>
                <div class="quiz-score-badge" style="padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.2); border-radius: 6px;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: #10b981;">{{ $result->percentage }}%</div>
                </div>
            </div>
            <div class="quiz-stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; padding: 1rem; background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.1); border-radius: 8px;">
                <div style="text-align: center;">
                    <div class="dashboard-text-secondary" style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">{{ trans('app.profile.dashboard.quiz.questions') ?? 'Questions' }}</div>
                    <div class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; color: #2c3e50;">{{ $result->total_questions }}</div>
                </div>
                <div style="text-align: center;">
                    <div class="dashboard-text-secondary" style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">{{ trans('app.profile.dashboard.quiz.correct_answers') }}</div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: #10b981;">{{ $result->correct_answers }}</div>
                </div>
                <div style="text-align: center;">
                    <div class="dashboard-text-secondary" style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">{{ trans('app.profile.dashboard.quiz.wrong_answers') }}</div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: #ef4444;">{{ $result->wrong_answers }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-question-circle"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.quiz.no_quiz') }}</h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">{{ trans('app.profile.dashboard.quiz.no_quiz_desc') }}</p>
    <a href="{{ route('quiz') }}" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-radius: 6px; text-decoration: none; font-weight: 500; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-question-circle"></i>
        {{ trans('app.profile.dashboard.quiz.view_quiz') }}
    </a>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Quiz */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .quiz-score-badge {
        background: rgba(16, 185, 129, 0.3) !important;
    }
    
    body.dark-mode .quiz-score-badge div {
        color: #10b981 !important;
    }
    
    body.dark-mode .quiz-stats-grid {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .quiz-stats-grid .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .quiz-stats-grid div[style*="color: #10b981"] {
        color: #10b981 !important;
    }
    
    body.dark-mode .quiz-stats-grid div[style*="color: #ef4444"] {
        color: #ef4444 !important;
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.5) !important;
        transform: translateY(-2px);
    }
    
    /* Styles pour les cartes de quiz en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
</style>
@endsection
