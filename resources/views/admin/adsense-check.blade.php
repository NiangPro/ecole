@extends('admin.layout')

@section('title', 'Vérification AdSense')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Vérification AdSense</h3>
        <p class="text-gray-400">Vérifiez que votre site respecte toutes les exigences Google AdSense</p>
    </div>
    <a href="{{ route('admin.adsense') }}" class="btn-primary">
        <i class="fas fa-cog mr-2"></i>Configuration AdSense
    </a>
</div>

<!-- Score global -->
<div class="content-section mb-6">
    <div class="text-center">
        <div class="inline-block mb-4">
            <div class="relative w-32 h-32 mx-auto">
                <svg class="transform -rotate-90 w-32 h-32">
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" class="text-gray-700"></circle>
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="none" 
                            stroke-dasharray="{{ $percentage * 3.51 }} 351" 
                            class="text-cyan-400 transition-all duration-500"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-3xl font-bold text-cyan-400">{{ $percentage }}%</span>
                </div>
            </div>
        </div>
        <h4 class="text-2xl font-bold mb-2">{{ $score }} / {{ $total }} critères respectés</h4>
        <p class="text-gray-400">
            @if($percentage >= 90)
                <span class="text-green-400 font-semibold">Excellent ! Votre site est prêt pour AdSense</span>
            @elseif($percentage >= 70)
                <span class="text-yellow-400 font-semibold">Bien ! Quelques améliorations sont nécessaires</span>
            @else
                <span class="text-red-400 font-semibold">Attention ! Plusieurs critères doivent être corrigés</span>
            @endif
        </p>
    </div>
</div>

<!-- Liste des vérifications -->
<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-6">Critères de vérification</h4>
    <div class="space-y-4">
        @foreach($checks as $key => $check)
        <div class="flex items-start gap-4 p-4 bg-black/30 rounded-lg border {{ $check['status'] ? 'border-green-500/30' : 'border-red-500/30' }}">
            <div class="flex-shrink-0 mt-1">
                @if($check['status'] === true)
                    <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-400"></i>
                    </div>
                @elseif($check['status'] === false)
                    <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-red-400"></i>
                    </div>
                @else
                    <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-question text-yellow-400"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h5 class="font-semibold text-lg mb-1 {{ $check['status'] ? 'text-green-400' : ($check['status'] === false ? 'text-red-400' : 'text-yellow-400') }}">
                    {{ $check['title'] }}
                </h5>
                <p class="text-gray-400 text-sm">{{ $check['message'] }}</p>
                @if($check['status'] === false)
                    <div class="mt-3 p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                        <p class="text-red-400 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Action requise :</strong> 
                            @if($key === 'content_quality')
                                Publiez au moins 30 articles de qualité pour améliorer vos chances d'acceptation.
                            @elseif($key === 'pages_legal')
                                Assurez-vous que les pages de politique de confidentialité et mentions légales sont accessibles et complètes.
                            @elseif($key === 'contact_page')
                                Créez une page de contact accessible depuis le menu principal.
                            @elseif($key === 'about_page')
                                Ajoutez une page "À propos" pour établir la crédibilité de votre site.
                            @elseif($key === 'ads_txt')
                                Créez un fichier ads.txt à la racine de votre site (public/ads.txt).
                            @elseif($key === 'sitemap')
                                Générez un sitemap.xml pour aider les moteurs de recherche à indexer votre site.
                            @elseif($key === 'robots_txt')
                                Créez un fichier robots.txt à la racine de votre site (public/robots.txt).
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Conseils pour AdSense -->
<div class="content-section">
    <h4 class="text-xl font-bold mb-4 text-cyan-400">
        <i class="fas fa-lightbulb mr-2"></i>Conseils pour maximiser vos chances d'acceptation AdSense
    </h4>
    <div class="grid md:grid-cols-2 gap-4">
        <div class="p-4 bg-black/30 rounded-lg border border-cyan-500/20">
            <h5 class="font-semibold text-cyan-400 mb-2">
                <i class="fas fa-file-alt mr-2"></i>Contenu de qualité
            </h5>
            <p class="text-gray-400 text-sm">
                Publiez régulièrement du contenu original, utile et bien écrit. Minimum 30 articles de qualité sont recommandés.
            </p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg border border-cyan-500/20">
            <h5 class="font-semibold text-cyan-400 mb-2">
                <i class="fas fa-users mr-2"></i>Trafic organique
            </h5>
            <p class="text-gray-400 text-sm">
                AdSense préfère les sites avec du trafic organique stable. Utilisez le SEO pour attirer des visiteurs naturels.
            </p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg border border-cyan-500/20">
            <h5 class="font-semibold text-cyan-400 mb-2">
                <i class="fas fa-shield-alt mr-2"></i>Conformité
            </h5>
            <p class="text-gray-400 text-sm">
                Respectez les politiques AdSense : pas de contenu dupliqué, pas de clics frauduleux, navigation claire.
            </p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg border border-cyan-500/20">
            <h5 class="font-semibold text-cyan-400 mb-2">
                <i class="fas fa-mobile-alt mr-2"></i>Design responsive
            </h5>
            <p class="text-gray-400 text-sm">
                Votre site doit être optimisé pour mobile. Assurez-vous que tous les contenus sont accessibles sur tous les appareils.
            </p>
        </div>
    </div>
</div>
@endsection

