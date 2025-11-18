@extends('layouts.app')

@section('title', 'Exercice ' . $id . ' - ' . $exercise['title'] . ' | NiangProgrammeur')

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
    
    /* Hide textarea but keep it in DOM for CodeMirror */
    #codeEditor {
        position: absolute;
        left: -9999px;
        opacity: 0;
        width: 1px;
        height: 1px;
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
                <div class="code-editor-wrapper">
                    <textarea id="codeEditor" spellcheck="false">{{ $exercise['startCode'] }}</textarea>
                </div>
                
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
@endsection

@section('scripts')
<!-- CodeMirror JS - Chargement séquentiel -->
<script>
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
        
        const startCode = @json($exercise['startCode']);
        const language = @json($language);
        const exerciseId = @json($id);
        
        // Déterminer le mode CodeMirror selon la langue
        let codeMirrorMode = 'htmlmixed';
        if (language === 'css3' || language === 'css') {
            codeMirrorMode = 'css';
        } else if (language === 'javascript' || language === 'js') {
            codeMirrorMode = 'javascript';
        } else if (language === 'php') {
            codeMirrorMode = 'application/x-httpd-php';
        } else if (language === 'python') {
            codeMirrorMode = 'python';
        } else if (language === 'html5' || language === 'html') {
            codeMirrorMode = 'htmlmixed';
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
            
            // Initialiser CodeMirror
            const codeEditor = CodeMirror.fromTextArea(textarea, {
                mode: codeMirrorMode,
                theme: codeMirrorTheme,
                lineNumbers: true,
                lineWrapping: true,
                indentUnit: 2,
                indentWithTabs: false,
                tabSize: 2,
                autofocus: false,
                matchBrackets: true,
                autoCloseBrackets: true,
                autoCloseTags: true,
                foldGutter: true,
                gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
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
            
            // Stocker l'instance globalement
            window.codeEditorInstance = codeEditor;
            
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
            
            // Vérifier que la fonction est bien définie
            console.log('Initialisation de runCode pour language:', language);
            
            window.runCode = function() {
                console.log('runCode appelé, language:', language);
                const code = codeEditor.getValue();
                console.log('Code récupéré:', code.substring(0, 100));
                const iframe = document.getElementById('resultFrame');
                if (!iframe) {
                    console.error('iframe non trouvé');
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
                            <p>Exécution du code en cours...</p>
                        </div>
                    </body>
                    </html>
                `);
                iframeDoc.close();
                
                // Si c'est du PHP ou Python, exécuter côté serveur
                if (language === 'php' || language === 'python') {
                    console.log('Envoi de la requête à /exercices/' + language + '/run');
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
                        console.log('Réponse reçue, status:', response.status);
                        // Vérifier si la réponse est du JSON
                        const contentType = response.headers.get('content-type');
                        console.log('Content-Type:', contentType);
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Réponse non-JSON:', text.substring(0, 500));
                                throw new Error('Réponse non-JSON reçue: ' + text.substring(0, 200));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Données reçues:', data);
                        iframeDoc.open();
                        
                        if (data.error) {
                            const langName = language === 'python' ? 'Python' : 'PHP';
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
                            const output = data.output || '';
                            const hasOutput = output.trim().length > 0;
                            const darkMode = window.isDarkMode || document.body.classList.contains('dark-mode');
                            
                            iframeDoc.write(`
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="UTF-8">
                                    <title>Résultat</title>
                                    <style>
                                        body {
                                            font-family: 'Courier New', 'Consolas', 'Monaco', monospace;
                                            padding: 20px;
                                            background: ${darkMode ? '#1e293b' : 'white'};
                                            color: ${darkMode ? '#e2e8f0' : '#333'};
                                            white-space: pre-wrap;
                                            word-wrap: break-word;
                                        }
                                        .no-output {
                                            color: #999;
                                            font-style: italic;
                                            font-family: Arial, sans-serif;
                                        }
                                    </style>
                                </head>
                                <body>
                                    ${hasOutput ? output : '<p class="no-output">Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.</p>'}
                                </body>
                                </html>
                            `);
                        }
                        
                        iframeDoc.close();
                        hideMessages();
                    })
                    .catch(error => {
                        console.error('Erreur complète:', error);
                        console.error('Stack:', error.stack);
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
                    iframeDoc.open();
                    iframeDoc.write(code);
                    iframeDoc.close();
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
