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

<div class="content-card">
    <h2 class="card-title dashboard-text-primary">
        <i class="fas fa-question-circle"></i>
        {{ $pageTitle }}
    </h2>
    <p class="dashboard-text-secondary" style="margin-bottom: 2rem;">
        {{ $pageDescription }}
    </p>

    <!-- Section : Quiz Disponibles -->
    @if(isset($languages) && count($languages) > 0)
    <div style="margin-bottom: 3rem;">
        <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-list-ul" style="color: #06b6d4;"></i>
            {{ trans('app.profile.dashboard.quiz.available_quiz') ?? 'Quiz Disponibles' }}
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
            @foreach($languages as $lang)
            <div class="quiz-available-card" style="
                padding: 1.5rem;
                background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.05));
                border: 2px solid rgba(6, 182, 212, 0.3);
                border-radius: 12px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            ">
                @if($lang['completed'])
                <div style="position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-check" style="color: white; font-size: 0.85rem;"></i>
                </div>
                @endif
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 60px; height: 60px; border-radius: 12px; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="{{ $lang['icon'] }}" style="font-size: 2rem; color: #06b6d4;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h4 class="dashboard-text-primary" style="font-size: 1.1rem; font-weight: 700; margin: 0 0 0.25rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $lang['name'] }}
                        </h4>
                        <div class="dashboard-text-secondary" style="font-size: 0.85rem; color: #64748b;">
                            <i class="fas fa-question-circle"></i> {{ $lang['questions'] }} {{ trans('app.quiz.questions_count') ?? 'questions' }}
                        </div>
                    </div>
                </div>
                
                @if($lang['completed'])
                <div style="margin-bottom: 1rem; padding: 0.75rem; background: rgba(16, 185, 129, 0.1); border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span class="dashboard-text-secondary" style="font-size: 0.85rem;">{{ trans('app.profile.dashboard.quiz.best_score') ?? 'Meilleur score' }}:</span>
                        <span style="font-size: 1.25rem; font-weight: 700; color: #10b981;">{{ number_format($lang['best_score'], 1) }}%</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="dashboard-text-secondary" style="font-size: 0.85rem;">{{ trans('app.profile.dashboard.quiz.attempts') ?? 'Tentatives' }}:</span>
                        <span class="dashboard-text-primary" style="font-weight: 600;">{{ $lang['attempts'] }}</span>
                    </div>
                    @if($lang['last_completed'])
                    <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(16, 185, 129, 0.2);">
                        <span class="dashboard-text-secondary" style="font-size: 0.75rem;">
                            <i class="fas fa-calendar"></i> {{ trans('app.profile.dashboard.quiz.last_completed') ?? 'Dernière fois' }}: {{ $lang['last_completed']->format('d/m/Y') }}
                        </span>
                    </div>
                    @endif
                </div>
                @else
                <div style="margin-bottom: 1rem; padding: 0.75rem; background: rgba(100, 116, 139, 0.1); border-radius: 8px; border: 1px solid rgba(100, 116, 139, 0.2); text-align: center;">
                    <span class="dashboard-text-secondary" style="font-size: 0.85rem;">
                        <i class="fas fa-clock"></i> {{ trans('app.profile.dashboard.quiz.not_completed') ?? 'Pas encore complété' }}
                    </span>
                </div>
                @endif
                
                <a href="{{ route('quiz.language', $lang['slug']) }}" class="quiz-start-button" style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1rem;
                    background: linear-gradient(135deg, #06b6d4, #14b8a6);
                    color: white;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
                ">
                    <i class="fas fa-play"></i>
                    <span>{{ $lang['completed'] ? (trans('app.profile.dashboard.quiz.retake_quiz') ?? 'Refaire le quiz') : (trans('app.profile.dashboard.quiz.start_quiz') ?? 'Commencer') }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Section : Historique des Quiz -->
    @if(isset($quizResults) && $quizResults->count() > 0)
    <div style="margin-top: 3rem; padding-top: 3rem; border-top: 2px solid rgba(6, 182, 212, 0.2);">
        <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-history" style="color: #06b6d4;"></i>
            {{ trans('app.profile.dashboard.quiz.history') ?? 'Historique des Quiz' }}
        </h3>
        
        <div style="display: grid; gap: 1.25rem;">
            @foreach($quizResults as $result)
            <div class="content-card quiz-history-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.25rem;">
                    <div style="flex: 1;">
                        <h4 class="dashboard-text-primary" style="font-size: 1.1rem; font-weight: 600; margin: 0 0 0.5rem 0;">
                            {{ trans('app.nav.quiz') }} {{ ucfirst($result->language ?? $result->quiz_id) }}
                        </h4>
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
    </div>
    @elseif(!isset($languages) || count($languages) == 0)
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
</div>

<style>
    /* Styles pour les cartes de quiz disponibles */
    .quiz-available-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .quiz-start-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
    }
    
    /* Dark Mode Styles pour la page Quiz */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .quiz-available-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .quiz-available-card:hover {
        border-color: rgba(6, 182, 212, 0.6) !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3) !important;
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
    
    body.dark-mode .quiz-history-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .quiz-history-card:hover {
        border-color: rgba(6, 182, 212, 0.3) !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.15) !important;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .quiz-available-card {
            min-width: 100%;
        }
    }
</style>
@endsection
