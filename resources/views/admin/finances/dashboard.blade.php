@extends('admin.layout')

@section('title', 'Gestion Financière - Admin')

@section('content')
@include('admin.finances._theme')
<div class="finance-module text-slate-100">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-white">💰 Gestion Financière</h1>
        <div class="flex flex-wrap gap-2">
            <button onclick="document.getElementById('quickAddModal').classList.remove('hidden')"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                ⚡ Ajout rapide
            </button>
            <a href="{{ route('admin.finances.transactions.create') }}"
               class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Transaction
            </a>
            <a href="{{ route('admin.finances.recurring.create') }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                + Récurrent
            </a>
            <a href="{{ route('admin.finances.categories.create') }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                + Catégorie
            </a>
            <a href="{{ route('admin.finances.reports') }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                📊 Rapports
            </a>
        </div>
    </div>

    <div class="flex flex-wrap gap-4 text-sm mb-6">
        <a href="{{ route('admin.finances.categories.index') }}" class="text-cyan-400 hover:underline">🏷️ Catégories</a>
        <a href="{{ route('admin.finances.recurring.index') }}" class="text-cyan-400 hover:underline">🔁 Récurrents</a>
        <a href="{{ route('admin.finances.notifications.index') }}" class="text-cyan-400 hover:underline">🔔 Notifications</a>
        <a href="{{ route('admin.finances.exchange-rates.index') }}" class="text-cyan-400 hover:underline">💱 Taux de change</a>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg p-3 mb-4 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Alertes budget --}}
    @if($budgetAlerts->count())
    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
        <p class="font-semibold text-red-400">⚠️ Budget dépassé ce mois :</p>
        @foreach($budgetAlerts as $cat)
        <p class="text-red-300 text-sm mt-1">{{ $cat->icon }} {{ $cat->name }} —
            {{ number_format($cat->current_month_amount, 0, ',', ' ') }} XOF
            / {{ number_format($cat->monthly_budget, 0, ',', ' ') }} XOF
        </p>
        @endforeach
    </div>
    @endif

    {{-- Notifications non lues --}}
    @if($unreadNotifications->count())
    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <p class="font-semibold text-yellow-400">🔔 {{ $unreadNotifications->count() }} rappel(s) en attente</p>
            <form method="POST" action="{{ route('admin.finances.notifications.read-all') }}">
                @csrf @method('PATCH')
                <button class="text-xs text-yellow-300 underline">Tout marquer lu</button>
            </form>
        </div>
        @foreach($unreadNotifications as $notif)
        <div class="flex justify-between items-center mt-2 text-sm gap-2">
            <span class="text-yellow-200">{{ $notif->title }} — {{ $notif->due_date->format('d/m/Y') }}</span>
            <form method="POST" action="{{ route('admin.finances.notifications.read', $notif) }}">
                @csrf @method('PATCH')
                <button class="text-xs text-slate-400 hover:text-slate-200">✓ Lu</button>
            </form>
        </div>
        @endforeach
    </div>
    @endif

    {{-- KPIs du mois --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <p class="text-sm text-green-400 font-medium mb-1">Revenus ce mois</p>
            <p id="kpiMonthIncome" class="text-3xl font-bold text-green-400">{{ number_format($monthIncome, 0, ',', ' ') }} <span class="text-lg">XOF</span></p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-red-400 font-medium mb-1">Dépenses ce mois</p>
            <p id="kpiMonthExpense" class="text-3xl font-bold text-red-400">{{ number_format($monthExpense, 0, ',', ' ') }} <span class="text-lg">XOF</span></p>
        </div>
        <div class="stat-card">
            <p id="kpiMonthBalanceLabel" class="text-sm font-medium mb-1 {{ $balance >= 0 ? 'text-cyan-400' : 'text-orange-400' }}">Solde ce mois</p>
            <p id="kpiMonthBalance" class="text-3xl font-bold {{ $balance >= 0 ? 'text-cyan-400' : 'text-orange-400' }}">
                {{ $balance >= 0 ? '+' : '' }}{{ number_format($balance, 0, ',', ' ') }} <span class="text-lg">XOF</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm text-slate-400">
        <div class="stat-card !p-4">Revenus {{ date('Y') }} : <span class="text-green-400 font-semibold">{{ number_format($yearIncome, 0, ',', ' ') }} XOF</span></div>
        <div class="stat-card !p-4">Dépenses {{ date('Y') }} : <span class="text-red-400 font-semibold">{{ number_format($yearExpense, 0, ',', ' ') }} XOF</span></div>
    </div>

    {{-- Graphique annuel --}}
    <div class="stat-card mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-slate-200">📊 Revenus vs Dépenses — {{ date('Y') }}</h2>
            <select id="chartYear" class="text-sm bg-slate-800 border border-slate-600 rounded px-2 py-1 text-slate-100">
                @for($y = date('Y'); $y >= $minChartYear; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <canvas id="financeChart" height="100"></canvas>
    </div>

    {{-- Donut charts --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="stat-card">
            <h2 class="font-semibold text-slate-200 mb-4">🔴 Dépenses par catégorie</h2>
            <canvas id="donutExpense" height="200"></canvas>
            <div id="donutExpenseLegend" class="mt-3 space-y-1 text-sm"></div>
        </div>
        <div class="stat-card">
            <h2 class="font-semibold text-slate-200 mb-4">🟢 Revenus par catégorie</h2>
            <canvas id="donutIncome" height="200"></canvas>
            <div id="donutIncomeLegend" class="mt-3 space-y-1 text-sm"></div>
        </div>
    </div>

    {{-- Prochaines échéances --}}
    <div class="stat-card mb-6">
        <h2 class="font-semibold text-slate-200 mb-4">📅 Prochaines échéances (30 jours)</h2>
        @forelse($upcomingRecurrings as $r)
        <div class="flex justify-between items-center py-2 border-b border-slate-700 last:border-0">
            <div class="flex items-center gap-2">
                <span>{{ $r->category->icon }}</span>
                <span class="text-sm text-slate-300">{{ $r->label }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $r->type === 'income' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                    {{ $r->type === 'income' ? 'Revenu' : 'Dépense' }}
                </span>
                <button onclick='quickAddFromTemplate({{ $r->id }}, {!! json_encode($r->label) !!}, {{ $r->amount }}, "{{ $r->type }}", {{ $r->finance_category_id }})'
                        class="text-xs bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500/20 px-2 py-0.5 rounded">
                    ✚ Enregistrer
                </button>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold {{ $r->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                    {{ number_format($r->amount, 0, ',', ' ') }} XOF
                </p>
                <p class="text-xs text-slate-500">{{ $r->next_due_date->format('d/m/Y') }}</p>
            </div>
        </div>
        @empty
        <p class="text-slate-500 text-sm">Aucune échéance dans les 30 prochains jours.</p>
        @endforelse
    </div>

    {{-- Dernières transactions --}}
    <div class="stat-card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-slate-200">📋 Dernières transactions</h2>
            <a href="{{ route('admin.finances.transactions.index') }}" class="text-sm text-cyan-400 hover:underline">Voir tout</a>
        </div>
        <div id="recentTransactionsList">
        @forelse($recentTransactions as $t)
        <div class="flex justify-between items-center py-2 border-b border-slate-700 last:border-0">
            <div>
                <p class="text-sm font-medium text-slate-200">
                    {{ $t->label }}
                    @if($t->is_recurring_instance)
                    <span class="text-[10px] bg-cyan-500/15 text-cyan-400 px-1.5 py-0.5 rounded-full ml-1" title="Générée depuis un récurrent"><i class="fas fa-sync"></i> auto</span>
                    @endif
                </p>
                <p class="text-xs text-slate-500">{{ $t->category->icon }} {{ $t->category->name }} · {{ $t->transaction_date->format('d/m/Y') }}</p>
            </div>
            <p class="font-semibold {{ $t->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount_xof, 0, ',', ' ') }} XOF
            </p>
        </div>
        @empty
        <p class="finance-empty-recent text-slate-500 text-sm">Aucune transaction pour l'instant.</p>
        @endforelse
        </div>
    </div>

    {{-- Modal Quick Add --}}
    <div id="quickAddModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-white text-lg">⚡ Ajout rapide</h3>
                <button onclick="document.getElementById('quickAddModal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-200 text-xl leading-none">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.finances.transactions.quick-store') }}" id="quickAddForm">
                @csrf

                <div class="flex gap-2 mb-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="income" class="sr-only peer" checked>
                        <div class="text-center py-2 rounded-lg border-2 border-slate-600 peer-checked:border-green-500 peer-checked:bg-green-500/10 peer-checked:text-green-400 text-slate-400 transition">
                            ↑ Revenu
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="expense" class="sr-only peer">
                        <div class="text-center py-2 rounded-lg border-2 border-slate-600 peer-checked:border-red-500 peer-checked:bg-red-500/10 peer-checked:text-red-400 text-slate-400 transition">
                            ↓ Dépense
                        </div>
                    </label>
                </div>

                <select name="finance_category_id" required
                        class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 mb-3">
                    <option value="">— Catégorie —</option>
                    @foreach(\App\Models\FinanceCategory::activeCached()->groupBy('type') as $type => $cats)
                    <optgroup label="{{ $type === 'income' ? '↑ Revenus' : '↓ Dépenses' }}">
                        @foreach($cats as $cat)
                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>

                <input type="text" name="label" placeholder="Libellé" required
                       class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 mb-3">

                <div class="flex gap-2 mb-3">
                    <input type="number" name="amount" placeholder="Montant" min="1" step="1" required
                           class="flex-1 border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2">
                    <select name="currency" class="w-24 border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-2 py-2">
                        <option value="XOF">XOF</option>
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                    </select>
                </div>

                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required
                       class="w-full border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 mb-4">

                <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-2 rounded-lg font-medium">
                    Ajouter la transaction
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    let financeChart;
    let currentChartYear = {{ date('Y') }};

    function isLightMode() {
        return document.body.classList.contains('light-mode');
    }

    function themeColors() {
        return isLightMode()
            ? { tick: '#475569', grid: 'rgba(71,85,105,0.15)', legend: '#1e293b', donutBorder: '#ffffff' }
            : { tick: '#94a3b8', grid: 'rgba(148,163,184,0.1)', legend: '#cbd5e1', donutBorder: '#1e293b' };
    }

    function renderChart(data) {
        const labels   = data.map(d => d.month);
        const incomes  = data.map(d => d.income);
        const expenses = data.map(d => d.expense);
        const balances = data.map(d => d.balance);
        const c = themeColors();

        if (financeChart) financeChart.destroy();

        financeChart = new Chart(document.getElementById('financeChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Revenus',  data: incomes,  backgroundColor: 'rgba(34,197,94,0.7)',  borderColor: '#22C55E', borderWidth: 1 },
                    { label: 'Dépenses', data: expenses, backgroundColor: 'rgba(239,68,68,0.7)',  borderColor: '#EF4444', borderWidth: 1 },
                    { label: 'Solde',    data: balances, type: 'line', borderColor: '#06B6D4', borderWidth: 2, pointBackgroundColor: '#06B6D4', fill: false },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: c.legend }, position: 'top' } },
                scales: {
                    x: { ticks: { color: c.tick }, grid: { color: c.grid } },
                    y: { beginAtZero: true, ticks: { color: c.tick, callback: v => v.toLocaleString('fr-FR') + ' XOF' }, grid: { color: c.grid } }
                }
            }
        });
    }

    async function loadChart(year) {
        currentChartYear = year;
        const res  = await fetch(`{{ route('admin.finances.api.chart') }}?year=${year}`);
        renderChart(await res.json());
    }

    function renderDonut(canvasId, legendId, data) {
        const existing = Chart.getChart(canvasId);
        if (existing) existing.destroy();

        if (!data.length) {
            document.getElementById(legendId).innerHTML = '<p class="text-slate-500">Aucune donnée ce mois-ci.</p>';
            return;
        }

        new Chart(document.getElementById(canvasId), {
            type: 'doughnut',
            data: {
                labels: data.map(d => d.icon + ' ' + d.name),
                datasets: [{
                    data: data.map(d => d.amount),
                    backgroundColor: data.map(d => d.color),
                    borderWidth: 2,
                    borderColor: themeColors().donutBorder
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed.toLocaleString('fr-FR')} XOF` } }
                }
            }
        });

        const legend = document.getElementById(legendId);
        legend.innerHTML = data.map(d =>
            `<div class="flex justify-between"><span style="color:${d.color}">${d.icon} ${d.name}</span><span class="font-medium text-slate-300">${d.amount.toLocaleString('fr-FR')} XOF</span></div>`
        ).join('');
    }

    async function loadDonut(canvasId, legendId, type) {
        const url = `{{ route('admin.finances.api.donut') }}?type=${type}&month={{ now()->month }}&year={{ now()->year }}`;
        renderDonut(canvasId, legendId, await fetch(url).then(r => r.json()));
    }

    function reloadAllCharts() {
        loadChart(currentChartYear);
        loadDonut('donutExpense', 'donutExpenseLegend', 'expense');
        loadDonut('donutIncome', 'donutIncomeLegend', 'income');
    }

    // Chargement initial : un seul appel combiné (chart + 2 donuts) au lieu
    // de 3 requêtes séparées, pour réduire la latence perçue au 1er rendu.
    async function loadInitialDashboard() {
        const res = await fetch(`{{ route('admin.finances.api.dashboard') }}?year=${currentChartYear}`);
        const data = await res.json();
        renderChart(data.chart);
        renderDonut('donutExpense', 'donutExpenseLegend', data.donutExpense);
        renderDonut('donutIncome', 'donutIncomeLegend', data.donutIncome);
    }

    loadInitialDashboard();
    document.getElementById('chartYear').addEventListener('change', e => loadChart(e.target.value));

    // Redessine les graphiques avec les bonnes couleurs quand l'utilisateur
    // bascule le thème (cf. window.dispatchEvent dans admin/layout.blade.php),
    // sans quoi les axes/labels restent calés sur les couleurs du thème sombre.
    window.addEventListener('admin:theme-changed', reloadAllCharts);

    function quickAddFromTemplate(id, label, amount, type, categoryId) {
        document.getElementById('quickAddModal').classList.remove('hidden');
        document.querySelector(`#quickAddForm input[name="type"][value="${type}"]`).checked = true;
        document.querySelector('#quickAddForm select[name="finance_category_id"]').value = categoryId;
        document.querySelector('#quickAddForm input[name="label"]').value = label;
        document.querySelector('#quickAddForm input[name="amount"]').value = amount;
        document.querySelector('#quickAddForm input[name="transaction_date"]').value = new Date().toISOString().split('T')[0];
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 z-[100] px-4 py-3 rounded-lg shadow-xl text-sm font-medium text-white transition-opacity duration-300 ${isError ? 'bg-red-500' : 'bg-green-500'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }

    function updateKpisFromTransaction(t) {
        const today = new Date();
        const txDate = new Date(t.transaction_date);
        if (txDate.getFullYear() !== today.getFullYear() || txDate.getMonth() !== today.getMonth()) return;

        const incomeEl = document.getElementById('kpiMonthIncome');
        const expenseEl = document.getElementById('kpiMonthExpense');
        const balanceEl = document.getElementById('kpiMonthBalance');
        const parseAmount = el => parseInt(el.textContent.replace(/[^\d-]/g, ''), 10) || 0;

        let income = parseAmount(incomeEl);
        let expense = parseAmount(expenseEl);
        const amount = Math.round(parseFloat(t.amount_xof));

        if (t.type === 'income') income += amount; else expense += amount;
        const balance = income - expense;

        incomeEl.innerHTML = `${income.toLocaleString('fr-FR')} <span class="text-lg">XOF</span>`;
        expenseEl.innerHTML = `${expense.toLocaleString('fr-FR')} <span class="text-lg">XOF</span>`;
        balanceEl.innerHTML = `${balance >= 0 ? '+' : ''}${balance.toLocaleString('fr-FR')} <span class="text-lg">XOF</span>`;

        const balanceClass = balance >= 0 ? 'text-cyan-400' : 'text-orange-400';
        balanceEl.className = `text-3xl font-bold ${balanceClass}`;
        document.getElementById('kpiMonthBalanceLabel').className = `text-sm font-medium mb-1 ${balanceClass}`;
    }

    function prependTransaction(t) {
        const container = document.getElementById('recentTransactionsList');
        const empty = container.querySelector('.finance-empty-recent');
        if (empty) empty.remove();

        const row = document.createElement('div');
        row.className = 'flex justify-between items-center py-2 border-b border-slate-700 last:border-0';
        const badge = t.is_recurring_instance
            ? '<span class="text-[10px] bg-cyan-500/15 text-cyan-400 px-1.5 py-0.5 rounded-full ml-1" title="Générée depuis un récurrent"><i class="fas fa-sync"></i> auto</span>'
            : '';
        row.innerHTML = `
            <div>
                <p class="text-sm font-medium text-slate-200">${t.label} ${badge}</p>
                <p class="text-xs text-slate-500">${t.category.icon} ${t.category.name} · ${new Date(t.transaction_date).toLocaleDateString('fr-FR')}</p>
            </div>
            <p class="font-semibold ${t.type === 'income' ? 'text-green-400' : 'text-red-400'}">
                ${t.type === 'income' ? '+' : '-'}${Math.round(t.amount_xof).toLocaleString('fr-FR')} XOF
            </p>`;
        container.prepend(row);
        while (container.children.length > 10) container.lastElementChild.remove();
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

            const { transaction } = await res.json();
            showToast('✅ Transaction ajoutée.');
            document.getElementById('quickAddModal').classList.add('hidden');
            form.reset();
            document.querySelector('#quickAddForm input[name="transaction_date"]').value = new Date().toISOString().split('T')[0];

            prependTransaction(transaction);
            updateKpisFromTransaction(transaction);
            reloadAllCharts();
        } catch (err) {
            showToast('Erreur réseau, veuillez réessayer.', true);
        } finally {
            submitBtn.disabled = false;
        }
    });
    </script>

</div>
@endsection
