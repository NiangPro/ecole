@extends('admin.layout')

@section('title', 'Modifier la catégorie - Admin')

@section('styles')
@include('admin.finances.categories._style')
@endsection

@section('content')
<div class="cat-page">

    <div class="cat-hero">
        <div class="cat-hero-icon">🏷️</div>
        <div class="cat-hero-text">
            <h1>Modifier « {{ $category->name }} »</h1>
            <p>Mettez à jour l'apparence, le type ou le budget de cette catégorie.</p>
        </div>
        <a href="{{ route('admin.finances.categories.index') }}" class="cat-hero-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
    <div class="cat-error-summary">
        <strong>Merci de corriger les champs suivants :</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="cat-layout">
        <form method="POST" action="{{ route('admin.finances.categories.update', $category) }}" class="cat-form-card" id="catForm">
            @csrf
            @method('PUT')

            <div class="cat-type-toggle">
                <label class="cat-type-option">
                    <input type="radio" name="type" value="income" class="cat-type-radio" id="typeIncome"
                           {{ old('type', $category->type) == 'income' ? 'checked' : '' }}>
                    <div class="cat-type-box type-income"><i class="fas fa-arrow-up"></i>&nbsp; Revenu</div>
                </label>
                <label class="cat-type-option">
                    <input type="radio" name="type" value="expense" class="cat-type-radio" id="typeExpense"
                           {{ old('type', $category->type) == 'expense' ? 'checked' : '' }}>
                    <div class="cat-type-box type-expense"><i class="fas fa-arrow-down"></i>&nbsp; Dépense</div>
                </label>
            </div>

            <div class="cat-field">
                <label class="cat-label"><i class="fas fa-signature"></i> Nom</label>
                <input type="text" name="name" id="nameInput" class="cat-input" maxlength="255" required
                       value="{{ old('name', $category->name) }}">
                @error('name')<div class="cat-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="cat-field">
                <label class="cat-label"><i class="fas fa-palette"></i> Couleur</label>
                <div class="cat-color-row">
                    <input type="color" name="color" id="colorInput" class="cat-color-native" value="{{ old('color', $category->color) }}">
                    @foreach(['#06B6D4','#22C55E','#EF4444','#F97316','#8B5CF6','#EC4899','#F59E0B','#14B8A6','#3B82F6','#6B7280'] as $swatch)
                    <span class="cat-swatch" data-color="{{ $swatch }}" style="background: {{ $swatch }};"></span>
                    @endforeach
                </div>
                @error('color')<div class="cat-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="cat-field">
                <label class="cat-label"><i class="fas fa-icons"></i> Icône (emoji)</label>
                <input type="text" name="icon" id="iconInput" class="cat-input" maxlength="50" value="{{ old('icon', $category->icon) }}">
                <div class="cat-icon-row">
                    @foreach(['💰','📈','📉','🏦','💳','🌐','🖥️','👥','🎓','📄','🙏','🤝','💸','📣','💾','🏢'] as $emoji)
                    <span class="cat-icon-choice" data-icon="{{ $emoji }}">{{ $emoji }}</span>
                    @endforeach
                </div>
            </div>

            <div class="cat-field">
                <label class="cat-label"><i class="fas fa-bullseye"></i> Budget mensuel cible XOF (optionnel)</label>
                <input type="number" name="monthly_budget" id="budgetInput" class="cat-input" step="1" min="0"
                       placeholder="Ex : 50000" value="{{ old('monthly_budget', $category->monthly_budget) }}">
                <p class="cat-hint">Une alerte apparaît sur le dashboard si les dépenses du mois dépassent ce montant.</p>
                @error('monthly_budget')<div class="cat-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="cat-field" style="margin-bottom: 0;">
                <label class="cat-switch-row">
                    <span class="cat-switch-text">
                        <strong>Catégorie active</strong>
                        <span>Visible dans les formulaires de saisie</span>
                    </span>
                    <span class="cat-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <span class="cat-switch-track"></span>
                    </span>
                </label>
            </div>

            <div class="cat-actions">
                <button type="submit" class="cat-btn-submit"><i class="fas fa-check"></i> Enregistrer les modifications</button>
                <a href="{{ route('admin.finances.categories.index') }}" class="cat-btn-cancel">Annuler</a>
            </div>
        </form>

        <aside class="cat-preview" id="catPreview">
            <div class="cat-preview-label">Aperçu en direct</div>
            <div class="cat-preview-icon" id="previewIcon">{{ $category->icon }}</div>
            <div class="cat-preview-name" id="previewName">{{ $category->name }}</div>
            <div class="cat-preview-pill" id="previewPill">{{ $category->type === 'income' ? 'Revenu' : 'Dépense' }}</div>
            <div class="cat-preview-meta">
                <div class="cat-preview-row"><span>Budget mensuel</span><span id="previewBudget">{{ $category->monthly_budget ? number_format($category->monthly_budget, 0, ',', ' ').' XOF' : 'Non défini' }}</span></div>
                <div class="cat-preview-row"><span>Statut</span><span id="previewStatus">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></div>
            </div>
        </aside>
    </div>

</div>

<script>
(function () {
    const typeIncome = document.getElementById('typeIncome');
    const nameInput = document.getElementById('nameInput');
    const colorInput = document.getElementById('colorInput');
    const iconInput = document.getElementById('iconInput');
    const budgetInput = document.getElementById('budgetInput');
    const activeInput = document.querySelector('input[name="is_active"]');

    const previewIcon = document.getElementById('previewIcon');
    const previewName = document.getElementById('previewName');
    const previewPill = document.getElementById('previewPill');
    const previewBudget = document.getElementById('previewBudget');
    const previewStatus = document.getElementById('previewStatus');

    function updateActiveSwatch() {
        document.querySelectorAll('.cat-swatch').forEach(s => {
            s.classList.toggle('is-active', s.dataset.color.toLowerCase() === colorInput.value.toLowerCase());
        });
    }

    function updateActiveIconChoice() {
        document.querySelectorAll('.cat-icon-choice').forEach(el => {
            el.classList.toggle('is-active', el.dataset.icon === iconInput.value);
        });
    }

    function updatePreview() {
        const isIncome = typeIncome.checked;
        const color = colorInput.value;

        previewIcon.textContent = iconInput.value || '💰';
        previewIcon.style.background = color + '26';
        previewIcon.style.borderColor = color;

        previewName.textContent = nameInput.value || 'Catégorie';

        previewPill.textContent = isIncome ? 'Revenu' : 'Dépense';
        previewPill.style.background = isIncome ? 'rgba(34,197,94,0.15)' : 'rgba(239,68,68,0.15)';
        previewPill.style.color = isIncome ? '#22c55e' : '#ef4444';

        const budget = parseFloat(budgetInput.value) || 0;
        previewBudget.textContent = budget > 0 ? Math.round(budget).toLocaleString('fr-FR') + ' XOF' : 'Non défini';

        previewStatus.textContent = activeInput.checked ? 'Active' : 'Inactive';

        updateActiveSwatch();
        updateActiveIconChoice();
    }

    document.querySelectorAll('.cat-swatch').forEach(s => {
        s.addEventListener('click', () => { colorInput.value = s.dataset.color; updatePreview(); });
    });

    document.querySelectorAll('.cat-icon-choice').forEach(el => {
        el.addEventListener('click', () => { iconInput.value = el.dataset.icon; updatePreview(); });
    });

    [typeIncome, nameInput, colorInput, iconInput, budgetInput, activeInput].forEach(el => {
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    updatePreview();
})();
</script>
@endsection
