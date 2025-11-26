@extends('layouts.app')

@section('title', 'Quiz | NiangProgrammeur')
@section('meta_description', 'Testez vos connaissances en programmation avec nos quiz interactifs. HTML5, CSS3, JavaScript, PHP, Bootstrap, Git, WordPress, IA, Python, Java, SQL et C.')

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
    
    .quiz-card {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.9), rgba(0, 0, 0, 0.9));
        border: 2px solid rgba(168, 85, 247, 0.2);
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .quiz-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(168, 85, 247, 0.25) !important;
        box-shadow: 0 10px 40px rgba(168, 85, 247, 0.1) !important;
    }
    
    .quiz-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #a855f7, #ec4899);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .quiz-card:hover {
        transform: translateY(-10px);
        border-color: rgba(168, 85, 247, 0.5);
        box-shadow: 0 20px 60px rgba(168, 85, 247, 0.3);
    }
    
    body:not(.dark-mode) .quiz-card:hover {
        box-shadow: 0 20px 60px rgba(168, 85, 247, 0.2) !important;
    }
    
    .quiz-card:hover::before {
        transform: scaleX(1);
    }
    
    .language-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .quiz-card:hover .language-icon {
        transform: scale(1.1) rotate(-5deg);
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
        border-color: rgba(168, 85, 247, 0.2) !important;
    }
    
    /* CTA section */
    body:not(.dark-mode) .bg-gradient-to-r.from-purple-500\/10.to-pink-500\/10 {
        background: linear-gradient(to right, rgba(168, 85, 247, 0.05), rgba(236, 72, 153, 0.05)) !important;
        border-color: rgba(168, 85, 247, 0.2) !important;
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
</style>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-8">
    <div class="container mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-6xl font-bold mb-6 bg-gradient-to-r from-purple-400 via-pink-500 to-purple-400 bg-clip-text text-transparent">
                Quiz
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                Testez vos connaissances en programmation avec nos quiz interactifs
            </p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold text-purple-400 mb-2">{{ count($languages) }}</div>
                <div class="text-gray-400">Langages</div>
            </div>
            <div class="bg-gradient-to-br from-pink-500/10 to-rose-500/10 border border-pink-500/20 rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold text-pink-400 mb-2">{{ array_sum(array_column($languages, 'questions')) }}</div>
                <div class="text-gray-400">Questions</div>
            </div>
            <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 border border-blue-500/20 rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold text-blue-400 mb-2">20</div>
                <div class="text-gray-400">Questions par quiz</div>
            </div>
            <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 border border-green-500/20 rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">100%</div>
                <div class="text-gray-400">Gratuit</div>
            </div>
        </div>

        <!-- Grille des langages -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($languages as $lang)
            <a href="{{ route('quiz.language', $lang['slug']) }}" class="quiz-card group">
                <div class="language-icon bg-{{ $lang['color'] }}-500/10 mx-auto">
                    <i class="{{ $lang['icon'] }} text-{{ $lang['color'] }}-400"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-3 text-center group-hover:text-purple-400 transition">
                    {{ $lang['name'] }}
                </h3>
                
                <div class="flex items-center justify-center gap-2 text-gray-400 mb-4">
                    <i class="fas fa-question-circle text-purple-400"></i>
                    <span>{{ $lang['questions'] }} questions</span>
                </div>
                
                <div class="flex items-center justify-center gap-2 text-sm mb-4">
                    <span class="px-3 py-1 bg-purple-500/10 text-purple-400 rounded-full">QCM</span>
                    <span class="px-3 py-1 bg-pink-500/10 text-pink-400 rounded-full">20 min</span>
                </div>
                
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center gap-2 text-purple-400 font-semibold group-hover:gap-4 transition-all">
                        Commencer le quiz
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="mt-16 bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-3xl p-12 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                <i class="fas fa-trophy text-yellow-400 mr-3"></i>
                Prêt à relever le défi ?
            </h2>
            <p class="text-gray-300 text-lg mb-6 max-w-2xl mx-auto">
                Améliorez vos compétences en programmation en pratiquant avec nos exercices et quiz interactifs. C'est gratuit et accessible à tous les niveaux.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('exercices') }}" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                    <i class="fas fa-code mr-2"></i>Voir les exercices
                </a>
                <a href="{{ route('about') }}" class="px-8 py-3 bg-white/10 border border-white/20 text-white font-bold rounded-lg hover:bg-white/20 transition">
                    <i class="fas fa-info-circle mr-2"></i>En savoir plus
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
