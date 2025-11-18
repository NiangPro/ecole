@extends('admin.layout')

@section('title', 'Réalisations')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Réalisations</h3>
        <p class="text-gray-400">Gérez vos réalisations et projets</p>
    </div>
    <a href="{{ route('admin.achievements.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
        <i class="fas fa-plus mr-2"></i>Nouvelle réalisation
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
                    <th class="text-left p-4 text-cyan-400">Image</th>
                    <th class="text-left p-4 text-cyan-400">Titre</th>
                    <th class="text-left p-4 text-cyan-400">Description</th>
                    <th class="text-left p-4 text-cyan-400">Visibilité</th>
                    <th class="text-left p-4 text-cyan-400">Ordre</th>
                    <th class="text-right p-4 text-cyan-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achievements as $achievement)
                <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition">
                    <td class="p-4">
                        @if($achievement->image)
                            <img src="{{ $achievement->image_url }}" 
                                 alt="{{ $achievement->title }}" 
                                 class="w-16 h-16 rounded-lg object-cover border-2 border-cyan-500/30"
                                 style="min-width: 64px; min-height: 64px;"
                                 onerror="this.style.display='none';">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-cyan-500/20 border-2 border-cyan-500/30 flex items-center justify-center">
                                <i class="fas fa-image text-cyan-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            @if($achievement->icon)
                                <i class="{{ $achievement->icon }} text-cyan-400 text-xl"></i>
                            @endif
                            <span class="font-semibold">{{ $achievement->title }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-gray-400 text-sm">
                        {{ Str::limit($achievement->description ?? 'Aucune description', 50) }}
                    </td>
                    <td class="p-4">
                        @if($achievement->is_visible)
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-sm">Visible</span>
                        @else
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-sm">Masquée</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-400">{{ $achievement->order }}</td>
                    <td class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.achievements.edit', $achievement->id) }}" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.achievements.destroy', $achievement->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réalisation ?');">
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
                        <i class="fas fa-trophy text-4xl mb-4 block"></i>
                        Aucune réalisation trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

