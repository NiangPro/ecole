@extends('admin.layout')

@section('title', 'Modifier le Code Promo')

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .input-admin { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .input-admin:focus { background: #ffffff; border-color: #06b6d4; }
    body.light-mode label { color: #06b6d4; }
    body.light-mode .text-gray-400 { color: #64748b; }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Modifier le Code Promo</h3>
        <p class="text-gray-400" style="font-family: monospace;">{{ $coupon->code }}</p>
    </div>
    <a href="{{ route('admin.documents.coupons.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.documents.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Code *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                           class="input-admin" style="font-family: monospace; text-transform: uppercase;">
                    @error('code')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Nom (optionnel)</label>
                    <input type="text" name="name" value="{{ old('name', $coupon->name) }}" class="input-admin">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="3" class="input-admin">{{ old('description', $coupon->description) }}</textarea>
                    @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Type *</label>
                        <select name="type" class="input-admin" required>
                            <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                            <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Montant fixe (FCFA)</option>
                        </select>
                        @error('type')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Valeur *</label>
                        <input type="number" name="value" value="{{ old('value', $coupon->value) }}" min="0" step="1" required class="input-admin">
                        @error('value')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Montant minimum d'achat (FCFA)</label>
                    <input type="number" name="minimum_amount" value="{{ old('minimum_amount', $coupon->minimum_amount) }}" min="0" class="input-admin">
                    @error('minimum_amount')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Restreindre à un document (optionnel)</label>
                    <select name="document_id" class="input-admin">
                        <option value="">Tous les documents</option>
                        @foreach($documents as $document)
                            <option value="{{ $document->id }}" {{ old('document_id', $coupon->document_id) == $document->id ? 'selected' : '' }}>{{ $document->title }}</option>
                        @endforeach
                    </select>
                    @error('document_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Restreindre à une catégorie (optionnel)</label>
                    <select name="category_id" class="input-admin">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $coupon->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Limite d'utilisation totale</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="input-admin" placeholder="Illimité">
                        @error('usage_limit')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Limite par utilisateur</label>
                        <input type="number" name="user_limit" value="{{ old('user_limit', $coupon->user_limit) }}" min="1" class="input-admin">
                        @error('user_limit')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Début de validité</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $coupon->starts_at ? \Carbon\Carbon::parse($coupon->starts_at)->format('Y-m-d') : '') }}" class="input-admin">
                        @error('starts_at')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Fin de validité</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') }}" class="input-admin">
                        @error('expires_at')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                    <span class="text-cyan-400 font-semibold">Code actif</span>
                </label>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
            <a href="{{ route('admin.documents.coupons.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
