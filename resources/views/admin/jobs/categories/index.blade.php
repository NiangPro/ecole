@extends('admin.layout')

@section('title', 'Catégories Emplois')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Catégories Emplois</h3>
        <p class="text-gray-400">Gérez les catégories d'articles d'emplois</p>
    </div>
    <a href="{{ route('admin.jobs.categories.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Nouvelle catégorie
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400">
        {{ session('error') }}
    </div>
@endif

<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-cyan-500/20">
                    <th class="text-left p-4 text-cyan-400">Nom</th>
                    <th class="text-left p-4 text-cyan-400">Slug</th>
                    <th class="text-left p-4 text-cyan-400">Articles</th>
                    <th class="text-left p-4 text-cyan-400">Statut</th>
                    <th class="text-left p-4 text-cyan-400">Ordre</th>
                    <th class="text-right p-4 text-cyan-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                                <img src="{{ $category->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($category->image) : $category->image }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover border-2 border-cyan-500/30"
                                     style="min-width: 40px; min-height: 40px;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <i class="{{ $category->icon ?? 'fas fa-folder' }} text-cyan-400" style="display: none;"></i>
                            @elseif($category->icon)
                                <i class="{{ $category->icon }} text-cyan-400 text-xl"></i>
                            @else
                                <i class="fas fa-folder text-cyan-400 text-xl"></i>
                            @endif
                            <span class="font-semibold">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-400 text-sm">{{ $category->slug }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-sm">
                            {{ $category->articles()->count() }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($category->is_active)
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-sm">Active</span>
                        @else
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-sm">Inactive</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-400">{{ $category->order }}</td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jobs.categories.edit', $category->id) }}" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.jobs.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        <i class="fas fa-folder-open text-4xl mb-4 block"></i>
                        Aucune catégorie trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

