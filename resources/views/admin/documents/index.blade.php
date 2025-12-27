@extends('admin.layout')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@section('title', 'Documents')

@section('styles')
<style>
    .document-thumbnail {
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
    
    .document-thumbnail:hover {
        border-color: rgba(6, 182, 212, 0.4);
        transform: scale(1.05);
    }
    
    .document-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-draft { background: rgba(156, 163, 175, 0.2); color: #9ca3af; }
    .status-published { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-archived { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    
    /* Dark Mode Styles */
    body.light-mode .documents-index-page h3 {
        color: #1e293b;
    }
    
    body.light-mode .documents-index-page p {
        color: #64748b;
    }
    
    body.light-mode .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .input-admin {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .input-admin:focus {
        background: #ffffff;
        border-color: #06b6d4;
    }
    
    body.light-mode table thead th {
        color: #06b6d4;
    }
    
    body.light-mode table tbody td {
        color: #1e293b;
    }
    
    body.light-mode table tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .document-thumbnail {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
    }
</style>
@endsection

@section('content')
<div class="documents-index-page">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h3 class="text-3xl font-bold mb-2">Documents</h3>
            <p class="text-gray-400">Gérez les documents à vendre</p>
        </div>
        <a href="{{ route('admin.documents.documents.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouveau document
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-cyan-400"></i>
            Filtres
        </h4>
        <form method="GET" action="{{ route('admin.documents.documents.index') }}" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher par titre..." 
                       class="input-admin">
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Catégorie</label>
                <select name="category_id" class="input-admin">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Statut</label>
                <select name="status" class="input-admin">
                    <option value="">Tous les statuts</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition flex-1">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.documents.documents.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
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
                        <th class="text-left p-4 text-cyan-400">Prix</th>
                        <th class="text-left p-4 text-cyan-400">Statut</th>
                        <th class="text-left p-4 text-cyan-400">Ventes</th>
                        <th class="text-left p-4 text-cyan-400">Vues</th>
                        <th class="text-right p-4 text-cyan-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                    <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                        <td class="p-4">
                            @if($document->cover_image)
                                <div class="document-thumbnail">
                                    <img src="{{ $document->cover_type === 'internal' ? Storage::url($document->cover_image) : $document->cover_image }}" 
                                         alt="{{ $document->title }}">
                                </div>
                            @else
                                <div class="document-thumbnail">
                                    <i class="fas fa-file-alt text-cyan-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-semibold">{{ $document->title }}</div>
                            <div class="text-sm text-gray-400 mt-1">{{ Str::limit($document->excerpt ?? $document->description, 50) }}</div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                                {{ $document->category->name }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="font-semibold">
                                @if($document->hasDiscount())
                                    <span class="text-gray-400 line-through">{{ number_format($document->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="text-green-400 ml-2">{{ number_format($document->current_price, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span>{{ number_format($document->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="status-badge status-{{ $document->status }}">
                                @if($document->status === 'draft') Brouillon
                                @elseif($document->status === 'published') Publié
                                @else Archivé
                                @endif
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="text-gray-300">{{ $document->sales_count }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-gray-300">{{ $document->views_count }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.documents.documents.show', $document->id) }}" 
                                   class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition" 
                                   title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.documents.documents.edit', $document->id) }}" 
                                   class="px-3 py-1 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded transition" 
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($document->status === 'draft')
                                    <form action="{{ route('admin.documents.documents.publish', $document->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded transition" title="Publier">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @elseif($document->status === 'published')
                                    <form action="{{ route('admin.documents.documents.unpublish', $document->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-orange-500/20 hover:bg-orange-500/30 text-orange-400 rounded transition" title="Dépublier">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.documents.documents.destroy', $document->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
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
                        <td colspan="8" class="p-8 text-center text-gray-400">
                            <i class="fas fa-file-alt text-4xl mb-4 block"></i>
                            <p>Aucun document trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

