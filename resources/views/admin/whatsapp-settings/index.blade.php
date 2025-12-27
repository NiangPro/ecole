@extends('admin.layout')

@section('title', 'Paramètres WhatsApp - Admin')

@push('styles')
<style>
.whatsapp-settings-page {
    max-width: 1200px;
    margin: 0 auto;
}

.settings-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.settings-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.settings-card h2 i {
    color: #06b6d4;
}

.form-input-admin {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input-admin:focus {
    outline: none;
    border-color: #06b6d4;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.checkbox-wrapper input[type="checkbox"] {
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.checkbox-wrapper label {
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    flex: 1;
}

.btn-save {
    padding: 0.875rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.btn-cancel {
    padding: 0.875rem 2rem;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

/* Dark Mode */
body.dark-mode .settings-card {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

body.dark-mode .settings-card h2 {
    color: white;
}

body.dark-mode .form-input-admin {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
    color: white;
}

body.dark-mode .form-input-admin:focus {
    background: rgba(15, 23, 42, 0.8);
    border-color: #06b6d4;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2);
}

body.dark-mode .checkbox-wrapper {
    background: rgba(15, 23, 42, 0.6);
}

body.dark-mode .checkbox-wrapper label {
    color: white;
}
</style>
@endpush

@section('content')
<div class="whatsapp-settings-page">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #1e293b;">
            <i class="fab fa-whatsapp" style="color: #25D366;"></i> Paramètres WhatsApp
        </h1>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem;"></i>
        <span style="color: #10b981; font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.documents.whatsapp-settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="settings-card">
            <h2>
                <i class="fas fa-toggle-on"></i> Activation
            </h2>
            
            <div class="checkbox-wrapper">
                <input type="checkbox" name="enabled" id="enabled" value="1" {{ $settings->enabled ? 'checked' : '' }}>
                <label for="enabled">
                    Activer l'envoi de messages WhatsApp
                </label>
            </div>
        </div>

        <div class="settings-card">
            <h2>
                <i class="fas fa-cog"></i> Configuration API
            </h2>
            
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <label for="api_provider" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                        Fournisseur API *
                    </label>
                    <select name="api_provider" id="api_provider" required class="form-input-admin">
                        <option value="">Sélectionner un fournisseur</option>
                        <option value="twilio" {{ $settings->api_provider === 'twilio' ? 'selected' : '' }}>Twilio</option>
                        <option value="whatsapp_business" {{ $settings->api_provider === 'whatsapp_business' ? 'selected' : '' }}>WhatsApp Business API</option>
                    </select>
                </div>

                <div>
                    <label for="api_key" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                        Clé API
                    </label>
                    <input type="text" name="api_key" id="api_key" value="{{ old('api_key', $settings->api_key) }}"
                        class="form-input-admin" placeholder="Votre clé API">
                </div>

                <div>
                    <label for="api_secret" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                        Secret API
                    </label>
                    <input type="password" name="api_secret" id="api_secret" value="{{ old('api_secret', $settings->api_secret) }}"
                        class="form-input-admin" placeholder="Votre secret API">
                </div>

                <div>
                    <label for="phone_number" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                        Numéro WhatsApp Business
                    </label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $settings->phone_number) }}"
                        class="form-input-admin" placeholder="+221XXXXXXXXX">
                </div>

                <div>
                    <label for="webhook_url" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                        URL Webhook (optionnel)
                    </label>
                    <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url', $settings->webhook_url) }}"
                        class="form-input-admin" placeholder="https://votre-domaine.com/webhook/whatsapp">
                </div>
            </div>
        </div>

        <div class="settings-card">
            <h2>
                <i class="fas fa-comment-alt"></i> Template du Message
            </h2>
            
            <div>
                <label for="message_template" style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">
                    Template du message WhatsApp
                </label>
                <textarea name="message_template" id="message_template" rows="8" class="form-input-admin"
                    placeholder="Bonjour {customer_name},&#10;&#10;Merci pour votre achat de : *{document_title}*&#10;&#10;Lien de téléchargement :&#10;{download_link}&#10;&#10;Ce lien est valide pendant 30 jours.">{{ old('message_template', $settings->message_template) }}</textarea>
                <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem;">
                    Variables disponibles : <code>{customer_name}</code>, <code>{document_title}</code>, <code>{download_link}</code>, <code>{download_token}</code>
                </p>
            </div>
        </div>

        <div class="settings-card">
            <h2>
                <i class="fas fa-bell"></i> Options d'Envoi
            </h2>
            
            <div style="display: grid; gap: 1rem;">
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="send_on_purchase" id="send_on_purchase" value="1" {{ $settings->send_on_purchase ? 'checked' : '' }}>
                    <label for="send_on_purchase">
                        Envoyer automatiquement lors de l'ajout au panier
                    </label>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="send_on_payment_confirmed" id="send_on_payment_confirmed" value="1" {{ $settings->send_on_payment_confirmed ? 'checked' : '' }}>
                    <label for="send_on_payment_confirmed">
                        Envoyer automatiquement lors de la confirmation du paiement
                    </label>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('admin.dashboard') }}" class="btn-cancel">
                Annuler
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
@endsection

