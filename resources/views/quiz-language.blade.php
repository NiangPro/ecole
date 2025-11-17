@extends('layouts.app')

@section('title', 'Quiz ' . ucfirst($language) . ' | NiangProgrammeur')

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
    
    .quiz-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .question-card {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(168, 85, 247, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    body:not(.dark-mode) .question-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
        box-shadow: 0 10px 40px rgba(168, 85, 247, 0.1) !important;
    }
    
    .option-label {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(168, 85, 247, 0.2);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    body:not(.dark-mode) .option-label {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
    }
    
    .option-label:hover {
        border-color: rgba(168, 85, 247, 0.5);
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(236, 72, 153, 0.1));
        transform: translateX(10px);
    }
    
    body:not(.dark-mode) .option-label:hover {
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.08), rgba(236, 72, 153, 0.08)) !important;
    }
    
    .option-input:checked + .option-label {
        border-color: rgba(168, 85, 247, 0.8);
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(236, 72, 153, 0.2));
        box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
    }
    
    body:not(.dark-mode) .option-input:checked + .option-label {
        box-shadow: 0 0 20px rgba(168, 85, 247, 0.2) !important;
    }
    
    .option-input {
        display: none;
    }
    
    .option-letter {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(168, 85, 247, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #a855f7;
        flex-shrink: 0;
    }
    
    .option-input:checked + .option-label .option-letter {
        background: #a855f7;
        color: white;
    }
    
    .progress-bar {
        height: 8px;
        background: rgba(168, 85, 247, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #a855f7, #ec4899);
        transition: width 0.3s ease;
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
    
    /* Number badge */
    body:not(.dark-mode) .bg-purple-500\/20 {
        background: rgba(168, 85, 247, 0.1) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
    }
    
    body:not(.dark-mode) .text-purple-400 {
        color: #a855f7 !important;
    }
</style>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-32">
    <div class="container mx-auto px-6">
        <div class="quiz-container">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('quiz') }}" class="text-purple-400 hover:text-purple-300 transition mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux quiz
                </a>
                <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">
                    Quiz {{ ucfirst($language) }}
                </h1>
                <p class="text-xl text-gray-300">
                    Répondez aux {{ count($questions) }} questions ci-dessous. Bonne chance !
                </p>
            </div>

            <!-- Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400">Progression</span>
                    <span class="text-purple-400 font-bold" id="progressText">0/{{ count($questions) }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>
            </div>

            <!-- Quiz Form -->
            <form id="quizForm" action="{{ route('quiz.submit', $language) }}" method="POST">
                @csrf
                
                @foreach($questions as $index => $question)
                <div class="question-card">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-purple-500/20 border-2 border-purple-500/40 flex items-center justify-center text-purple-400 font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white">{{ $question['question'] }}</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-3 pl-16">
                        @foreach($question['options'] as $optIndex => $option)
                        <div>
                            <input type="radio" 
                                   name="answers[{{ $index }}]" 
                                   value="{{ $optIndex }}" 
                                   id="q{{ $index }}_opt{{ $optIndex }}" 
                                   class="option-input"
                                   onchange="updateProgress()">
                            <label for="q{{ $index }}_opt{{ $optIndex }}" class="option-label">
                                <span class="option-letter">{{ chr(65 + $optIndex) }}</span>
                                <span class="text-white flex-1">{{ $option }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <button type="submit" class="px-12 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold text-lg rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-check-circle mr-2"></i>Soumettre le quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    const totalQuestions = {{ count($questions) }};
    
    function updateProgress() {
        const checkedInputs = document.querySelectorAll('input[type="radio"]:checked');
        const answered = checkedInputs.length;
        const percentage = (answered / totalQuestions) * 100;
        
        document.getElementById('progressText').textContent = answered + '/' + totalQuestions;
        document.getElementById('progressFill').style.width = percentage + '%';
    }
    
    // Prevent form submission if not all questions answered
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        const checkedInputs = document.querySelectorAll('input[type="radio"]:checked');
        
        if (checkedInputs.length < totalQuestions) {
            e.preventDefault();
            alert('Veuillez répondre à toutes les questions avant de soumettre le quiz.');
            return false;
        }
    });
    
    // Scroll to top on load
    window.scrollTo(0, 0);
</script>
@endsection
