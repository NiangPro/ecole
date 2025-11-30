@extends('admin.layout')

@section('title', 'Nouvel Utilisateur')

@section('styles')
<style>
    /* Fonts chargées via preload dans admin.layout - pas de @import bloquant */
    
    .user-form-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .form-hero::before {
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
    
    .form-hero-content {
        position: relative;
        z-index: 1;
    }
    
    .form-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .form-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    .form-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .form-card:hover::before {
        left: 100%;
    }
    
    .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
        transform: translateY(-5px);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin: 0;
    }
    
    .field-group {
        margin-bottom: 25px;
    }
    
    .field-label {
        display: block;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .field-label .required {
        color: #ef4444;
        font-size: 1.2rem;
    }
    
    .field-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .field-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    .field-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }
    
    .field-select {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        padding-right: 40px;
    }
    
    .field-select:focus {
        outline: none;
        border-color: #06b6d4;
        background-color: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }
    
    .field-select option {
        background: #0a0a0f;
        color: #fff;
        padding: 12px;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: rgba(10, 10, 26, 0.6);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .checkbox-group:hover {
        border-color: rgba(6, 182, 212, 0.4);
        background: rgba(10, 10, 26, 0.8);
    }
    
    .checkbox-input {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        accent-color: #06b6d4;
    }
    
    .checkbox-input:checked {
        background: #06b6d4;
        border-color: #06b6d4;
    }
    
    .checkbox-label {
        flex: 1;
    }
    
    .checkbox-title {
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        font-size: 1rem;
    }
    
    .checkbox-desc {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .error-message i {
        font-size: 0.8rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
    }
    
    .btn-save {
        flex: 1;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        color: #000;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        font-family: 'Inter', sans-serif;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.5);
    }
    
    .btn-cancel {
        padding: 0.7rem 1.5rem;
        background: rgba(100, 100, 100, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Inter', sans-serif;
    }
    
    .btn-cancel:hover {
        background: rgba(100, 100, 100, 0.3);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-hero {
            padding: 35px;
        }
        
        .form-hero h1 {
            font-size: 2.2rem;
        }
    }
    
    @media (max-width: 768px) {
        .form-hero {
            padding: 25px;
        }
        
        .form-hero h1 {
            font-size: 1.8rem;
        }
        
        .form-card {
            padding: 25px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="user-form-wrapper">
    <!-- Hero Section -->
    <div class="form-hero">
        <div class="form-hero-content">
            <h1><i class="fas fa-user-plus mr-3"></i>Nouvel Utilisateur</h1>
            <p>Créez un nouveau compte utilisateur pour accéder à la plateforme</p>
        </div>
    </div>
    
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            <!-- Informations de base -->
            <div class="form-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="card-title">Informations Personnelles</h2>
                </div>
                
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        <span>Nom complet</span>
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="field-input" placeholder="John Doe" required>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                        <span class="required">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="field-input" placeholder="john@example.com" required>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        <span>Téléphone</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="field-input" placeholder="+221 77 123 45 67">
                    @error('phone')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <!-- Paramètres du compte -->
            <div class="form-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h2 class="card-title">Paramètres du Compte</h2>
                </div>
                
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-lock"></i>
                        <span>Mot de passe</span>
                        <span class="required">*</span>
                    </label>
                    <input type="password" name="password" 
                           class="field-input" placeholder="••••••••" required>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="field-group">
                    <label class="field-label">
                        <i class="fas fa-shield-alt"></i>
                        <span>Rôle</span>
                        <span class="required">*</span>
                    </label>
                    <select name="role" class="field-select" required>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="field-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_active" value="1" class="checkbox-input" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="checkbox-label">
                            <div class="checkbox-title">Compte actif</div>
                            <div class="checkbox-desc">L'utilisateur pourra se connecter à la plateforme</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="form-card">
            <div class="action-buttons" style="margin-top: 0; padding-top: 0; border-top: none;">
                <a href="{{ route('admin.users') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    Créer l'utilisateur
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
