@extends('admin.layout')

@section('title', 'Récurrents - Admin')

@section('styles')
<style>
    .recx-page { max-width: 1300px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .recx-hero {
        display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .recx-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .recx-hero-icon {
        width: 62px; height: 62px; flex-shrink: 0; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; font-size: 1.7rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 1px solid rgba(6, 182, 212, 0.35);
        position: relative; z-index: 1;
    }

    .recx-hero-text { position: relative; z-index: 1; flex: 1; min-width: 220px; }

    .recx-hero-text h1 {
        font-family: 'Poppins', sans-serif; font-size: 1.9rem; font-weight: 800;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 60%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        margin: 0 0 0.35rem;
    }

    .recx-hero-text p { margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.65); }
    body.light-mode .recx-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .recx-hero-actions { position: relative; z-index: 1; display: flex; gap: 0.6rem; flex-wrap: wrap; }

    .recx-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.15rem; border-radius: 10px;
        text-decoration: none; font-size: 0.85rem; font-weight: 700;
        transition: all 0.2s ease; border: 1px solid transparent; cursor: pointer;
    }

    .recx-btn-primary { background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #04121a; box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3); }
    .recx-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(6, 182, 212, 0.4); }

    .recx-btn-ghost { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.12); color: rgba(255, 255, 255, 0.8); }
    .recx-btn-ghost:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }
    body.light-mode .recx-btn-ghost { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.25); color: #1e293b; }

    /* ---------- Flash ---------- */
    .recx-flash {
        border-radius: 12px; padding: 0.85rem 1.1rem; margin-bottom: 1.5rem; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e;
    }

    /* ---------- Grid ---------- */
    .recx-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.1rem; }

    .recx-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        padding: 1.4rem;
        transition: all 0.25s ease;
    }

    .recx-card:hover { border-color: rgba(6, 182, 212, 0.4); transform: translateY(-3px); box-shadow: 0 12px 28px rgba(6, 182, 212, 0.15); }
    body.light-mode .recx-card { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }
    .recx-card.is-inactive { opacity: 0.55; }

    .recx-card-top { display: flex; align-items: flex-start; gap: 0.85rem; margin-bottom: 1rem; }

    .recx-card-icon {
        width: 46px; height: 46px; flex-shrink: 0; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 1.35rem;
        background: rgba(6, 182, 212, 0.12); border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .recx-card-name { flex: 1; min-width: 0; }

    .recx-card-name strong {
        display: block; font-size: 1rem; font-weight: 700; color: #fff;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    body.light-mode .recx-card-name strong { color: #1e293b; }

    .recx-card-cat { font-size: 0.75rem; color: rgba(255, 255, 255, 0.45); }
    body.light-mode .recx-card-cat { color: rgba(30, 41, 59, 0.55); }

    .recx-status-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; margin-top: 0.35rem; background: #475569; }
    .recx-status-dot.is-active { background: #22c55e; box-shadow: 0 0 8px rgba(34, 197, 94, 0.6); }

    .recx-amount { font-size: 1.5rem; font-weight: 800; font-family: 'Poppins', sans-serif; margin-bottom: 0.85rem; }
    .recx-amount.is-income { color: #22c55e; }
    .recx-amount.is-expense { color: #ef4444; }

    .recx-meta-row {
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.8rem; padding: 0.4rem 0;
        border-top: 1px solid rgba(6, 182, 212, 0.1);
    }

    .recx-meta-row:first-of-type { border-top: none; }

    .recx-meta-row span:first-child { color: rgba(255, 255, 255, 0.45); }
    body.light-mode .recx-meta-row span:first-child { color: rgba(30, 41, 59, 0.55); }
    .recx-meta-row span:last-child { color: rgba(255, 255, 255, 0.85); font-weight: 600; text-align: right; }
    body.light-mode .recx-meta-row span:last-child { color: #1e293b; }

    .recx-due-badge {
        display: inline-block; padding: 0.15rem 0.55rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 700;
        background: rgba(6, 182, 212, 0.15); color: #06b6d4;
    }

    .recx-due-badge.is-overdue { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .recx-due-badge.is-soon { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }

    /* Footer actions */
    .recx-card-footer {
        display: flex; flex-wrap: wrap; gap: 0.5rem;
        margin-top: 1.1rem; padding-top: 0.9rem;
        border-top: 1px solid rgba(6, 182, 212, 0.12);
    }

    .recx-action-btn {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.35rem 0.65rem; border-radius: 8px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.75rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: all 0.15s ease;
    }

    .recx-action-btn:hover { border-color: #06b6d4; color: #06b6d4; }
    .recx-action-btn.is-danger:hover { border-color: #ef4444; color: #ef4444; }
    .recx-action-btn.is-fill { background: rgba(6, 182, 212, 0.12); border-color: rgba(6, 182, 212, 0.3); color: #06b6d4; margin-left: auto; }

    body.light-mode .recx-action-btn { background: rgba(255, 255, 255, 0.6); color: rgba(30, 41, 59, 0.7); border-color: rgba(100, 116, 139, 0.25); }

    .recx-empty {
        padding: 3rem; text-align: center; font-size: 0.9rem; color: rgba(255, 255, 255, 0.4);
        border: 1px dashed rgba(148, 163, 184, 0.25); border-radius: 18px;
    }

    body.light-mode .recx-empty { color: rgba(30, 41, 59, 0.5); }

    /* ---------- Filters ---------- */
    .recx-filters {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.18);
        border-radius: 18px;
        padding: 1.1rem 1.35rem;
        margin-bottom: 1.5rem;
        display: flex; flex-wrap: wrap; gap: 1rem; align-items: end;
    }

    body.light-mode .recx-filters { background: rgba(255, 255, 255, 0.85); border-color: rgba(6, 182, 212, 0.22); }

    .recx-filter-field { display: flex; flex-direction: column; gap: 0.4rem; }

    .recx-filter-field label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: rgba(255, 255, 255, 0.45);
    }

    body.light-mode .recx-filter-field label { color: rgba(30, 41, 59, 0.55); }

    .recx-input {
        padding: 0.55rem 0.85rem;
        background: rgba(10, 10, 26, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
    }

    .recx-input:focus { outline: none; border-color: #06b6d4; }

    body.light-mode .recx-input { background: rgba(255, 255, 255, 0.9); border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .recx-input option { background: #fff; color: #1e293b; }

    .recx-filter-submit {
        padding: 0.6rem 1.25rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; border: none; border-radius: 10px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer;
    }

    .recx-filter-reset { font-size: 0.82rem; color: rgba(255, 255, 255, 0.45); text-decoration: none; padding-bottom: 0.6rem; }
    .recx-filter-reset:hover { color: #06b6d4; }
    body.light-mode .recx-filter-reset { color: rgba(30, 41, 59, 0.55); }

    .recx-pagination { margin-top: 1.5rem; }
    .recx-pagination :is(span, a) {
        background: rgba(15, 23, 42, 0.6) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
        color: rgba(255, 255, 255, 0.75) !important;
    }
    body.light-mode .recx-pagination :is(span, a) {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #1e293b !important;
    }

    /* ---------- Quick add modal ---------- */
    .recx-modal-overlay {
        position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); z-index: 50;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
    }

    .recx-modal {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 1.75rem;
        width: 100%; max-width: 420px;
    }

    body.light-mode .recx-modal { background: rgba(255, 255, 255, 0.97); border-color: rgba(6, 182, 212, 0.3); }

    .recx-modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .recx-modal-head h3 { margin: 0; font-size: 1.1rem; font-weight: 800; color: #fff; }
    body.light-mode .recx-modal-head h3 { color: #1e293b; }

    .recx-modal-close { background: none; border: none; font-size: 1.3rem; line-height: 1; cursor: pointer; color: rgba(255, 255, 255, 0.5); }
    .recx-modal-close:hover { color: #ef4444; }
    body.light-mode .recx-modal-close { color: rgba(30, 41, 59, 0.5); }

    .recx-modal .txnx-input,
    .recx-modal-field {
        width: 100%;
        padding: 0.65rem 0.9rem;
        background: rgba(10, 10, 26, 0.7);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        font-family: 'Inter', sans-serif;
    }

    .recx-modal-field:focus { outline: none; border-color: #06b6d4; }

    body.light-mode .recx-modal-field { background: rgba(255, 255, 255, 0.9); border-color: rgba(6, 182, 212, 0.3); color: #1e293b; }
    body.light-mode .recx-modal-field option { background: #fff; color: #1e293b; }

    .recx-modal-row { display: flex; gap: 0.6rem; }
    .recx-modal-row .recx-modal-field.currency { flex: 0 0 90px; }

    .recx-modal-submit {
        width: 100%; padding: 0.75rem; border: none; border-radius: 10px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; font-weight: 800; font-size: 0.9rem; cursor: pointer;
        transition: all 0.2s ease;
    }

    .recx-modal-submit:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="recx-page">

    <div class="recx-hero">
        <div class="recx-hero-icon">🔁</div>
        <div class="recx-hero-text">
            <h1>Récurrents</h1>
            <p>Dépenses et revenus qui reviennent régulièrement — abonnements, hébergement, revenus publicitaires…</p>
        </div>
        <div class="recx-hero-actions">
            <a href="{{ route('admin.finances.recurring.create') }}" class="recx-btn recx-btn-primary">
                <i class="fas fa-plus"></i> Récurrent
            </a>
            <a href="{{ route('admin.finances.dashboard') }}" class="recx-btn recx-btn-ghost">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="recx-flash"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form method="GET" class="recx-filters">
        <div class="recx-filter-field">
            <label>Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Libellé…" class="recx-input">
        </div>
        <div class="recx-filter-field">
            <label>Catégorie</label>
            <select name="category" class="recx-input">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="recx-filter-field">
            <label>Statut</label>
            <select name="status" class="recx-input">
                <option value="">Tous</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>
        </div>
        <div class="recx-filter-field">
            <label>Tri</label>
            <select name="sort" class="recx-input">
                <option value="due_asc" {{ request('sort', 'due_asc') == 'due_asc' ? 'selected' : '' }}>Échéance ↑</option>
                <option value="due_desc" {{ request('sort') == 'due_desc' ? 'selected' : '' }}>Échéance ↓</option>
                <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Montant ↓</option>
                <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Montant ↑</option>
            </select>
        </div>
        <button type="submit" class="recx-filter-submit"><i class="fas fa-filter"></i> Filtrer</button>
        <a href="{{ route('admin.finances.recurring.index') }}" class="recx-filter-reset">Réinitialiser</a>
    </form>

    @if($recurrings->isEmpty())
    <div class="recx-empty">Aucun récurrent ne correspond à ces filtres.</div>
    @else
    <div class="recx-grid">
        @foreach($recurrings as $r)
        @php
            $isOverdue = $r->next_due_date->isPast();
            $isSoon = !$isOverdue && $r->next_due_date->diffInDays(now()) <= 3;
        @endphp
        <div class="recx-card {{ $r->is_active ? '' : 'is-inactive' }}">
            <div class="recx-card-top">
                <div class="recx-card-icon">{{ $r->category->icon }}</div>
                <div class="recx-card-name">
                    <strong>{{ $r->label }}</strong>
                    <div class="recx-card-cat">{{ $r->category->name }}</div>
                </div>
                <span class="recx-status-dot {{ $r->is_active ? 'is-active' : '' }}" title="{{ $r->is_active ? 'Actif' : 'Inactif' }}"></span>
            </div>

            <div class="recx-amount {{ $r->type === 'income' ? 'is-income' : 'is-expense' }}">
                {{ $r->type === 'income' ? '+' : '-' }}{{ number_format($r->amount, 0, ',', ' ') }} XOF
            </div>

            <div class="recx-meta-row">
                <span>Récurrence</span>
                <span>
                    @if($r->recurrence_type === 'day_of_month')
                        Le {{ $r->recurrence_value }} du mois
                    @else
                        Tous les {{ $r->recurrence_value }} jours
                    @endif
                </span>
            </div>
            <div class="recx-meta-row">
                <span>Prochaine échéance</span>
                <span>
                    {{ $r->next_due_date->format('d/m/Y') }}
                    <span class="recx-due-badge {{ $isOverdue ? 'is-overdue' : ($isSoon ? 'is-soon' : '') }}">
                        {{ $r->next_due_date->diffForHumans() }}
                    </span>
                </span>
            </div>

            <div class="recx-card-footer">
                <button onclick='quickAddFromTemplate({{ $r->id }}, {!! json_encode($r->label) !!}, {{ $r->amount }}, "{{ $r->type }}", {{ $r->finance_category_id }})'
                        class="recx-action-btn">
                    <i class="fas fa-plus"></i> Enregistrer
                </button>
                <a href="{{ route('admin.finances.recurring.edit', $r) }}" class="recx-action-btn"><i class="fas fa-pen"></i> Modifier</a>
                <form method="POST" action="{{ route('admin.finances.recurring.toggle', $r) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="recx-action-btn">
                        <i class="fas fa-power-off"></i> {{ $r->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.finances.recurring.destroy', $r) }}"
                      onsubmit="return confirm('Supprimer ce récurrent ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="recx-action-btn is-danger"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="recx-pagination">{{ $recurrings->links() }}</div>
    @endif

    {{-- Modal Quick Add (pré-rempli depuis un récurrent) --}}
    <div id="quickAddModal" class="recx-modal-overlay" style="display:none;">
        <div class="recx-modal">
            <div class="recx-modal-head">
                <h3>✚ Enregistrer maintenant</h3>
                <button onclick="document.getElementById('quickAddModal').style.display='none'" class="recx-modal-close">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.finances.transactions.quick-store') }}" id="quickAddForm">
                @csrf
                <input type="hidden" name="type" id="qaType">

                <select name="finance_category_id" id="qaCategory" required class="recx-modal-field">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="label" id="qaLabel" placeholder="Libellé" required class="recx-modal-field">

                <div class="recx-modal-row">
                    <input type="number" name="amount" id="qaAmount" placeholder="Montant" min="1" required class="recx-modal-field" style="flex:1;">
                    <select name="currency" class="recx-modal-field currency">
                        <option value="XOF">XOF</option>
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                    </select>
                </div>

                <input type="date" name="transaction_date" id="qaDate" value="{{ date('Y-m-d') }}" required class="recx-modal-field">

                <button type="submit" class="recx-modal-submit">Ajouter la transaction</button>
            </form>
        </div>
    </div>

    <script>
    function quickAddFromTemplate(id, label, amount, type, categoryId) {
        document.getElementById('quickAddModal').style.display = 'flex';
        document.getElementById('qaType').value = type;
        document.getElementById('qaCategory').value = categoryId;
        document.getElementById('qaLabel').value = label;
        document.getElementById('qaAmount').value = amount;
        document.getElementById('qaDate').value = new Date().toISOString().split('T')[0];
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 z-[100] px-4 py-3 rounded-lg shadow-xl text-sm font-medium text-white transition-opacity duration-300 ${isError ? 'bg-red-500' : 'bg-green-500'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }

    document.getElementById('quickAddForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: new FormData(form),
            });

            if (!res.ok) {
                const err = await res.json().catch(() => null);
                showToast(err?.message || "Erreur lors de l'ajout.", true);
                return;
            }

            showToast('✅ Transaction ajoutée.');
            document.getElementById('quickAddModal').style.display = 'none';
            form.reset();
            document.getElementById('qaDate').value = new Date().toISOString().split('T')[0];
        } catch (err) {
            showToast('Erreur réseau, veuillez réessayer.', true);
        } finally {
            submitBtn.disabled = false;
        }
    });
    </script>

</div>
@endsection
