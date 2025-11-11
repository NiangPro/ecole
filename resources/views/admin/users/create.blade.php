@extends('admin.layout')

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.users') }}" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-lg flex items-center justify-center transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h3 class="text-3xl font-bold">Ajouter un utilisateur</h3>
</div>

<div class="content-section max-w-3xl">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-user mr-2"></i>Nom complet *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="input-admin" placeholder="John Doe" required>
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-envelope mr-2"></i>Email *
                </label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="input-admin" placeholder="john@example.com" required>
                @error('email')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-lock mr-2"></i>Mot de passe *
                </label>
                <input type="password" name="password" 
                       class="input-admin" placeholder="••••••••" required>
                @error('password')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-phone mr-2"></i>Téléphone
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
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
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
            @error('role')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="flex items-center gap-3 p-4 bg-black/30 rounded-lg cursor-pointer hover:bg-black/40 transition">
                <input type="checkbox" name="is_active" class="w-5 h-5" checked>
                <div>
                    <span class="font-semibold">Compte actif</span>
                    <p class="text-sm text-gray-400">L'utilisateur pourra se connecter</p>
                </div>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Créer l'utilisateur
            </button>
            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Annuler
            </a>
        </div>
    </form>
</div>
@endsection
