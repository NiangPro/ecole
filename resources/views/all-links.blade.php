@extends('layouts.app')

@section('title', 'Tous les Liens - Formations, Exercices et Quiz | NiangProgrammeur')
@section('meta_description', 'Liste complète de tous les liens des formations, exercices et quiz disponibles sur NiangProgrammeur. Copiez tous les liens d\'un coup pour faciliter votre navigation.')
@section('meta_keywords', 'liens formations, liens exercices, liens quiz, HTML5, CSS3, JavaScript, PHP, Python, Bootstrap, Git, WordPress, IA')

@push('meta')
    <link rel="canonical" href="{{ route('all.links') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('all.links') }}">
    <meta property="og:title" content="Tous les Liens - Formations, Exercices et Quiz | NiangProgrammeur">
    <meta property="og:description" content="Liste complète de tous les liens des formations, exercices et quiz disponibles sur NiangProgrammeur.">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
@endpush

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #ffffff !important;
    }
    
    .links-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 60px 20px 40px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .links-hero h1 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
    }
    
    .links-hero p {
        font-size: 1.1rem;
        color: rgba(0, 0, 0, 0.7);
        max-width: 800px;
        margin: 0 auto;
    }
    
    body.dark-mode .links-hero p {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .links-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }
    
    .section {
        margin-bottom: 50px;
    }
    
    .section-title {
        font-size: 1.8em;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #06b6d4;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .links-group {
        background: #ffffff;
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .links-group:hover {
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.15);
        transform: translateY(-2px);
    }
    
    .links-group h3 {
        color: #333;
        margin-bottom: 20px;
        font-size: 1.3em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    body.dark-mode .links-group h3 {
        color: #fff;
    }
    
    .link-item {
        padding: 15px;
        background: rgba(6, 182, 212, 0.05);
        margin-bottom: 12px;
        border-radius: 10px;
        border-left: 4px solid #06b6d4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        gap: 15px;
    }
    
    .link-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        background: rgba(6, 182, 212, 0.1);
    }
    
    .link-url {
        font-family: 'Courier New', monospace;
        color: #06b6d4;
        font-size: 0.95em;
        flex: 1;
        word-break: break-all;
        font-weight: 500;
    }
    
    body.dark-mode .link-url {
        color: #06b6d4;
    }
    
    .copy-btn {
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9em;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .copy-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    }
    
    .copy-btn.copied {
        background: #28a745;
    }
    
    .copy-all-section {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 30px;
        margin-top: 40px;
    }
    
    .copy-all-section h2 {
        font-size: 1.5em;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .all-links-textarea {
        width: 100%;
        min-height: 250px;
        padding: 20px;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        font-family: 'Courier New', monospace;
        font-size: 0.95em;
        margin-bottom: 20px;
        resize: vertical;
        background: #ffffff;
        color: #333;
        line-height: 1.8;
    }
    
    body.dark-mode .all-links-textarea {
        background: #1a1a1a;
        color: #fff;
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    .copy-all-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 1.1em;
        font-weight: 700;
        transition: all 0.3s ease;
        display: block;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .copy-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(40, 167, 69, 0.4);
    }
    
    .copy-all-btn.copied {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    @media (max-width: 768px) {
        .links-hero h1 {
            font-size: 1.8rem;
        }
        
        .link-item {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        
        .copy-btn {
            width: 100%;
            justify-content: center;
        }
        
        .section-title {
            font-size: 1.5em;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="links-hero">
    <div class="links-container">
        <h1><i class="fas fa-link"></i> Tous les Liens du Site</h1>
        <p>Liste complète de tous les liens des formations, exercices et quiz disponibles sur NiangProgrammeur. Copiez tous les liens d'un coup ou individuellement pour faciliter votre navigation.</p>
    </div>
</div>

<!-- Links Container -->
<div class="links-container">
    <!-- Formations -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-graduation-cap"></i>
            Formations
        </h2>
        
        <div class="links-group">
            <h3><i class="fas fa-home"></i> Page Principale</h3>
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/formations</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/formations', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/formations</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/formations', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
        </div>

        <div class="links-group">
            <h3><i class="fas fa-code"></i> Formations par Langage</h3>
            @foreach($languages as $lang)
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/formations/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/formations/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/formations/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/formations/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Exercices -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-laptop-code"></i>
            Exercices
        </h2>
        
        <div class="links-group">
            <h3><i class="fas fa-home"></i> Page Principale</h3>
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/exercices</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/exercices', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/exercices</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/exercices', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
        </div>

        <div class="links-group">
            <h3><i class="fas fa-code"></i> Exercices par Langage</h3>
            @foreach($languages as $lang)
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/exercices/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/exercices/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/exercices/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/exercices/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quiz -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-question-circle"></i>
            Quiz
        </h2>
        
        <div class="links-group">
            <h3><i class="fas fa-home"></i> Page Principale</h3>
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/quiz</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/quiz', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/quiz</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/quiz', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
        </div>

        <div class="links-group">
            <h3><i class="fas fa-code"></i> Quiz par Langage</h3>
            @foreach($languages as $lang)
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/quiz/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/quiz/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/quiz/{{ $lang['slug'] }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/quiz/{{ $lang['slug'] }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Articles -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-newspaper"></i>
            Articles (49 derniers)
        </h2>
        
        <div class="links-group">
            <h3><i class="fas fa-file-alt"></i> Articles Récents</h3>
            @foreach($recentArticles as $article)
            <div class="link-item">
                <span class="link-url">{{ $baseUrl }}/emplois/article/{{ $article->slug }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $baseUrl }}/emplois/article/{{ $article->slug }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            <div class="link-item">
                <span class="link-url">{{ $localUrl }}/emplois/article/{{ $article->slug }}</span>
                <button class="copy-btn" onclick="copyToClipboard('{{ $localUrl }}/emplois/article/{{ $article->slug }}', this)">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Copier Tout -->
    <div class="copy-all-section">
        <h2>
            <i class="fas fa-clipboard-list"></i>
            Copier Tous les Liens
        </h2>
        <textarea id="allLinksText" class="all-links-textarea" readonly>{{ $allLinksText }}</textarea>
        <button class="copy-all-btn" onclick="copyAllLinks(this)">
            <i class="fas fa-copy"></i>
            Copier Tous les Liens
        </button>
    </div>
</div>

<script>
    function copyToClipboard(text, button) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Copié !';
                button.classList.add('copied');
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copied');
                }, 2000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie:', err);
                fallbackCopy(text, button);
            });
        } else {
            fallbackCopy(text, button);
        }
    }

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.left = '-999999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copié !';
            button.classList.add('copied');
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('copied');
            }, 2000);
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
            alert('Impossible de copier. Veuillez sélectionner le texte manuellement.');
        }
        document.body.removeChild(textarea);
    }

    function copyAllLinks(button) {
        const textarea = document.getElementById('allLinksText');
        textarea.select();
        textarea.setSelectionRange(0, 99999); // Pour mobile
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textarea.value).then(function() {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Tous les liens copiés !';
                button.classList.add('copied');
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copied');
                }, 3000);
            }).catch(function(err) {
                fallbackCopyAll(textarea, button);
            });
        } else {
            fallbackCopyAll(textarea, button);
        }
    }

    function fallbackCopyAll(textarea, button) {
        try {
            document.execCommand('copy');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Tous les liens copiés !';
            button.classList.add('copied');
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('copied');
            }, 3000);
        } catch (err) {
            alert('Impossible de copier. Veuillez sélectionner le texte dans la zone de texte et copier manuellement (Ctrl+C ou Cmd+C).');
        }
    }
</script>
@endsection
