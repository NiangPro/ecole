@extends('admin.layout')

@section('title', 'Configuration des Moyens de Paiement - Admin')

@section('styles')
<style>
    .payment-gateways-page h3 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .payment-gateways-page h3 {
        color: #1e293b;
    }
    
    .payment-gateways-page h4 {
        color: #fff;
        transition: color 0.3s ease;
    }
    
    body.light-mode .payment-gateways-page h4 {
        color: #1e293b;
    }
    
    .payment-gateways-page .text-gray-300 {
        color: rgba(209, 213, 219, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .payment-gateways-page .text-gray-300 {
        color: rgba(30, 41, 59, 0.8);
    }
    
    .payment-gateways-page .text-gray-400 {
        color: rgba(156, 163, 175, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .payment-gateways-page .text-gray-400 {
        color: rgba(100, 116, 139, 1);
    }
    
    .payment-gateways-page .text-gray-500 {
        color: rgba(107, 114, 128, 1);
        transition: color 0.3s ease;
    }
    
    body.light-mode .payment-gateways-page .text-gray-500 {
        color: rgba(148, 163, 184, 1);
    }
    
    .payment-gateways-page .bg-blue-500\/10 {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    body.light-mode .payment-gateways-page .bg-blue-500\/10 {
        background: rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.4);
    }
    
    .payment-gateways-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    body.light-mode .payment-gateways-page .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.4);
    }
    
    .payment-gateways-page .text-cyan-400 {
        color: #06b6d4;
    }
    
    .payment-gateways-page .text-blue-400 {
        color: #60a5fa;
    }
    
    .payment-gateways-page .text-green-400 {
        color: #4ade80;
    }
    
    .payment-gateways-page .text-red-400 {
        color: #f87171;
    }
    
    body.light-mode .payment-gateways-page .text-red-400 {
        color: #dc2626;
    }
</style>
@endsection

@section('content')
<div class="payment-gateways-page">
    <h3 class="text-3xl font-bold mb-8">Configuration des Moyens de Paiement</h3>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.payment-gateways.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Configuration Wave -->
        <div class="content-section mb-6">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-mobile-alt text-cyan-400"></i>
                Wave
                <label class="ml-auto flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="wave_enabled" value="1" {{ old('wave_enabled', $settings->wave_enabled ?? true) ? 'checked' : '' }} class="w-5 h-5">
                    <span class="text-gray-300 font-semibold">Activé</span>
                </label>
            </h4>
            
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-400 text-xl mt-1"></i>
                    <div>
                        <p class="font-semibold text-blue-400 mb-2">Configuration Wave</p>
                        <p class="text-sm text-gray-300">
                            Wave est un service de paiement mobile populaire en Afrique de l'Ouest. Configurez votre ID marchand pour générer automatiquement les liens de paiement.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-key mr-2"></i>ID Marchand Wave <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="wave_merchant_id" value="{{ old('wave_merchant_id', $settings->wave_merchant_id ?? 'M_sn_XKgk0Xq-zzy4') }}" 
                           class="input-admin" required placeholder="M_sn_XXXXXXXXX" style="font-family: monospace;">
                    <p class="text-gray-500 text-sm mt-2">
                        Format : M_sn_XXXXXXXXX
                    </p>
                    @error('wave_merchant_id')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-globe mr-2"></i>Code Pays
                    </label>
                    <select name="wave_country_code" class="input-admin">
                        <option value="sn" {{ old('wave_country_code', $settings->wave_country_code ?? 'sn') === 'sn' ? 'selected' : '' }}>SN (Sénégal)</option>
                        <option value="ci" {{ old('wave_country_code', $settings->wave_country_code ?? 'sn') === 'ci' ? 'selected' : '' }}>CI (Côte d'Ivoire)</option>
                        <option value="ml" {{ old('wave_country_code', $settings->wave_country_code ?? 'sn') === 'ml' ? 'selected' : '' }}>ML (Mali)</option>
                        <option value="bf" {{ old('wave_country_code', $settings->wave_country_code ?? 'sn') === 'bf' ? 'selected' : '' }}>BF (Burkina Faso)</option>
                        <option value="ne" {{ old('wave_country_code', $settings->wave_country_code ?? 'sn') === 'ne' ? 'selected' : '' }}>NE (Niger)</option>
                    </select>
                    @error('wave_country_code')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Configuration PayPal -->
        <div class="content-section mb-6">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fab fa-paypal text-blue-400"></i>
                PayPal
                <label class="ml-auto flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="paypal_enabled" value="1" {{ old('paypal_enabled', $settings->paypal_enabled ?? false) ? 'checked' : '' }} class="w-5 h-5">
                    <span class="text-gray-300 font-semibold">Activé</span>
                </label>
            </h4>
            
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-400 text-xl mt-1"></i>
                    <div>
                        <p class="font-semibold text-blue-400 mb-2">Configuration PayPal</p>
                        <p class="text-sm text-gray-300">
                            Configurez vos identifiants PayPal pour accepter les paiements via PayPal. Vous pouvez obtenir ces informations depuis votre compte PayPal Developer.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-id-card mr-2"></i>Client ID
                    </label>
                    <input type="text" name="paypal_client_id" value="{{ old('paypal_client_id', $settings->paypal_client_id ?? '') }}" 
                           class="input-admin" placeholder="Votre Client ID PayPal">
                    @error('paypal_client_id')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-key mr-2"></i>Client Secret
                    </label>
                    <input type="password" name="paypal_client_secret" value="" 
                           class="input-admin" placeholder="{{ $settings->paypal_client_secret ? '•••••••• (Laisser vide pour conserver)' : 'Votre client secret PayPal' }}">
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-shield-alt mr-1"></i>
                        @if($settings->paypal_client_secret)
                            Un secret est déjà configuré. Laissez vide pour le conserver.
                        @else
                            Le secret sera stocké de manière sécurisée.
                        @endif
                    </p>
                    @error('paypal_client_secret')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-toggle-on mr-2"></i>Mode
                </label>
                <select name="paypal_mode" class="input-admin">
                    <option value="sandbox" {{ old('paypal_mode', $settings->paypal_mode ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                    <option value="live" {{ old('paypal_mode', $settings->paypal_mode ?? 'sandbox') === 'live' ? 'selected' : '' }}>Live (Production)</option>
                </select>
                @error('paypal_mode')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Configuration Stripe -->
        <div class="content-section mb-6">
            <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="fab fa-stripe text-purple-400"></i>
                Stripe
                <label class="ml-auto flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="stripe_enabled" value="1" {{ old('stripe_enabled', $settings->stripe_enabled ?? false) ? 'checked' : '' }} class="w-5 h-5">
                    <span class="text-gray-300 font-semibold">Activé</span>
                </label>
            </h4>
            
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-400 text-xl mt-1"></i>
                    <div>
                        <p class="font-semibold text-blue-400 mb-2">Configuration Stripe</p>
                        <p class="text-sm text-gray-300">
                            Configurez vos clés API Stripe pour accepter les paiements par carte bancaire. Vous pouvez obtenir ces clés depuis votre tableau de bord Stripe.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-key mr-2"></i>Clé Publique (Publishable Key)
                    </label>
                    <input type="text" name="stripe_public_key" value="{{ old('stripe_public_key', $settings->stripe_public_key ?? '') }}" 
                           class="input-admin" placeholder="pk_test_..." style="font-family: monospace;">
                    @error('stripe_public_key')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-lock mr-2"></i>Clé Secrète (Secret Key)
                    </label>
                    <input type="password" name="stripe_secret_key" value="" 
                           class="input-admin" placeholder="{{ $settings->stripe_secret_key ? '•••••••• (Laisser vide pour conserver)' : 'sk_test_...' }}" style="font-family: monospace;">
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-shield-alt mr-1"></i>
                        @if($settings->stripe_secret_key)
                            Une clé secrète est déjà configurée. Laissez vide pour la conserver.
                        @else
                            La clé sera stockée de manière sécurisée.
                        @endif
                    </p>
                    @error('stripe_secret_key')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2 font-semibold">
                        <i class="fas fa-webhook mr-2"></i>Webhook Secret (optionnel)
                    </label>
                    <input type="password" name="stripe_webhook_secret" value="" 
                           class="input-admin" placeholder="{{ $settings->stripe_webhook_secret ? '•••••••• (Laisser vide pour conserver)' : 'whsec_...' }}" style="font-family: monospace;">
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Utilisé pour vérifier les webhooks Stripe. Optionnel mais recommandé.
                    </p>
                    @error('stripe_webhook_secret')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="flex gap-4 justify-end mt-8">
            <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="background: rgba(107, 114, 128, 0.2); border: 2px solid rgba(107, 114, 128, 0.3); color: rgba(255, 255, 255, 0.8);">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les Configurations
            </button>
        </div>
    </form>
</div>
@endsection
