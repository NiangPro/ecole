@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page Users Index */
    .users-index-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-index-page h3 {
        color: #1e293b;
    }
    
    .users-index-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-index-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .users-index-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-index-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .users-index-page .bg-gray-700 {
        background: rgba(55, 65, 81, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .users-index-page .bg-gray-700 {
        background: rgba(241, 245, 249, 1);
    }
    
    .users-index-page .border-gray-700 {
        border-color: rgba(55, 65, 81, 1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .users-index-page .border-gray-700 {
        border-color: rgba(226, 232, 240, 1);
    }
    
    .users-index-page .border-gray-800 {
        border-color: rgba(31, 41, 55, 1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .users-index-page .border-gray-800 {
        border-color: rgba(203, 213, 225, 1);
    }
    
    .users-index-page .hover\:bg-black\/30:hover {
        background: rgba(0, 0, 0, 0.3);
    }
    
    body.light-mode .users-index-page .hover\:bg-black\/30:hover {
        background: rgba(6, 182, 212, 0.05);
    }
    
    .users-index-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .users-index-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
</style>
@endsection

@section('content')
<div class="users-index-page">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h3 class="text-3xl font-bold">Gestion des Utilisateurs</h3>
    <a href="{{ route('admin.users.create') }}" class="btn-primary w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
    </a>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Statistiques -->
<div class="grid md:grid-cols-5 gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-users text-3xl text-cyan-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['total']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Total utilisateurs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-user-check text-3xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['active']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Actifs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-user-times text-3xl text-red-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['inactive']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Inactifs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-shield-alt text-3xl text-purple-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['admins']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Administrateurs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-user text-3xl text-blue-400"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['users']) }}</div>
        <p class="text-gray-400 mt-2 text-sm">Utilisateurs</p>
    </div>
</div>

<!-- Barre de recherche et filtres -->
<div class="content-section mb-6">
    <form action="{{ route('admin.users') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Recherche</label>
            <input type="text" name="search" value="{{ $search }}" 
                   placeholder="Rechercher par nom, email ou téléphone..." 
                       class="input-admin w-full">
            </div>
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Rôle</label>
                <select name="role" class="input-admin w-full">
                <option value="">Tous les rôles</option>
                <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $role == 'user' ? 'selected' : '' }}>Utilisateur</option>
            </select>
            </div>
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Statut</label>
                <select name="status" class="input-admin w-full">
                <option value="">Tous les statuts</option>
                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>
            </div>
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Trier par</label>
                <select name="sort" class="input-admin w-full">
                <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nom</option>
                <option value="email" {{ $sortBy == 'email' ? 'selected' : '' }}>Email</option>
            </select>
            </div>
            <div>
                <label class="block text-gray-300 mb-2 text-sm font-semibold">Ordre</label>
                <select name="order" class="input-admin w-full">
                <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
            </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary flex-1">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
            @if($search || $role || $status)
                <a href="{{ route('admin.users') }}" class="px-4 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
            </a>
            @endif
            </div>
        </div>
    </form>
</div>

<!-- Liste des utilisateurs -->
<div class="content-section">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Nom</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Email</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Téléphone</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Rôle</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Statut</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Inscrit le</th>
                    <th class="text-right py-4 px-4 font-semibold text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-800 hover:bg-black/30 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-gray-400">{{ $user->email }}</td>
                    <td class="py-4 px-4 text-gray-400">{{ $user->phone ?? '-' }}</td>
                    <td class="py-4 px-4">
                        @if($user->role == 'admin')
                            <span class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-shield-alt mr-1"></i>Admin
                            </span>
                        @else
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-user mr-1"></i>Utilisateur
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-4">
                        @if($user->is_active)
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Actif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-semibold">
                                <i class="fas fa-times-circle mr-1"></i>Inactif
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="py-4 px-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="px-3 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        <i class="fas fa-users text-5xl mb-4 opacity-50"></i>
                        <p>Aucun utilisateur trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
    </div>
</div>
@endsection
