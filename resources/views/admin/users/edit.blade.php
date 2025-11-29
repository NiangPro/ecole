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
</style>
@endsection

@section('content')
<div class="users-edit-page">
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.users') }}" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-lg flex items-center justify-center transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h3 class="text-3xl font-bold">Modifier l'utilisateur</h3>
</div>

<div class="content-section max-w-3xl">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-user mr-2"></i>Nom complet *
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       class="input-admin" placeholder="John Doe" required>
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-envelope mr-2"></i>Email *
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="input-admin" placeholder="john@example.com" required>
                @error('email')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-lock mr-2"></i>Nouveau mot de passe
                </label>
                <input type="password" name="password" 
                       class="input-admin" placeholder="Laisser vide pour ne pas changer">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Laissez vide si vous ne voulez pas changer le mot de passe
                </p>
                @error('password')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-phone mr-2"></i>Téléphone
                </label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="input-admin" placeholder="+221 77 123 45 67">
                @error('phone')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">
                <i class="fas fa-shield-alt mr-2"></i>Rôle *
            </label>
            <select name="role" class="input-admin" required>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
            @error('role')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                <input type="checkbox" name="is_active" class="w-5 h-5" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <div>
                    <span class="font-semibold">Compte actif</span>
                    <p class="text-sm text-gray-400">L'utilisateur pourra se connecter</p>
                </div>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Enregistrer les modifications
            </button>
            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Annuler
            </a>
        </div>
    </form>
</div>

<!-- Informations supplémentaires -->
<div class="content-section max-w-3xl mt-6">
    <h4 class="text-xl font-bold mb-4">Informations du compte</h4>
    <div class="grid md:grid-cols-2 gap-4">
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Date d'inscription</p>
            <p class="font-mono text-cyan-400">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="p-4 bg-black/30 rounded-lg">
            <p class="text-gray-400 text-sm mb-2">Dernière modification</p>
            <p class="font-mono text-cyan-400">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
        </div>
        </div>
    </div>
</div>
@endsection
