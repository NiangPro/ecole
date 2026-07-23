@extends('dashboard.layout')

@section('dashboard-content')
@php
    // S'assurer que la locale est définie
    if (!app()->getLocale()) {
        app()->setLocale(session('language', 'fr'));
    }
    $pageTitle = trans('app.profile.dashboard.profile.title');
    $pageDescription = trans('app.profile.dashboard.profile.description');
    $completion = $user->profile_completion;
@endphp

@if(session('success'))
<div class="content-card success-message" style="background: rgba(4, 170, 109, 0.1); border: 1px solid rgba(4, 170, 109, 0.3); margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; color: #04AA6D;">
        <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
        <span style="font-weight: 500;">{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="content-card error-message" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; color: #ef4444;">
        <i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>
        <span style="font-weight: 500;">{{ session('error') }}</span>
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

<div class="profile-page">

    <!-- Hero: identité + complétion du profil -->
    <div class="profile-hero">
        <div class="profile-hero-avatar">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>
        <div class="profile-hero-info">
            <h1 class="profile-hero-name">{{ $user->name }}</h1>
            <p class="profile-hero-email"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
            <div class="profile-hero-badges">
                <span class="profile-hero-badge">
                    <i class="fas fa-calendar-alt"></i>
                    {{ trans('app.profile.dashboard.profile.member_since') }}
                    {{ $user->created_at->format(app()->getLocale() === 'fr' ? 'd F Y' : 'F d, Y') }}
                </span>
                <span class="profile-hero-badge">
                    <i class="fas fa-clock"></i>
                    {{ trans('app.profile.dashboard.profile.last_login') }}
                    {{ $user->updated_at ? $user->updated_at->diffForHumans() : trans('app.profile.dashboard.profile.never') }}
                </span>
            </div>
        </div>
        <div class="profile-hero-completion">
            <div class="completion-ring" style="--pct: {{ $completion }}">
                <span class="completion-value">{{ $completion }}%</span>
            </div>
            <span class="completion-label">{{ trans('app.profile.dashboard.profile.profile_completion') }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('dashboard.profile.update') }}" class="profile-grid">
        @csrf

        <!-- Informations personnelles -->
        <div class="content-card profile-card">
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-user"></i>
                {{ trans('app.profile.dashboard.profile.personal_info') }}
            </h2>

            <div class="profile-field-group">
                <div class="profile-field-row">
                    <div class="profile-field">
                        <label for="first_name" class="dashboard-text-primary form-label">
                            {{ trans('app.profile.dashboard.profile.first_name') }} <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="form-input">
                        </div>
                        @error('first_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="last_name" class="dashboard-text-primary form-label">
                            {{ trans('app.profile.dashboard.profile.last_name') }} <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="form-input">
                        </div>
                        @error('last_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-field">
                    <label for="email" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.email') }} <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="phone" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.phone') }}
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    <input type="hidden" id="phone_country" name="phone_country" value="">
                    @error('phone')
                        <div class="field-error" style="padding-left: 30px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="occupation" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.occupation') }}
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-briefcase"></i>
                        <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $user->occupation) }}" placeholder="{{ trans('app.profile.dashboard.profile.occupation_placeholder') }}" class="form-input">
                    </div>
                    @error('occupation')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="content-card profile-card">
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-id-card"></i>
                {{ trans('app.profile.dashboard.profile.additional_info') }}
            </h2>

            <div class="profile-field-group">
                <div class="profile-field-row">
                    <div class="profile-field">
                        <label for="date_of_birth" class="dashboard-text-primary form-label">
                            {{ trans('app.profile.dashboard.profile.date_of_birth') }}
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-birthday-cake"></i>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" max="{{ now()->subDay()->format('Y-m-d') }}" class="form-input">
                        </div>
                        @error('date_of_birth')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="gender" class="dashboard-text-primary form-label">
                            {{ trans('app.profile.dashboard.profile.gender') }}
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-venus-mars"></i>
                            <select id="gender" name="gender" class="form-input">
                                <option value="">{{ trans('app.profile.dashboard.profile.gender_select') }}</option>
                                <option value="male" @selected(old('gender', $user->gender) === 'male')>{{ trans('app.profile.dashboard.profile.gender_male') }}</option>
                                <option value="female" @selected(old('gender', $user->gender) === 'female')>{{ trans('app.profile.dashboard.profile.gender_female') }}</option>
                                <option value="other" @selected(old('gender', $user->gender) === 'other')>{{ trans('app.profile.dashboard.profile.gender_other') }}</option>
                            </select>
                        </div>
                        @error('gender')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-field">
                    <label for="bio" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.bio') }}
                    </label>
                    <textarea id="bio" name="bio" rows="5" maxlength="1000" placeholder="{{ trans('app.profile.dashboard.profile.bio_placeholder') }}" class="form-input">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="content-card profile-card profile-card-full">
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-map-marker-alt"></i>
                {{ trans('app.profile.dashboard.profile.location_info') }}
            </h2>

            <div class="profile-field-row profile-field-row-3">
                <div class="profile-field">
                    <label for="country" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.country') }}
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-globe-africa"></i>
                        <select id="country" name="country" class="form-input">
                            <option value="">{{ trans('app.profile.dashboard.profile.country_select') }}</option>
                            @foreach(countries_list_fr() as $countryName)
                                <option value="{{ $countryName }}" @selected(old('country', $user->country) === $countryName)>{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('country')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="region" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.region') }}
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-map"></i>
                        <input type="text" id="region" name="region" value="{{ old('region', $user->region) }}" placeholder="{{ trans('app.profile.dashboard.profile.region_placeholder') }}" class="form-input">
                    </div>
                    @error('region')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="city" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.city') }}
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-city"></i>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="{{ trans('app.profile.dashboard.profile.city_placeholder') }}" class="form-input">
                    </div>
                    @error('city')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="profile-submit-bar">
            <button type="submit" class="dashboard-button-primary profile-submit-btn">
                <i class="fas fa-save"></i>
                {{ trans('app.profile.dashboard.profile.save_changes') }}
            </button>
        </div>
    </form>

    <div class="profile-grid" style="margin-top: 1.5rem;">
        <!-- Changer le mot de passe -->
        <div class="content-card profile-card">
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-lock"></i>
                {{ trans('app.profile.dashboard.profile.change_password') }}
            </h2>

            <form method="POST" action="{{ route('dashboard.profile.update') }}" id="passwordForm" class="profile-field-group">
                @csrf

                <div class="profile-field">
                    <label for="current_password" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.current_password') }} <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" id="current_password" name="current_password" required class="form-input">
                    </div>
                    @error('current_password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="password" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.new_password') }} <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required minlength="8" class="form-input">
                    </div>
                    <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.8rem; margin-top: 0.25rem;">{{ trans('app.profile.dashboard.profile.min_characters') }}</div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="password_confirmation" class="dashboard-text-primary form-label">
                        {{ trans('app.profile.dashboard.profile.confirm_password') }} <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="form-input">
                    </div>
                </div>

                <div class="profile-submit-bar">
                    <button type="submit" class="dashboard-button-primary profile-submit-btn">
                        <i class="fas fa-key"></i>
                        {{ trans('app.profile.dashboard.profile.change_password') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations du compte -->
        <div class="content-card profile-card">
            <h2 class="card-title dashboard-text-primary">
                <i class="fas fa-info-circle"></i>
                {{ trans('app.profile.dashboard.profile.account_info') }}
            </h2>

            <div class="profile-field-group">
                <div class="account-info-item">
                    <div>
                        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ trans('app.profile.dashboard.profile.member_since') }}</div>
                        <div class="dashboard-text-primary" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem;">{{ $user->created_at->format(app()->getLocale() === 'fr' ? 'd F Y' : 'F d, Y') }}</div>
                    </div>
                    <div class="account-info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>

                <div class="account-info-item">
                    <div>
                        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ trans('app.profile.dashboard.profile.last_login') }}</div>
                        <div class="dashboard-text-primary" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem;">
                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : trans('app.profile.dashboard.profile.never') }}
                        </div>
                    </div>
                    <div class="account-info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="account-info-item">
                    <div>
                        <div class="dashboard-text-secondary" style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ trans('app.profile.dashboard.profile.profile_completion') }}</div>
                        <div class="dashboard-text-primary" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem;">{{ $completion }}%</div>
                    </div>
                    <div class="account-info-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
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

<style>
    /* ============ Layout ultra moderne ============ */
    .profile-page {
        max-width: 1200px;
    }

    .profile-hero {
        display: flex;
        align-items: center;
        gap: 1.75rem;
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.08), rgba(4, 170, 109, 0.02));
        border: 1px solid rgba(4, 170, 109, 0.15);
        border-radius: 18px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .profile-hero-avatar {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: linear-gradient(135deg, #04AA6D, #038f5a);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.25rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(4, 170, 109, 0.35);
    }

    .profile-hero-info {
        flex: 1;
        min-width: 220px;
    }

    .profile-hero-name {
        margin: 0 0 0.35rem 0;
        font-size: 1.6rem;
        font-weight: 800;
        color: #2c3e50;
    }

    .profile-hero-email {
        margin: 0 0 0.9rem 0;
        color: #64748b;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .profile-hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .profile-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: rgba(4, 170, 109, 0.1);
        border: 1px solid rgba(4, 170, 109, 0.2);
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #04AA6D;
    }

    .profile-hero-completion {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    .completion-ring {
        --pct: 0;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: conic-gradient(#04AA6D calc(var(--pct) * 1%), rgba(4, 170, 109, 0.12) 0);
        position: relative;
    }

    .completion-ring::before {
        content: '';
        position: absolute;
        inset: 8px;
        border-radius: 50%;
        background: #ffffff;
    }

    .completion-value {
        position: relative;
        z-index: 1;
        font-weight: 800;
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .completion-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 1.5rem;
        align-items: start;
    }

    .profile-card {
        margin-bottom: 0;
    }

    .profile-card-full {
        grid-column: 1 / -1;
    }

    .profile-field-group {
        display: grid;
        gap: 1.25rem;
    }

    .profile-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .profile-field-row-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .profile-field {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        display: block;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: left;
    }

    .required {
        color: #ef4444;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-icon > i {
        position: absolute;
        left: 1rem;
        color: #04AA6D;
        opacity: 0.7;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .input-with-icon .form-input {
        padding-left: 2.6rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        background: #ffffff;
        border: 1px solid rgba(4, 170, 109, 0.2);
        border-radius: 10px;
        color: #2c3e50;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    textarea.form-input {
        resize: vertical;
        line-height: 1.5;
    }

    .form-input:focus {
        outline: none;
        border-color: #04AA6D;
        box-shadow: 0 0 0 3px rgba(4, 170, 109, 0.1);
    }

    .field-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    .profile-submit-bar {
        padding-top: 1.25rem;
        margin-top: 0.25rem;
        border-top: 1px solid rgba(4, 170, 109, 0.1);
        grid-column: 1 / -1;
    }

    .profile-submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.75rem;
        background: linear-gradient(135deg, #04AA6D, #038f5a);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(4, 170, 109, 0.3);
    }

    .profile-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(4, 170, 109, 0.4);
    }

    .account-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(4, 170, 109, 0.05);
        border: 1px solid rgba(4, 170, 109, 0.1);
        border-radius: 10px;
    }

    .account-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(4, 170, 109, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #04AA6D;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .profile-hero {
            flex-direction: column;
            text-align: center;
        }

        .profile-hero-badges {
            justify-content: center;
        }

        .profile-field-row,
        .profile-field-row-3 {
            grid-template-columns: 1fr;
        }
    }

    /* ============ Dark Mode ============ */
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

    body.dark-mode .profile-hero {
        background: linear-gradient(135deg, rgba(4, 170, 109, 0.15), rgba(15, 23, 42, 0.4));
        border-color: rgba(4, 170, 109, 0.25);
    }

    body.dark-mode .profile-hero-name {
        color: rgba(255, 255, 255, 0.95) !important;
    }

    body.dark-mode .completion-ring::before {
        background: #0f172a;
    }

    body.dark-mode .completion-value {
        color: rgba(255, 255, 255, 0.95) !important;
    }

    body.dark-mode .account-info-item {
        background: rgba(4, 170, 109, 0.1) !important;
        border-color: rgba(4, 170, 109, 0.2) !important;
    }

    body.dark-mode .account-info-icon {
        background: rgba(4, 170, 109, 0.2) !important;
    }

    body.dark-mode .dashboard-button-primary,
    body.dark-mode .profile-submit-btn {
        box-shadow: 0 4px 6px rgba(4, 170, 109, 0.4) !important;
    }

    body.dark-mode .dashboard-button-primary:hover,
    body.dark-mode .profile-submit-btn:hover {
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
