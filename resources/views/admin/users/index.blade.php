@extends('admin.layout')

@section('content')
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

<!-- Barre de recherche -->
<div class="content-section mb-6">
    <form action="{{ route('admin.users') }}" method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ $search }}" 
               placeholder="Rechercher par nom ou email..." 
               class="input-admin flex-1">
        <button type="submit" class="btn-primary">
            <i class="fas fa-search mr-2"></i>Rechercher
        </button>
        @if($search)
        <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
            <i class="fas fa-times mr-2"></i>Réinitialiser
        </a>
        @endif
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

<!-- Statistiques -->
<div class="grid md:grid-cols-3 gap-6 mt-6">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-users text-4xl text-cyan-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\User::count() }}</div>
        <p class="text-gray-400 mt-2">Total utilisateurs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-user-check text-4xl text-green-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\User::where('is_active', true)->count() }}</div>
        <p class="text-gray-400 mt-2">Utilisateurs actifs</p>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-shield-alt text-4xl text-purple-400"></i>
        </div>
        <div class="stat-number">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
        <p class="text-gray-400 mt-2">Administrateurs</p>
    </div>
</div>
@endsection
