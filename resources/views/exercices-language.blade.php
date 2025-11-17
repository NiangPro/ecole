@extends('layouts.app')

@section('title', 'Exercices ' . ucfirst($language) . ' | NiangProgrammeur')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
    
    /* Body background */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    .exercise-item {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    body:not(.dark-mode) .exercise-item {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .exercise-item:hover {
        transform: translateX(10px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .exercise-item:hover {
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.15) !important;
    }
    
    .difficulty-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .difficulty-facile {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .difficulty-moyen {
        background: rgba(234, 179, 8, 0.1);
        color: #eab308;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }
    
    .difficulty-difficile {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    /* Text colors */
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body:not(.dark-mode) .text-white {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Stats cards */
    body:not(.dark-mode) .bg-gradient-to-br {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    /* CTA section */
    body:not(.dark-mode) .bg-gradient-to-r.from-cyan-500\/10.to-teal-500\/10 {
        background: linear-gradient(to right, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    /* Number badge */
    body:not(.dark-mode) .bg-cyan-500\/10 {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    body:not(.dark-mode) .text-cyan-400 {
        color: #06b6d4 !important;
    }
    
    /* Filtres */
    .filter-btn.active {
        background: rgba(6, 182, 212, 0.3) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
    }
    
    body:not(.dark-mode) .filter-btn {
        background: rgba(6, 182, 212, 0.1) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .filter-btn.active {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    .exercise-item.hidden {
        display: none;
    }
</style>
@endsection

@section('scripts')
<script>
    function filterExercises(difficulty) {
        const exercises = document.querySelectorAll('.exercise-item');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Mettre à jour les boutons actifs
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.includes(difficulty === 'all' ? 'Tous' : difficulty)) {
                btn.classList.add('active');
            }
        });
        
        // Filtrer les exercices
        exercises.forEach(exercise => {
            if (difficulty === 'all') {
                exercise.classList.remove('hidden');
            } else {
                if (exercise.dataset.difficulty === difficulty) {
                    exercise.classList.remove('hidden');
                } else {
                    exercise.classList.add('hidden');
                }
            }
        });
    }
</script>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-8">
    <div class="container mx-auto px-6">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <a href="{{ route('exercices') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux exercices
            </a>
        </div>

        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">
                Exercices {{ ucfirst($language) }}
            </h1>
            <p class="text-xl text-gray-300">
                Pratiquez vos compétences avec ces exercices interactifs
            </p>
        </div>

        @if(count($exercises) > 0)
        <!-- Filtres par niveau -->
        <div class="mb-8 flex flex-wrap gap-4 items-center">
            <span class="text-gray-400 font-semibold">Filtrer par niveau:</span>
            <button onclick="filterExercises('all')" class="filter-btn active px-4 py-2 rounded-lg bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 hover:bg-cyan-500/30 transition">
                Tous ({{ count($exercises) }})
            </button>
            <button onclick="filterExercises('Facile')" class="filter-btn px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 hover:bg-green-500/30 transition">
                Facile ({{ collect($exercises)->where('difficulty', 'Facile')->count() }})
            </button>
            <button onclick="filterExercises('Moyen')" class="filter-btn px-4 py-2 rounded-lg bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 hover:bg-yellow-500/30 transition">
                Moyen ({{ collect($exercises)->where('difficulty', 'Moyen')->count() }})
            </button>
            <button onclick="filterExercises('Difficile')" class="filter-btn px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition">
                Difficile ({{ collect($exercises)->where('difficulty', 'Difficile')->count() }})
            </button>
        </div>
        
        <!-- Liste des exercices -->
        <div class="grid grid-cols-1 gap-6" id="exercisesList">
            @foreach($exercises as $index => $exercise)
            <div class="exercise-item" data-difficulty="{{ $exercise['difficulty'] }}">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="w-12 h-12 rounded-full bg-cyan-500/10 border-2 border-cyan-500/30 flex items-center justify-center text-cyan-400 font-bold text-lg">
                            {{ $exercise['display_index'] ?? ($index + 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $exercise['title'] }}</h3>
                            <div class="flex items-center gap-4 flex-wrap">
                                <span class="difficulty-badge difficulty-{{ strtolower($exercise['difficulty']) }}">
                                    <i class="fas fa-signal mr-1"></i>{{ $exercise['difficulty'] }}
                                </span>
                                <span class="text-gray-400 text-sm">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $exercise['points'] }} points
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('exercices.detail', [$language, $exercise['display_index'] ?? ($index + 1)]) }}" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition inline-block">
                        <i class="fas fa-play mr-2"></i>Commencer
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Statistiques -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-green-400 mb-2">{{ count($exercises) }}</div>
                <div class="text-gray-400">Exercices disponibles</div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border border-yellow-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-yellow-400 mb-2">{{ array_sum(array_column($exercises, 'points')) }}</div>
                <div class="text-gray-400">Points totaux</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-purple-400 mb-2">0%</div>
                <div class="text-gray-400">Progression</div>
            </div>
        </div>
        @else
        <!-- Aucun exercice -->
        <div class="text-center py-20">
            <div class="w-32 h-32 mx-auto mb-6 bg-cyan-500/10 rounded-full flex items-center justify-center">
                <i class="fas fa-dumbbell text-cyan-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Exercices bientôt disponibles</h3>
            <p class="text-gray-400 mb-8">Les exercices pour {{ ucfirst($language) }} seront ajoutés prochainement.</p>
            <a href="{{ route('exercices') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux exercices
            </a>
        </div>
        @endif

        <!-- Conseils -->
        <div class="mt-12 bg-gradient-to-r from-cyan-500/10 to-teal-500/10 border border-cyan-500/20 rounded-3xl p-8">
            <h3 class="text-2xl font-bold text-white mb-4 flex items-center gap-3">
                <i class="fas fa-lightbulb text-yellow-400"></i>
                Conseils pour réussir
            </h3>
            <ul class="space-y-3 text-gray-300">
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>Lisez attentivement chaque énoncé avant de commencer</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>Testez votre code régulièrement pendant que vous travaillez</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>N'hésitez pas à consulter la documentation si nécessaire</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>Prenez votre temps et ne vous découragez pas face aux difficultés</span>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection
