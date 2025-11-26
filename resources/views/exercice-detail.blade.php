@extends('layouts.app')

@section('title', trans('app.exercices.exercise') . ' ' . $id . ' - ' . $exercise['title'] . ' | NiangProgrammeur')

@section('styles')
<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/eclipse.min.css">
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
    
    /* Empêcher le débordement */
    .exercise-panel {
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .code-editor-wrapper {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        box-sizing: border-box;
    }
    
    .CodeMirror {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
    }
    
    .result-frame {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
        overflow-x: auto;
        overflow-y: auto;
    }
    
    @media (max-width: 768px) {
        section.py-20 {
            padding-top: 100px !important;
            padding-bottom: 40px !important;
        }
        
        .container.mx-auto.px-6 {
            padding-left: 16px !important;
            padding-right: 16px !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        
        .exercise-container {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        
        .flex.items-center.justify-between.mb-3 {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        
        .flex.gap-2 {
            width: 100% !important;
            flex-direction: column !important;
        }
        
        .flex.gap-2 a {
            width: 100% !important;
            text-align: center !important;
        }
        
        h1.text-4xl {
            font-size: 1.75rem !important;
            line-height: 1.3 !important;
        }
        
        .flex.items-center.gap-4 {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        
        .text-xl {
            font-size: 1rem !important;
        }
        
        .exercise-panel {
            padding: 1.5rem !important;
        }
        
        h3.text-xl {
            font-size: 1.125rem !important;
        }
        
        .code-editor-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
        }
        
        .CodeMirror {
            min-height: 300px !important;
            font-size: 12px !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
        }
        
        .CodeMirror-scroll {
            min-height: 300px !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
        }
        
        .CodeMirror-scrollbar {
            overflow-x: auto !important;
        }
        
        .result-frame {
            min-height: 300px !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
        }
        
        /* Scroll horizontal dans l'iframe */
        .result-frame iframe {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
        }
        
        .mt-4.flex.gap-3 {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        
        .exercise-buttons button,
        .mt-4.flex.gap-3 button {
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .exercise-buttons button i,
        .mt-4.flex.gap-3 button i {
            font-size: 0.875rem !important;
            margin-right: 0.5rem !important;
        }
        
        .p-4 {
            padding: 1rem !important;
        }
        
        .text-sm {
            font-size: 0.875rem !important;
        }
    }
    
    @media (max-width: 480px) {
        section.py-20 {
            padding-top: 80px !important;
            padding-bottom: 30px !important;
        }
        
        h1.text-4xl {
            font-size: 1.5rem !important;
        }
        
        .exercise-panel {
            padding: 1.25rem !important;
        }
        
        .CodeMirror {
            min-height: 250px !important;
            font-size: 11px !important;
        }
        
        .CodeMirror-scroll {
            min-height: 250px !important;
        }
        
        .result-frame {
            min-height: 250px !important;
        }
        
        .text-xl {
            font-size: 0.9rem !important;
        }
        
        .px-4.py-2 {
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .px-6.py-3 {
            padding: 0.75rem 0.875rem !important;
            font-size: 0.8rem !important;
        }
        
        .code-editor-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
        }
        
        .CodeMirror {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
        }
        
        .result-frame {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
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
    
    /* CodeMirror Editor Styles */
    .CodeMirror {
        width: 100%;
        min-height: 400px;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.6;
        font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
    }
    
    .CodeMirror:focus-within {
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .CodeMirror:focus-within {
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
    }
    
    /* CodeMirror Scrollbar */
    .CodeMirror-scroll {
        min-height: 400px;
    }
    
    .CodeMirror-vscrollbar {
        overflow-x: hidden;
        overflow-y: scroll;
    }
    
    /* CodeMirror Line Numbers */
    .CodeMirror-linenumber {
        color: #858585;
        padding: 0 10px 0 5px;
    }
    
    body:not(.dark-mode) .CodeMirror-linenumber {
        color: #999 !important;
    }
    
    /* CodeMirror Cursor */
    .CodeMirror-cursor {
        border-left: 2px solid #06b6d4;
    }
    
    body:not(.dark-mode) .CodeMirror-cursor {
        border-left-color: #06b6d4 !important;
    }
    
    /* CodeMirror Selection */
    .CodeMirror-selected {
        background: rgba(6, 182, 212, 0.3) !important;
    }
    
    body:not(.dark-mode) .CodeMirror-selected {
        background: rgba(6, 182, 212, 0.2) !important;
    }
    
    /* Amélioration de la coloration syntaxique HTML */
    /* Balises HTML - couleur bleue/cyan */
    .cm-tag {
        color: #569cd6 !important;
        font-weight: 500;
    }
    
    body:not(.dark-mode) .cm-tag {
        color: #0066cc !important;
    }
    
    /* Attributs HTML - couleur jaune/orange */
    .cm-attribute {
        color: #9cdcfe !important;
    }
    
    body:not(.dark-mode) .cm-attribute {
        color: #d97706 !important;
    }
    
    /* Valeurs d'attributs - couleur verte */
    .cm-string {
        color: #ce9178 !important;
    }
    
    body:not(.dark-mode) .cm-string {
        color: #008000 !important;
    }
    
    /* Contenu texte - couleur blanche/gris */
    .cm-meta {
        color: #d4d4d4 !important;
    }
    
    body:not(.dark-mode) .cm-meta {
        color: #333333 !important;
    }
    
    /* Commentaires - couleur grise */
    .cm-comment {
        color: #6a9955 !important;
        font-style: italic;
    }
    
    body:not(.dark-mode) .cm-comment {
        color: #6a737d !important;
    }
    
    /* Désactiver les erreurs de balises dans CodeMirror - Masquer toutes les erreurs rouges */
    .CodeMirror .cm-error,
    .CodeMirror .cm-tag.cm-error,
    .CodeMirror .cm-tag.cm-bracket.cm-error,
    .CodeMirror span.cm-error,
    .CodeMirror .cm-error.cm-tag,
    .CodeMirror .cm-error.cm-bracket,
    .CodeMirror .cm-error.cm-tag-name,
    .CodeMirror span[class*="error"] {
        color: inherit !important;
        background: transparent !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Forcer la couleur normale pour les balises (pas rouge) - Override toutes les erreurs */
    .CodeMirror .cm-tag,
    .CodeMirror .cm-tag.cm-error,
    .CodeMirror .cm-tag.cm-bracket.cm-error {
        color: #f472b6 !important;
    }
    
    body:not(.dark-mode) .CodeMirror .cm-tag,
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-error,
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-bracket.cm-error {
        color: #c026d3 !important;
    }
    
    .CodeMirror .cm-tag.cm-bracket,
    .CodeMirror .cm-tag.cm-bracket.cm-error {
        color: #94a3b8 !important;
    }
    
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-bracket,
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-bracket.cm-error {
        color: #64748b !important;
    }
    
    /* S'assurer que les balises fermantes ne sont pas en rouge */
    .CodeMirror .cm-tag.cm-tag-name,
    .CodeMirror .cm-tag.cm-tag-name.cm-error {
        color: #f472b6 !important;
    }
    
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-tag-name,
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-tag-name.cm-error {
        color: #c026d3 !important;
    }
    
    /* S'assurer que les balises fermantes ne sont pas en rouge */
    .CodeMirror .cm-tag.cm-tag-name {
        color: #f472b6 !important;
    }
    
    body:not(.dark-mode) .CodeMirror .cm-tag.cm-tag-name {
        color: #c026d3 !important;
    }
    
    /* Hide textarea but keep it in DOM for CodeMirror */
    #codeEditor {
        position: absolute !important;
        left: -9999px !important;
        opacity: 0 !important;
        width: 1px !important;
        height: 1px !important;
        visibility: hidden !important;
        display: none !important;
    }
    
    /* S'assurer que CodeMirror est visible */
    .CodeMirror {
        display: block !important;
        visibility: visible !important;
    }
    
    .code-editor-wrapper {
        position: relative;
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
                    <i class="fas fa-arrow-left mr-2"></i>{{ trans('app.exercices.all_exercices') }}
                </a>
                <span class="text-gray-500 mx-2">/</span>
                <a href="{{ route('exercices.language', $language) }}" class="text-cyan-400 hover:text-cyan-300 transition">
                    {{ ucfirst($language) }}
                </a>
                <span class="text-gray-500 mx-2">/</span>
                <span class="text-gray-400">{{ trans('app.exercices.exercise') }} {{ $id }}</span>
            </div>
            <div class="flex gap-2">
                @if($id > 1)
                <a href="{{ route('exercices.detail', [$language, $id - 1]) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    <i class="fas fa-chevron-left mr-2"></i>{{ trans('app.exercices.detail.previous') }}
                </a>
                @endif
                @if($id < 5)
                <a href="{{ route('exercices.detail', [$language, $id + 1]) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    {{ trans('app.exercices.detail.next') }}<i class="fas fa-chevron-right ml-2"></i>
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
                    <i class="fas fa-star mr-1"></i>{{ $exercise['points'] }} {{ trans('app.exercices.points') }}
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
                    <div class="font-bold text-lg">{{ trans('app.exercices.detail.success_title') }}</div>
                    <div>{{ str_replace(':points', $exercise['points'], trans('app.exercices.detail.success_message')) }}</div>
                </div>
            </div>
        </div>

        <div id="errorMessage" class="error-message mb-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-times-circle text-2xl"></i>
                <div>
                    <div class="font-bold text-lg">{{ trans('app.exercices.detail.error_title') }}</div>
                    <div id="errorText">{{ trans('app.exercices.detail.error_message') }}</div>
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
                        {{ trans('app.exercices.detail.your_code') }}
                    </h3>
                    <button onclick="resetCode()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition text-sm">
                        <i class="fas fa-undo mr-2"></i>{{ trans('app.exercices.detail.reset') }}
                    </button>
                </div>
                <div class="code-editor-wrapper">
                    <textarea id="codeEditor" spellcheck="false" style="display: none !important; visibility: hidden !important; position: absolute !important; left: -9999px !important; opacity: 0 !important; width: 1px !important; height: 1px !important;">{!! htmlspecialchars($exercise['startCode'], ENT_QUOTES, 'UTF-8') !!}</textarea>
                </div>
                
                <div class="mt-4 flex gap-3">
                    <button onclick="runCode()" class="flex-1 px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-play mr-2"></i>{{ trans('app.exercices.detail.run_code') }}
                    </button>
                    <button onclick="submitCode()" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                        <i class="fas fa-check mr-2"></i>{{ trans('app.exercices.detail.submit') }}
                    </button>
                </div>

                <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-lightbulb text-yellow-400 mt-1"></i>
                        <div>
                            <div class="font-semibold text-yellow-400 mb-1">{{ trans('app.exercices.detail.hint') }}</div>
                            <div class="text-gray-300 text-sm">{{ $exercise['hint'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Result -->
            <div class="exercise-panel">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-eye text-cyan-400"></i>
                    {{ trans('app.exercices.detail.result') }}
                </h3>
                <iframe id="resultFrame" class="result-frame"></iframe>
                
                <div class="mt-4 p-4 bg-cyan-500/10 border border-cyan-500/20 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-cyan-400 mt-1"></i>
                        <div class="text-gray-300 text-sm">
                            {{ trans('app.exercices.detail.result_help') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- CodeMirror JS - Chargement séquentiel -->
<script>
    // Traductions pour JavaScript
    const translations = {
        executing: @json(trans('app.exercices.detail.executing')),
        noOutput: @json(trans('app.exercices.detail.no_output')),
        result: @json(trans('app.exercices.detail.result')),
        successTitle: @json(trans('app.exercices.detail.success_title')),
        successMessage: @json(trans('app.exercices.detail.success_message')),
        errorTitle: @json(trans('app.exercices.detail.error_title')),
        errorMessage: @json(trans('app.exercices.detail.error_message')),
    };
    // Cacher immédiatement le textarea avant le chargement de CodeMirror
    (function() {
        const textarea = document.getElementById('codeEditor');
        if (textarea) {
            textarea.style.display = 'none';
            textarea.style.visibility = 'hidden';
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            textarea.style.opacity = '0';
            textarea.style.width = '1px';
            textarea.style.height = '1px';
        }
    })();
    
    // Charger les scripts CodeMirror de manière séquentielle
    function loadScript(src, callback) {
        const script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        script.onerror = function() {
            console.error('Erreur lors du chargement de:', src);
            if (callback) callback();
        };
        document.head.appendChild(script);
    }
    
    // Charger tous les scripts CodeMirror
    loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js', function() {
        loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js', function() {
            loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js', function() {
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js', function() {
                    loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js', function() {
                        loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js', function() {
                            loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js', function() {
                                loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js', function() {
                                    // Tous les scripts sont chargés, initialiser CodeMirror
                                    initCodeMirror();
                                });
                            });
                        });
                    });
                });
            });
        });
    });
    
    // Fonction pour initialiser CodeMirror
    function initCodeMirror() {
        // Vérifier que CodeMirror est chargé
        if (typeof CodeMirror === 'undefined') {
            console.error('CodeMirror n\'est pas chargé');
            return;
        }
        
        console.log('CodeMirror chargé, initialisation...');
        
    // Préserver les retours à la ligne en utilisant directement la valeur du textarea
    const textareaElement = document.getElementById('codeEditor');
    let startCode = textareaElement ? textareaElement.value : @json($exercise['startCode']);
    // Normaliser les retours à la ligne (Windows \r\n, Mac \r, Unix \n -> \n)
    startCode = startCode.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
    
    const language = @json($language);
    const exerciseId = @json($id);
    
        // Déterminer le mode CodeMirror selon la langue
        let codeMirrorMode = 'htmlmixed';
        if (language === 'css3' || language === 'css') {
            codeMirrorMode = 'css';
        } else if (language === 'javascript' || language === 'js') {
            // Pour JavaScript, utiliser htmlmixed car les exercices contiennent du HTML avec des scripts
            codeMirrorMode = 'htmlmixed';
        } else if (language === 'php') {
            codeMirrorMode = 'application/x-httpd-php';
        } else if (language === 'python') {
            codeMirrorMode = 'python';
        } else if (language === 'html5' || language === 'html') {
            codeMirrorMode = 'htmlmixed';
        } else if (language === 'java') {
            codeMirrorMode = 'text/x-java';
        } else if (language === 'c' || language === 'cpp' || language === 'c++') {
            codeMirrorMode = 'text/x-csrc';
        } else if (language === 'sql') {
            codeMirrorMode = 'text/x-sql';
        }
        
        // Déterminer le thème selon le mode sombre
        const isDarkMode = document.body.classList.contains('dark-mode');
        const codeMirrorTheme = isDarkMode ? 'monokai' : 'eclipse';
        
        // Stocker isDarkMode globalement pour l'utiliser dans runCode
        window.isDarkMode = isDarkMode;
        
        // Attendre un peu pour s'assurer que le textarea est dans le DOM
        setTimeout(function() {
            const textarea = document.getElementById('codeEditor');
            if (!textarea) {
                console.error('Textarea codeEditor non trouvé');
                return;
            }
            
            // S'assurer que le textarea a le code avec les retours à la ligne préservés
            // startCode est déjà normalisé au-dessus
            if (textarea.value !== startCode) {
                textarea.value = startCode;
            }
            
            // Initialiser CodeMirror
            const codeEditor = CodeMirror.fromTextArea(textarea, {
                mode: codeMirrorMode,
                theme: codeMirrorTheme,
                lineNumbers: true,
                lineWrapping: true,
                indentUnit: (language === 'java' || language === 'c' || language === 'cpp' || language === 'c++') ? 4 : 2,
                indentWithTabs: false,
                tabSize: (language === 'java' || language === 'c' || language === 'cpp' || language === 'c++') ? 4 : 2,
                autofocus: false,
                matchBrackets: true,
                autoCloseBrackets: true,
                autoCloseTags: true,
                foldGutter: true,
                gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                // Options pour mieux gérer le HTML avec JavaScript
                matchTags: { bothTags: true },
                extraKeys: {
                    'Ctrl-Space': 'autocomplete',
                    'Ctrl-/': 'toggleComment',
                    'Ctrl-Enter': function() {
                        runCode();
                    },
                    'Cmd-Enter': function() {
                        runCode();
                    },
                    'Tab': function(cm) {
                        if (cm.somethingSelected()) {
                            cm.indentSelection('add');
                        } else {
                            cm.replaceSelection('  ', 'end');
                        }
                    },
                    'Shift-Tab': function(cm) {
                        cm.indentSelection('subtract');
                    }
                }
            });
            
            // S'assurer que le code initial est correctement formaté avec les retours à la ligne
            // CodeMirror lit automatiquement le contenu du textarea, donc on s'assure qu'il est correct
            const currentValue = codeEditor.getValue();
            if (currentValue !== startCode) {
                codeEditor.setValue(startCode);
            }
            
            // Stocker l'instance globalement
            window.codeEditorInstance = codeEditor;
            
            // Afficher le résultat par défaut pour l'exercice C numéro 1
            if (language === 'c' && exerciseId === 1) {
                const iframe = document.getElementById('resultFrame');
                if (iframe) {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const darkMode = window.isDarkMode || document.body.classList.contains('dark-mode');
                    iframeDoc.open();
                    iframeDoc.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>Résultat</title>
                            <style>
                                * {
                                    margin: 0;
                                    padding: 0;
                                    box-sizing: border-box;
                                }
                                body {
                                    font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
                                    padding: 20px;
                                    background: ${darkMode ? '#1e293b' : '#ffffff'};
                                    color: ${darkMode ? '#e2e8f0' : '#1e293b'};
                                    font-size: 14px;
                                    line-height: 1.6;
                                }
                                pre {
                                    margin: 0;
                                    white-space: pre-wrap;
                                    word-wrap: break-word;
                                }
                            </style>
                        </head>
                        <body>
                            <pre>Bonjour C !</pre>
                        </body>
                        </html>
                    `);
                    iframeDoc.close();
                }
            }
            
            // Synchroniser CodeMirror avec le textarea pour les soumissions
            codeEditor.on('change', function(cm) {
                cm.save();
            });
            
            // Adapter le thème si le mode sombre change
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.body.classList.contains('dark-mode');
                        codeEditor.setOption('theme', isDark ? 'monokai' : 'eclipse');
                        window.isDarkMode = isDark; // Mettre à jour la variable globale
                    }
                });
            });
            observer.observe(document.body, { attributes: true });
            
            // Fonctions globales
            window.resetCode = function() {
                codeEditor.setValue(startCode);
                hideMessages();
            };
            
            window.runCode = function() {
                const code = codeEditor.getValue();
        const iframe = document.getElementById('resultFrame');
                if (!iframe) {
                    return;
                }
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        
                // Afficher un message de chargement
        iframeDoc.open();
                iframeDoc.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Exécution...</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                padding: 20px;
                                background: #1e293b;
                                color: #e2e8f0;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                height: 100vh;
                                margin: 0;
                            }
                            .loading {
                                text-align: center;
                            }
                            .spinner {
                                border: 4px solid rgba(6, 182, 212, 0.2);
                                border-top: 4px solid #06b6d4;
                                border-radius: 50%;
                                width: 40px;
                                height: 40px;
                                animation: spin 1s linear infinite;
                                margin: 0 auto 20px;
                            }
                            @keyframes spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="loading">
                            <div class="spinner"></div>
                            <p>{{ trans('app.exercices.detail.executing') }}</p>
                        </div>
                    </body>
                    </html>
                `);
        iframeDoc.close();
        
                // Si c'est du PHP, Python, Java ou C, exécuter côté serveur
                if (language === 'php' || language === 'python' || language === 'java' || language === 'c' || language === 'cpp' || language === 'c++') {
                    fetch(`/exercices/${language}/run`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code: code })
                    })
                    .then(response => {
                        // Vérifier si la réponse est du JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                throw new Error('Réponse non-JSON reçue: ' + text.substring(0, 200));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        iframeDoc.open();
                        
                        if (data.error) {
                            const langName = language === 'python' ? 'Python' : (language === 'java' ? 'Java' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'C' : 'PHP');
                            iframeDoc.write(`
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="UTF-8">
                                    <title>Erreur</title>
                                    <style>
                                        body {
                                            font-family: Arial, sans-serif;
                                            padding: 20px;
                                            background: #fee;
                                            color: #c33;
                                        }
                                        .error {
                                            background: #fcc;
                                            border: 2px solid #c33;
                                            padding: 15px;
                                            border-radius: 5px;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class="error">
                                        <h3>Erreur ${langName} :</h3>
                                        <pre>${data.error}</pre>
                                    </div>
                                </body>
                                </html>
                            `);
                        } else {
                            // Envelopper la sortie dans du HTML
                            let output = data.output || '';
                            
                            // NETTOYAGE SIMPLIFIÉ (MÊME LOGIQUE QUE PYTHON)
                            // Supprimer tous les espaces, tabulations et retours à la ligne en début/fin
                            output = output.trim();
                            
                            // Supprimer les espaces en début de chaque ligne (indentation indésirable)
                            if (output) {
                                // Supprimer tous les caractères d'espacement Unicode en début/fin (comme Python)
                                output = output.replace(/^[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                                output = output.replace(/[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+$/g, '');
                                
                                // Nettoyer ligne par ligne (comme Python)
                                const lines = output.split('\n');
                                const cleanedLines = [];
                                for (let line of lines) {
                                    // Supprimer tous les espaces, tabulations et caractères invisibles en début de ligne
                                    const cleaned = line.replace(/^[\s\t\r\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                                    cleanedLines.push(cleaned);
                                }
                                output = cleanedLines.join('\n');
                                
                                // Retrim pour supprimer les lignes vides en début/fin et tous les espaces (comme Python)
                                output = output.trim();
                                
                                // Supprimer une dernière fois tous les espaces invisibles (comme Python)
                                output = output.replace(/^[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                                
                                // ÉTAPE FINALE : Trouver le premier caractère non-blanc et supprimer TOUT avant
                                const match = output.match(/\S/);
                                if (match && match.index !== undefined) {
                                    // Supprimer tout avant ce caractère
                                    output = output.substring(match.index);
                                }
                            }
                            const hasOutput = output.length > 0;
                            const darkMode = window.isDarkMode || document.body.classList.contains('dark-mode');
                            
                            // Vérifier si le output contient déjà une structure HTML complète (DOCTYPE, html, head, body)
                            const hasFullHTML = /^\s*<!DOCTYPE\s+html\s*>/i.test(output) || /^\s*<html[\s>]/i.test(output);
                            
                            if (hasFullHTML) {
                                // Si le output contient déjà du HTML complet, l'utiliser directement
                                // Mais ajouter nos styles dans le head s'il existe
                                let finalOutput = output;
                                
                                // Injecter nos styles dans le <head> si il existe
                                const headStyle = `
                                    <style>
                                        * {
                                            margin: 0;
                                            padding: 0;
                                            box-sizing: border-box;
                                        }
                                        html {
                                            margin: 0;
                                            padding: 0;
                                            height: 100%;
                                            overflow: hidden;
                                        }
                                        html, body {
                                            margin: 0 !important;
                                            padding: 0 !important;
                                            width: 100%;
                                            height: 100%;
                                        }
                                        body {
                                            padding: 0 !important;
                                            margin: 0 !important;
                                            padding-top: 0 !important;
                                            margin-top: 0 !important;
                                        }
                                        body > *:first-child,
                                        body > *:first-of-type {
                                            margin-top: 0 !important;
                                            padding-top: 0 !important;
                                            margin: 0 !important;
                                            padding: 0 !important;
                                        }
                                        body::before {
                                            display: none !important;
                                            content: none !important;
                                        }
                                    </style>
                                `;
                                
                                // Si un <head> existe, injecter les styles dedans
                                if (/<head[^>]*>/i.test(finalOutput)) {
                                    finalOutput = finalOutput.replace(/(<head[^>]*>)/i, '$1' + headStyle);
                                } else if (/<html[^>]*>/i.test(finalOutput)) {
                                    // Si pas de head, l'ajouter après <html>
                                    finalOutput = finalOutput.replace(/(<html[^>]*>)/i, '$1<head>' + headStyle + '</head>');
                                }
                                
                                // S'assurer que le body a les styles inline
                                finalOutput = finalOutput.replace(/(<body[^>]*)/i, '$1 style="margin: 0 !important; padding: 0 !important; padding-top: 0 !important; margin-top: 0 !important;"');
                                
                                iframeDoc.write(finalOutput);
                                
                                // Intercepter les formulaires et liens après écriture
                                setTimeout(() => {
                                    interceptFormsAndLinks(iframeDoc);
                                }, 100);
                            } else {
                                // Si pas de HTML complet, utiliser notre structure
                                iframeDoc.write(`
                                    <!DOCTYPE html>
                                    <html>
                                    <head>
                                        <meta charset="UTF-8">
                                        <title>{{ trans('app.exercices.detail.result') }}</title>
                                        <style>
                                            * {
                                                margin: 0;
                                                padding: 0;
                                                box-sizing: border-box;
                                            }
                                            html {
                                                margin: 0;
                                                padding: 0;
                                                height: 100%;
                                                overflow-x: auto;
                                                overflow-y: auto;
                                                width: 100%;
                                            }
                                            html, body {
                                                margin: 0 !important;
                                                padding: 0 !important;
                                                width: 100%;
                                                min-width: 100%;
                                                max-width: 100%;
                                                height: 100%;
                                                overflow-x: auto;
                                                overflow-y: auto;
                                            }
                                            body {
                                                font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
                                                padding: 0 !important;
                                                margin: 0 !important;
                                                padding-top: 0 !important;
                                                margin-top: 0 !important;
                                                background: ${darkMode ? '#1e293b' : 'white'};
                                                color: ${darkMode ? '#e2e8f0' : '#333'};
                                                white-space: pre-wrap;
                                                word-wrap: break-word;
                                                line-height: 1.5;
                                                display: block;
                                                overflow-x: auto;
                                                overflow-y: auto;
                                                position: relative;
                                                top: 0 !important;
                                                width: 100%;
                                                min-width: 100%;
                                                max-width: 100%;
                                            }
                                            
                                            /* Forcer l'absence d'espace en haut pour TOUS les éléments */
                                            body > *:first-child,
                                            body > *:first-of-type {
                                                margin-top: 0 !important;
                                                padding-top: 0 !important;
                                                margin: 0 !important;
                                                padding: 0 !important;
                                            }
                                            
                                            /* Supprimer tout espace avant le premier élément */
                                            body::before {
                                                display: none !important;
                                                content: none !important;
                                            }
                                            
                                            /* Si le contenu commence par du HTML (DOCTYPE, html, etc.), s'assurer qu'il n'y a pas d'espace */
                                            html > body {
                                                margin: 0 !important;
                                                padding: 0 !important;
                                            }
                                            
                                            /* Supprimer tout espace avant le DOCTYPE ou html */
                                            html:first-child {
                                                margin: 0 !important;
                                                padding: 0 !important;
                                            }
                                            .no-output {
                                                color: #999;
                                                font-style: italic;
                                                font-family: Arial, sans-serif;
                                                margin: 0;
                                                padding: 0;
                                            }
                                        </style>
                                    </head>
                                    <body style="margin: 0 !important; padding: 0 !important; padding-top: 0 !important; margin-top: 0 !important;">${hasOutput ? output : '<p class="no-output">' + (language === 'java' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez System.out.println() pour afficher des résultats.' : language === 'python' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez printf() pour afficher des résultats.' : translations.noOutput) + '</p>'}
                                    </body>
                                    </html>
                                `);
                            }
                        }
                        
                        // Intercepter les formulaires et les liens dans l'iframe
                        if (iframeDoc.readyState === 'complete') {
                            interceptFormsAndLinks(iframeDoc);
                        } else {
                            iframeDoc.addEventListener('load', () => {
                                interceptFormsAndLinks(iframeDoc);
                            });
                        }
                        
                        iframeDoc.close();
        hideMessages();
                    })
                    .catch(error => {
                        iframeDoc.open();
                        iframeDoc.write(`
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="UTF-8">
                                <title>Erreur</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                        background: #fee;
                                        color: #c33;
                                    }
                                    pre {
                                        background: #fff;
                                        padding: 10px;
                                        border: 1px solid #c33;
                                        border-radius: 5px;
                                        overflow-x: auto;
                                    }
                                </style>
                            </head>
                            <body>
                                <h3>Erreur lors de l'exécution :</h3>
                                <pre>${error.message}</pre>
                                <p><small>Vérifiez la console du navigateur pour plus de détails.</small></p>
                            </body>
                            </html>
                        `);
                        iframeDoc.close();
                    });
                } else {
                    // Pour HTML/CSS/JS, afficher directement
                    // Le JavaScript sera exécuté automatiquement par le navigateur
                    
                    try {
                        // Écrire directement le code dans l'iframe
                        // IMPORTANT: Le JavaScript dans les balises <script> s'exécutera automatiquement
                        // quand on appelle iframeDoc.close() après iframeDoc.write()
        iframeDoc.open();
        iframeDoc.write(code);
                        iframeDoc.close(); // Cette ligne déclenche l'exécution du JavaScript
                        
                        // Le JavaScript s'exécute automatiquement lors du close()
                        // Attendre que l'iframe soit complètement chargé pour intercepter les formulaires
                        const setupInterception = () => {
                            try {
                                const currentDoc = iframe.contentDocument || iframe.contentWindow.document;
                                if (currentDoc && currentDoc.body) {
                                    // Ajouter un gestionnaire d'erreurs simple dans l'iframe
                                    try {
                                        const iframeWindow = currentDoc.defaultView || currentDoc.parentWindow;
                                        if (iframeWindow) {
                                            iframeWindow.addEventListener('error', function(e) {
                                                console.error('Erreur JavaScript dans l\'iframe:', e.error || e.message);
                                                // Afficher l'erreur dans l'iframe si possible
                                                if (currentDoc.body && !currentDoc.getElementById('js-error-display')) {
                                                    const errorDiv = currentDoc.createElement('div');
                                                    errorDiv.id = 'js-error-display';
                                                    errorDiv.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; background: #fee; color: #c33; padding: 15px; border-bottom: 2px solid #c33; z-index: 10000; font-family: monospace; font-size: 12px;';
                                                    errorDiv.innerHTML = '<strong>⚠️ Erreur JavaScript:</strong> ' + (e.error ? e.error.message : e.message || 'Erreur inconnue');
                                                    currentDoc.body.insertBefore(errorDiv, currentDoc.body.firstChild);
                                                    setTimeout(() => {
                                                        if (errorDiv.parentNode) {
                                                            errorDiv.parentNode.removeChild(errorDiv);
                                                        }
                                                    }, 10000);
                                                }
                                            }, true);
                                        }
                                    } catch (err) {
                                        // Ignorer les erreurs de gestionnaire
                                    }
                                    
                                    // Attendre un peu pour que tous les scripts s'exécutent
                                    setTimeout(() => {
                                        try {
                                            if (typeof interceptFormsAndLinks === 'function') {
                                                interceptFormsAndLinks(currentDoc);
                                            }
                                        } catch (e) {
                                            // Ignorer les erreurs d'interception
                                        }
                                    }, 200);
                                } else {
                                    // Réessayer si pas encore prêt
                                    setTimeout(setupInterception, 50);
                                }
                            } catch (e) {
                                // Ignorer les erreurs
                            }
                        };
                        
                        // Utiliser l'événement load de l'iframe
                        iframe.addEventListener('load', function() {
                            setTimeout(setupInterception, 150);
                        }, { once: true });
                        
                        // Si déjà chargé, exécuter immédiatement
                        setTimeout(setupInterception, 300);
                    } catch (e) {
                        console.error('Erreur lors de l\'écriture dans l\'iframe:', e);
                        iframeDoc.open();
                        iframeDoc.write(`
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="UTF-8">
                                <title>Erreur</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                        background: #fee;
                                        color: #c33;
                                    }
                                </style>
                            </head>
                            <body>
                                <h3>Erreur lors du chargement :</h3>
                                <pre>${e.message}</pre>
                            </body>
                            </html>
                        `);
        iframeDoc.close();
                    }
        
        hideMessages();
    }
            };
    
            window.submitCode = function() {
                const code = codeEditor.getValue();
        
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
            };
    
            window.hideMessages = function() {
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'none';
            };
            
            // Fonction pour intercepter les formulaires et les liens dans l'iframe
            function interceptFormsAndLinks(iframeDoc) {
                try {
                    const iframeWindow = iframeDoc.defaultView || iframeDoc.parentWindow;
                    const iframeBody = iframeDoc.body;
                    
                    if (!iframeBody) return;
                    
                    // Intercepter les formulaires
                    const forms = iframeBody.querySelectorAll('form');
                    forms.forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const formData = new FormData(form);
                            const postData = {};
                            formData.forEach((value, key) => {
                                postData[key] = value;
                            });
                            
                            // Récupérer la méthode du formulaire
                            const method = (form.method || 'GET').toUpperCase();
                            
                            // Si c'est GET, récupérer les paramètres de l'action
                            let getData = {};
                            if (method === 'GET' && form.action) {
                                try {
                                    const url = new URL(form.action, window.location.origin);
                                    url.searchParams.forEach((value, key) => {
                                        getData[key] = value;
                                    });
                                } catch (err) {
                                    // Si l'URL n'est pas valide, ignorer
                                }
                            }
                            
                            // Exécuter le code avec les données POST/GET
                            const code = codeEditor.getValue();
                            runCodeWithFormData(code, method === 'POST' ? postData : {}, method === 'GET' ? getData : {});
                        });
                    });
                    
                    // Intercepter les liens avec paramètres GET
                    const links = iframeBody.querySelectorAll('a[href]');
                    links.forEach(link => {
                        link.addEventListener('click', function(e) {
                            const href = link.getAttribute('href');
                            
                            // Si le lien contient des paramètres GET (?) ou pointe vers une autre page
                            if (href && (href.includes('?') || href.startsWith('http') || href.startsWith('/'))) {
            e.preventDefault();
                                e.stopPropagation();
                                
                                // Extraire les paramètres GET de l'URL
                                let getData = {};
                                try {
                                    const url = new URL(href, window.location.origin);
                                    url.searchParams.forEach((value, key) => {
                                        getData[key] = value;
                                    });
                                } catch (err) {
                                    // Si l'URL n'est pas valide, essayer de parser manuellement
                                    if (href.includes('?')) {
                                        const parts = href.split('?');
                                        if (parts.length > 1) {
                                            const params = parts[1].split('&');
                                            params.forEach(param => {
                                                const [key, value] = param.split('=');
                                                if (key) {
                                                    getData[decodeURIComponent(key)] = value ? decodeURIComponent(value) : '';
                                                }
                                            });
                                        }
                                    }
                                }
                                
                                // Exécuter le code avec les paramètres GET
                                const code = codeEditor.getValue();
                                runCodeWithFormData(code, {}, getData);
                            }
                        });
                    });
                } catch (err) {
                    console.error('Erreur lors de l\'interception des formulaires/liens:', err);
                }
            }
            
            // Fonction pour exécuter le code avec des données POST/GET
            function runCodeWithFormData(code, postData, getData) {
                const iframe = document.getElementById('resultFrame');
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                
                // Afficher un indicateur de chargement
                iframeDoc.open();
                iframeDoc.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Chargement...</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                padding: 20px;
                                background: #f0f0f0;
                                color: #333;
                                text-align: center;
                            }
                        </style>
                    </head>
                    <body>
                        <p>⏳ Traitement du formulaire...</p>
                    </body>
                    </html>
                `);
                iframeDoc.close();
                
                // Envoyer les données au backend
                fetch(`/exercices/${language}/run`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code,
                        post_data: postData,
                        get_data: getData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Réutiliser la logique d'affichage existante
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const darkMode = window.isDarkMode || document.body.classList.contains('dark-mode');
                    
                    if (data.error) {
                        iframeDoc.open();
                        iframeDoc.write(`
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="UTF-8">
                                <title>Erreur</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                        background: #fee;
                                        color: #c33;
                                    }
                                    .error {
                                        background: #fcc;
                                        border: 2px solid #c33;
                                        padding: 15px;
                                        border-radius: 5px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="error">
                                    <h3>Erreur ${langName} :</h3>
                                    <pre>${data.error}</pre>
                                </div>
                            </body>
                            </html>
                        `);
                        iframeDoc.close();
                    } else {
                        // Utiliser la même logique que runCode() pour afficher le résultat
                        let output = data.output || '';
                        
                        // Nettoyage (même logique que dans runCode)
                        output = output.trim();
                        if (output) {
                            output = output.replace(/^[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                            output = output.replace(/[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+$/g, '');
                            
                            const lines = output.split('\n');
                            const cleanedLines = [];
                            for (let line of lines) {
                                const cleaned = line.replace(/^[\s\t\r\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                                cleanedLines.push(cleaned);
                            }
                            output = cleanedLines.join('\n');
                            output = output.trim();
                            output = output.replace(/^[\s\u00A0\u2000-\u200B\u2028\u2029\u202F\u205F\u3000]+/g, '');
                            
                            const match = output.match(/\S/);
                            if (match && match.index !== undefined) {
                                output = output.substring(match.index);
                            }
                        }
                        
                        const hasOutput = output.length > 0;
                        const hasFullHTML = /^\s*<!DOCTYPE\s+html\s*>/i.test(output) || /^\s*<html[\s>]/i.test(output);
                        
                        if (hasFullHTML) {
                            let finalOutput = output;
                            const headStyle = `
                                <style>
                                    * { margin: 0; padding: 0; box-sizing: border-box; }
                                    html, body { margin: 0 !important; padding: 0 !important; width: 100%; height: 100%; }
                                    body { padding: 0 !important; margin: 0 !important; }
                                </style>
                            `;
                            
                            if (/<head[^>]*>/i.test(finalOutput)) {
                                finalOutput = finalOutput.replace(/(<head[^>]*>)/i, '$1' + headStyle);
                            } else if (/<html[^>]*>/i.test(finalOutput)) {
                                finalOutput = finalOutput.replace(/(<html[^>]*>)/i, '$1<head>' + headStyle + '</head>');
                            }
                            
                            finalOutput = finalOutput.replace(/(<body[^>]*)/i, '$1 style="margin: 0 !important; padding: 0 !important;"');
                            
                            iframeDoc.open();
                            iframeDoc.write(finalOutput);
                            iframeDoc.close();
                            
                            // Réintercepter après le chargement
                            setTimeout(() => {
                                interceptFormsAndLinks(iframeDoc);
                            }, 100);
                        } else {
                            iframeDoc.open();
                            iframeDoc.write(`
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="UTF-8">
                                    <title>Résultat</title>
                                    <style>
                                        * { margin: 0; padding: 0; box-sizing: border-box; }
                                        html, body { margin: 0 !important; padding: 0 !important; width: 100%; height: 100%; }
                                        body {
                                            font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
                                            padding: 0 !important;
                                            margin: 0 !important;
                                            background: ${darkMode ? '#1e293b' : 'white'};
                                            color: ${darkMode ? '#e2e8f0' : '#333'};
                                            white-space: pre-wrap;
                                            word-wrap: break-word;
                                            line-height: 1.5;
                                        }
                                    </style>
                                </head>
                                <body style="margin: 0 !important; padding: 0 !important;">${hasOutput ? output : '<p class="no-output">' + (language === 'java' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez System.out.println() pour afficher des résultats.' : language === 'python' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez printf() pour afficher des résultats.' : 'Aucune sortie.') + '</p>'}
                                </body>
                                </html>
                            `);
                            iframeDoc.close();
                            
                            // Réintercepter après le chargement
                            setTimeout(() => {
                                interceptFormsAndLinks(iframeDoc);
                            }, 100);
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>Erreur</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    padding: 20px;
                                    background: #fee;
                                    color: #c33;
                                }
                            </style>
                        </head>
                        <body>
                            <h3>Erreur lors de l'exécution :</h3>
                            <pre>${error.message}</pre>
                        </body>
                        </html>
                    `);
                    iframeDoc.close();
                });
            }
            
            // Auto-run code on load
            setTimeout(() => {
                runCode();
            }, 200);
        }, 100);
    }
    
    // S'assurer que le DOM est prêt avant d'initialiser
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Les scripts seront chargés après le DOM
        });
    } else {
        // DOM déjà chargé, les scripts se chargeront automatiquement
    }
</script>
@endsection
