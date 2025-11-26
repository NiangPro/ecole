@extends('admin.layout')

@section('title', 'Voir Article')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Détails de l'article</h3>
        <p class="text-gray-400">{{ $article->title }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.jobs.articles.edit', $article->id) }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-edit mr-2"></i>Modifier
        </a>
        <a href="{{ route('admin.jobs.articles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contenu principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Image de couverture -->
        @if($article->cover_image)
        <div class="content-section p-0 overflow-hidden">
            <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-64 object-cover">
        </div>
        @endif

        <!-- Titre -->
        <div class="content-section">
            <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
            <div class="flex items-center gap-4 flex-wrap">
                <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm font-semibold">
                    {{ $article->category->name }}
                </span>
                @if($article->status === 'published')
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded text-sm">Publié</span>
                @elseif($article->status === 'draft')
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded text-sm">Brouillon</span>
                @else
                    <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded text-sm">Archivé</span>
                @endif
                @if($article->is_sponsored)
                    <span class="px-3 py-1 bg-gradient-to-r from-orange-500/20 to-red-500/20 text-orange-400 rounded text-sm font-bold flex items-center gap-1">
                        <i class="fas fa-star text-xs"></i>
                        Sponsorisé
                    </span>
                @endif
                <span class="text-gray-400 text-sm">
                    <i class="fas fa-eye mr-1"></i>{{ $article->views }} vues
                </span>
                @if($article->published_at)
                <span class="text-gray-400 text-sm">
                    <i class="fas fa-calendar mr-1"></i>{{ $article->published_at->format('d/m/Y H:i') }}
                </span>
                @endif
            </div>
        </div>

        <!-- Extrait -->
        @if($article->excerpt)
        <div class="content-section">
            <h3 class="text-xl font-bold mb-3 text-cyan-400">Extrait</h3>
            <p class="text-gray-300 leading-relaxed">{{ $article->excerpt }}</p>
        </div>
        @endif

        <!-- Contenu -->
        <div class="content-section">
            <h3 class="text-xl font-bold mb-4 text-cyan-400">Contenu</h3>
            <div class="prose prose-invert max-w-none">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Métadonnées -->
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Métadonnées</h4>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-400 text-sm">Slug:</span>
                    <p class="text-white font-mono text-sm">{{ $article->slug }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Statut sponsorisé:</span>
                    <p class="text-white">
                        @if($article->is_sponsored)
                            <span class="px-2 py-1 bg-gradient-to-r from-orange-500/20 to-red-500/20 text-orange-400 rounded text-xs font-bold inline-flex items-center gap-1">
                                <i class="fas fa-star text-xs"></i>
                                Oui
                            </span>
                        @else
                            <span class="text-gray-500">Non</span>
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Créé le:</span>
                    <p class="text-white">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Modifié le:</span>
                    <p class="text-white">{{ $article->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- SEO & Lisibilité -->
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">SEO & Lisibilité</h4>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-cyan-400 font-semibold">Score SEO</span>
                        <span class="text-2xl font-bold text-cyan-400">{{ $article->seo_score }}/100</span>
                    </div>
                    <div class="w-full h-3 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: {{ $article->seo_score }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-cyan-400 font-semibold">Lisibilité</span>
                        <span class="text-2xl font-bold text-cyan-400">{{ $article->readability_score }}/100</span>
                    </div>
                    <div class="w-full h-3 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full transition-all duration-300" style="width: {{ $article->readability_score }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métadonnées SEO -->
        @if($article->meta_title || $article->meta_description || $article->meta_keywords)
        <div class="content-section">
            <h4 class="text-cyan-400 font-bold mb-4 border-b border-cyan-500/20 pb-2">Métadonnées SEO</h4>
            <div class="space-y-3">
                @if($article->meta_title)
                <div>
                    <span class="text-gray-400 text-sm">Meta Title:</span>
                    <p class="text-white text-sm">{{ $article->meta_title }}</p>
                </div>
                @endif
                @if($article->meta_description)
                <div>
                    <span class="text-gray-400 text-sm">Meta Description:</span>
                    <p class="text-white text-sm">{{ $article->meta_description }}</p>
                </div>
                @endif
                @if($article->meta_keywords && is_array($article->meta_keywords) && count($article->meta_keywords) > 0)
                <div>
                    <span class="text-gray-400 text-sm">Mots-clés:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($article->meta_keywords as $keyword)
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-xs">{{ $keyword }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

