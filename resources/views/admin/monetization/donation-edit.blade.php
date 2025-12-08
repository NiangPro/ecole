@extends('admin.layout')

@section('title', 'Éditer une Donation - Admin')

@push('styles')
<style>
    /* Adaptation Dark Mode */
    body.light-mode .donation-edit-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1));
    }

    body.light-mode .donation-edit-page h1,
    body.light-mode .donation-edit-page h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donation-edit-page p {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .donation-edit-page .form-card {
        background: rgba(255, 255, 255, 0.8) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donation-edit-page .form-card h3 {
        border-bottom-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .donation-edit-page label {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .donation-edit-page input,
    body.light-mode .donation-edit-page select,
    body.light-mode .donation-edit-page textarea {
        background: rgba(255, 255, 255, 0.9) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.light-mode .donation-edit-page input::placeholder,
    body.light-mode .donation-edit-page textarea::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }

    body.light-mode .donation-edit-page input[type="checkbox"] {
        accent-color: #06b6d4;
    }

    body.light-mode .donation-edit-page .actions-section {
        border-top-color: rgba(6, 182, 212, 0.2) !important;
    }
</style>
@endpush

@section('content')
<div class="donation-edit-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-heart" style="color: #06b6d4; margin-right: 15px;"></i>
            Éditer la Donation #{{ $donation->id }}
        </h1>
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
            Modifier les informations de cette donation
        </p>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <a href="{{ route('admin.monetization.donations.show', $donation->id) }}" style="padding: 12px 24px; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 2px solid #3b82f6; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-eye" style="margin-right: 8px;"></i>
            Voir les détails
        </a>
        <a href="{{ route('admin.monetization.donations.index') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour à la liste
        </a>
    </div>

    <div class="form-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 40px; max-width: 800px;">
        <form action="{{ route('admin.monetization.donations.update', $donation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; gap: 25px;">
                <!-- Informations Donateur -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-user" style="color: #06b6d4; margin-right: 10px;"></i>
                        Informations Donateur
                    </h3>
                    
                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Nom du Donateur <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="donor_name" value="{{ old('donor_name', $donation->donor_name) }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('donor_name')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Email (optionnel)
                            </label>
                            <input type="email" name="donor_email" value="{{ old('donor_email', $donation->donor_email) }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('donor_email')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations Paiement -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-credit-card" style="color: #06b6d4; margin-right: 10px;"></i>
                        Informations Paiement
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Montant <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" name="amount" value="{{ old('amount', $donation->amount) }}" required min="100" step="100" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('amount')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Devise <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="currency" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="XOF" {{ old('currency', $donation->currency) === 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                <option value="EUR" {{ old('currency', $donation->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('currency', $donation->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Méthode de Paiement <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="payment_method" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                                <option value="wave" {{ old('payment_method', $donation->payment_method) === 'wave' ? 'selected' : '' }}>Wave</option>
                                <option value="mobile_money" {{ old('payment_method', $donation->payment_method) === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="bank_transfer" {{ old('payment_method', $donation->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                                <option value="stripe" {{ old('payment_method', $donation->payment_method) === 'stripe' ? 'selected' : '' }}>Carte bancaire (Stripe)</option>
                                <option value="paypal" {{ old('payment_method', $donation->payment_method) === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="cash" {{ old('payment_method', $donation->payment_method) === 'cash' ? 'selected' : '' }}>Espèces</option>
                                <option value="other" {{ old('payment_method', $donation->payment_method) === 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('payment_method')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Référence Paiement
                            </label>
                            <input type="text" name="payment_reference" value="{{ old('payment_reference', $donation->payment_reference) }}" placeholder="REF-XXXXX" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            @error('payment_reference')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Statut <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            <option value="pending" {{ old('status', $donation->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="completed" {{ old('status', $donation->status) === 'completed' ? 'selected' : '' }}>Complétée</option>
                            <option value="failed" {{ old('status', $donation->status) === 'failed' ? 'selected' : '' }}>Échouée</option>
                            <option value="cancelled" {{ old('status', $donation->status) === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('status')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Options -->
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-cog" style="color: #06b6d4; margin-right: 10px;"></i>
                        Options
                    </h3>
                    
                    <div style="display: grid; gap: 15px;">
                        <div>
                            <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                                Message (optionnel)
                            </label>
                            <textarea name="message" rows="4" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;">{{ old('message', $donation->message) }}</textarea>
                            @error('message')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; color: white; cursor: pointer;">
                                <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous', $donation->is_anonymous) ? 'checked' : '' }} style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                <span>Don anonyme</span>
                            </label>
                            <label style="display: flex; align-items: center; color: white; cursor: pointer;">
                                <input type="checkbox" name="show_on_wall" value="1" {{ old('show_on_wall', $donation->show_on_wall) ? 'checked' : '' }} style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                <span>Afficher sur le mur des donateurs</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-section" style="display: flex; gap: 15px; margin-top: 10px; padding-top: 25px; border-top: 2px solid rgba(6, 182, 212, 0.3);">
                    <button type="submit" style="flex: 1; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-save" style="margin-right: 8px;"></i>
                        Enregistrer les Modifications
                    </button>
                    <a href="{{ route('admin.monetization.donations.show', $donation->id) }}" style="padding: 15px 30px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 2px solid #6b7280; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection

