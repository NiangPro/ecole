@extends('admin.layout')

@section('title', 'Gestion des Utilisateurs - Admin')

@section('content')
<div class="users-admin">
    <!-- Header Section -->
    <div class="users-header">
        <div class="users-header-content">
            <div class="users-header-text">
                <h1 class="users-title">
                    <span class="users-icon-wrapper">
                        <i class="fas fa-users users-icon"></i>
                    </span>
                    Gestion des Utilisateurs
                </h1>
                <p class="users-subtitle">
                    Gérez tous les utilisateurs de la plateforme
                </p>
            </div>
            <div class="users-header-actions">
                <a href="{{ route('admin.users.create') }}" class="header-btn header-btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    <span>Ajouter un utilisateur</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="users-stats">
        <div class="stat-card stat-total">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card stat-active">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['active']) }}</div>
                <div class="stat-label">Actifs</div>
            </div>
        </div>
        <div class="stat-card stat-inactive">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['inactive']) }}</div>
                <div class="stat-label">Inactifs</div>
            </div>
        </div>
        <div class="stat-card stat-admins">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['admins']) }}</div>
                <div class="stat-label">Administrateurs</div>
            </div>
        </div>
        <div class="stat-card stat-users">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['users']) }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <span>Filtres de recherche</span>
        </div>
        <form method="GET" action="{{ route('admin.users') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Recherche
                    </label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nom, email, téléphone..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-user-tag"></i>
                        Rôle
                    </label>
                    <select name="role" class="filter-select">
                        <option value="">Tous les rôles</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ $role == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-toggle-on"></i>
                        Statut
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Trier par
                    </label>
                    <select name="sort" class="filter-select">
                        <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                        <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="email" {{ $sortBy == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort-amount-down"></i>
                        Ordre
                    </label>
                    <select name="order" class="filter-select">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-btn-primary">
                        <i class="fas fa-search"></i>
                        <span>Rechercher</span>
                    </button>
                    @if($search || $role || $status)
                    <a href="{{ route('admin.users') }}" class="filter-btn filter-btn-secondary">
                        <i class="fas fa-redo"></i>
                        <span>Réinitialiser</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    @if($users->count() > 0)
    <div class="users-table-wrapper">
        <div class="table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-user"></i>
                                <span>Nom</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-envelope"></i>
                                <span>Email</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-user-tag"></i>
                                <span>Rôle</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-toggle-on"></i>
                                <span>Statut</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-calendar"></i>
                                <span>Inscrit le</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header-cell">
                                <i class="fas fa-cog"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="table-row user-row user-{{ $user->is_active ? 'active' : 'inactive' }}">
                        <td>
                            <div class="table-cell-content">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->name }}</div>
                                        @if($user->phone)
                                        <div class="user-phone">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ $user->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="user-email-cell">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                @if($user->role == 'admin')
                                    <span class="role-badge role-admin">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Admin</span>
                                    </span>
                                @else
                                    <span class="role-badge role-user">
                                        <i class="fas fa-user"></i>
                                        <span>Utilisateur</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                @if($user->is_active)
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Actif</span>
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Inactif</span>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="date-cell">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="time-cell">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $user->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-cell-content">
                                <div class="table-actions">
                                    <button onclick="showUserInfo({{ $user->id }})" class="table-action-btn action-view" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="table-action-btn action-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->role != 'admin')
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="table-action-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action-btn action-delete" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="empty-state-title">Aucun utilisateur</h3>
        <p class="empty-state-text">
            @if($search || $role || $status)
                Aucun utilisateur ne correspond à vos critères de recherche.
            @else
                Aucun utilisateur n'a été enregistré pour le moment.
            @endif
        </p>
        <a href="{{ route('admin.users.create') }}" class="empty-state-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Créer un Utilisateur</span>
        </a>
    </div>
    @endif
</div>

<!-- Modal d'informations utilisateur -->
<div id="userInfoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-user-circle"></i>
                <span id="modalUserName">Informations utilisateur</span>
            </h3>
            <button class="modal-close" onclick="closeUserInfo()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
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
            <span class="info-badge ${user.role === 'admin' ? 'role-admin' : 'role-user'}">
                <i class="fas ${user.role === 'admin' ? 'fa-shield-alt' : 'fa-user'}"></i>
                <span>${user.role === 'admin' ? 'Administrateur' : 'Utilisateur'}</span>
            </span>
            <span class="info-badge ${user.is_active ? 'status-active' : 'status-inactive'}">
                <i class="fas ${user.is_active ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                <span>${user.is_active ? 'Actif' : 'Inactif'}</span>
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

<style>
.users-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.users-header {
    margin-bottom: 2rem;
}

.users-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .users-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.users-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.users-header-text {
    position: relative;
    z-index: 1;
}

.users-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.users-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.users-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.users-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .users-subtitle {
    color: #64748b;
}

.users-header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.header-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.header-btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.header-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Stats */
.users-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

body.light-mode .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.stat-total::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-active::before {
    background: linear-gradient(180deg, #10b981, #059669);
}

.stat-inactive::before {
    background: linear-gradient(180deg, #ef4444, #dc2626);
}

.stat-admins::before {
    background: linear-gradient(180deg, #3b82f6, #2563eb);
}

.stat-users::before {
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
}

.stat-icon-wrapper {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-active .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-inactive .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
    color: #ef4444;
}

.stat-admins .stat-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
}

.stat-users .stat-icon {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 0.25rem;
}

body.light-mode .stat-value {
    color: #1e293b;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.25rem;
}

body.light-mode .stat-label {
    color: #64748b;
}

/* Filters */
.filters-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

body.light-mode .filters-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
}

.filters-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 700;
    font-size: 1.1rem;
}

body.light-mode .filters-header {
    color: #1e293b;
}

.filters-form {
    display: block;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

body.light-mode .filter-label {
    color: #475569;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

body.light-mode .filter-input,
body.light-mode .filter-select {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

body.light-mode .filter-input:focus,
body.light-mode .filter-select:focus {
    background: rgba(255, 255, 255, 1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.filter-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.filter-btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .filter-btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.filter-btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

/* Users Table */
.users-table-wrapper {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    overflow: visible;
}

body.light-mode .users-table-wrapper {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.table-container {
    overflow-x: auto;
    overflow-y: visible;
}

/* Scrollbar moderne */
.table-container::-webkit-scrollbar {
    height: 12px;
}

.table-container::-webkit-scrollbar-track {
    background: rgba(30, 41, 59, 0.5);
    border-radius: 10px;
    margin: 0 2rem;
}

body.light-mode .table-container::-webkit-scrollbar-track {
    background: rgba(241, 245, 249, 0.8);
}

.table-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 10px;
    border: 2px solid rgba(30, 41, 59, 0.5);
    transition: all 0.3s ease;
}

body.light-mode .table-container::-webkit-scrollbar-thumb {
    border-color: rgba(241, 245, 249, 0.8);
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #14b8a6, #06b6d4);
    box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
}

/* Firefox */
.table-container {
    scrollbar-width: thin;
    scrollbar-color: #06b6d4 rgba(30, 41, 59, 0.5);
}

body.light-mode .table-container {
    scrollbar-color: #06b6d4 rgba(241, 245, 249, 0.8);
}

.users-table {
    width: 100%;
    min-width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: auto;
}

.users-table thead {
    background: rgba(6, 182, 212, 0.1);
}

body.light-mode .users-table thead {
    background: rgba(6, 182, 212, 0.05);
}

.users-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    border-bottom: 2px solid rgba(6, 182, 212, 0.3);
    white-space: nowrap;
}

body.light-mode .users-table th {
    color: #1e293b;
}

.users-table th:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
}

.table-header-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-header-cell i {
    color: #06b6d4;
    font-size: 1rem;
}

.users-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.users-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
    transform: scale(1.01);
}

body.light-mode .users-table tbody tr:hover {
    background: rgba(6, 182, 212, 0.03);
}

.user-row.user-active {
    border-left: 4px solid #10b981;
}

.user-row.user-inactive {
    border-left: 4px solid #ef4444;
    opacity: 0.85;
}

.users-table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    white-space: nowrap;
}

.users-table td:last-child {
    white-space: normal;
    min-width: fit-content;
    width: auto;
}

.table-cell-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: #06b6d4;
    border: 2px solid rgba(6, 182, 212, 0.3);
    flex-shrink: 0;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 700;
    font-size: 1rem;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .user-name {
    color: #1e293b;
}

.user-phone {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .user-phone {
    color: #64748b;
}

.user-phone i {
    color: #06b6d4;
    font-size: 0.8rem;
}

.user-email-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: white;
}

body.light-mode .user-email-cell {
    color: #1e293b;
}

.user-email-cell i {
    color: #06b6d4;
    font-size: 0.9rem;
}

/* Role Badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
}

.role-admin {
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.4);
    color: #3b82f6;
}

.role-user {
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    color: #06b6d4;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

/* Date */
.date-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

body.light-mode .date-cell {
    color: #1e293b;
}

.date-cell i {
    color: #06b6d4;
    font-size: 0.9rem;
}

.time-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .time-cell {
    color: #64748b;
}

.time-cell i {
    color: #06b6d4;
    font-size: 0.8rem;
}

/* Table Actions */
.table-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: nowrap;
    justify-content: flex-start;
    min-width: fit-content;
}

.table-action-form {
    display: inline-flex;
    flex-shrink: 0;
}

.table-action-btn {
    min-width: 36px;
    width: auto;
    height: 36px;
    padding: 0 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: none;
    border-radius: 10px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    white-space: nowrap;
}

.action-view {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.action-view:hover {
    background: rgba(59, 130, 246, 0.25);
    transform: translateY(-2px);
}

.action-edit {
    background: rgba(6, 182, 212, 0.15);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #06b6d4;
}

.action-edit:hover {
    background: rgba(6, 182, 212, 0.25);
    transform: translateY(-2px);
}

.action-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(51, 65, 85, 0.6));
    border: 2px dashed rgba(6, 182, 212, 0.3);
    border-radius: 24px;
}

body.light-mode .empty-state {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(248, 250, 252, 0.8));
    border-color: rgba(6, 182, 212, 0.4);
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: rgba(6, 182, 212, 0.5);
    border: 3px dashed rgba(6, 182, 212, 0.3);
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .empty-state-title {
    color: #1e293b;
}

.empty-state-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

body.light-mode .empty-state-text {
    color: #64748b;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
}

.empty-state-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modal.active {
    display: flex;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-content {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 0;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

body.light-mode .modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
    border-color: rgba(6, 182, 212, 0.4);
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 24px 24px 0 0;
}

.modal-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

body.light-mode .modal-title {
    color: #1e293b;
}

.modal-title i {
    color: #06b6d4;
}

.modal-close {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.2s;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

body.light-mode .modal-close {
    color: #64748b;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transform: rotate(90deg);
}

body.light-mode .modal-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #1e293b;
}

.modal-body {
    padding: 2rem;
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
    color: white;
    margin-bottom: 1rem;
}

body.light-mode .user-info-name {
    color: #1e293b;
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

body.light-mode .user-info-item {
    background: rgba(6, 182, 212, 0.05);
}

.user-info-item:hover {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(6, 182, 212, 0.2);
}

.user-info-item-label {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

body.light-mode .user-info-item-label {
    color: #64748b;
}

.user-info-item-label i {
    color: #06b6d4;
    width: 20px;
    text-align: center;
}

.user-info-item-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
}

body.light-mode .user-info-item-value {
    color: #1e293b;
}

.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 10px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.8), rgba(20, 184, 166, 0.8));
    border-radius: 10px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, rgba(6, 182, 212, 1), rgba(20, 184, 166, 1));
}

/* Responsive */
@media (max-width: 768px) {
    .users-title {
        font-size: 1.75rem;
    }
    
    .users-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .users-icon {
        font-size: 1.5rem;
    }
    
    .users-header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .header-btn {
        width: 100%;
        justify-content: center;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .filter-btn {
        width: 100%;
        justify-content: center;
    }
    
    .users-table-wrapper {
        padding: 1rem;
    }
    
    .users-table {
        font-size: 0.85rem;
    }
    
    .users-table th,
    .users-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .user-avatar {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .user-name {
        font-size: 0.9rem;
    }
    
    .table-actions {
        flex-wrap: wrap;
    }
    
    .table-action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>
@endsection
