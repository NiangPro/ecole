@extends('admin.layout')

@section('title', 'Articles Emplois')

@section('styles')
<style>
    /* Scrollbar personnalisée pour la page */
    body {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    body::-webkit-scrollbar {
        width: 12px;
    }
    
    body::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    body::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
        transition: all 0.3s ease;
    }
    
    body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #14b8a6, #06b6d4);
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }
    
    /* Scrollbar pour les éléments avec overflow */
    .content-section,
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    .content-section::-webkit-scrollbar,
    .overflow-x-auto::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    .content-section::-webkit-scrollbar-track,
    .overflow-x-auto::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    .content-section::-webkit-scrollbar-thumb,
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
    }
    
    .content-section::-webkit-scrollbar-thumb:hover,
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }
    
    .article-thumbnail {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .article-thumbnail:hover {
        border-color: rgba(6, 182, 212, 0.4);
        transform: scale(1.05);
    }
    
    .article-thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .article-thumbnail:hover .article-thumbnail-img {
        transform: scale(1.1);
    }
    
    .article-thumbnail-placeholder {
        width: 80px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 8px;
        color: rgba(6, 182, 212, 0.5);
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Articles Emplois</h3>
        <p class="text-gray-400">Gérez les articles d'emplois</p>
    </div>
    <a href="{{ route('admin.jobs.articles.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Nouvel article
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
        {{ session('success') }}
    </div>
@endif

<div class="content-section mb-6">
    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-cyan-400"></i>
        Filtres
    </h4>
    <form method="GET" action="{{ route('admin.jobs.articles.index') }}" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par titre..." 
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Catégorie</label>
            <select name="category" class="input-admin">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Score SEO min</label>
            <input type="number" name="seo_min" value="{{ request('seo_min') }}" 
                   placeholder="0" min="0" max="100"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Score Lisibilité min</label>
            <input type="number" name="readability_min" value="{{ request('readability_min') }}" 
                   placeholder="0" min="0" max="100"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Vues min</label>
            <input type="number" name="views_min" value="{{ request('views_min') }}" 
                   placeholder="0" min="0"
                   class="input-admin">
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Trier par</label>
            <select name="sort_by" class="input-admin">
                <option value="updated_at" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Date de modification</option>
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>Date de publication</option>
                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Vues</option>
                <option value="seo_score" {{ request('sort_by') == 'seo_score' ? 'selected' : '' }}>Score SEO</option>
                <option value="readability_score" {{ request('sort_by') == 'readability_score' ? 'selected' : '' }}>Score Lisibilité</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 mb-2 text-sm font-semibold">Ordre</label>
            <select name="sort_order" class="input-admin">
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex-1">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            <a href="{{ route('admin.jobs.articles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-redo mr-2"></i>Réinitialiser
            </a>
        </div>
    </form>
</div>

<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-cyan-500/20">
                    <th class="text-left p-4 text-cyan-400">Image</th>
                    <th class="text-left p-4 text-cyan-400">Titre</th>
                    <th class="text-left p-4 text-cyan-400">Catégorie</th>
                    <th class="text-left p-4 text-cyan-400">SEO</th>
                    <th class="text-left p-4 text-cyan-400">Lisibilité</th>
                    <th class="text-left p-4 text-cyan-400">Vues</th>
                    <th class="text-right p-4 text-cyan-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                    <td class="p-4">
                        @if($article->cover_image)
                            <div class="article-thumbnail">
                                <img src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" 
                                     alt="{{ $article->title }}"
                                     class="article-thumbnail-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="article-thumbnail-placeholder" style="display: none;">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                        @else
                            <div class="article-thumbnail-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-semibold">{{ $article->title }}</div>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                            {{ $article->category->name }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full" style="width: {{ $article->seo_score }}%"></div>
                            </div>
                            <span class="text-sm text-gray-400">{{ $article->seo_score }}/100</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-full" style="width: {{ $article->readability_score }}%"></div>
                            </div>
                            <span class="text-sm text-gray-400">{{ $article->readability_score }}/100</span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-400">{{ $article->views }}</td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jobs.articles.show', $article->id) }}" class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.jobs.articles.edit', $article->id) }}" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.jobs.articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <i class="fas fa-newspaper text-4xl mb-4 block"></i>
                        Aucun article trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($articles->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $articles->links() }}
    </div>
    @endif
</div>
@endsection

