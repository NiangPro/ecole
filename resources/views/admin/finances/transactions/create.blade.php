@extends('admin.layout')

@section('title', 'Nouvelle Transaction - Admin')

@section('styles')
@include('admin.finances.transactions._style')
@endsection

@section('content')
<div class="txn-page">

    <div class="txn-hero">
        <div class="txn-hero-icon">💸</div>
        <div class="txn-hero-text">
            <h1>Nouvelle transaction</h1>
            <p>Enregistrez un revenu ou une dépense pour le suivi financier de la plateforme.</p>
        </div>
        <a href="{{ route('admin.finances.transactions.index') }}" class="txn-hero-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
    <div class="txn-error-summary">
        <strong>Merci de corriger les champs suivants :</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="txn-layout">
        <form method="POST" action="{{ route('admin.finances.transactions.store') }}" class="txn-form-card" id="txnForm">
            @csrf

            <div class="txn-type-toggle">
                <label class="txn-type-option">
                    <input type="radio" name="type" value="income" class="txn-type-radio" id="typeIncome"
                           {{ old('type', 'income') == 'income' ? 'checked' : '' }}>
                    <div class="txn-type-box type-income"><i class="fas fa-arrow-up"></i>&nbsp; Revenu</div>
                </label>
                <label class="txn-type-option">
                    <input type="radio" name="type" value="expense" class="txn-type-radio" id="typeExpense"
                           {{ old('type') == 'expense' ? 'checked' : '' }}>
                    <div class="txn-type-box type-expense"><i class="fas fa-arrow-down"></i>&nbsp; Dépense</div>
                </label>
            </div>

            <div class="txn-field">
                <label class="txn-label"><i class="fas fa-tag"></i> Catégorie</label>
                <select name="finance_category_id" id="categorySelect" class="txn-input" required>
                    <option value="">— Choisir une catégorie —</option>
                    @foreach($categories as $type => $cats)
                    <optgroup label="{{ $type === 'income' ? '↑ Revenus' : '↓ Dépenses' }}">
                        @foreach($cats as $cat)
                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" data-icon="{{ $cat->icon }}" data-name="{{ $cat->name }}"
                            {{ old('finance_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('finance_category_id')<div class="txn-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                <p class="txn-hint">Pas la bonne catégorie ? <a href="{{ route('admin.finances.categories.create') }}" style="color:#06b6d4;">Créez-en une nouvelle</a>.</p>
            </div>

            <div class="txn-field">
                <label class="txn-label"><i class="fas fa-pen"></i> Libellé</label>
                <input type="text" name="label" id="labelInput" class="txn-input" maxlength="255" required
                       placeholder="Ex : Hébergement serveur — août" value="{{ old('label') }}">
                @error('label')<div class="txn-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="txn-row">
                <div class="txn-field">
                    <label class="txn-label"><i class="fas fa-coins"></i> Montant</label>
                    <input type="number" name="amount" id="amountInput" class="txn-input" step="1" min="1" required
                           placeholder="0" value="{{ old('amount') }}">
                    @error('amount')<div class="txn-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                <div class="txn-field txn-currency">
                    <label class="txn-label">Devise</label>
                    <select name="currency" id="currencySelect" class="txn-input">
                        <option value="XOF" {{ old('currency', 'XOF') == 'XOF' ? 'selected' : '' }}>XOF</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                </div>
            </div>

            <div class="txn-field">
                <label class="txn-label"><i class="fas fa-calendar"></i> Date</label>
                <input type="date" name="transaction_date" id="dateInput" class="txn-input" required
                       value="{{ old('transaction_date', date('Y-m-d')) }}">
                @error('transaction_date')<div class="txn-field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>

            <div class="txn-field" style="margin-bottom: 0;">
                <label class="txn-label"><i class="fas fa-align-left"></i> Notes (optionnel)</label>
                <textarea name="notes" id="notesInput" class="txn-input" rows="3">{{ old('notes') }}</textarea>
            </div>

            <div class="txn-actions">
                <button type="submit" class="txn-btn-submit"><i class="fas fa-check"></i> Ajouter la transaction</button>
                <a href="{{ route('admin.finances.transactions.index') }}" class="txn-btn-cancel">Annuler</a>
            </div>
        </form>

        <aside class="txn-preview" id="txnPreview">
            <div class="txn-preview-label">Aperçu en direct</div>
            <div class="txn-preview-icon" id="previewIcon">💰</div>
            <div class="txn-preview-amount is-income" id="previewAmount">0 XOF</div>
            <div class="txn-preview-converted" id="previewConverted"></div>
            <div class="txn-preview-meta">
                <div class="txn-preview-row"><span>Type</span><span id="previewType">Revenu</span></div>
                <div class="txn-preview-row"><span>Catégorie</span><span id="previewCategory">—</span></div>
                <div class="txn-preview-row"><span>Date</span><span id="previewDate">—</span></div>
            </div>
            <p class="txn-preview-hint">Ce récapitulatif se met à jour en direct pendant la saisie, avant l'enregistrement.</p>
        </aside>
    </div>

</div>

<script>
(function () {
    const rates = {
        XOF: 1,
        EUR: {{ \App\Models\FinanceExchangeRate::getRate('EUR') }},
        USD: {{ \App\Models\FinanceExchangeRate::getRate('USD') }},
    };

    const typeIncome = document.getElementById('typeIncome');
    const typeExpense = document.getElementById('typeExpense');
    const categorySelect = document.getElementById('categorySelect');
    const amountInput = document.getElementById('amountInput');
    const currencySelect = document.getElementById('currencySelect');
    const dateInput = document.getElementById('dateInput');

    const previewIcon = document.getElementById('previewIcon');
    const previewAmount = document.getElementById('previewAmount');
    const previewConverted = document.getElementById('previewConverted');
    const previewType = document.getElementById('previewType');
    const previewCategory = document.getElementById('previewCategory');
    const previewDate = document.getElementById('previewDate');

    function formatNumber(n) {
        return Math.round(n).toLocaleString('fr-FR');
    }

    function updatePreview() {
        const isIncome = typeIncome.checked;
        previewType.textContent = isIncome ? 'Revenu' : 'Dépense';
        previewAmount.classList.toggle('is-income', isIncome);
        previewAmount.classList.toggle('is-expense', !isIncome);

        const opt = categorySelect.selectedOptions[0];
        if (opt && opt.value) {
            previewIcon.textContent = opt.dataset.icon || '💰';
            previewCategory.textContent = (opt.dataset.icon || '') + ' ' + (opt.dataset.name || '');
        } else {
            previewIcon.textContent = '💰';
            previewCategory.textContent = '—';
        }

        const amount = parseFloat(amountInput.value) || 0;
        const currency = currencySelect.value;
        previewAmount.textContent = (isIncome ? '+' : '−') + formatNumber(amount) + ' ' + currency;

        if (currency !== 'XOF' && amount > 0) {
            previewConverted.textContent = '≈ ' + formatNumber(amount * rates[currency]) + ' XOF au taux actuel';
        } else {
            previewConverted.textContent = '';
        }

        if (dateInput.value) {
            const d = new Date(dateInput.value + 'T00:00:00');
            previewDate.textContent = d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
        } else {
            previewDate.textContent = '—';
        }
    }

    [typeIncome, typeExpense, categorySelect, amountInput, currencySelect, dateInput].forEach(el => {
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    updatePreview();
})();
</script>
@endsection
