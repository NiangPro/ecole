@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page Users Edit */
    .users-edit-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-edit-page h3 {
        color: #1e293b;
    }
    
    .users-edit-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-edit-page h4 {
        color: #1e293b;
    }
    
    .users-edit-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-edit-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .users-edit-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-edit-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .users-edit-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .users-edit-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    .users-edit-page .bg-gray-700 {
        background: rgba(55, 65, 81, 1);
        transition: background 0.3s ease;
    }
    
    body.light-mode .users-edit-page .bg-gray-700 {
        background: rgba(241, 245, 249, 1);
    }
    
    .users-edit-page .bg-black\/30 {
        background: rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease;
    }
    
    body.light-mode .users-edit-page .bg-black\/30 {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .users-edit-page .hover\:bg-black\/40:hover {
        background: rgba(0, 0, 0, 0.4);
    }
    
    body.light-mode .users-edit-page .hover\:bg-black\/40:hover {
        background: rgba(255, 255, 255, 1);
    }
    
    .users-edit-page .text-cyan-400 {
        color: #06b6d4;
    }
    
    .users-edit-page .text-red-400 {
        color: #f87171;
    }
    
    .user-header-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    
    .form-section {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    body.light-mode .form-section {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .form-section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .form-section-title i {
        color: #06b6d4;
        font-size: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .form-label {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .form-label i {
        color: #06b6d4;
        width: 20px;
        text-align: center;
    }
    
    .info-card {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .info-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #06b6d4;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .info-card-content {
        flex: 1;
    }
    
    .info-card-label {
        font-size: 0.875rem;
        color: rgba(156, 163, 175, 1);
        margin-bottom: 0.25rem;
    }
    
    .info-card-value {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(209, 213, 219, 1);
    }
    
    body.light-mode .info-card-value {
        color: rgba(30, 41, 59, 0.9);
    }
</style>
@endsection

@section('content')
<div class="users-edit-page">
    <!-- Header avec bouton retour -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.users') }}" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-lg flex items-center justify-center transition text-gray-300 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h3 class="text-3xl font-bold mb-1">Modifier l'utilisateur</h3>
            <p class="text-gray-400">Gérez les informations et les paramètres de l'utilisateur</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Carte d'en-tête avec avatar -->
    <div class="user-header-card">
        <div class="flex items-center gap-6">
            <div class="user-avatar-large">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h4 class="text-2xl font-bold mb-2">{{ $user->name }}</h4>
                <p class="text-gray-400 mb-1">
                    <i class="fas fa-envelope mr-2"></i>{{ $user->email }}
                </p>
                <div class="flex items-center gap-4 mt-3">
                    @if($user->role == 'admin')
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm font-semibold">
                            <i class="fas fa-shield-alt mr-1"></i>Administrateur
                        </span>
                    @else
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm font-semibold">
                            <i class="fas fa-user mr-1"></i>Utilisateur
                        </span>
                    @endif
                    @if($user->is_active)
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Actif
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm font-semibold">
                            <i class="fas fa-times-circle mr-1"></i>Inactif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du compte -->
    <div class="form-section">
        <div class="form-section-title">
            <i class="fas fa-info-circle"></i>
            <span>Informations du compte</span>
        </div>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="info-card-content">
                    <p class="info-card-label">Date d'inscription</p>
                    <p class="info-card-value">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="info-card-content">
                    <p class="info-card-label">Dernière modification</p>
                    <p class="info-card-value">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Informations personnelles -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fas fa-user-edit"></i>
                <span>Informations personnelles</span>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        <span>Nom complet <span class="text-red-400">*</span></span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="input-admin w-full" placeholder="John Doe" required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        <span>Email <span class="text-red-400">*</span></span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="input-admin w-full" placeholder="john@example.com" required>
                    @error('email')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone"></i>
                        <span>Téléphone</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="input-admin w-full" placeholder="+221 77 123 45 67">
                    @error('phone')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        <span>Nouveau mot de passe</span>
                    </label>
                    <input type="password" name="password" 
                           class="input-admin w-full" placeholder="Laisser vide pour ne pas changer">
                    <p class="text-gray-500 text-sm mt-2 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        Laissez vide si vous ne voulez pas changer le mot de passe
                    </p>
                    @error('password')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Paramètres du compte -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fas fa-cog"></i>
                <span>Paramètres du compte</span>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-shield-alt"></i>
                        <span>Rôle <span class="text-red-400">*</span></span>
                    </label>
                    <select name="role" class="input-admin w-full" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i>
                        <span>Statut du compte</span>
                    </label>
                    <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 accent-cyan-500" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="font-semibold block">Compte actif</span>
                            <p class="text-sm text-gray-400">L'utilisateur pourra se connecter au système</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="flex gap-4 mb-6">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Enregistrer les modifications</span>
            </button>
            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition flex items-center gap-2">
                <i class="fas fa-times"></i>
                <span>Annuler</span>
            </a>
        </div>
    </form>
</div>
@endsection
