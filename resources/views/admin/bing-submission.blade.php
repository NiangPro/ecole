@extends('admin.layout')

@section('title', 'Soumission d\'URLs à Bing - Admin')

@section('content')
<div class="bing-admin">
    <!-- Header Section -->
    <div class="bing-header">
        <div class="bing-header-content">
            <div class="bing-header-text">
                <h1 class="bing-title">
                    <span class="bing-icon-wrapper">
                        <i class="fab fa-microsoft bing-icon"></i>
                    </span>
                    Soumission d'URLs à Bing
                </h1>
                <p class="bing-subtitle">
                    Soumettez automatiquement vos URLs à Bing pour une indexation rapide
                </p>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Erreur !</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(!$isConfigured)
    <div class="alert alert-warning">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <strong>Clé API Bing non configurée</strong>
            <p>Veuillez configurer votre clé API Bing dans les <a href="{{ route('admin.settings') }}" class="alert-link">paramètres du site</a>.</p>
            <p class="mt-2">
                <i class="fas fa-info-circle"></i>
                Obtenez votre clé API depuis <a href="https://www.bing.com/webmasters" target="_blank" class="alert-link">Bing Webmaster Tools</a>
            </p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="bing-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-link"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalUrls }}</div>
                <div class="stat-label">Total URLs</div>
            </div>
        </div>
        <div class="stat-card stat-formations">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $formationsExercicesQuiz }}</div>
                <div class="stat-label">Formations/Exercices/Quiz</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <i class="fas fa-book"></i>
                        <span>{{ $formationsCount ?? 16 }} Formations</span>
                    </div>
                    <div class="stat-detail-item">
                        <i class="fas fa-code"></i>
                        <span>{{ $exercicesCount ?? 16 }} Exercices</span>
                    </div>
                    <div class="stat-detail-item">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ $quizCount ?? 16 }} Quiz</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="stat-card stat-pages">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pagesStatiques }}</div>
                <div class="stat-label">Pages statiques</div>
            </div>
        </div>
        <div class="stat-card stat-articles">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $articlesIncluded }}</div>
                <div class="stat-label">Articles inclus</div>
                @if($articlesCount > $articlesIncluded)
                <div class="stat-hint">sur {{ $articlesCount }} disponibles</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="fas fa-lightbulb"></i>
            <span>Comment ça fonctionne ?</span>
        </div>
        <div class="info-card-body">
            <p>
                Cette fonctionnalité soumet automatiquement toutes les URLs importantes de votre site à Bing pour une indexation rapide.
            </p>
            <div class="info-steps">
                <div class="info-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <strong>Formations, Exercices et Quiz</strong>
                        <p>{{ $formationsExercicesQuiz }} URLs soumises en premier</p>
                    </div>
                </div>
                <div class="info-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <strong>Pages statiques</strong>
                        <p>{{ $pagesStatiques }} pages importantes à conserver</p>
                    </div>
                </div>
                <div class="info-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <strong>Articles récents</strong>
                        <p>{{ $articlesIncluded }} articles pour compléter jusqu'à 100 URLs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- URLs List -->
    <div class="urls-section">
        <div class="urls-header">
            <div class="urls-header-left">
                <i class="fas fa-list"></i>
                <span>URLs à soumettre ({{ count($urls) }})</span>
            </div>
            <div class="urls-count-badge">
                {{ count($urls) }} URLs
            </div>
        </div>
        <div class="urls-list-container">
            <div class="urls-list">
                @foreach($urls as $index => $url)
                <div class="url-item">
                    <div class="url-number">{{ $index + 1 }}</div>
                    <div class="url-content">
                        <i class="fas fa-link url-icon"></i>
                        <span class="url-text">{{ $url }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Actions Section -->
    <div class="actions-section">
        <div class="actions-header">
            <i class="fas fa-rocket"></i>
            <span>Actions</span>
        </div>
        <div class="actions-content">
            <form action="{{ route('admin.bing.submit') }}" method="POST" class="submit-form" onsubmit="return confirm('Êtes-vous sûr de vouloir soumettre {{ count($urls) }} URLs à Bing ?');">
                @csrf
                <button type="submit" class="btn btn-primary btn-submit" {{ !$isConfigured ? 'disabled' : '' }}>
                    <i class="fab fa-microsoft"></i>
                    <span>Soumettre les URLs à Bing</span>
                </button>
            </form>

            <div class="command-section">
                <div class="command-header">
                    <i class="fas fa-terminal"></i>
                    <span>Commande Artisan</span>
                </div>
                <div class="command-box">
                    <code class="command-text">php artisan bing:submit-urls</code>
                    <button class="command-copy" onclick="copyCommand()" title="Copier">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="command-hint">
                    Vous pouvez aussi utiliser cette commande depuis votre terminal
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function copyCommand() {
    const command = 'php artisan bing:submit-urls';
    navigator.clipboard.writeText(command).then(() => {
        const btn = event.target.closest('.command-copy');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = 'rgba(16, 185, 129, 0.2)';
        btn.style.borderColor = 'rgba(16, 185, 129, 0.4)';
        btn.style.color = '#10b981';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 2000);
    });
}
</script>

<style>
.bing-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.bing-header {
    margin-bottom: 2rem;
}

.bing-header-content {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    border: 2px solid rgba(59, 130, 246, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .bing-header-content {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(37, 99, 235, 0.08) 100%);
    border-color: rgba(59, 130, 246, 0.4);
}

.bing-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.bing-header-text {
    position: relative;
    z-index: 1;
}

.bing-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #3b82f6 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.bing-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(59, 130, 246, 0.3);
}

.bing-icon {
    font-size: 1.8rem;
    color: #3b82f6;
}

.bing-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .bing-subtitle {
    color: #64748b;
}

/* Alerts */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-warning {
    background: rgba(251, 191, 36, 0.15);
    border: 2px solid rgba(251, 191, 36, 0.3);
    color: #fbbf24;
}

.alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.alert-link {
    color: inherit;
    text-decoration: underline;
    font-weight: 600;
    transition: opacity 0.2s;
}

.alert-link:hover {
    opacity: 0.8;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
    flex-shrink: 0;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Stats */
.bing-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.stat-total::before {
    background: linear-gradient(180deg, #3b82f6, #2563eb);
}

.stat-formations::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-pages::before {
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
}

.stat-articles::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-icon-wrapper {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
}

.stat-formations .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-pages .stat-icon {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
    color: #fbbf24;
}

.stat-articles .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value {
    color: #1e293b;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.25rem;
}

body.light-mode .stat-label {
    color: #64748b;
}

.stat-hint {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
}

body.light-mode .stat-hint {
    color: #94a3b8;
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(6, 182, 212, 0.2);
}

.stat-detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
}

body.light-mode .stat-detail-item {
    color: #475569;
}

.stat-detail-item i {
    color: #06b6d4;
    font-size: 0.9rem;
    width: 16px;
    text-align: center;
}

/* Info Card */
.info-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
}

body.light-mode .info-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
}

body.light-mode .info-card-header {
    color: #1e293b;
}

.info-card-header i {
    color: #06b6d4;
    font-size: 1.5rem;
}

.info-card-body {
    color: rgba(255, 255, 255, 0.9);
}

body.light-mode .info-card-body {
    color: #334155;
}

.info-card-body p {
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.info-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.info-step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 16px;
    border: 1px solid rgba(6, 182, 212, 0.2);
}

body.light-mode .info-step {
    background: rgba(6, 182, 212, 0.03);
}

.step-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content strong {
    display: block;
    color: white;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

body.light-mode .step-content strong {
    color: #1e293b;
}

.step-content p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin: 0;
}

body.light-mode .step-content p {
    color: #64748b;
}

/* URLs Section */
.urls-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
}

body.light-mode .urls-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.urls-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

.urls-header-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
}

body.light-mode .urls-header-left {
    color: #1e293b;
}

.urls-header-left i {
    color: #06b6d4;
}

.urls-count-badge {
    padding: 0.5rem 1rem;
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #06b6d4;
}

.urls-list-container {
    max-height: 500px;
    overflow-y: auto;
    overflow-x: visible;
}

/* Scrollbar pour la liste d'URLs */
.urls-list-container::-webkit-scrollbar {
    width: 12px;
}

.urls-list-container::-webkit-scrollbar-track {
    background: rgba(30, 41, 59, 0.5);
    border-radius: 10px;
}

body.light-mode .urls-list-container::-webkit-scrollbar-track {
    background: rgba(241, 245, 249, 0.8);
}

.urls-list-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 10px;
    border: 2px solid rgba(30, 41, 59, 0.5);
    transition: all 0.3s ease;
}

body.light-mode .urls-list-container::-webkit-scrollbar-thumb {
    border-color: rgba(241, 245, 249, 0.8);
}

.urls-list-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #14b8a6, #06b6d4);
    box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
}

.urls-list-container {
    scrollbar-width: thin;
    scrollbar-color: #06b6d4 rgba(30, 41, 59, 0.5);
}

body.light-mode .urls-list-container {
    scrollbar-color: #06b6d4 rgba(241, 245, 249, 0.8);
}

.urls-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.url-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    transition: all 0.3s ease;
}

body.light-mode .url-item {
    background: rgba(6, 182, 212, 0.03);
}

.url-item:hover {
    background: rgba(6, 182, 212, 0.1);
    border-color: rgba(6, 182, 212, 0.4);
    transform: translateX(4px);
}

.url-number {
    width: 35px;
    height: 35px;
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    color: #06b6d4;
    font-family: monospace;
    flex-shrink: 0;
}

.url-content {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.url-icon {
    color: #06b6d4;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.url-text {
    font-family: monospace;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    word-break: break-all;
}

body.light-mode .url-text {
    color: #1e293b;
}

/* Actions Section */
.actions-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
}

body.light-mode .actions-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.actions-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

body.light-mode .actions-header {
    color: #1e293b;
}

.actions-header i {
    color: #06b6d4;
}

.actions-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.submit-form {
    display: flex;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 30px rgba(59, 130, 246, 0.6);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.command-section {
    background: rgba(6, 182, 212, 0.05);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
}

body.light-mode .command-section {
    background: rgba(6, 182, 212, 0.03);
}

.command-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .command-header {
    color: #1e293b;
}

.command-header i {
    color: #06b6d4;
}

.command-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 0.75rem;
}

body.light-mode .command-box {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
}

.command-text {
    flex: 1;
    font-family: 'Courier New', monospace;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

body.light-mode .command-text {
    color: #1e293b;
}

.command-copy {
    width: 40px;
    height: 40px;
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 10px;
    color: #06b6d4;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.command-copy:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: scale(1.1);
}

.command-hint {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .command-hint {
    color: #64748b;
}

/* Responsive */
@media (max-width: 768px) {
    .bing-title {
        font-size: 1.75rem;
    }
    
    .bing-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .bing-icon {
        font-size: 1.5rem;
    }
    
    .info-steps {
        grid-template-columns: 1fr;
    }
    
    .urls-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .url-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .url-content {
        width: 100%;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
