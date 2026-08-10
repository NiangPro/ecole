@extends('admin.layout')

@section('title', 'Catégories Finances - Admin')

@section('styles')
<style>
    .catx-page { max-width: 1300px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .catx-hero {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
        flex-wrap: wrap;
    }

    .catx-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .catx-hero-icon {
        width: 62px; height: 62px; flex-shrink: 0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; font-size: 1.7rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 1px solid rgba(6, 182, 212, 0.35);
        position: relative; z-index: 1;
    }

    .catx-hero-text { position: relative; z-index: 1; flex: 1; min-width: 220px; }

    .catx-hero-text h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.9rem; font-weight: 800;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 60%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        margin: 0 0 0.35rem;
    }

    .catx-hero-text p { margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.65); }
    body.light-mode .catx-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .catx-hero-actions { position: relative; z-index: 1; display: flex; gap: 0.6rem; flex-wrap: wrap; }

    .catx-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.15rem; border-radius: 10px;
        text-decoration: none; font-size: 0.85rem; font-weight: 700;
        transition: all 0.2s ease; border: 1px solid transparent; cursor: pointer;
    }

    .catx-btn-primary {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a;
        box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3);
    }

    .catx-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(6, 182, 212, 0.4); }

    .catx-btn-ghost {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.8);
    }

    .catx-btn-ghost:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }

    body.light-mode .catx-btn-ghost { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.25); color: #1e293b; }

    /* ---------- Flash ---------- */
    .catx-flash {
        border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .catx-flash-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; }
    .catx-flash-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }

    /* ---------- Section ---------- */
    .catx-section { margin-bottom: 2.25rem; }

    .catx-section-title {
        display: flex; align-items: center; gap: 0.6rem;
        font-size: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;
        margin-bottom: 1rem;
    }

    .catx-section-title.is-income { color: #22c55e; }
    .catx-section-title.is-expense { color: #ef4444; }

    .catx-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1.1rem;
    }

    /* ---------- Card ---------- */
    .catx-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        padding: 1.4rem;
        transition: all 0.25s ease;
        position: relative;
    }

    .catx-card:hover { border-color: rgba(6, 182, 212, 0.4); transform: translateY(-3px); box-shadow: 0 12px 28px rgba(6, 182, 212, 0.15); }

    body.light-mode .catx-card { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }

    .catx-card.is-inactive { opacity: 0.55; }

    .catx-card-top { display: flex; align-items: flex-start; gap: 0.85rem; margin-bottom: 1rem; }

    .catx-card-icon {
        width: 46px; height: 46px; flex-shrink: 0; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 1.35rem;
    }

    .catx-card-name { flex: 1; min-width: 0; }

    .catx-card-name strong {
        display: block; font-size: 1rem; font-weight: 700; color: #fff;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    body.light-mode .catx-card-name strong { color: #1e293b; }

    .catx-card-tag { font-size: 0.7rem; color: rgba(255, 255, 255, 0.4); font-weight: 500; }
    body.light-mode .catx-card-tag { color: rgba(30, 41, 59, 0.5); }

    .catx-status-dot {
        width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; margin-top: 0.35rem;
        background: #475569;
    }

    .catx-status-dot.is-active { background: #22c55e; box-shadow: 0 0 8px rgba(34, 197, 94, 0.6); }

    /* Budget bar */
    .catx-budget-label {
        display: flex; justify-content: space-between; font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.55); margin-bottom: 0.4rem;
    }

    body.light-mode .catx-budget-label { color: rgba(30, 41, 59, 0.6); }

    .catx-budget-bar { height: 7px; border-radius: 999px; background: rgba(148, 163, 184, 0.2); overflow: hidden; }

    .catx-budget-fill { height: 100%; border-radius: 999px; transition: width 0.4s ease; }
    .catx-budget-fill.ok { background: linear-gradient(90deg, #22c55e, #14b8a6); }
    .catx-budget-fill.warn { background: linear-gradient(90deg, #f59e0b, #f97316); }
    .catx-budget-fill.over { background: linear-gradient(90deg, #ef4444, #dc2626); }

    .catx-no-budget {
        font-size: 0.78rem; color: rgba(255, 255, 255, 0.45);
    }

    body.light-mode .catx-no-budget { color: rgba(30, 41, 59, 0.5); }

    .catx-no-budget strong { color: rgba(255, 255, 255, 0.75); font-weight: 700; }
    body.light-mode .catx-no-budget strong { color: #1e293b; }

    /* Footer actions */
    .catx-card-footer {
        display: flex; justify-content: flex-end; gap: 0.5rem;
        margin-top: 1.1rem; padding-top: 0.9rem;
        border-top: 1px solid rgba(6, 182, 212, 0.12);
    }

    .catx-icon-btn {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .catx-icon-btn:hover { border-color: #06b6d4; color: #06b6d4; }
    .catx-icon-btn.is-danger:hover { border-color: #ef4444; color: #ef4444; }

    body.light-mode .catx-icon-btn { background: rgba(255, 255, 255, 0.6); color: rgba(30, 41, 59, 0.7); border-color: rgba(100, 116, 139, 0.25); }

    .catx-empty {
        padding: 2rem; text-align: center; font-size: 0.85rem; color: rgba(255, 255, 255, 0.4);
        border: 1px dashed rgba(148, 163, 184, 0.25); border-radius: 16px;
    }

    body.light-mode .catx-empty { color: rgba(30, 41, 59, 0.5); }

    /* ---------- Filters ---------- */
    .catx-filters {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        padding: 1.1rem 1.35rem;
        margin-bottom: 1.5rem;
        display: flex; flex-wrap: wrap; gap: 1rem; align-items: end;
    }

    body.light-mode .catx-filters { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }

    .catx-filter-field { display: flex; flex-direction: column; gap: 0.4rem; }

    .catx-filter-field label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: rgba(255, 255, 255, 0.45);
    }

    body.light-mode .catx-filter-field label { color: rgba(30, 41, 59, 0.55); }

    .catx-input {
        padding: 0.55rem 0.85rem;
        background: rgba(10, 10, 26, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
    }

    .catx-input:focus { outline: none; border-color: #06b6d4; }

    body.light-mode .catx-input { background: rgba(255, 255, 255, 0.9); border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .catx-input option { background: #fff; color: #1e293b; }

    .catx-filter-submit {
        padding: 0.6rem 1.25rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; border: none; border-radius: 10px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer;
    }

    .catx-filter-reset { font-size: 0.82rem; color: rgba(255, 255, 255, 0.45); text-decoration: none; padding-bottom: 0.6rem; }
    .catx-filter-reset:hover { color: #06b6d4; }
    body.light-mode .catx-filter-reset { color: rgba(30, 41, 59, 0.55); }

    .catx-pagination { margin-top: 1.5rem; }
    .catx-pagination :is(span, a) {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
        color: rgba(255, 255, 255, 0.75) !important;
    }
    body.light-mode .catx-pagination :is(span, a) {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #1e293b !important;
    }
</style>
@endsection

@section('content')
<div class="catx-page">

    <div class="catx-hero">
        <div class="catx-hero-icon">🏷️</div>
        <div class="catx-hero-text">
            <h1>Catégories</h1>
            <p>Organisez vos revenus et dépenses, et suivez vos budgets mensuels par catégorie.</p>
        </div>
        <div class="catx-hero-actions">
            <a href="{{ route('admin.finances.categories.create') }}" class="catx-btn catx-btn-primary">
                <i class="fas fa-plus"></i> Nouvelle catégorie
            </a>
            <a href="{{ route('admin.finances.dashboard') }}" class="catx-btn catx-btn-ghost">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="catx-flash catx-flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="catx-flash catx-flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <form method="GET" class="catx-filters">
        <div class="catx-filter-field">
            <label>Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom de catégorie…" class="catx-input">
        </div>
        <div class="catx-filter-field">
            <label>Statut</label>
            <select name="status" class="catx-input">
                <option value="">Toutes</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
            </select>
        </div>
        <button type="submit" class="catx-filter-submit"><i class="fas fa-filter"></i> Filtrer</button>
        <a href="{{ route('admin.finances.categories.index') }}" class="catx-filter-reset">Réinitialiser</a>
    </form>

    @php $grouped = $categories->getCollection()->groupBy('type'); @endphp

    @foreach(['income' => '↑ Revenus', 'expense' => '↓ Dépenses'] as $type => $label)
    <div class="catx-section">
        <div class="catx-section-title {{ $type === 'income' ? 'is-income' : 'is-expense' }}">{{ $label }}</div>

        @php $items = $grouped->get($type, collect()); @endphp

        @if($items->isEmpty())
        <div class="catx-empty">Aucune catégorie {{ $type === 'income' ? 'de revenu' : 'de dépense' }} ne correspond.</div>
        @else
        <div class="catx-grid">
            @foreach($items as $cat)
            @php
                $pct = $cat->monthly_budget ? min(100, ($cat->current_month_amount / $cat->monthly_budget) * 100) : null;
                $barClass = $pct === null ? '' : ($pct >= 100 ? 'over' : ($pct >= 70 ? 'warn' : 'ok'));
            @endphp
            <div class="catx-card {{ $cat->is_active ? '' : 'is-inactive' }}">
                <div class="catx-card-top">
                    <div class="catx-card-icon" style="background: {{ $cat->color }}26; border: 1px solid {{ $cat->color }};">
                        {{ $cat->icon }}
                    </div>
                    <div class="catx-card-name">
                        <strong>{{ $cat->name }}</strong>
                        <span class="catx-card-tag">{{ $cat->is_default ? 'Catégorie système' : 'Personnalisée' }}</span>
                    </div>
                    <span class="catx-status-dot {{ $cat->is_active ? 'is-active' : '' }}" title="{{ $cat->is_active ? 'Active' : 'Inactive' }}"></span>
                </div>

                @if($cat->monthly_budget)
                <div class="catx-budget-label">
                    <span>{{ number_format($cat->current_month_amount, 0, ',', ' ') }} / {{ number_format($cat->monthly_budget, 0, ',', ' ') }} XOF</span>
                    <span>{{ round($pct) }}%</span>
                </div>
                <div class="catx-budget-bar"><div class="catx-budget-fill {{ $barClass }}" style="width: {{ $pct }}%;"></div></div>
                @else
                <div class="catx-no-budget">Ce mois-ci : <strong>{{ number_format($cat->current_month_amount, 0, ',', ' ') }} XOF</strong> · pas de budget défini</div>
                @endif

                <div class="catx-card-footer">
                    <a href="{{ route('admin.finances.categories.edit', $cat) }}" class="catx-icon-btn" title="Modifier">
                        <i class="fas fa-pen"></i>
                    </a>
                    @unless($cat->is_default)
                    <form method="POST" action="{{ route('admin.finances.categories.destroy', $cat) }}"
                          onsubmit="return confirm('Supprimer cette catégorie ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="catx-icon-btn is-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endunless
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach

    <div class="catx-pagination">{{ $categories->links() }}</div>

</div>
@endsection
