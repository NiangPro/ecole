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
    
    /* Scrollbar moderne */
    .modern-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.5) rgba(15, 23, 42, 0.3);
    }
    
    .modern-scrollbar::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }
    
    .modern-scrollbar::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    .modern-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.8), rgba(20, 184, 166, 0.8));
        border-radius: 10px;
        border: 2px solid rgba(15, 23, 42, 0.3);
    }
    
    .modern-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 1), rgba(20, 184, 166, 1));
    }
    
    body.light-mode .modern-scrollbar {
        scrollbar-color: rgba(6, 182, 212, 0.6) rgba(241, 245, 249, 0.5);
    }
    
    body.light-mode .modern-scrollbar::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
    }
    
    body.light-mode .modern-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.7), rgba(20, 184, 166, 0.7));
        border-color: rgba(241, 245, 249, 0.5);
    }
    
    /* Modal moderne */
    .user-info-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .user-info-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .user-info-modal-content {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    body.light-mode .user-info-modal-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
        border-color: rgba(6, 182, 212, 0.4);
    }
    
    .user-info-modal-header {
        padding: 2rem 2.5rem 1.5rem;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border-radius: 24px 24px 0 0;
    }
    
    .user-info-modal-header h3 {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-info-modal-close {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        color: #06b6d4;
        font-size: 1.25rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .user-info-modal-close:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: rotate(90deg);
    }
    
    .user-info-modal-body {
        padding: 2rem 2.5rem;
    }
    
    .user-info-avatar-section {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .user-info-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
        border: 4px solid rgba(6, 182, 212, 0.3);
    }
    
    .user-info-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: rgba(209, 213, 219, 1);
        margin-bottom: 0.5rem;
    }
    
    body.light-mode .user-info-name {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .user-info-badges {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    
    .user-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .user-info-item {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .user-info-item:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.2);
    }
    
    .user-info-item-label {
        font-size: 0.875rem;
        color: rgba(156, 163, 175, 1);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-info-item-label i {
        color: #06b6d4;
        width: 20px;
        text-align: center;
    }
    
    .user-info-item-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .user-info-item-value {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .user-info-modal-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .user-info-modal-content::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 10px;
    }
    
    .user-info-modal-content::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.8), rgba(20, 184, 166, 0.8));
        border-radius: 10px;
    }
    
    .user-info-modal-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 1), rgba(20, 184, 166, 1));
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
                   placeholder="Rechercher par nom ou email..." 
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
    <div class="overflow-x-auto modern-scrollbar">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-4 px-4 font-semibold text-gray-300">Nom</th>
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
                            <button onclick="showUserInfo({{ $user->id }})" 
                               class="px-3 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition"
                               title="Voir les détails">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="px-3 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->role != 'admin')
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
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

<!-- Modal d'informations utilisateur -->
<div id="userInfoModal" class="user-info-modal">
    <div class="user-info-modal-content">
        <div class="user-info-modal-header">
            <h3>
                <i class="fas fa-user-circle"></i>
                <span id="modalUserName">Informations utilisateur</span>
            </h3>
            <button class="user-info-modal-close" onclick="closeUserInfo()" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="user-info-modal-body">
            <div class="user-info-avatar-section">
                <div class="user-info-avatar-large" id="modalUserAvatar">
                    U
                </div>
                <div class="user-info-name" id="modalUserFullName">Nom utilisateur</div>
                <div class="user-info-badges" id="modalUserBadges">
                    <!-- Badges seront ajoutés dynamiquement -->
                </div>
            </div>
            <div class="user-info-grid" id="modalUserInfo">
                <!-- Informations seront ajoutées dynamiquement -->
            </div>
        </div>
    </div>
</div>

<script>
// Données des utilisateurs pour le modal
const usersData = {
    @foreach($users as $user)
    {{ $user->id }}: {
        id: {{ $user->id }},
        name: @json($user->name),
        email: @json($user->email),
        phone: @json($user->phone ?? 'Non renseigné'),
        role: @json($user->role),
        is_active: {{ $user->is_active ? 'true' : 'false' }},
        created_at: @json($user->created_at->format('d/m/Y à H:i')),
        updated_at: @json($user->updated_at->format('d/m/Y à H:i')),
        member_since: @json($user->created_at->diffForHumans()),
    },
    @endforeach
};

function showUserInfo(userId) {
    const user = usersData[userId];
    if (!user) {
        console.error('Utilisateur non trouvé:', userId);
        return;
    }
    
    // Mettre à jour l'avatar
    const avatar = document.getElementById('modalUserAvatar');
    if (avatar) {
        avatar.textContent = user.name.charAt(0).toUpperCase();
    }
    
    // Mettre à jour le nom
    const fullName = document.getElementById('modalUserFullName');
    if (fullName) {
        fullName.textContent = user.name;
    }
    
    // Mettre à jour les badges
    const badgesContainer = document.getElementById('modalUserBadges');
    if (badgesContainer) {
        badgesContainer.innerHTML = `
            <span class="px-3 py-1 ${user.role === 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400'} rounded-full text-sm font-semibold">
                <i class="fas ${user.role === 'admin' ? 'fa-shield-alt' : 'fa-user'} mr-1"></i>${user.role === 'admin' ? 'Administrateur' : 'Utilisateur'}
            </span>
            <span class="px-3 py-1 ${user.is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'} rounded-full text-sm font-semibold">
                <i class="fas ${user.is_active ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>${user.is_active ? 'Actif' : 'Inactif'}
            </span>
        `;
    }
    
    // Mettre à jour les informations
    const infoContainer = document.getElementById('modalUserInfo');
    if (infoContainer) {
        infoContainer.innerHTML = `
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-envelope"></i>
                    <span>Email</span>
                </div>
                <div class="user-info-item-value">${user.email}</div>
            </div>
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-phone"></i>
                    <span>Téléphone</span>
                </div>
                <div class="user-info-item-value">${user.phone}</div>
            </div>
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Date d'inscription</span>
                </div>
                <div class="user-info-item-value">${user.created_at}</div>
            </div>
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-calendar-edit"></i>
                    <span>Dernière modification</span>
                </div>
                <div class="user-info-item-value">${user.updated_at}</div>
            </div>
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-clock"></i>
                    <span>Membre depuis</span>
                </div>
                <div class="user-info-item-value">${user.member_since}</div>
            </div>
            <div class="user-info-item">
                <div class="user-info-item-label">
                    <i class="fas fa-id-card"></i>
                    <span>ID Utilisateur</span>
                </div>
                <div class="user-info-item-value">#${user.id}</div>
            </div>
        `;
    }
    
    // Afficher le modal
    const modal = document.getElementById('userInfoModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeUserInfo() {
    const modal = document.getElementById('userInfoModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Fermer le modal en cliquant en dehors
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('userInfoModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeUserInfo();
            }
        });
    }
    
    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUserInfo();
        }
    });
});
</script>
@endsection
