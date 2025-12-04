@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page Bing Submission */
    .bing-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .bing-page h3 {
        color: #1e293b;
    }
    
    .bing-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .bing-page h4 {
        color: #1e293b;
    }
    
    .bing-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .bing-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .bing-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .bing-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .bing-page .bg-gray-800\/50 {
        background: rgba(31, 41, 55, 0.5);
        transition: background 0.3s ease;
    }
    
    body.light-mode .bing-page .bg-gray-800\/50 {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .bing-page .border-gray-700 {
        border-color: rgba(55, 65, 81, 1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .bing-page .border-gray-700 {
        border-color: rgba(226, 232, 240, 1);
    }
    
    .bing-page .bg-gray-900 {
        background: rgba(17, 24, 39, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .bing-page .bg-gray-900 {
        background: rgba(241, 245, 249, 1);
    }
    
    .bing-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .bing-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .bing-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .bing-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .bing-page .bg-red-500\/10 {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
    }
    
    body.light-mode .bing-page .bg-red-500\/10 {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    .bing-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.1);
        border-color: rgba(234, 179, 8, 0.3);
    }
    
    body.light-mode .bing-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.15);
        border-color: rgba(234, 179, 8, 0.4);
    }
    
    .bing-page .bg-blue-500\/10 {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    body.light-mode .bing-page .bg-blue-500\/10 {
        background: rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.4);
    }
    
    .bing-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .bing-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .bing-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.1);
        border-color: rgba(234, 179, 8, 0.3);
    }
    
    body.light-mode .bing-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.15);
        border-color: rgba(234, 179, 8, 0.4);
    }
    
    .bing-page .bg-purple-500\/10 {
        background: rgba(168, 85, 247, 0.1);
        border-color: rgba(168, 85, 247, 0.3);
    }
    
    body.light-mode .bing-page .bg-purple-500\/10 {
        background: rgba(168, 85, 247, 0.15);
        border-color: rgba(168, 85, 247, 0.4);
    }
    
    .bing-page .bg-cyan-500\/10 {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .bing-page .bg-cyan-500\/10 {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .bing-page .text-yellow-400 {
        color: #facc15;
    }
    
    .bing-page .text-yellow-300 {
        color: #fde047;
    }
    
    body.light-mode .bing-page .text-yellow-300 {
        color: #ca8a04;
    }
    
    .bing-page .text-blue-400 {
        color: #60a5fa;
    }
    
    .bing-page .text-green-400 {
        color: #4ade80;
    }
    
    .bing-page .text-yellow-400 {
        color: #facc15;
    }
    
    .bing-page .text-purple-400 {
        color: #a78bfa;
    }
    
    .bing-page .text-cyan-400 {
        color: #06b6d4;
    }
</style>
@endsection

@section('content')
<div class="bing-page">
<h3 class="text-3xl font-bold mb-8">
    <i class="fab fa-microsoft mr-2 text-blue-400"></i>
    Soumission d'URLs à Bing
</h3>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(!$isConfigured)
    <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 px-6 py-4 rounded-lg mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-xl mt-1"></i>
            <div>
                <p class="font-semibold mb-2">Clé API Bing non configurée</p>
                <p class="text-sm mb-3">Veuillez configurer votre clé API Bing dans les <a href="{{ route('admin.settings') }}" class="underline hover:text-yellow-300">paramètres du site</a>.</p>
                <p class="text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Obtenez votre clé API depuis <a href="https://www.bing.com/webmasters" target="_blank" class="underline hover:text-yellow-300">Bing Webmaster Tools</a>
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Informations -->
<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-info-circle text-cyan-400"></i>
        Informations
    </h4>
    
    <div class="grid md:grid-cols-4 gap-4 mb-4">
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
            <div class="text-2xl font-bold text-blue-400 mb-1">{{ $totalUrls }}</div>
            <div class="text-gray-400 text-sm">Total URLs</div>
        </div>
        <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4">
            <div class="text-2xl font-bold text-green-400 mb-1">{{ $formationsExercicesQuiz }}</div>
            <div class="text-gray-400 text-sm">Formations/Exercices/Quiz</div>
        </div>
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
            <div class="text-2xl font-bold text-yellow-400 mb-1">{{ $pagesStatiques }}</div>
            <div class="text-gray-400 text-sm">Pages statiques</div>
        </div>
        <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-4">
            <div class="text-2xl font-bold text-purple-400 mb-1">{{ $articlesIncluded }}</div>
            <div class="text-gray-400 text-sm">Articles inclus</div>
            @if($articlesCount > $articlesIncluded)
                <div class="text-xs text-gray-500 mt-1">sur {{ $articlesCount }} disponibles</div>
            @endif
        </div>
    </div>

        <div class="bg-cyan-500/10 border border-cyan-500/30 rounded-lg p-4">
        <p class="text-sm text-gray-300">
            <i class="fas fa-lightbulb mr-2 text-cyan-400"></i>
            Cette fonctionnalité soumet automatiquement toutes les URLs importantes de votre site à Bing pour un indexation rapide. 
            <strong>Ordre de soumission :</strong> D'abord tous les liens des formations, exercices et quiz (48 URLs), 
            ensuite les 10 liens des pages statiques à conserver, puis compléter avec les liens des nouveaux articles 
            jusqu'à atteindre 100 URLs au total.
        </p>
    </div>
</div>

<!-- Liste des URLs -->
<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-list text-cyan-400"></i>
        URLs à soumettre ({{ count($urls) }})
    </h4>
    
    <div class="max-h-96 overflow-y-auto">
        <div class="space-y-2">
            @foreach($urls as $index => $url)
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-3 flex items-center gap-3">
                <span class="text-gray-500 text-sm font-mono w-8">{{ $index + 1 }}</span>
                <span class="text-gray-300 text-sm font-mono flex-1 break-all">{{ $url }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Actions -->
<div class="content-section">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-rocket text-cyan-400"></i>
        Actions
    </h4>
    
    <form action="{{ route('admin.bing.submit') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir soumettre {{ count($urls) }} URLs à Bing ?');">
        @csrf
        <button type="submit" class="btn-primary" {{ !$isConfigured ? 'disabled' : '' }}>
            <i class="fab fa-microsoft mr-2"></i>
            Soumettre les URLs à Bing
        </button>
    </form>

    <div class="mt-4">
        <p class="text-sm text-gray-400 mb-2">
            <i class="fas fa-terminal mr-2"></i>
            Vous pouvez aussi utiliser la commande artisan :
        </p>
        <code class="block bg-gray-900 border border-gray-700 rounded-lg p-3 text-sm text-gray-300">
            php artisan bing:submit-urls
        </code>
    </div>
</div>
</div>
@endsection

