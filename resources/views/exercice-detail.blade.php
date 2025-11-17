@extends('layouts.app')

@section('title', 'Exercice ' . $id . ' - ' . $exercise['title'] . ' | NiangProgrammeur')

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
    
    .exercise-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        min-height: calc(100vh - 200px);
    }
    
    @media (max-width: 768px) {
        .exercise-container {
            grid-template-columns: 1fr;
        }
    }
    
    .exercise-panel {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 15px;
        padding: 2rem;
        height: fit-content;
    }
    
    body:not(.dark-mode) .exercise-panel {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.1) !important;
    }
    
    .code-editor {
        width: 100%;
        min-height: 400px;
        background: #1e1e1e;
        color: #d4d4d4;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        line-height: 1.6;
        resize: vertical;
    }
    
    body:not(.dark-mode) .code-editor {
        background: #f8fafc !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .code-editor:focus {
        outline: none;
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .code-editor:focus {
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
    }
    
    .result-frame {
        width: 100%;
        min-height: 400px;
        background: white;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
    }
    
    .success-message {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 10px;
        padding: 1rem;
        color: #22c55e;
        display: none;
    }
    
    .error-message {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
        border: 2px solid rgba(239, 68, 68, 0.3);
        border-radius: 10px;
        padding: 1rem;
        color: #ef4444;
        display: none;
    }
    
    /* Text colors */
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body:not(.dark-mode) .text-gray-500 {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    body:not(.dark-mode) .text-white {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Buttons */
    body:not(.dark-mode) .bg-gray-700 {
        background: rgba(6, 182, 212, 0.1) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border: 1px solid rgba(6, 182, 212, 0.3) !important;
    }
    
    body:not(.dark-mode) .bg-gray-700:hover {
        background: rgba(6, 182, 212, 0.15) !important;
    }
    
    /* Info boxes */
    body:not(.dark-mode) .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.05) !important;
        border-color: rgba(234, 179, 8, 0.2) !important;
    }
    
    body:not(.dark-mode) .bg-cyan-500\/10 {
        background: rgba(6, 182, 212, 0.05) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body:not(.dark-mode) .text-cyan-400 {
        color: #06b6d4 !important;
    }
</style>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-8">
    <div class="container mx-auto px-6">
        <!-- Breadcrumb & Navigation -->
        <div class="flex items-center justify-between mb-3 flex-wrap gap-4">
            <div>
                <a href="{{ route('exercices') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Tous les exercices
                </a>
                <span class="text-gray-500 mx-2">/</span>
                <a href="{{ route('exercices.language', $language) }}" class="text-cyan-400 hover:text-cyan-300 transition">
                    {{ ucfirst($language) }}
                </a>
                <span class="text-gray-500 mx-2">/</span>
                <span class="text-gray-400">Exercice {{ $id }}</span>
            </div>
            <div class="flex gap-2">
                @if($id > 1)
                <a href="{{ route('exercices.detail', [$language, $id - 1]) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    <i class="fas fa-chevron-left mr-2"></i>Précédent
                </a>
                @endif
                @if($id < 5)
                <a href="{{ route('exercices.detail', [$language, $id + 1]) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    Suivant<i class="fas fa-chevron-right ml-2"></i>
                </a>
                @endif
            </div>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <h1 class="text-4xl font-bold text-white">{{ $exercise['title'] }}</h1>
                <span class="px-4 py-2 bg-{{ $exercise['difficulty'] === 'Facile' ? 'green' : ($exercise['difficulty'] === 'Moyen' ? 'yellow' : 'red') }}-500/10 text-{{ $exercise['difficulty'] === 'Facile' ? 'green' : ($exercise['difficulty'] === 'Moyen' ? 'yellow' : 'red') }}-400 rounded-full text-sm font-semibold">
                    {{ $exercise['difficulty'] }}
                </span>
                <span class="px-4 py-2 bg-purple-500/10 text-purple-400 rounded-full text-sm font-semibold">
                    <i class="fas fa-star mr-1"></i>{{ $exercise['points'] }} points
                </span>
            </div>
            <p class="text-xl text-cyan-400 mb-2">{{ $exercise['instruction'] }}</p>
            <p class="text-gray-400">{{ $exercise['description'] }}</p>
        </div>

        <!-- Messages -->
        <div id="successMessage" class="success-message mb-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <div class="font-bold text-lg">Bravo ! 🎉</div>
                    <div>Votre réponse est correcte ! Vous avez gagné {{ $exercise['points'] }} points.</div>
                </div>
            </div>
        </div>

        <div id="errorMessage" class="error-message mb-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-times-circle text-2xl"></i>
                <div>
                    <div class="font-bold text-lg">Pas tout à fait...</div>
                    <div id="errorText">Réessayez ! Vous pouvez le faire.</div>
                </div>
            </div>
        </div>

        <!-- Exercise Container -->
        <div class="exercise-container">
            <!-- Left Panel - Code Editor -->
            <div class="exercise-panel">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-code text-cyan-400"></i>
                        Votre code
                    </h3>
                    <button onclick="resetCode()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition text-sm">
                        <i class="fas fa-undo mr-2"></i>Réinitialiser
                    </button>
                </div>
                <textarea id="codeEditor" class="code-editor" spellcheck="false">{{ $exercise['startCode'] }}</textarea>
                
                <div class="mt-4 flex gap-3">
                    <button onclick="runCode()" class="flex-1 px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-play mr-2"></i>Exécuter le code
                    </button>
                    <button onclick="submitCode()" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-check mr-2"></i>Soumettre
                    </button>
                </div>

                <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-lightbulb text-yellow-400 mt-1"></i>
                        <div>
                            <div class="font-semibold text-yellow-400 mb-1">Indice</div>
                            <div class="text-gray-300 text-sm">{{ $exercise['hint'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Result -->
            <div class="exercise-panel">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-eye text-cyan-400"></i>
                    Résultat
                </h3>
                <iframe id="resultFrame" class="result-frame"></iframe>
                
                <div class="mt-4 p-4 bg-cyan-500/10 border border-cyan-500/20 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-cyan-400 mt-1"></i>
                        <div class="text-gray-300 text-sm">
                            Cliquez sur "Exécuter le code" pour voir le résultat de votre code. 
                            Cliquez sur "Soumettre" pour vérifier si votre réponse est correcte.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const startCode = @json($exercise['startCode']);
    const language = @json($language);
    const exerciseId = @json($id);
    
    function resetCode() {
        document.getElementById('codeEditor').value = startCode;
        hideMessages();
    }
    
    function runCode() {
        const code = document.getElementById('codeEditor').value;
        const iframe = document.getElementById('resultFrame');
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        
        iframeDoc.open();
        iframeDoc.write(code);
        iframeDoc.close();
        
        hideMessages();
    }
    
    function submitCode() {
        const code = document.getElementById('codeEditor').value;
        
        fetch(`/exercices/${language}/${exerciseId}/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            hideMessages();
            
            if (data.correct) {
                document.getElementById('successMessage').style.display = 'block';
                
                // Scroll to success message
                document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Confetti effect (optional)
                setTimeout(() => {
                    // Auto-redirect to next exercise after 3 seconds
                    @if($id < 5)
                    setTimeout(() => {
                        window.location.href = '{{ route('exercices.detail', [$language, $id + 1]) }}';
                    }, 3000);
                    @endif
                }, 500);
            } else {
                document.getElementById('errorMessage').style.display = 'block';
                document.getElementById('errorText').textContent = data.message;
                
                // Scroll to error message
                document.getElementById('errorMessage').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
        });
    }
    
    function hideMessages() {
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'none';
    }
    
    // Auto-run code on load
    window.addEventListener('load', () => {
        runCode();
    });
    
    // Keyboard shortcuts
    document.getElementById('codeEditor').addEventListener('keydown', (e) => {
        // Ctrl/Cmd + Enter to run
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            runCode();
        }
        
        // Tab key for indentation
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = e.target.selectionStart;
            const end = e.target.selectionEnd;
            const value = e.target.value;
            
            e.target.value = value.substring(0, start) + '  ' + value.substring(end);
            e.target.selectionStart = e.target.selectionEnd = start + 2;
        }
    });
</script>
@endsection
