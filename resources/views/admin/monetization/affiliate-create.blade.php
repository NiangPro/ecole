@extends('admin.layout')

@section('title', 'Créer un Affilié - Admin')

@section('content')
<div style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
            <i class="fas fa-user-plus" style="color: #06b6d4; margin-right: 15px;"></i>
            Créer un Nouvel Affilié
        </h1>
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
            Ajoutez un nouvel affilié au système
        </p>
    </div>

    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <form action="{{ route('admin.monetization.affiliates.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Utilisateur (optionnel)</label>
                <select name="user_id" style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="">Aucun utilisateur</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Nom <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                @error('name')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Email <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                @error('email')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Code d'Affiliation (optionnel)</label>
                <input type="text" name="affiliate_code" value="{{ old('affiliate_code') }}" maxlength="20" style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; font-family: monospace;">
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Laissez vide pour générer automatiquement</div>
                @error('affiliate_code')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Taux de Commission (%) <span style="color: #ef4444;">*</span></label>
                <input type="number" name="commission_rate" value="{{ old('commission_rate', 10) }}" min="0" max="100" step="0.01" required style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                @error('commission_rate')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Statut <span style="color: #ef4444;">*</span></label>
                <select name="status" required style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                </select>
                @error('status')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">Notes</label>
                <textarea name="notes" rows="4" style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem; resize: vertical;">{{ old('notes') }}</textarea>
                @error('notes')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('admin.monetization.affiliates') }}" style="padding: 12px 24px; background: rgba(107, 114, 128, 0.3); color: white; border: 1px solid rgba(107, 114, 128, 0.5); border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Annuler
                </a>
                <button type="submit" style="padding: 12px 24px; background: #06b6d4; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-save" style="margin-right: 8px;"></i>Créer l'Affilié
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

