@extends('admin.layout')

@section('title', 'Gestion des Catégories du Forum')

@section('styles')
<style>
    .forum-categories-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .forum-categories-page h3 {
        color: #1e293b;
    }
    
    .forum-categories-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .forum-categories-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .forum-categories-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .forum-categories-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
</style>
@endsection

@section('content')
<div class="forum-categories-page">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h3 class="text-3xl font-bold">Gestion des Catégories du Forum</h3>
        <a href="{{ route('admin.forum.categories.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Nouvelle Catégorie
        </a>
    </div>

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

    @if($categories->count() > 0)
    <div class="content-section">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Nom</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Slug</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Description</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Icône</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Couleur</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Ordre</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Topics</th>
                        <th class="text-left py-4 px-4 text-gray-300 font-semibold">Statut</th>
                        <th class="text-right py-4 px-4 text-gray-300 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                        <td class="py-4 px-4">
                            <div class="font-semibold text-gray-300">{{ $category->name }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <code class="text-cyan-400 text-sm">{{ $category->slug }}</code>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm text-gray-400">{{ Str::limit($category->description, 50) }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }}" style="color: {{ $category->color ?? '#06b6d4' }}; font-size: 1.25rem;"></i>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded border border-gray-600" style="background-color: {{ $category->color ?? '#06b6d4' }};"></div>
                                <span class="text-gray-300 text-sm">{{ $category->color ?? '#06b6d4' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-gray-300">{{ $category->order }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-semibold">
                                {{ $category->topics_count }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($category->is_active)
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                            @else
                            <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-times-circle mr-1"></i>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.forum.categories.edit', $category) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.forum.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-semibold transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
    @else
    <div class="content-section text-center py-12">
        <i class="fas fa-folder-open text-6xl text-gray-400 mb-4"></i>
        <p class="text-gray-300 text-lg mb-4">Aucune catégorie trouvée</p>
        <a href="{{ route('admin.forum.categories.create') }}" class="btn-primary inline-block">
            <i class="fas fa-plus mr-2"></i>Créer la première catégorie
        </a>
    </div>
    @endif
</div>
@endsection

