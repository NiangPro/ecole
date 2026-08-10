@extends('admin.layout')

@section('title', 'Transactions - Admin')

@section('styles')
<style>
    .txnx-page { max-width: 1300px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .txnx-hero {
        display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .txnx-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .txnx-hero-icon {
        width: 62px; height: 62px; flex-shrink: 0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; font-size: 1.7rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 1px solid rgba(6, 182, 212, 0.35);
        position: relative; z-index: 1;
    }

    .txnx-hero-text { position: relative; z-index: 1; flex: 1; min-width: 220px; }

    .txnx-hero-text h1 {
        font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 800;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 60%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        margin: 0 0 0.35rem;
    }

    .txnx-hero-text p { margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.65); }
    body.light-mode .txnx-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .txnx-hero-actions { position: relative; z-index: 1; display: flex; gap: 0.6rem; flex-wrap: wrap; }

    .txnx-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.15rem; border-radius: 10px;
        text-decoration: none; font-size: 0.85rem; font-weight: 700;
        transition: all 0.2s ease; border: 1px solid transparent; cursor: pointer;
    }

    .txnx-btn-primary { background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #04121a; box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3); }
    .txnx-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(6, 182, 212, 0.4); }

    .txnx-btn-ghost { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.12); color: rgba(255, 255, 255, 0.8); }
    .txnx-btn-ghost:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }
    body.light-mode .txnx-btn-ghost { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.25); color: #1e293b; }

    /* ---------- Flash ---------- */
    .txnx-flash {
        border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e;
    }

    /* ---------- Filters ---------- */
    .txnx-filters {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        padding: 1.35rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex; flex-wrap: wrap; gap: 1rem; align-items: end;
    }

    body.light-mode .txnx-filters { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }

    .txnx-filter-field { display: flex; flex-direction: column; gap: 0.4rem; }

    .txnx-filter-field label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: rgba(255, 255, 255, 0.45);
    }

    body.light-mode .txnx-filter-field label { color: rgba(30, 41, 59, 0.55); }

    .txnx-input {
        padding: 0.55rem 0.85rem;
        background: rgba(10, 10, 26, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s ease;
    }

    .txnx-input:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15); }

    body.light-mode .txnx-input { background: rgba(255, 255, 255, 0.9); border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .txnx-input option { background: #fff; color: #1e293b; }

    .txnx-year-input { width: 90px; }

    .txnx-filter-submit {
        padding: 0.6rem 1.25rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; border: none; border-radius: 10px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer;
        transition: all 0.2s ease;
    }

    .txnx-filter-submit:hover { transform: translateY(-2px); }

    .txnx-filter-reset { font-size: 0.82rem; color: rgba(255, 255, 255, 0.45); text-decoration: none; padding-bottom: 0.6rem; }
    .txnx-filter-reset:hover { color: #06b6d4; }
    body.light-mode .txnx-filter-reset { color: rgba(30, 41, 59, 0.55); }

    /* ---------- Sort chips ---------- */
    .txnx-sort { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }

    .txnx-sort-label { font-size: 0.78rem; color: rgba(255, 255, 255, 0.4); margin-right: 0.25rem; }
    body.light-mode .txnx-sort-label { color: rgba(30, 41, 59, 0.5); }

    .txnx-sort-chip {
        padding: 0.35rem 0.85rem; border-radius: 999px;
        font-size: 0.78rem; font-weight: 600;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.55);
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .txnx-sort-chip:hover { border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }
    .txnx-sort-chip.is-active { background: rgba(6, 182, 212, 0.18); border-color: #06b6d4; color: #06b6d4; }

    body.light-mode .txnx-sort-chip { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.2); color: rgba(30, 41, 59, 0.6); }

    /* ---------- Table card ---------- */
    .txnx-table-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        overflow: hidden;
    }

    body.light-mode .txnx-table-card { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }

    .txnx-table-wrap { overflow-x: auto; }
    .txnx-table { width: 100%; border-collapse: collapse; font-size: 0.87rem; }

    .txnx-table thead th {
        text-align: left;
        padding: 0.9rem 1.1rem;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: rgba(255, 255, 255, 0.4);
        border-bottom: 1px solid rgba(6, 182, 212, 0.15);
        white-space: nowrap;
    }

    body.light-mode .txnx-table thead th { color: rgba(30, 41, 59, 0.5); }

    .txnx-table tbody tr { border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.15s ease; }
    .txnx-table tbody tr:last-child { border-bottom: none; }
    .txnx-table tbody tr:hover { background: rgba(6, 182, 212, 0.05); }
    body.light-mode .txnx-table tbody tr { border-bottom-color: rgba(30, 41, 59, 0.06); }

    .txnx-table td { padding: 0.85rem 1.1rem; vertical-align: middle; }

    .txnx-date { color: rgba(255, 255, 255, 0.5); white-space: nowrap; }
    body.light-mode .txnx-date { color: rgba(30, 41, 59, 0.6); }

    .txnx-cat { display: inline-flex; align-items: center; gap: 0.5rem; white-space: nowrap; }
    .txnx-cat-icon {
        width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 0.85rem;
        background: rgba(6, 182, 212, 0.12);
    }

    .txnx-label { color: #fff; font-weight: 500; }
    body.light-mode .txnx-label { color: #1e293b; }

    .txnx-auto-badge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.62rem; font-weight: 700; margin-left: 0.4rem;
        padding: 0.1rem 0.4rem; border-radius: 999px;
        background: rgba(6, 182, 212, 0.15); color: #06b6d4;
    }

    .txnx-amount { text-align: right; font-weight: 700; white-space: nowrap; }
    .txnx-amount.is-income { color: #22c55e; }
    .txnx-amount.is-expense { color: #ef4444; }
    .txnx-amount-converted { display: block; font-size: 0.72rem; font-weight: 400; color: rgba(255, 255, 255, 0.4); }
    body.light-mode .txnx-amount-converted { color: rgba(30, 41, 59, 0.5); }

    .txnx-actions { text-align: right; white-space: nowrap; }

    .txnx-icon-btn {
        width: 30px; height: 30px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.65);
        text-decoration: none; font-size: 0.78rem;
        cursor: pointer; margin-left: 0.35rem;
        transition: all 0.15s ease;
    }

    .txnx-icon-btn:hover { border-color: #06b6d4; color: #06b6d4; }
    .txnx-icon-btn.is-danger:hover { border-color: #ef4444; color: #ef4444; }

    body.light-mode .txnx-icon-btn { background: rgba(255, 255, 255, 0.6); color: rgba(30, 41, 59, 0.6); border-color: rgba(100, 116, 139, 0.25); }

    .txnx-empty { padding: 3rem 1rem; text-align: center; color: rgba(255, 255, 255, 0.4); font-size: 0.88rem; }
    body.light-mode .txnx-empty { color: rgba(30, 41, 59, 0.5); }

    .txnx-pagination { margin-top: 1.25rem; }

    /* Neutralise le style Tailwind par défaut (clair uniquement) de la pagination
       Laravel pour qu'elle reste lisible sur le fond sombre par défaut du panel. */
    .txnx-pagination :is(span, a) {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
        color: rgba(255, 255, 255, 0.75) !important;
    }

    body.light-mode .txnx-pagination :is(span, a) {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #1e293b !important;
    }

    @media (max-width: 640px) {
        .txnx-hero { padding: 1.5rem; }
        .txnx-filters { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')
<div class="txnx-page">

    <div class="txnx-hero">
        <div class="txnx-hero-icon">📋</div>
        <div class="txnx-hero-text">
            <h1>Transactions</h1>
            <p>Historique complet des revenus et dépenses enregistrés.</p>
        </div>
        <div class="txnx-hero-actions">
            <a href="{{ route('admin.finances.transactions.export', request()->query()) }}" class="txnx-btn txnx-btn-ghost">
                <i class="fas fa-download"></i> Export CSV
            </a>
            <a href="{{ route('admin.finances.transactions.create') }}" class="txnx-btn txnx-btn-primary">
                <i class="fas fa-plus"></i> Transaction
            </a>
            <a href="{{ route('admin.finances.dashboard') }}" class="txnx-btn txnx-btn-ghost">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="txnx-flash"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form method="GET" class="txnx-filters">
        <div class="txnx-filter-field">
            <label>Type</label>
            <select name="type" class="txnx-input">
                <option value="">Tous</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenus</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
            </select>
        </div>
        <div class="txnx-filter-field">
            <label>Catégorie</label>
            <select name="category" class="txnx-input">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="txnx-filter-field">
            <label>Mois</label>
            <select name="month" class="txnx-input">
                <option value="">Tous</option>
                @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $name)
                <option value="{{ $i + 1 }}" {{ (int) request('month') === $i + 1 ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="txnx-filter-field">
            <label>Année</label>
            <input type="number" name="year" value="{{ request('year') }}" placeholder="AAAA" class="txnx-input txnx-year-input">
        </div>
        <button type="submit" class="txnx-filter-submit"><i class="fas fa-filter"></i> Filtrer</button>
        <a href="{{ route('admin.finances.transactions.index') }}" class="txnx-filter-reset">Réinitialiser</a>
    </form>

    <div class="txnx-sort">
        <span class="txnx-sort-label">Trier par :</span>
        @foreach(['date_desc' => 'Date ↓', 'date_asc' => 'Date ↑', 'amount_desc' => 'Montant ↓', 'amount_asc' => 'Montant ↑'] as $key => $lbl)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
           class="txnx-sort-chip {{ request('sort', 'date_desc') == $key ? 'is-active' : '' }}">
           {{ $lbl }}
        </a>
        @endforeach
    </div>

    <div class="txnx-table-card">
        <div class="txnx-table-wrap">
        <table class="txnx-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Catégorie</th>
                    <th>Libellé</th>
                    <th style="text-align:right;">Montant</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td class="txnx-date">{{ $t->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="txnx-cat">
                            <span class="txnx-cat-icon">{{ $t->category->icon }}</span>
                            {{ $t->category->name }}
                        </span>
                    </td>
                    <td class="txnx-label">
                        {{ $t->label }}
                        @if($t->is_recurring_instance)
                        <span class="txnx-auto-badge" title="Générée depuis un récurrent"><i class="fas fa-sync"></i> auto</span>
                        @endif
                    </td>
                    <td class="txnx-amount {{ $t->type === 'income' ? 'is-income' : 'is-expense' }}">
                        {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', ' ') }} {{ $t->currency }}
                        @if($t->currency !== 'XOF')
                        <span class="txnx-amount-converted">≈ {{ number_format($t->amount_xof, 0, ',', ' ') }} XOF</span>
                        @endif
                    </td>
                    <td class="txnx-actions">
                        <a href="{{ route('admin.finances.transactions.edit', $t) }}" class="txnx-icon-btn" title="Modifier"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.finances.transactions.destroy', $t) }}" class="inline"
                              style="display:inline;" onsubmit="return confirm('Supprimer cette transaction ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="txnx-icon-btn is-danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="txnx-empty">Aucune transaction ne correspond à ces filtres.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="txnx-pagination">{{ $transactions->links() }}</div>

</div>
@endsection
