@php
    $document = $document ?? null;
    $isEdit = $document && $document->exists;
    $categories = $categories ?? [];
    $currentCategory = old('category', $document ? $document->category : null);
    $isNewCategory = $currentCategory === '__new__' || ($currentCategory && !in_array($currentCategory, $categories, true));
    $categorySelectValue = $isNewCategory ? '__new__' : $currentCategory;
    $categoryNewValue = old('category_new', $isNewCategory && $document ? $document->category : '');
@endphp

<div class="space-y-6">
    @if($errors->any())
        <div class="mb-2 p-4 bg-red-500/10 border border-red-500/40 rounded-lg text-red-300 text-sm">
            <div class="font-semibold mb-1">Merci de corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Titre *</label>
            <input type="text" name="title" value="{{ old('title', $document ? $document->title : '') }}" required
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            @error('title')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Slug (optionnel, auto si vide)</label>
            <input type="text" name="slug" value="{{ old('slug', $document ? $document->slug : '') }}"
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500" placeholder="ex: carte-nationale-identite">
            @error('slug')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Titre SEO (balise &lt;title&gt;)</label>
            <input type="text" name="seo_title" value="{{ old('seo_title', $document ? $document->seo_title : '') }}"
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                   placeholder="ex: Carte nationale d'identité CEDEAO - Démarches au Sénégal">
            @error('seo_title')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Mots-clés (SEO)</label>
            <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $document ? $document->seo_keywords : '') }}"
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                   placeholder="ex: carte d'identité, cni, papiers administratifs Sénégal">
            @error('seo_keywords')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Catégorie</label>
            <select name="category" id="categorySelect" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                <option value="">— Aucune —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $categorySelectValue === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
                <option value="__new__" {{ $categorySelectValue === '__new__' ? 'selected' : '' }}>+ Nouvelle catégorie</option>
            </select>
            <div id="categoryNewWrap" style="display: {{ $categorySelectValue === '__new__' ? 'block' : 'none' }};" class="mt-2">
                <label class="block text-sm font-medium text-gray-400 mb-1">Nom de la nouvelle catégorie</label>
                <input type="text" name="category_new" id="categoryNew" value="{{ $categoryNewValue }}"
                       class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500" placeholder="ex: Identité & État civil">
                @error('category_new')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            @error('category')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $document ? $document->is_featured : false) ? 'checked' : '' }} class="rounded border-gray-600 bg-gray-900 text-cyan-500 focus:ring-cyan-500">
                <span class="text-gray-300">Fiche vedette</span>
            </label>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Statut *</label>
                <select name="status" required class="px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="draft" {{ old('status', $document ? $document->status : 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ old('status', $document ? $document->status : '') === 'published' ? 'selected' : '' }}>Publié</option>
                </select>
                @error('status')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Image de couverture</label>
        <select name="cover_type" id="coverType" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 mb-3">
            <option value="internal" {{ old('cover_type', $document ? $document->cover_type : 'internal') === 'internal' ? 'selected' : '' }}>Interne (upload)</option>
            <option value="external" {{ old('cover_type', $document ? $document->cover_type : '') === 'external' ? 'selected' : '' }}>Externe (URL)</option>
        </select>
        <div id="coverInternal" style="display: {{ old('cover_type', $document ? $document->cover_type : 'internal') === 'internal' ? 'block' : 'none' }};">
            <input type="file" name="cover_image_file" accept="image/*" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            @error('cover_image_file')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            @if($document && $document->cover_type === 'internal' && $document->cover_image)
                <p class="text-sm text-gray-400 mt-2">Image actuelle :</p>
                <img loading="lazy" src="{{ asset('storage/' . $document->cover_image) }}" alt="" class="mt-1 w-20 h-20 object-cover rounded-lg border border-gray-700">
            @endif
        </div>
        <div id="coverExternal" style="display: {{ old('cover_type', $document ? $document->cover_type : '') === 'external' ? 'block' : 'none' }};">
            <input type="url" name="cover_image_url" value="{{ old('cover_image_url', $document && $document->cover_type === 'external' ? $document->cover_image : '') }}"
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500" placeholder="https://example.com/image.jpg">
            @error('cover_image_url')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Résumé / courte description</label>
        <textarea name="summary" rows="2" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('summary', $document ? $document->summary : '') }}</textarea>
        @error('summary')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Meta Description (SEO)</label>
        <textarea name="seo_description" rows="3"
                  class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                  placeholder="Phrase courte pour Google qui résume la démarche (150 à 160 caractères conseillés).">{{ old('seo_description', $document ? $document->seo_description : '') }}</textarea>
        @error('seo_description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">À quoi sert ce document ?</label>
        <textarea name="purpose" rows="3" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('purpose', $document ? $document->purpose : '') }}</textarea>
        @error('purpose')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Qui peut le demander ?</label>
        <textarea name="target_audience" rows="2" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('target_audience', $document ? $document->target_audience : '') }}</textarea>
        @error('target_audience')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Pièces à fournir (une par ligne)</label>
        <textarea name="required_documents_text" rows="6" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" placeholder="Copie de l'extrait de naissance&#10;2 photos d'identité&#10;...">{{ old('required_documents_text', isset($document) && is_array($document->required_documents) ? implode("\n", $document->required_documents) : '') }}</textarea>
        @error('required_documents_text')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Où déposer le dossier ? (une par ligne)</label>
        <textarea name="where_to_apply_text" rows="4" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" placeholder="Centre d'état civil de votre commune&#10;Préfecture / Sous-préfecture">{{ old('where_to_apply_text', isset($document) && is_array($document->where_to_apply) ? implode("\n", $document->where_to_apply) : '') }}</textarea>
        @error('where_to_apply_text')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Coût approximatif</label>
            <textarea name="approx_cost" rows="2"
                      class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                      placeholder="ex: Gratuit ou 5 000 FCFA">{{ old('approx_cost', $document ? $document->approx_cost : '') }}</textarea>
            @error('approx_cost')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Délais moyens</label>
            <textarea name="approx_delay" rows="2"
                      class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                      placeholder="ex: 7 à 21 jours">{{ old('approx_delay', $document ? $document->approx_delay : '') }}</textarea>
            @error('approx_delay')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Conseils & erreurs à éviter</label>
        <textarea name="tips" rows="4" class="w-full px-4 py-2.5 rounded-lg bg-gray-900/70 border border-gray-700 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('tips', $document ? $document->tips : '') }}</textarea>
        @error('tips')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
</div>

