@extends('admin.layout')

@section('title', 'Articles Emplois')

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

<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-cyan-500/20">
                    <th class="text-left p-4 text-cyan-400">Titre</th>
                    <th class="text-left p-4 text-cyan-400">Catégorie</th>
                    <th class="text-left p-4 text-cyan-400">Statut</th>
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
                        <div class="font-semibold">{{ $article->title }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ Str::limit($article->excerpt ?? $article->content, 60) }}</div>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                            {{ $article->category->name }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($article->status === 'published')
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-sm">Publié</span>
                        @elseif($article->status === 'draft')
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-sm">Brouillon</span>
                        @else
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-sm">Archivé</span>
                        @endif
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

