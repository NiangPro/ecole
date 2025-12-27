@extends('admin.layout')

@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@section('title', 'Catégories Documents')

@section('styles')
<style>
    /* Dark Mode Styles */
    body.light-mode h3 {
        color: #1e293b;
    }
    
    body.light-mode p {
        color: #64748b;
    }
    
    body.light-mode .content-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .bg-gray-800\/50 {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .bg-gray-800\/50:hover {
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    body.light-mode .bg-gray-800\/50 h4 {
        color: #1e293b;
    }
    
    body.light-mode .bg-gray-800\/50 p {
        color: #64748b;
    }
    
    body.light-mode .text-gray-400 {
        color: #64748b;
    }
    
    body.light-mode .bg-cyan-500\/20 {
        background: rgba(6, 182, 212, 0.1);
    }
</style>
@endsection

@section('content')
<div class="categories-admin">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h3 class="text-3xl font-bold mb-2">Catégories Documents</h3>
            <p class="text-gray-400">Gérez les catégories de documents</p>
        </div>
        <a href="{{ route('admin.documents.categories.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouvelle catégorie
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

    <div class="content-section">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($categories as $category)
            <div class="p-6 bg-gray-800/50 rounded-lg border border-cyan-500/20 hover:border-cyan-500/40 transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        @if($category->icon)
                            <div class="w-12 h-12 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                                <i class="{{ $category->icon }} text-cyan-400 text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-lg">{{ $category->name }}</h4>
                            @if($category->parent)
                                <p class="text-sm text-gray-400">Sous-catégorie de: {{ $category->parent->name }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded text-xs {{ $category->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                @if($category->description)
                    <p class="text-gray-400 text-sm mb-4">{{ Str::limit($category->description, 100) }}</p>
                @endif
                
                <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                    <span><i class="fas fa-file mr-1"></i>{{ $category->documents()->count() }} documents</span>
                    <span><i class="fas fa-sort-numeric-down mr-1"></i>Ordre: {{ $category->order }}</span>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.documents.categories.edit', $category->id) }}" 
                       class="flex-1 px-3 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded transition text-center text-sm">
                        <i class="fas fa-edit mr-1"></i>Modifier
                    </a>
                    <form action="{{ route('admin.documents.categories.destroy', $category->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition text-sm">
                            <i class="fas fa-trash mr-1"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center text-gray-400">
                <i class="fas fa-folder text-4xl mb-4 block"></i>
                <p>Aucune catégorie trouvée</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection


