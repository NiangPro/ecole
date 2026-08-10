@csrf

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-1">Type</label>
    <select name="type" required class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
        <option value="income" {{ old('type', $recurring->type ?? '') == 'income' ? 'selected' : '' }}>↑ Revenu</option>
        <option value="expense" {{ old('type', $recurring->type ?? '') == 'expense' ? 'selected' : '' }}>↓ Dépense</option>
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-1">Catégorie</label>
    <select name="finance_category_id" required class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
        <option value="">— Choisir —</option>
        @foreach($categories as $type => $cats)
        <optgroup label="{{ $type === 'income' ? '↑ Revenus' : '↓ Dépenses' }}">
            @foreach($cats as $cat)
            <option value="{{ $cat->id }}" {{ old('finance_category_id', $recurring->finance_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icon }} {{ $cat->name }}
            </option>
            @endforeach
        </optgroup>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-1">Libellé</label>
    <input type="text" name="label" required maxlength="255"
           value="{{ old('label', $recurring->label ?? '') }}"
           class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-1">Montant (XOF)</label>
    <input type="number" name="amount" step="1" min="1" required
           value="{{ old('amount', $recurring->amount ?? '') }}"
           class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-2">Récurrence</label>
    <div class="flex gap-4 mb-2">
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="radio" name="recurrence_type" value="day_of_month" id="rtDayOfMonth"
                   {{ old('recurrence_type', $recurring->recurrence_type ?? 'day_of_month') == 'day_of_month' ? 'checked' : '' }}>
            Jour fixe du mois
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="radio" name="recurrence_type" value="every_n_days" id="rtEveryNDays"
                   {{ old('recurrence_type', $recurring->recurrence_type ?? '') == 'every_n_days' ? 'checked' : '' }}>
            Toutes les N jours
        </label>
    </div>
    <input type="number" name="recurrence_value" min="1" max="365" required
           value="{{ old('recurrence_value', $recurring->recurrence_value ?? '') }}"
           placeholder="Ex: 5 (jour du mois) ou 30 (jours)"
           class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-slate-300 mb-1">Rappel (jours avant échéance)</label>
    <input type="number" name="reminder_days_before" min="0" max="30" required
           value="{{ old('reminder_days_before', $recurring->reminder_days_before ?? 3) }}"
           class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
</div>

<div class="flex gap-3 mb-4">
    <div class="flex-1">
        <label class="block text-sm font-medium text-slate-300 mb-1">Date de début</label>
        <input type="date" name="start_date" required
               value="{{ old('start_date', isset($recurring) ? $recurring->start_date->format('Y-m-d') : date('Y-m-d')) }}"
               class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium text-slate-300 mb-1">Date de fin (optionnel)</label>
        <input type="date" name="end_date"
               value="{{ old('end_date', isset($recurring) && $recurring->end_date ? $recurring->end_date->format('Y-m-d') : '') }}"
               class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
    </div>
</div>

<div class="mb-4">
    <label class="flex items-center gap-2 text-sm text-slate-300">
        <input type="checkbox" name="auto_create_transaction" value="1"
               {{ old('auto_create_transaction', $recurring->auto_create_transaction ?? false) ? 'checked' : '' }}>
        Créer automatiquement la transaction à l'échéance
    </label>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-slate-300 mb-1">Notes (optionnel)</label>
    <textarea name="notes" rows="3" class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">{{ old('notes', $recurring->notes ?? '') }}</textarea>
</div>

<div class="flex gap-2">
    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg font-medium">
        {{ isset($recurring) ? 'Enregistrer' : 'Créer' }}
    </button>
    <a href="{{ route('admin.finances.recurring.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-5 py-2 rounded-lg">Annuler</a>
</div>
