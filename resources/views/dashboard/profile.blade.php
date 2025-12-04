@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.profile.title');
    $pageDescription = trans('app.profile.dashboard.profile.description');
@endphp

@if(session('success'))
<div class="content-card success-message" style="background: rgba(4, 170, 109, 0.1); border: 1px solid rgba(4, 170, 109, 0.3); margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; color: #04AA6D;">
        <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
        <span style="font-weight: 500;">{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="content-card error-message" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: start; gap: 0.75rem; color: #ef4444;">
        <i class="fas fa-exclamation-circle" style="font-size: 1.25rem; margin-top: 2px;"></i>
        <div style="flex: 1;">
            <div class="dashboard-text-primary" style="font-weight: 600; margin-bottom: 0.5rem;">{{ trans('app.common.validation_errors') ?? 'Erreurs de validation' }} :</div>
            <ul style="margin: 0; padding-left: 1.25rem; list-style: disc;">
                @foreach($errors->all() as $error)
                <li style="margin-bottom: 0.25rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<div style="max-width: 900px;">
    <!-- Informations personnelles -->
    <div class="content-card">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-user"></i>
            {{ trans('app.profile.dashboard.profile.personal_info') }}
        </h2>
        
        <form method="POST" action="{{ route('dashboard.profile.update') }}" style="display: grid; gap: 1.5rem;">
            @csrf
            
            <div style="display: grid; gap: 1.25rem;">
                <div>
                    <label for="name" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.full_name') }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                    @error('name')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.email') }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                    @error('email')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.phone') }}
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', $user->phone) }}" 
                        placeholder="+33 6 12 34 56 78"
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                    @error('phone')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="padding-top: 1rem; border-top: 1px solid rgba(4, 170, 109, 0.1);">
                <button 
                    type="submit" 
                    class="dashboard-button-primary"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(4, 170, 109, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(4, 170, 109, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(4, 170, 109, 0.3)';"
                >
                    <i class="fas fa-save"></i>
                    {{ trans('app.profile.dashboard.profile.save_changes') }}
                </button>
            </div>
        </form>
    </div>
    
    <!-- Changer le mot de passe -->
    <div class="content-card" style="margin-top: 1.5rem;">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-lock"></i>
            {{ trans('app.profile.dashboard.profile.change_password') }}
        </h2>
        
        <form method="POST" action="{{ route('dashboard.profile.update') }}" id="passwordForm" style="display: grid; gap: 1.5rem;">
            @csrf
            
            <div style="display: grid; gap: 1.25rem;">
                <div>
                    <label for="current_password" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.current_password') }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        required
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                    @error('current_password')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.new_password') }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="8"
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                    <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.8rem; margin-top: 0.25rem;">{{ trans('app.profile.dashboard.profile.min_characters') }}</div>
                    @error('password')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                        {{ trans('app.profile.dashboard.profile.confirm_password') }} <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        minlength="8"
                        class="form-input"
                        style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                        onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                    >
                </div>
            </div>
            
            <div style="padding-top: 1rem; border-top: 1px solid rgba(4, 170, 109, 0.1);">
                <button 
                    type="submit" 
                    class="dashboard-button-primary"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #04AA6D, #038f5a); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(4, 170, 109, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(4, 170, 109, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(4, 170, 109, 0.3)';"
                >
                    <i class="fas fa-key"></i>
                    {{ trans('app.profile.dashboard.profile.change_password') }}
                </button>
            </div>
        </form>
    </div>
    
    <!-- Informations du compte -->
    <div class="content-card" style="margin-top: 1.5rem;">
        <h2 class="card-title dashboard-text-primary">
            <i class="fas fa-info-circle"></i>
            {{ trans('app.profile.dashboard.profile.account_info') }}
        </h2>
        
        <div style="display: grid; gap: 1.25rem;">
            <div class="account-info-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(4, 170, 109, 0.05); border: 1px solid rgba(4, 170, 109, 0.1); border-radius: 8px;">
                <div>
                    <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ trans('app.profile.dashboard.profile.member_since') }}</div>
                    <div class="dashboard-text-primary" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem;">{{ $user->created_at->format(app()->getLocale() === 'fr' ? 'd F Y' : 'F d, Y') }}</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(4, 170, 109, 0.1); display: flex; align-items: center; justify-content: center; color: #04AA6D; font-size: 1.25rem;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            
            <div class="account-info-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(4, 170, 109, 0.05); border: 1px solid rgba(4, 170, 109, 0.1); border-radius: 8px;">
                <div>
                    <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ trans('app.profile.dashboard.profile.last_login') }}</div>
                    <div class="dashboard-text-primary" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem;">
                        {{ $user->updated_at ? $user->updated_at->diffForHumans() : trans('app.profile.dashboard.profile.never') }}
                    </div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(4, 170, 109, 0.1); display: flex; align-items: center; justify-content: center; color: #04AA6D; font-size: 1.25rem;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation du formulaire de mot de passe
const passwordForm = document.getElementById('passwordForm');
if (passwordForm) {
    passwordForm.addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        
        if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
            e.preventDefault();
            alert('{{ trans('app.profile.dashboard.profile.password_mismatch') ?? 'Les mots de passe ne correspondent pas.' }}');
            passwordConfirmation.focus();
        }
    });
}
</script>

<style>
    /* Dark Mode Styles pour la page Profile */
    body.dark-mode .dashboard-text-primary {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .dashboard-text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    body.dark-mode .form-label {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .form-input {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: rgba(4, 170, 109, 0.3) !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .form-input::placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }
    
    body.dark-mode .form-input:focus {
        border-color: #04AA6D !important;
        box-shadow: 0 0 0 3px rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .card-title {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .account-info-item {
        background: rgba(4, 170, 109, 0.1) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .account-info-item div[style*="background: rgba(4, 170, 109"] {
        background: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .dashboard-button-primary {
        box-shadow: 0 4px 6px rgba(4, 170, 109, 0.4) !important;
    }
    
    body.dark-mode .dashboard-button-primary:hover {
        box-shadow: 0 6px 12px rgba(4, 170, 109, 0.5) !important;
    }
    
    /* Styles pour les cartes en dark mode */
    body.dark-mode .content-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }
    
    body.dark-mode .content-card:hover {
        border-color: rgba(4, 170, 109, 0.3) !important;
        box-shadow: 0 8px 25px rgba(4, 170, 109, 0.15) !important;
    }
    
    body.dark-mode .success-message {
        background: rgba(4, 170, 109, 0.2) !important;
        border-color: rgba(4, 170, 109, 0.4) !important;
    }
    
    body.dark-mode .error-message {
        background: rgba(239, 68, 68, 0.2) !important;
        border-color: rgba(239, 68, 68, 0.4) !important;
    }
</style>
@endsection
