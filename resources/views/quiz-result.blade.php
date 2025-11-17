@extends('layouts.app')

@section('title', 'Résultat Quiz ' . ucfirst($language) . ' | NiangProgrammeur')

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
    
    .result-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .score-card {
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(236, 72, 153, 0.2));
        border: 2px solid rgba(168, 85, 247, 0.3);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        margin-bottom: 3rem;
    }
    
    body:not(.dark-mode) .score-card {
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(236, 72, 153, 0.1)) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
        box-shadow: 0 10px 40px rgba(168, 85, 247, 0.1) !important;
    }
    
    .score-circle {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 0 40px rgba(168, 85, 247, 0.5);
    }
    
    .answer-card {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(168, 85, 247, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    body:not(.dark-mode) .answer-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
        box-shadow: 0 10px 40px rgba(168, 85, 247, 0.1) !important;
    }
    
    .answer-card.correct {
        border-color: rgba(34, 197, 94, 0.5);
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    }
    
    body:not(.dark-mode) .answer-card.correct {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.08), rgba(34, 197, 94, 0.05)) !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }
    
    .answer-card.incorrect {
        border-color: rgba(239, 68, 68, 0.5);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    }
    
    body:not(.dark-mode) .answer-card.incorrect {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.05)) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }
    
    /* Text colors */
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-white {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Buttons */
    body:not(.dark-mode) .bg-white\/10 {
        background: rgba(168, 85, 247, 0.1) !important;
        border-color: rgba(168, 85, 247, 0.3) !important;
    }
    
    body:not(.dark-mode) .bg-white\/10:hover {
        background: rgba(168, 85, 247, 0.15) !important;
    }
    
    body:not(.dark-mode) .text-white.border-white\/20 {
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(168, 85, 247, 0.3) !important;
    }
    
    /* CTA section */
    body:not(.dark-mode) .bg-gradient-to-r.from-purple-500\/10.to-pink-500\/10 {
        background: linear-gradient(to right, rgba(168, 85, 247, 0.05), rgba(236, 72, 153, 0.05)) !important;
        border-color: rgba(168, 85, 247, 0.2) !important;
    }
    
    body:not(.dark-mode) .text-purple-400 {
        color: #a855f7 !important;
    }
</style>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-32">
    <div class="container mx-auto px-6">
        <div class="result-container">
            <!-- Score Card -->
            <div class="score-card">
                <div class="score-circle">
                    <div class="text-center">
                        <div class="text-6xl font-bold text-white">{{ number_format($percentage, 0) }}%</div>
                        <div class="text-white text-lg">Score</div>
                    </div>
                </div>
                
                <h1 class="text-4xl font-bold text-white mb-4">
                    @if($percentage >= 80)
                        🎉 Excellent travail !
                    @elseif($percentage >= 60)
                        👍 Bon travail !
                    @elseif($percentage >= 40)
                        📚 Continuez à apprendre !
                    @else
                        💪 Ne vous découragez pas !
                    @endif
                </h1>
                
                <p class="text-2xl text-gray-300 mb-6">
                    Vous avez obtenu <span class="text-purple-400 font-bold">{{ $score }}/{{ $total }}</span> bonnes réponses
                </p>
                
                <div class="flex gap-4 justify-center flex-wrap">
                    <a href="{{ route('quiz.language', $language) }}" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-redo mr-2"></i>Refaire le quiz
                    </a>
                    <a href="{{ route('quiz') }}" class="px-8 py-3 bg-white/10 border border-white/20 text-white font-bold rounded-lg hover:bg-white/20 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Autres quiz
                    </a>
                    <a href="{{ route('exercices.language', $language) }}" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-code mr-2"></i>Faire les exercices
                    </a>
                </div>
            </div>

            <!-- Results Details -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white mb-6">
                    <i class="fas fa-list-check text-purple-400 mr-3"></i>
                    Détails des réponses
                </h2>
            </div>

            @foreach($results as $index => $result)
            <div class="answer-card {{ $result['isCorrect'] ? 'correct' : 'incorrect' }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full {{ $result['isCorrect'] ? 'bg-green-500/20 border-2 border-green-500/40' : 'bg-red-500/20 border-2 border-red-500/40' }} flex items-center justify-center flex-shrink-0">
                        @if($result['isCorrect'])
                            <i class="fas fa-check text-green-400"></i>
                        @else
                            <i class="fas fa-times text-red-400"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-white mb-3">
                            Question {{ $index + 1 }}: {{ $result['question'] }}
                        </h3>
                        
                        @if($result['isCorrect'])
                            <div class="flex items-center gap-2 text-green-400">
                                <i class="fas fa-check-circle"></i>
                                <span class="font-semibold">Bonne réponse !</span>
                            </div>
                        @else
                            <div class="mb-2">
                                <div class="flex items-center gap-2 text-red-400 mb-2">
                                    <i class="fas fa-times-circle"></i>
                                    <span class="font-semibold">Votre réponse :</span>
                                    <span>{{ $result['options'][$result['userAnswer']] ?? 'Aucune réponse' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-green-400">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="font-semibold">Bonne réponse :</span>
                                    <span>{{ $result['options'][$result['correctAnswer']] }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Bottom Actions -->
            <div class="mt-12 text-center">
                <div class="bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        Continuez votre apprentissage !
                    </h3>
                    <p class="text-gray-300 mb-6">
                        Pratiquez avec nos exercices interactifs ou testez-vous sur d'autres langages.
                    </p>
                    <div class="flex gap-4 justify-center flex-wrap">
                        <a href="{{ route('exercices') }}" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                            <i class="fas fa-code mr-2"></i>Exercices
                        </a>
                        <a href="{{ route('quiz') }}" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                            <i class="fas fa-question-circle mr-2"></i>Quiz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
