@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.goals.title');
    $pageDescription = trans('app.profile.dashboard.goals.description');
@endphp

@if(isset($goals) && $goals->count() > 0)
<div style="margin-bottom: 1.25rem; text-align: right;">
    <button onclick="document.getElementById('createGoalModal').style.display='block'" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border: none; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-plus"></i>
        {{ trans('app.profile.dashboard.goals.create_goal') }}
    </button>
</div>
<div style="display: grid; gap: 1.25rem;">
    @foreach($goals as $goal)
    <div class="content-card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.25rem;">
            <div style="flex: 1;">
                <h3 class="dashboard-text-primary" style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ $goal->title }}</h3>
                @if($goal->description)
                <p class="dashboard-text-secondary" style="color: #64748b; margin: 0; font-size: 0.9rem; line-height: 1.5;">{{ $goal->description }}</p>
                @endif
            </div>
            <div class="goal-status-badge" style="padding: 0.375rem 0.75rem; background: {{ $goal->completed ? 'rgba(16, 185, 129, 0.2)' : 'rgba(6, 182, 212, 0.2)' }}; border-radius: 6px;">
                <span class="goal-status-text" style="font-size: 0.8rem; font-weight: 600; color: {{ $goal->completed ? '#10b981' : '#06b6d4' }};">
                    {{ $goal->completed ? trans('app.profile.dashboard.goals.completed') : trans('app.profile.dashboard.goals.in_progress') }}
                </span>
            </div>
        </div>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b; font-size: 0.875rem;" class="dashboard-text-secondary">
                <span>{{ trans('app.profile.dashboard.goals.progress') }}</span>
                <span class="dashboard-text-primary" style="font-weight: 600; color: #2c3e50;">{{ $goal->current_value }}/{{ $goal->target_value }} {{ $goal->unit ?? '' }}</span>
            </div>
            <div class="goal-progress-bar-bg" style="width: 100%; height: 8px; background: rgba(6, 182, 212, 0.1); border-radius: 4px; overflow: hidden;">
                <div class="goal-progress-bar-fill" style="height: 100%; width: {{ $goal->progress_percentage }}%; background: linear-gradient(90deg, #06b6d4, #22d3ee); transition: width 0.6s ease;"></div>
            </div>
        </div>
        @if($goal->deadline)
        <div class="dashboard-text-secondary" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.875rem;">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ trans('app.profile.dashboard.goals.deadline') }} : {{ $goal->deadline->format(app()->getLocale() === 'fr' ? 'd/m/Y' : 'm/d/Y') }}</span>
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="content-card" style="text-align: center; padding: 3rem 2rem;">
    <div class="dashboard-empty-icon" style="width: 80px; height: 80px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(6, 182, 212, 0.2); display: flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 2rem;">
        <i class="fas fa-bullseye"></i>
    </div>
    <h3 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin: 0 0 0.5rem 0;">{{ trans('app.profile.dashboard.goals.no_goals') }}</h3>
    <p class="dashboard-text-secondary" style="color: #64748b; margin: 0 0 1.5rem 0;">{{ trans('app.profile.dashboard.goals.no_goals_desc') }}</p>
    <button onclick="document.getElementById('createGoalModal').style.display='block'" class="dashboard-button-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border: none; border-radius: 6px; text-decoration: none; font-weight: 500; cursor: pointer; box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);">
        <i class="fas fa-plus"></i>
        {{ trans('app.profile.dashboard.goals.create_goal') }}
    </button>
</div>
@endif

<style>
    /* Dark Mode Styles pour la page Goals */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .goal-progress-bar-bg {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .goal-progress-bar-fill {
        background: linear-gradient(90deg, #06b6d4, #22d3ee) !important;
    }
    
    body.dark-mode .dashboard-empty-icon {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #06b6d4 !important;
    }
    
    /* Styles pour les cartes d'objectifs en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(6, 182, 212, 0.3) !important;
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.15) !important;
    }
    
    /* Styles dynamiques pour les badges de statut */
    body.dark-mode .goal-status-badge[style*="rgba(16, 185, 129"] {
        background: rgba(16, 185, 129, 0.3) !important;
    }
    
    body.dark-mode .goal-status-badge[style*="rgba(6, 182, 212"] {
        background: rgba(6, 182, 212, 0.3) !important;
    }
    
    body.dark-mode .goal-status-text[style*="color: #10b981"] {
        color: #10b981 !important;
    }
    
    body.dark-mode .goal-status-text[style*="color: #06b6d4"] {
        color: #06b6d4 !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(6, 182, 212, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.5) !important;
        transform: translateY(-2px);
    }
</style>

<!-- Modal pour créer un objectif -->
<div id="createGoalModal" class="modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px);">
    <div class="modal-content" style="background-color: #ffffff; margin: 5% auto; padding: 0; border-radius: 16px; width: 90%; max-width: 600px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
        <div class="modal-header" style="padding: 1.5rem 2rem; border-bottom: 1px solid rgba(6, 182, 212, 0.2); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1)); border-radius: 16px 16px 0 0;">
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #2c3e50; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-bullseye" style="color: #06b6d4;"></i>
                {{ trans('app.profile.dashboard.goals.create_goal') }}
            </h2>
            <button onclick="document.getElementById('createGoalModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.3s ease; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('dashboard.goals.store') }}" method="POST" style="padding: 2rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label for="goal_type" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                    {{ trans('app.profile.goal.type') ?? 'Type d\'objectif' }} <span style="color: #ef4444;">*</span>
                </label>
                <select name="goal_type" id="goal_type" required style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease; cursor: pointer;">
                    <option value="">{{ trans('app.profile.goal.type') ?? 'Sélectionner un type' }}</option>
                    <option value="formation">{{ trans('app.profile.goal.type_formation') ?? 'Formation' }}</option>
                    <option value="exercise">{{ trans('app.profile.goal.type_exercise') ?? 'Exercice' }}</option>
                    <option value="quiz">{{ trans('app.profile.goal.type_quiz') ?? 'Quiz' }}</option>
                    <option value="time">{{ trans('app.profile.goal.type_time') ?? 'Temps d\'apprentissage' }}</option>
                    <option value="score">{{ trans('app.profile.goal.type_score') ?? 'Score' }}</option>
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                    {{ trans('app.profile.goal.title') ?? 'Titre' }} <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" id="title" required maxlength="255" placeholder="{{ trans('app.profile.goal.title_placeholder') ?? 'Ex: Terminer la formation PHP' }}" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                    {{ trans('app.profile.goal.description') ?? 'Description (optionnel)' }}
                </label>
                <textarea name="description" id="description" rows="4" maxlength="1000" placeholder="{{ trans('app.profile.goal.description_placeholder') ?? 'Décrivez votre objectif...' }}" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease; resize: vertical; font-family: inherit;"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="target_value" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                        {{ trans('app.profile.goal.target_value') ?? 'Valeur cible' }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="target_value" id="target_value" required min="1" placeholder="100" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease;">
                </div>
                <div>
                    <label for="unit" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                        {{ trans('app.profile.goal.unit') ?? 'Unité' }}
                    </label>
                    <input type="text" name="unit" id="unit" maxlength="20" placeholder="%, min, points..." style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease;">
                </div>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label for="deadline" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.95rem;">
                    {{ trans('app.profile.goal.deadline') ?? 'Échéance (optionnel)' }}
                </label>
                <input type="date" name="deadline" id="deadline" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(6, 182, 212, 0.2); border-radius: 8px; font-size: 0.95rem; background: #ffffff; color: #2c3e50; transition: all 0.3s ease;">
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                <button type="button" onclick="document.getElementById('createGoalModal').style.display='none'" style="padding: 0.75rem 1.5rem; background: rgba(148, 163, 184, 0.1); color: #64748b; border: 2px solid rgba(148, 163, 184, 0.2); border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease;">
                    {{ trans('app.profile.goal.cancel') ?? 'Annuler' }}
                </button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-check mr-2"></i>
                    {{ trans('app.profile.dashboard.goals.create_goal') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-content input:focus,
    .modal-content select:focus,
    .modal-content textarea:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
    
    .modal-content button[type="button"]:hover {
        background: rgba(148, 163, 184, 0.2);
        border-color: rgba(148, 163, 184, 0.3);
    }
    
    .modal-content button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(6, 182, 212, 0.4);
    }
    
    /* Dark Mode */
    body.dark-mode .modal-content {
        background-color: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .modal-header {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-bottom-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .modal-header h2 {
        color: rgba(255, 255, 255, 0.9);
    }
    
    body.dark-mode .modal-content label {
        color: rgba(255, 255, 255, 0.9);
    }
    
    body.dark-mode .modal-content input,
    body.dark-mode .modal-content select,
    body.dark-mode .modal-content textarea {
        background: rgba(30, 41, 59, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        color: rgba(255, 255, 255, 0.9);
    }
    
    body.dark-mode .modal-content input:focus,
    body.dark-mode .modal-content select:focus,
    body.dark-mode .modal-content textarea:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
    }
</style>

<script>
    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('createGoalModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
