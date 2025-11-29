@extends('admin.layout')

@section('styles')
<style>
    /* Styles pour la page Profile */
    .profile-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page h3 {
        color: #1e293b;
    }
    
    .profile-page p.text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page p.text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    /* Labels */
    .profile-page label {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page label {
        color: rgba(30, 41, 59, 0.9);
    }
    
    /* Cards avec bg-black/30 */
    .profile-card {
        background: rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease;
    }
    
    body.light-mode .profile-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    /* Bordures */
    .profile-page .border-gray-700 {
        border-color: rgba(55, 65, 81, 1);
        transition: border-color 0.3s ease;
    }
    
    body.light-mode .profile-page .border-gray-700 {
        border-color: rgba(226, 232, 240, 1);
    }
    
    /* Textes dans les cards */
    .profile-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .profile-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .profile-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    /* Font semibold */
    .profile-page .font-semibold {
        color: rgba(255, 255, 255, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page .font-semibold {
        color: rgba(30, 41, 59, 0.9);
    }
    
    /* Warning box */
    .profile-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.1);
        border-color: rgba(234, 179, 8, 0.3);
        transition: all 0.3s ease;
    }
    
    body.light-mode .profile-page .bg-yellow-500\/10 {
        background: rgba(234, 179, 8, 0.1);
        border-color: rgba(234, 179, 8, 0.3);
    }
    
    .profile-page .text-yellow-400 {
        color: rgba(250, 204, 21, 1);
    }
    
    body.light-mode .profile-page .text-yellow-400 {
        color: rgba(202, 138, 4, 1);
    }
    
    /* Bouton Annuler */
    .profile-page .bg-gray-600 {
        background: rgba(75, 85, 99, 1);
        transition: background 0.3s ease;
    }
    
    .profile-page .bg-gray-600:hover {
        background: rgba(55, 65, 81, 1);
    }
    
    body.light-mode .profile-page .bg-gray-600 {
        background: rgba(226, 232, 240, 1);
        color: rgba(30, 41, 59, 1);
    }
    
    body.light-mode .profile-page .bg-gray-600:hover {
        background: rgba(203, 213, 225, 1);
    }
    
    /* Titres h4 */
    .profile-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .profile-page h4 {
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="profile-page">
<div class="flex items-center gap-4 mb-8">
    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center text-2xl font-bold">
        <i class="fas fa-user-shield"></i>
    </div>
    <div>
        <h3 class="text-3xl font-bold">Mon Profil</h3>
        <p class="text-gray-400">Gérez vos informations personnelles</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Sidebar Profil -->
    <div class="lg:col-span-1">
        <div class="content-section">
            <div class="text-center">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center text-5xl font-bold mb-4">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4 class="text-xl font-bold mb-2">{{ $admin->name }}</h4>
                <p class="text-gray-400 mb-4">{{ $admin->email }}</p>
                <span class="px-4 py-2 bg-purple-500/20 text-purple-400 rounded-full text-sm font-semibold">
                    <i class="fas fa-shield-alt mr-2"></i>Administrateur
                </span>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-700">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 profile-card rounded-lg">
                        <span class="text-gray-400">Rôle</span>
                        <span class="font-semibold text-cyan-400">Admin</span>
                    </div>
                    <div class="flex items-center justify-between p-3 profile-card rounded-lg">
                        <span class="text-gray-400">Statut</span>
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Actif
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 profile-card rounded-lg">
                        <span class="text-gray-400">Connexion</span>
                        <span class="font-semibold">Aujourd'hui</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="content-section mt-6">
            <h4 class="text-lg font-bold mb-4">Statistiques</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 profile-card rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users text-cyan-400 text-xl"></i>
                        <span class="text-gray-400">Utilisateurs</span>
                    </div>
                    <span class="font-bold text-cyan-400">{{ \App\Models\User::count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 profile-card rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-eye text-purple-400 text-xl"></i>
                        <span class="text-gray-400">Visites (mois)</span>
                    </div>
                    <span class="font-bold text-purple-400">{{ number_format(\App\Models\Statistic::whereMonth('visit_date', \Carbon\Carbon::now()->month)->count()) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulaire de modification -->
    <div class="lg:col-span-2">
        <!-- Informations personnelles -->
        <div class="content-section mb-6">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-user-edit text-cyan-400"></i>
                Informations personnelles
            </h4>
            
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">
                            <i class="fas fa-user mr-2"></i>Nom complet *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" 
                               class="input-admin" required>
                        @error('name')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">
                            <i class="fas fa-envelope mr-2"></i>Email *
                        </label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" 
                               class="input-admin" required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Changer le mot de passe -->
        <div class="content-section">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-lock text-purple-400"></i>
                Sécurité du compte
            </h4>
            
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                
                <input type="hidden" name="name" value="{{ $admin->name }}">
                <input type="hidden" name="email" value="{{ $admin->email }}">
                
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-key mr-2"></i>Mot de passe actuel *
                    </label>
                    <input type="password" name="current_password" 
                           class="input-admin" placeholder="••••••••">
                    @error('current_password')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Requis uniquement si vous changez votre mot de passe
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">
                            <i class="fas fa-lock mr-2"></i>Nouveau mot de passe
                        </label>
                        <input type="password" name="new_password" 
                               class="input-admin" placeholder="••••••••">
                        @error('new_password')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-2">Minimum 6 caractères</p>
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-semibold">
                            <i class="fas fa-lock mr-2"></i>Confirmer le mot de passe
                        </label>
                        <input type="password" name="new_password_confirmation" 
                               class="input-admin" placeholder="••••••••">
                    </div>
                </div>
                
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mt-1"></i>
                        <div>
                            <p class="font-semibold text-yellow-400 mb-2">Attention</p>
                            <p class="text-sm text-gray-300">
                                Après avoir changé votre mot de passe, vous devrez vous reconnecter avec le nouveau mot de passe.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-shield-alt mr-2"></i>Mettre à jour le mot de passe
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Activité récente -->
        <div class="content-section mt-6">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-history text-green-400"></i>
                Activité récente
            </h4>
            
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 profile-card rounded-lg">
                    <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-sign-in-alt text-cyan-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">Connexion au dashboard</p>
                        <p class="text-sm text-gray-400">Aujourd'hui à {{ date('H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 p-4 profile-card rounded-lg">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-purple-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">Modification des paramètres</p>
                        <p class="text-sm text-gray-400">Il y a 2 heures</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 p-4 profile-card rounded-lg">
                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">Création d'un utilisateur</p>
                        <p class="text-sm text-gray-400">Hier à 15:30</p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
