@extends('layouts.app')

@section('title', trans('app.exercices.exercises') . ' ' . trans('app.formations.languages.' . $language, [], null, ucfirst($language)) . ' | NiangProgrammeur')

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
    
    .difficulty-facile,
    .difficulty-easy {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    body:not(.dark-mode) .difficulty-facile,
    body:not(.dark-mode) .difficulty-easy {
        background: rgba(34, 197, 94, 0.15) !important;
        color: #16a34a !important;
        border: 1px solid rgba(34, 197, 94, 0.4) !important;
    }
    
    .difficulty-moyen,
    .difficulty-medium {
        background: rgba(234, 179, 8, 0.1);
        color: #eab308;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }
    
    body:not(.dark-mode) .difficulty-moyen,
    body:not(.dark-mode) .difficulty-medium {
        background: rgba(234, 179, 8, 0.15) !important;
        color: #ca8a04 !important;
        border: 1px solid rgba(234, 179, 8, 0.4) !important;
    }
    
    .difficulty-difficile,
    .difficulty-hard {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    body:not(.dark-mode) .difficulty-difficile,
    body:not(.dark-mode) .difficulty-hard {
        background: rgba(239, 68, 68, 0.15) !important;
        color: #dc2626 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
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
            const allText = @json(trans('app.exercices.difficulty.all'));
            if (btn.textContent.includes(difficulty === 'all' ? allText : difficulty)) {
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
                <i class="fas fa-arrow-left mr-2"></i>{{ trans('app.exercices.back_to_exercices') }}
            </a>
        </div>

        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">
                {{ trans('app.exercices.exercises') }} {{ trans('app.formations.languages.' . $language, [], null, ucfirst($language)) }}
            </h1>
            <p class="text-xl text-gray-300">
                {{ trans('app.exercices.practice_skills') }}
            </p>
        </div>

        @php
            // Définir les difficultés traduites une seule fois pour toute la page
            $easyDifficulty = trans('app.exercices.difficulty.easy');
            $mediumDifficulty = trans('app.exercices.difficulty.medium');
            $hardDifficulty = trans('app.exercices.difficulty.hard');
        @endphp

        <!-- Stats Section -->
        <div class="mb-12 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-cyan-500/10 to-teal-500/10 border border-cyan-500/20 rounded-xl p-6 text-center">
                <div class="text-4xl font-bold text-cyan-400 mb-2">{{ count($exercises) }}+</div>
                <div class="text-gray-300">{{ trans('app.exercices.exercises') }}</div>
            </div>
            <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-xl p-6 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">{{ collect($exercises)->where('difficulty', $easyDifficulty)->count() }}</div>
                <div class="text-gray-300">{{ trans('app.exercices.difficulty.easy') }}</div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border border-yellow-500/20 rounded-xl p-6 text-center">
                <div class="text-4xl font-bold text-yellow-400 mb-2">{{ collect($exercises)->where('difficulty', $mediumDifficulty)->count() }}</div>
                <div class="text-gray-300">{{ trans('app.exercices.difficulty.medium') }}</div>
            </div>
            <div class="bg-gradient-to-br from-red-500/10 to-rose-500/10 border border-red-500/20 rounded-xl p-6 text-center">
                <div class="text-4xl font-bold text-red-400 mb-2">{{ collect($exercises)->where('difficulty', $hardDifficulty)->count() }}</div>
                <div class="text-gray-300">{{ trans('app.exercices.difficulty.hard') }}</div>
            </div>
        </div>

        @if(count($exercises) > 0)
        <!-- Filtres par niveau -->
        <div class="mb-8 flex flex-wrap gap-4 items-center">
            <span class="text-gray-400 font-semibold">{{ trans('app.exercices.filter_by_level') }}:</span>
            <button onclick="filterExercises('all')" class="filter-btn active px-4 py-2 rounded-lg bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 hover:bg-cyan-500/30 transition">
                {{ trans('app.exercices.difficulty.all') }} ({{ count($exercises) }})
            </button>
            <button onclick="filterExercises('{{ $easyDifficulty }}')" class="filter-btn px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 hover:bg-green-500/30 transition">
                {{ trans('app.exercices.difficulty.easy') }} ({{ collect($exercises)->where('difficulty', $easyDifficulty)->count() }})
            </button>
            <button onclick="filterExercises('{{ $mediumDifficulty }}')" class="filter-btn px-4 py-2 rounded-lg bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 hover:bg-yellow-500/30 transition">
                {{ trans('app.exercices.difficulty.medium') }} ({{ collect($exercises)->where('difficulty', $mediumDifficulty)->count() }})
            </button>
            <button onclick="filterExercises('{{ $hardDifficulty }}')" class="filter-btn px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition">
                {{ trans('app.exercices.difficulty.hard') }} ({{ collect($exercises)->where('difficulty', $hardDifficulty)->count() }})
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
                                @php
                                    $difficultyLower = strtolower($exercise['difficulty']);
                                    $difficultyClass = 'difficulty-facile';
                                    if ($difficultyLower === 'facile' || $difficultyLower === 'easy') {
                                        $difficultyClass = 'difficulty-facile difficulty-easy';
                                    } elseif ($difficultyLower === 'moyen' || $difficultyLower === 'medium') {
                                        $difficultyClass = 'difficulty-moyen difficulty-medium';
                                    } elseif ($difficultyLower === 'difficile' || $difficultyLower === 'hard') {
                                        $difficultyClass = 'difficulty-difficile difficulty-hard';
                                    }
                                @endphp
                                <span class="difficulty-badge {{ $difficultyClass }}">
                                    <i class="fas fa-signal mr-1"></i>{{ $exercise['difficulty'] }}
                                </span>
                                <span class="text-gray-400 text-sm">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $exercise['points'] }} {{ trans('app.exercices.points') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('exercices.detail', [$language, $exercise['display_index'] ?? ($index + 1)]) }}" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition inline-block">
                        <i class="fas fa-play mr-2"></i>{{ trans('app.exercices.start_exercise') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Statistiques -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-green-400 mb-2">{{ count($exercises) }}</div>
                <div class="text-gray-400">{{ trans('app.exercices.available_exercices') }}</div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border border-yellow-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-yellow-400 mb-2">{{ array_sum(array_column($exercises, 'points')) }}</div>
                <div class="text-gray-400">{{ trans('app.exercices.total_points') }}</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-2xl p-6 text-center">
                <div class="text-3xl font-bold text-purple-400 mb-2">0%</div>
                <div class="text-gray-400">{{ trans('app.exercices.progress') }}</div>
            </div>
        </div>
        @else
        <!-- Aucun exercice -->
        <div class="text-center py-20">
            <div class="w-32 h-32 mx-auto mb-6 bg-cyan-500/10 rounded-full flex items-center justify-center">
                <i class="fas fa-dumbbell text-cyan-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">{{ trans('app.exercices.coming_soon') }}</h3>
            <p class="text-gray-400 mb-8">{{ str_replace(':language', ucfirst($language), trans('app.exercices.coming_soon_desc')) }}</p>
            <a href="{{ route('exercices') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                <i class="fas fa-arrow-left mr-2"></i>{{ trans('app.exercices.back_to_exercices') }}
            </a>
        </div>
        @endif

        <!-- Conseils -->
        <div class="mt-12 bg-gradient-to-r from-cyan-500/10 to-teal-500/10 border border-cyan-500/20 rounded-3xl p-8">
            <h3 class="text-2xl font-bold text-white mb-4 flex items-center gap-3">
                <i class="fas fa-lightbulb text-yellow-400"></i>
                {{ trans('app.exercices.tips.title') }}
            </h3>
            <ul class="space-y-3 text-gray-300">
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>{{ trans('app.exercices.tips.read_carefully') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>{{ trans('app.exercices.tips.test_regularly') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>{{ trans('app.exercices.tips.check_docs') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check text-green-400 mt-1"></i>
                    <span>{{ trans('app.exercices.tips.take_time') }}</span>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection
