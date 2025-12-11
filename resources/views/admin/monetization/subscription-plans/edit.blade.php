@extends('admin.layout')

@section('title', 'Modifier le Plan d\'Abonnement - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.monetization.subscription-plans.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #06b6d4; text-decoration: none; margin-bottom: 20px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Retour aux plans
        </a>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-edit" style="color: #06b6d4; margin-right: 15px;"></i>
            Modifier le Plan : {{ $plan->name }}
        </h1>
    </div>

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 3px; margin-bottom: 10px;">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Erreurs de validation</strong>
            </div>
            <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.monetization.subscription-plans.update', $plan->id) }}" method="POST" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        @csrf
        @method('PUT')
        
        <div style="display: grid; gap: 25px;">
            <!-- Nom et Slug -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Nom du plan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    @error('name')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Slug
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; font-family: monospace;">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Généré automatiquement si vide</p>
                    @error('slug')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    Description
                </label>
                <textarea name="description" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;">{{ old('description', $plan->description) }}</textarea>
                @error('description')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix et Devise -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Prix <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $plan->price) }}" step="0.01" min="0" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    @error('price')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Devise <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="currency" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                        <option value="XOF" {{ old('currency', $plan->currency) === 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                        <option value="EUR" {{ old('currency', $plan->currency) === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="USD" {{ old('currency', $plan->currency) === 'USD' ? 'selected' : '' }}>USD ($)</option>
                    </select>
                    @error('currency')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Période de facturation et Durée -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Période de facturation <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="billing_period" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                        <option value="monthly" {{ old('billing_period', $plan->billing_period) === 'monthly' ? 'selected' : '' }}>Mensuel</option>
                        <option value="yearly" {{ old('billing_period', $plan->billing_period) === 'yearly' ? 'selected' : '' }}>Annuel</option>
                    </select>
                    @error('billing_period')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Durée (jours) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" min="1" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">30 pour mensuel, 365 pour annuel</p>
                    @error('duration_days')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fonctionnalités -->
            <div>
                <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                    Fonctionnalités (une par ligne)
                </label>
                <div id="features-container">
                    @php
                        $features = old('features', $plan->features ?? []);
                    @endphp
                    @if(!empty($features))
                        @foreach($features as $index => $feature)
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="features[]" value="{{ $feature }}" style="flex: 1; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Accès à tous les cours premium">
                            <button type="button" onclick="removeFeature(this)" style="padding: 12px 20px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    @endif
                    @if(empty($features))
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" name="features[]" style="flex: 1; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Accès à tous les cours premium">
                            <button type="button" onclick="removeFeature(this)" style="padding: 12px 20px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addFeature()" style="margin-top: 10px; padding: 10px 20px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-plus"></i> Ajouter une fonctionnalité
                </button>
                @error('features.*')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Ordre d'affichage
                    </label>
                    <input type="number" name="order" value="{{ old('order', $plan->order) }}" min="0" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    @error('order')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                        Badge (optionnel)
                    </label>
                    <input type="text" name="badge" value="{{ old('badge', $plan->badge) }}" maxlength="50" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Populaire">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Badge affiché sur le plan</p>
                    @error('badge')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Checkboxes -->
            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 10px; color: white; cursor: pointer;">
                    <input type="checkbox" name="is_active" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                    <span style="font-weight: 600;">Plan actif</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; color: white; cursor: pointer;">
                    <input type="checkbox" name="is_featured" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                    <span style="font-weight: 600;">Mettre en avant</span>
                </label>
            </div>

            <!-- Boutons -->
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="padding: 12px 24px; background: #06b6d4; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('admin.monetization.subscription-plans.index') }}" style="padding: 12px 24px; background: rgba(107, 114, 128, 0.3); color: white; border: 1px solid rgba(107, 114, 128, 0.5); border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
    div.innerHTML = `
        <input type="text" name="features[]" style="flex: 1; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;" placeholder="Ex: Accès à tous les cours premium">
        <button type="button" onclick="removeFeature(this)" style="padding: 12px 20px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    } else {
        alert('Vous devez avoir au moins une fonctionnalité.');
    }
}

// Auto-générer la durée selon la période
document.querySelector('select[name="billing_period"]')?.addEventListener('change', function() {
    const durationInput = document.querySelector('input[name="duration_days"]');
    if (this.value === 'yearly') {
        durationInput.value = 365;
    } else {
        durationInput.value = 30;
    }
});
</script>
@endsection

