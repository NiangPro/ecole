@extends('admin.layout')

@section('title', 'Réalisations')

@section('styles')
<style>
    /* Styles pour la page Achievements */
    .achievements-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .achievements-page h3 {
        color: #1e293b;
    }
    
    .achievements-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .achievements-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .achievements-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    body.light-mode .achievements-page .hover\:bg-cyan-500\/5:hover {
        background: rgba(6, 182, 212, 0.1);
    }
    
    .achievements-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.5);
    }
    
    body.light-mode .achievements-page .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .achievements-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
    }
    
    body.light-mode .achievements-page .bg-red-500\/20 {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    .achievements-page table td {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }
    
    body.light-mode .achievements-page table td {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .achievements-page .font-semibold {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }
    
    body.light-mode .achievements-page .font-semibold {
        color: rgba(30, 41, 59, 0.9);
    }
</style>
@endsection

@section('content')
<div class="achievements-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Réalisations</h3>
        <p class="text-gray-400">Gérez vos réalisations et projets</p>
    </div>
    <div class="flex gap-3">
        <form action="{{ route('admin.achievements.toggle-section') }}" method="POST" class="inline" id="toggleSectionForm">
            @csrf
            <button type="submit" 
                    id="toggleSectionBtn"
                    class="px-4 py-2 {{ $showSection ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-black font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas {{ $showSection ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                <span>{{ $showSection ? 'Masquer la section' : 'Afficher la section' }}</span>
            </button>
        </form>
        <script>
            // Protection contre les doubles clics avec feedback visuel
            (function() {
                const form = document.getElementById('toggleSectionForm');
                const btn = document.getElementById('toggleSectionBtn');
                
                if (!form || !btn) return;
                
                let isSubmitting = false;
                const originalContent = btn.innerHTML;
                
                form.addEventListener('submit', function(e) {
                    // Empêcher la double soumission
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    isSubmitting = true;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Chargement...</span>';
                    
                    // Le formulaire va se soumettre normalement et la page va se recharger
                    // Si après 10 secondes la page n'a pas rechargé, réactiver le bouton
                    setTimeout(function() {
                        if (isSubmitting) {
                            isSubmitting = false;
                            btn.disabled = false;
                            btn.innerHTML = originalContent;
                        }
                    }, 10000);
                });
            })();
        </script>
        <a href="{{ route('admin.achievements.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Nouvelle réalisation
        </a>
    </div>
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
</div>
@endsection

