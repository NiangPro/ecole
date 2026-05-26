@extends('layouts.app')

@php
  $langMap = [
    'html5'        => ['icon' => 'fab fa-html5',       'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'css3'         => ['icon' => 'fab fa-css3-alt',    'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'javascript'   => ['icon' => 'fab fa-js',           'bg' => '#fefce8', 'fg' => '#ca8a04'],
    'php'          => ['icon' => 'fab fa-php',          'bg' => '#ecfeff', 'fg' => '#0891b2'],
    'bootstrap'    => ['icon' => 'fab fa-bootstrap',   'bg' => '#ecfeff', 'fg' => '#0891b2'],
    'git'          => ['icon' => 'fab fa-git-alt',     'bg' => '#fff1f2', 'fg' => '#dc2626'],
    'wordpress'    => ['icon' => 'fab fa-wordpress',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'ia'           => ['icon' => 'fas fa-robot',        'bg' => '#f0fdf4', 'fg' => '#16a34a'],
    'python'       => ['icon' => 'fab fa-python',       'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'java'         => ['icon' => 'fab fa-java',         'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'sql'          => ['icon' => 'fas fa-database',    'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'c'            => ['icon' => 'fab fa-c',            'bg' => '#f8fafc', 'fg' => '#475569'],
    'cpp'          => ['icon' => 'fab fa-cuttlefish',  'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'csharp'       => ['icon' => 'fab fa-microsoft',   'bg' => '#f0fdf4', 'fg' => '#16a34a'],
    'dart'         => ['icon' => 'fas fa-feather-alt', 'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'go'           => ['icon' => 'fab fa-golang',      'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'swift'        => ['icon' => 'fab fa-swift',       'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'perl'         => ['icon' => 'fas fa-code',        'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'typescript'   => ['icon' => 'fab fa-js-square',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'rust'         => ['icon' => 'fab fa-rust',        'bg' => '#f8fafc', 'fg' => '#475569'],
    'ruby'         => ['icon' => 'fas fa-gem',         'bg' => '#fff1f2', 'fg' => '#dc2626'],
    'cybersecurite'=> ['icon' => 'fas fa-shield-alt',  'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'data-science' => ['icon' => 'fas fa-chart-line',  'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'big-data'     => ['icon' => 'fas fa-database',    'bg' => '#ecfeff', 'fg' => '#0891b2'],
  ];
  $info = $langMap[$language] ?? ['icon' => 'fas fa-code', 'bg' => '#ecfeff', 'fg' => '#0891b2'];

  $easyKey   = trans('app.exercices.difficulty.easy');
  $mediumKey = trans('app.exercices.difficulty.medium');
  $diffLower = strtolower($exercise['difficulty'] ?? '');
  if (in_array($diffLower, ['facile','easy']))                $diffTier = 'easy';
  elseif (in_array($diffLower, ['moyen','medium','intermédiaire'])) $diffTier = 'medium';
  else                                                         $diffTier = 'hard';

  $langLabel = trans('app.formations.languages.' . $language, [], null, ucfirst($language));
@endphp

@section('title', trans('app.exercices.exercise') . ' ' . $id . ' — ' . $exercise['title'] . ' | NiangProgrammeur')

@section('styles')
{{-- CodeMirror --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/eclipse.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css">
{{-- Hide textarea before CodeMirror init --}}
<style>
  #codeEditor {
    position: absolute !important; left: -9999px !important;
    opacity: 0 !important; width: 1px !important; height: 1px !important;
    display: none !important; visibility: hidden !important;
  }
  .ed-topbar {
    padding-block-start: calc(var(--spacing-navbar, 76px) + .75rem) !important;
  }
</style>
@endsection

@section('content')
<div class="ed-page">

  {{-- ── TOPBAR ─────────────────────────────────────────────── --}}
  <div class="ed-topbar">
    <nav class="ed-breadcrumb" aria-label="Fil d'Ariane">
      <a href="{{ route('exercices') }}"><i class="fas fa-dumbbell"></i> {{ trans('app.exercices.all_exercices') }}</a>
      <span class="ed-breadcrumb__sep">›</span>
      <a href="{{ route('exercices.language', $language) }}">{{ $langLabel }}</a>
      <span class="ed-breadcrumb__sep">›</span>
      <span class="ed-breadcrumb__current">{{ trans('app.exercices.exercise') }} {{ $id }}</span>
    </nav>

    <div class="ed-nav">
      @if($id > 1)
      <a href="{{ route('exercices.detail', [$language, $id - 1]) }}" class="ed-nav-btn">
        <i class="fas fa-chevron-left"></i>{{ trans('app.exercices.detail.previous') }}
      </a>
      @endif
      @if(isset($totalExercises) && $id < $totalExercises)
      <a href="{{ route('exercices.detail', [$language, $id + 1]) }}" class="ed-nav-btn">
        {{ trans('app.exercices.detail.next') }}<i class="fas fa-chevron-right"></i>
      </a>
      @endif
    </div>
  </div>

  {{-- ── INFO ──────────────────────────────────────────────── --}}
  <div class="ed-info">
    <div class="ed-info__top">
      <h1 class="ed-ex-title">{{ $exercise['title'] }}</h1>
      <div class="ed-badges">
        <span class="ed-badge ed-badge--{{ $diffTier }}">
          <i class="fas fa-signal"></i>
          {{ $exercise['difficulty'] }}
        </span>
        <span class="ed-badge ed-badge--points">
          <i class="fas fa-star"></i>
          {{ $exercise['points'] }} {{ trans('app.exercices.points') }}
        </span>
      </div>
    </div>
    <p class="ed-instruction">{{ $exercise['instruction'] }}</p>
    <p class="ed-description">{{ $exercise['description'] }}</p>
  </div>

  {{-- ── MESSAGES ──────────────────────────────────────────── --}}
  <div id="successMessage" class="ed-msg ed-msg--success">
    <div class="ed-msg__inner">
      <i class="fas fa-check-circle ed-msg__icon"></i>
      <div>
        <div class="ed-msg__title">{{ trans('app.exercices.detail.success_title') }}</div>
        <div class="ed-msg__text">{{ str_replace(':points', $exercise['points'], trans('app.exercices.detail.success_message')) }}</div>
      </div>
    </div>
  </div>

  <div id="errorMessage" class="ed-msg ed-msg--error">
    <div class="ed-msg__inner">
      <i class="fas fa-times-circle ed-msg__icon"></i>
      <div>
        <div class="ed-msg__title">{{ trans('app.exercices.detail.error_title') }}</div>
        <div class="ed-msg__text" id="errorText">{{ trans('app.exercices.detail.error_message') }}</div>
      </div>
    </div>
  </div>

  {{-- ── SPLIT ─────────────────────────────────────────────── --}}
  <div class="ed-split">

    {{-- LEFT — Code Editor --}}
    <div class="ed-panel">
      <div class="ed-panel__header">
        <div class="ed-panel__title">
          <i class="fas fa-code"></i>
          {{ trans('app.exercices.detail.your_code') }}
          <span class="ed-panel__lang-chip" style="background:{{ $info['bg'] }}22;color:{{ $info['fg'] }};border-color:{{ $info['fg'] }}44">
            <i class="{{ $info['icon'] }}"></i>
            {{ $langLabel }}
          </span>
        </div>
        <button onclick="resetCode()" class="ed-panel__btn">
          <i class="fas fa-undo"></i>{{ trans('app.exercices.detail.reset') }}
        </button>
      </div>

      <div class="ed-editor-wrap">
        <textarea id="codeEditor" spellcheck="false">{!! htmlspecialchars($exercise['startCode'], ENT_QUOTES, 'UTF-8') !!}</textarea>
      </div>

      <div class="ed-panel__footer">
        <div class="ed-hint">
          <i class="fas fa-lightbulb"></i>
          <div>
            <div class="ed-hint__label">{{ trans('app.exercices.detail.hint') }}</div>
            <div class="ed-hint__text">{{ $exercise['hint'] }}</div>
          </div>
        </div>

        <div class="ed-actions">
          <button onclick="runCode()" class="ed-btn ed-btn--run">
            <i class="fas fa-play"></i>{{ trans('app.exercices.detail.run_code') }}
          </button>
          <button onclick="submitCode()" class="ed-btn ed-btn--submit">
            <i class="fas fa-check"></i>{{ trans('app.exercices.detail.submit') }}
          </button>
        </div>
        <div class="ed-shortcut-hint">Ctrl+Enter → Exécuter</div>
      </div>
    </div>

    {{-- RIGHT — Preview --}}
    <div class="ed-panel">
      <div class="ed-panel__header">
        <div class="ed-panel__title">
          <i class="fas fa-eye"></i>
          {{ trans('app.exercices.detail.result') }}
        </div>
        <button onclick="runCode()" class="ed-panel__btn">
          <i class="fas fa-sync-alt"></i>Actualiser
        </button>
      </div>

      <iframe id="resultFrame" class="ed-iframe"></iframe>

      <div class="ed-panel__footer">
        <div class="ed-tip">
          <i class="fas fa-info-circle"></i>
          <span class="ed-tip__text">{{ trans('app.exercices.detail.result_help') }}</span>
        </div>
      </div>
    </div>

  </div>
</div>
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
                                    loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js', function() {
                                        loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/xml-hint.min.js', function() {
                                            loadScript('https://cdn.jsdelivr.net/npm/emmet@2.3.6/dist/emmet.umd.js', function() {
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
        } else if (language === 'csharp' || language === 'c#') {
            codeMirrorMode = 'text/x-csharp';
        } else if (language === 'dart') {
            codeMirrorMode = 'text/x-csrc'; // Utiliser clike pour Dart (syntaxe similaire)
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
            
            // Configuration de base pour CodeMirror
            const editorConfig = {
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
                matchTags: { bothTags: true },
                extraKeys: {
                    'Ctrl-Space': function(cm) {
                        // Autocomplétion pour HTML/XML
                        if (codeMirrorMode === 'htmlmixed' || codeMirrorMode === 'xml') {
                            CodeMirror.commands.autocomplete(cm);
                        } else {
                            // Autocomplétion par défaut pour les autres langages
                            CodeMirror.commands.autocomplete(cm);
                        }
                    },
                    'Ctrl-/': 'toggleComment',
                    'Ctrl-Enter': function() {
                        runCode();
                    },
                    'Cmd-Enter': function() {
                        runCode();
                    },
                    'Tab': function(cm) {
                        // Vérifier si on peut expand Emmet pour HTML
                        if (codeMirrorMode === 'htmlmixed' || codeMirrorMode === 'xml' || language === 'html5' || language === 'html') {
                            // Vérifier si emmet est disponible (différentes façons selon le chargement)
                            const emmetLib = window.emmet || (typeof emmet !== 'undefined' ? emmet : null);
                            
                            if (emmetLib && emmetLib.expandAbbreviation) {
                                const cursor = cm.getCursor();
                                const line = cm.getLine(cursor.line);
                                const textBeforeCursor = line.substring(0, cursor.ch);
                                
                                // Chercher une abréviation Emmet valide
                                const emmetPattern = /[a-zA-Z][a-zA-Z0-9]*(\.[a-zA-Z][a-zA-Z0-9-]*)*(#[a-zA-Z][a-zA-Z0-9-]*)*(\[[^\]]*\])*(\{[^\}]*\})*(\*[0-9]+)?(\+[a-zA-Z][a-zA-Z0-9]*(\.[a-zA-Z][a-zA-Z0-9-]*)*(#[a-zA-Z][a-zA-Z0-9-]*)*)*$/;
                                const match = textBeforeCursor.match(emmetPattern);
                                
                                if (match && match[0].length > 0) {
                                    try {
                                        const abbreviation = match[0];
                                        const expanded = emmetLib.expandAbbreviation(abbreviation, {
                                            syntax: 'html',
                                            options: {
                                                'output.indent': '  ',
                                                'output.baseIndent': '',
                                                'output.newline': '\n'
                                            }
                                        });
                                        
                                        if (expanded) {
                                            const startPos = { line: cursor.line, ch: cursor.ch - abbreviation.length };
                                            const endPos = cursor;
                                            cm.replaceRange(expanded, startPos, endPos);
                                            return;
                                        }
                                    } catch (e) {
                                        // Si l'expansion échoue, continuer avec le comportement par défaut
                                        console.log('Emmet expansion failed:', e);
                                    }
                                }
                            }
                        }
                        
                        // Comportement par défaut
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
            };
            
            // Ajouter l'autocomplétion pour HTML/XML
            if (codeMirrorMode === 'htmlmixed' || codeMirrorMode === 'xml' || language === 'html5' || language === 'html') {
                editorConfig.hintOptions = {
                    schemaInfo: CodeMirror.hint.xml,
                    closeOnUnfocus: false,
                    completeSingle: false
                };
            }
            
            // Initialiser CodeMirror
            const codeEditor = CodeMirror.fromTextArea(textarea, editorConfig);
            
            // Activer Emmet pour HTML
            if (codeMirrorMode === 'htmlmixed' || codeMirrorMode === 'xml' || language === 'html5' || language === 'html') {
                // Ajouter un raccourci Ctrl+E pour expand Emmet manuellement
                codeEditor.addKeyMap({
                    'Ctrl-E': function(cm) {
                        const emmetLib = window.emmet || (typeof emmet !== 'undefined' ? emmet : null);
                        if (!emmetLib || !emmetLib.expandAbbreviation) return;
                        
                        const cursor = cm.getCursor();
                        const line = cm.getLine(cursor.line);
                        const textBeforeCursor = line.substring(0, cursor.ch);
                        
                        // Chercher une abréviation Emmet
                        const emmetPattern = /[a-zA-Z][a-zA-Z0-9]*(\.[a-zA-Z][a-zA-Z0-9-]*)*(#[a-zA-Z][a-zA-Z0-9-]*)*(\[[^\]]*\])*(\{[^\}]*\})*(\*[0-9]+)?(\+[a-zA-Z][a-zA-Z0-9]*(\.[a-zA-Z][a-zA-Z0-9-]*)*(#[a-zA-Z][a-zA-Z0-9-]*)*)*$/;
                        const match = textBeforeCursor.match(emmetPattern);
                        
                        if (match && match[0].length > 0) {
                            try {
                                const abbreviation = match[0];
                                const expanded = emmetLib.expandAbbreviation(abbreviation, {
                                    syntax: 'html',
                                    options: {
                                        'output.indent': '  ',
                                        'output.baseIndent': '',
                                        'output.newline': '\n'
                                    }
                                });
                                
                                if (expanded) {
                                    const startPos = { line: cursor.line, ch: cursor.ch - abbreviation.length };
                                    const endPos = cursor;
                                    cm.replaceRange(expanded, startPos, endPos);
                                }
                            } catch (e) {
                                console.error('Erreur Emmet:', e);
                            }
                        }
                    }
                });
            }
            
            // Activer l'autocomplétion automatique pour HTML (après avoir tapé <)
            if (codeMirrorMode === 'htmlmixed' || codeMirrorMode === 'xml' || language === 'html5' || language === 'html') {
                codeEditor.on('inputRead', function(cm, change) {
                    if (change.text && change.text.length > 0 && (change.text[0] === '<' || change.text[0].includes('<'))) {
                        // Délai pour permettre à l'utilisateur de continuer à taper
                        setTimeout(function() {
                            if (cm.state.completionActive) return;
                            CodeMirror.commands.autocomplete(cm, null, {completeSingle: false});
                        }, 300);
                    }
                });
            }
            
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
        
                // Si c'est du PHP, Python, Java, C, C++, C# ou Dart, exécuter côté serveur
                if (language === 'php' || language === 'python' || language === 'java' || language === 'c' || language === 'cpp' || language === 'c++' || language === 'csharp' || language === 'c#' || language === 'dart') {
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
                            const langName = language === 'python' ? 'Python' : (language === 'java' ? 'Java' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'C' : (language === 'csharp' || language === 'c#') ? 'C#' : (language === 'dart') ? 'Dart' : 'PHP');
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
                                    <body style="margin: 0 !important; padding: 0 !important; padding-top: 0 !important; margin-top: 0 !important;">${hasOutput ? output : '<p class="no-output">' + (language === 'java' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez System.out.println() pour afficher des résultats.' : language === 'python' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez printf() pour afficher des résultats.' : (language === 'csharp' || language === 'c#') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez Console.WriteLine() pour afficher des résultats.' : (language === 'dart') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : translations.noOutput) + '</p>'}
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
                    @if(isset($totalExercises) && $id < $totalExercises)
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
                                <body style="margin: 0 !important; padding: 0 !important;">${hasOutput ? output : '<p class="no-output">' + (language === 'java' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez System.out.println() pour afficher des résultats.' : language === 'python' ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : (language === 'c' || language === 'cpp' || language === 'c++') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez printf() pour afficher des résultats.' : (language === 'csharp' || language === 'c#') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez Console.WriteLine() pour afficher des résultats.' : (language === 'dart') ? 'Aucune sortie. Le code s\'est exécuté sans erreur mais n\'a rien affiché. Utilisez print() pour afficher des résultats.' : 'Aucune sortie.') + '</p>'}
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
