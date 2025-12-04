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
                    <label for="name" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
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
                    <label for="email" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
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
                    <label for="phone" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
                        {{ trans('app.profile.dashboard.profile.phone') }}
                    </label>
                    <div style="width: 100%;">
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            class="form-input"
                            style="width: 100%; padding: 0.875rem 1rem; background: #ffffff; border: 1px solid rgba(4, 170, 109, 0.2); border-radius: 8px; color: #2c3e50; font-size: 0.95rem; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='#04AA6D'; this.style.boxShadow='0 0 0 3px rgba(4, 170, 109, 0.1)';"
                            onblur="this.style.borderColor='rgba(4, 170, 109, 0.2)'; this.style.boxShadow='none';"
                        >
                    </div>
                    <input type="hidden" id="phone_country" name="phone_country" value="">
                    @error('phone')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;padding-left: 30px;">{{ $message }}</div>
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
                    <label for="current_password" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
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
                    <label for="password" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
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
                    <label for="password_confirmation" class="dashboard-text-primary form-label" style="display: block; color: #2c3e50; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; text-align: left;">
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

<!-- Intl Tel Input CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css">

<!-- Intl Tel Input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js"></script>

<script>
// Initialiser intl-tel-input pour le champ téléphone
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput && window.intlTelInput) {
        // Sauvegarder la valeur existante
        const existingPhone = phoneInput.value;
        
        // Déterminer le pays initial basé sur le numéro existant
        let initialCountry = 'sn';
        if (existingPhone) {
            if (existingPhone.startsWith('+221')) initialCountry = 'sn';
            else if (existingPhone.startsWith('+33')) initialCountry = 'fr';
            else if (existingPhone.startsWith('+1')) initialCountry = 'us';
            else if (existingPhone.startsWith('+44')) initialCountry = 'gb';
        }
        
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: initialCountry,
            preferredCountries: ['sn', 'fr', 'us', 'gb', 'de', 'es', 'it', 'ma', 'ci', 'cm', 'bf', 'ml', 'ne', 'td', 'mr'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js",
            separateDialCode: true,
            nationalMode: false,
            autoPlaceholder: "aggressive",
            formatOnDisplay: true,
        });
        
        // Si une valeur existe, la formater correctement
        if (existingPhone) {
            // Attendre que l'initialisation soit complète
            setTimeout(function() {
                try {
                    iti.setNumber(existingPhone);
                } catch(e) {
                    // Si le formatage échoue, garder la valeur originale
                    console.log('Erreur formatage téléphone:', e);
                }
            }, 100);
        }
        
        // Mettre à jour le champ hidden avec le pays sélectionné
        phoneInput.addEventListener('countrychange', function() {
            const countryField = document.getElementById('phone_country');
            if (countryField) {
                countryField.value = iti.getSelectedCountryData().iso2;
            }
        });
        
        // Mettre à jour le champ hidden au chargement
        const countryField = document.getElementById('phone_country');
        if (countryField) {
            countryField.value = iti.getSelectedCountryData().iso2;
        }
        
        // Valider le numéro avant la soumission
        const form = phoneInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (phoneInput.value.trim()) {
                    if (!iti.isValidNumber()) {
                        e.preventDefault();
                        alert('{{ trans('app.profile.dashboard.profile.invalid_phone') ?? 'Numéro de téléphone invalide. Veuillez vérifier le format.' }}');
                        phoneInput.focus();
                        return false;
                    }
                    // Mettre à jour le champ avec le numéro formaté international
                    phoneInput.value = iti.getNumber();
                }
            });
        }
    }
});

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

<!-- Intl Tel Input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js"></script>

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
    
    /* Styles pour intl-tel-input */
    .iti {
        width: 100%;
        display: flex !important;
        align-items: center !important;
    }
    
    .iti__flag-container {
        z-index: 10;
        flex-shrink: 0;
        width: auto !important;
        min-width: auto !important;
        max-width: 80px !important;
    }
    
    .iti__selected-flag {
        padding: 0 6px !important;
        border-radius: 8px 0 0 8px;
        background: rgba(255, 255, 255, 0.9);
        display: flex !important;
        align-items: center !important;
        width: auto !important;
        min-width: auto !important;
        max-width: 75px !important;
    }
    
    body.dark-mode .iti__selected-flag {
        background: rgba(15, 23, 42, 0.8) !important;
    }
    
    /* Réduire la largeur du drapeau */
    .iti__flag {
        width: 18px !important;
        height: 14px !important;
        margin-right: 3px !important;
    }
    
    /* Réduire la largeur du code pays - comme dans contact */
    .iti__selected-dial-code {
        font-size: 0.9rem !important;
        padding: 0 4px !important;
        margin-right: 8px !important;
    }
    
    /* Réduire la largeur de la flèche dropdown */
    .iti__arrow {
        margin-left: 2px !important;
        width: 8px !important;
    }
    
    /* S'assurer que l'input a un padding à gauche pour l'espace après le drapeau - comme dans contact */
    .iti input[type="tel"],
    .iti input[type="text"],
    #phone {
        padding-left: 50px !important;
        flex: 1 !important;
        margin-left: 0 !important;
        min-width: 0 !important;
    }
    
    /* Ajouter un espace supplémentaire après le conteneur du drapeau */
    .iti__flag-container {
        margin-right: 0 !important;
    }
    
    /* Conteneur parent pour éviter les retours à la ligne */
    #phone {
        display: inline-block !important;
    }
    
    /* S'assurer que le wrapper est en ligne */
    .iti__tel-input {
        display: inline-flex !important;
        align-items: center !important;
        width: 100% !important;
    }
    
    .iti__selected-flag {
        border-right: none !important;
    }
    
    /* S'assurer que le conteneur principal est en ligne */
    .iti__tel-input {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }
    
    .iti__country-list {
        z-index: 10000;
        background: rgba(15, 23, 42, 0.98);
        border: 1px solid rgba(4, 170, 109, 0.3);
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    
    body:not(.dark-mode) .iti__country-list {
        background: rgba(255, 255, 255, 0.98) !important;
        border-color: rgba(4, 170, 109, 0.3) !important;
    }
    
    .iti__country {
        color: rgba(255, 255, 255, 0.9);
        padding: 8px 12px;
    }
    
    body:not(.dark-mode) .iti__country {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .iti__country:hover,
    .iti__country.iti__highlight {
        background: rgba(4, 170, 109, 0.2);
    }
    
    body:not(.dark-mode) .iti__country:hover,
    body:not(.dark-mode) .iti__country.iti__highlight {
        background: rgba(4, 170, 109, 0.1) !important;
    }
    
    .iti__dial-code {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body:not(.dark-mode) .iti__dial-code {
        color: rgba(30, 41, 59, 0.7) !important;
    }
</style>
@endsection
