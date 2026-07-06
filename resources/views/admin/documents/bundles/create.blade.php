@extends('admin.layout')

@section('title', 'Nouveau Pack')

@section('styles')
<style>
    body.light-mode h3 { color: #1e293b; }
    body.light-mode p { color: #64748b; }
    body.light-mode .content-section { background: #ffffff; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .input-admin { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .input-admin:focus { background: #ffffff; border-color: #06b6d4; }
    body.light-mode label { color: #06b6d4; }
    body.light-mode .text-gray-400 { color: #64748b; }
    body.light-mode .doc-picker-item { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.2); }
    body.light-mode .fascicule-box { background: #f8f9fa; border-color: rgba(6, 182, 212, 0.3); }

    .doc-picker {
        max-height: 220px;
        overflow-y: auto;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 0.5rem;
    }
    .doc-picker-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 8px;
        background: rgba(30, 41, 59, 0.5);
        margin-bottom: 0.375rem;
    }
    .doc-picker-item:last-child { margin-bottom: 0; }
    .doc-picker-item input[type="checkbox"] { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

    .fascicule-box {
        border: 1px dashed rgba(6, 182, 212, 0.4);
        border-radius: 12px;
        padding: 1rem;
        background: rgba(30, 41, 59, 0.3);
    }
</style>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h3 class="text-3xl font-bold mb-2">Nouveau Pack</h3>
        <p class="text-gray-400">Regroupez plusieurs documents et/ou épreuves à prix réduit</p>
    </div>
    <a href="{{ route('admin.documents.bundles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<div class="content-section">
    <form action="{{ route('admin.documents.bundles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Nom du pack *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="input-admin" placeholder="Ex : Pack BAC Série G complet">
                    @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Description</label>
                    <textarea name="description" rows="4" class="input-admin" placeholder="Décrivez ce pack">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix normal (FCFA) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" step="50" required class="input-admin">
                        @error('price')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-cyan-400 mb-2 font-semibold">Prix réduit (FCFA)</label>
                        <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="50" class="input-admin" placeholder="Optionnel">
                        @error('discount_price')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Image de couverture</label>
                    <select name="cover_type" id="coverType" class="input-admin mb-3">
                        <option value="internal" {{ old('cover_type', 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
                        <option value="external" {{ old('cover_type') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
                    </select>
                    <div id="coverInternal" style="display: {{ old('cover_type', 'internal') === 'internal' ? 'block' : 'none' }};">
                        <input type="file" name="cover_image_file" accept="image/*" class="input-admin">
                        @error('cover_image_file')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div id="coverExternal" style="display: {{ old('cover_type') === 'external' ? 'block' : 'none' }};">
                        <input type="url" name="cover_image_url" value="{{ old('cover_image_url') }}" class="input-admin" placeholder="https://exemple.com/image.jpg">
                        @error('cover_image_url')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-cyan-400 font-semibold">Pack actif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-cyan-500/20 border-cyan-500/50 text-cyan-500 focus:ring-cyan-500">
                        <span class="text-cyan-400 font-semibold">Pack en vedette</span>
                    </label>
                </div>
            </div>

            <div class="space-y-5">
                @error('documents')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Documents inclus</label>
                    <input type="text" id="doc-search" class="input-admin mb-2" placeholder="Rechercher un document...">
                    <div class="doc-picker" id="doc-picker-list">
                        @forelse($documents as $document)
                        <label class="doc-picker-item" data-doc-title="{{ Str::lower($document->title) }}">
                            <input type="checkbox" name="document_ids[]" value="{{ $document->id }}"
                                   {{ in_array($document->id, old('document_ids', [])) ? 'checked' : '' }}>
                            <span>{{ $document->title }}</span>
                        </label>
                        @empty
                        <p class="text-gray-400 text-sm p-2">Aucun document publié</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <label class="block text-cyan-400 mb-2 font-semibold">Épreuves &amp; corrigés inclus</label>
                    <input type="text" id="epreuve-search" class="input-admin mb-2" placeholder="Rechercher une épreuve...">
                    <div class="doc-picker" id="epreuve-picker-list">
                        @forelse($epreuves as $epreuve)
                        <label class="doc-picker-item" data-doc-title="{{ Str::lower($epreuve->title) }}">
                            <input type="checkbox" name="epreuve_ids[]" value="{{ $epreuve->id }}"
                                   {{ in_array($epreuve->id, old('epreuve_ids', [])) ? 'checked' : '' }}>
                            <span>{{ $epreuve->title }}</span>
                        </label>
                        @empty
                        <p class="text-gray-400 text-sm p-2">Aucune épreuve publiée</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <button type="button" id="toggle-fascicule" class="text-cyan-400 font-semibold text-sm">
                        <i class="fas fa-plus-circle mr-1"></i>Créer un nouveau document (fascicule)
                    </button>
                    <div class="fascicule-box mt-3" id="fascicule-box" style="display: none;">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-cyan-400 mb-1 text-sm font-semibold">Titre</label>
                                <input type="text" name="new_document[title]" value="{{ old('new_document.title') }}" class="input-admin">
                                @error('new_document.title')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-cyan-400 mb-1 text-sm font-semibold">Catégorie</label>
                                    <select name="new_document[category_id]" class="input-admin">
                                        <option value="">—</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('new_document.category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('new_document.category_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-cyan-400 mb-1 text-sm font-semibold">Prix (FCFA)</label>
                                    <input type="number" name="new_document[price]" value="{{ old('new_document.price') }}" min="0" step="50" class="input-admin">
                                    @error('new_document.price')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-cyan-400 mb-1 text-sm font-semibold">Fichier</label>
                                <input type="file" name="new_document[file]" class="input-admin">
                                @error('new_document.file')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <p class="text-gray-400 text-xs">Ce fascicule sera créé comme document publié et ajouté au pack automatiquement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-black font-semibold rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Créer le pack
            </button>
            <a href="{{ route('admin.documents.bundles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('doc-search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#doc-picker-list .doc-picker-item').forEach(function(item) {
        item.style.display = item.dataset.docTitle.includes(query) ? 'flex' : 'none';
    });
});

document.getElementById('epreuve-search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#epreuve-picker-list .doc-picker-item').forEach(function(item) {
        item.style.display = item.dataset.docTitle.includes(query) ? 'flex' : 'none';
    });
});

document.getElementById('coverType').addEventListener('change', function() {
    const internal = document.getElementById('coverInternal');
    const external = document.getElementById('coverExternal');
    if (this.value === 'internal') {
        internal.style.display = 'block';
        external.style.display = 'none';
    } else {
        internal.style.display = 'none';
        external.style.display = 'block';
    }
});

document.getElementById('toggle-fascicule').addEventListener('click', function() {
    const box = document.getElementById('fascicule-box');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
});

@if($errors->has('new_document.title') || $errors->has('new_document.category_id') || $errors->has('new_document.price') || $errors->has('new_document.file'))
document.getElementById('fascicule-box').style.display = 'block';
@endif
</script>
@endsection
