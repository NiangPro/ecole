<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Rapport Finances</title>
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; }
    h1 { font-size: 18px; margin: 0 0 4px; }
    .period { color: #64748b; margin-bottom: 18px; }

    .totals { width: 100%; margin-bottom: 18px; }
    .totals td { width: 33.33%; padding: 10px; border: 1px solid #e2e8f0; text-align: center; }
    .totals .label { display: block; font-size: 10px; color: #64748b; margin-bottom: 4px; }
    .totals .value { font-size: 15px; font-weight: bold; }
    .is-income { color: #16a34a; }
    .is-expense { color: #dc2626; }

    h2 { font-size: 13px; margin: 22px 0 8px; }

    table.data { width: 100%; border-collapse: collapse; }
    table.data th, table.data td { border: 1px solid #e2e8f0; padding: 5px 7px; font-size: 10px; }
    table.data th { background: #f1f5f9; text-align: left; }
    table.data td.amount { text-align: right; white-space: nowrap; }
</style>
</head>
<body>
    <h1>Rapport financier</h1>
    <p class="period">Période du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>

    <table class="totals">
        <tr>
            <td><span class="label">Revenus</span><span class="value is-income">{{ number_format($totalIncome, 0, ',', ' ') }} XOF</span></td>
            <td><span class="label">Dépenses</span><span class="value is-expense">{{ number_format($totalExpense, 0, ',', ' ') }} XOF</span></td>
            @php $balance = $totalIncome - $totalExpense; @endphp
            <td><span class="label">Solde</span><span class="value {{ $balance >= 0 ? 'is-income' : 'is-expense' }}">{{ $balance >= 0 ? '+' : '' }}{{ number_format($balance, 0, ',', ' ') }} XOF</span></td>
        </tr>
    </table>

    <h2>Répartition par catégorie</h2>
    <table class="data">
        <thead>
            <tr><th>Catégorie</th><th>Type</th><th style="text-align:right;">Montant</th></tr>
        </thead>
        <tbody>
            @forelse($byCategory as $cat)
            <tr>
                <td>{{ $cat['icon'] }} {{ $cat['name'] }}</td>
                <td>{{ $cat['type'] === 'income' ? 'Revenu' : 'Dépense' }}</td>
                <td class="amount {{ $cat['type'] === 'income' ? 'is-income' : 'is-expense' }}">{{ number_format($cat['amount'], 0, ',', ' ') }} XOF</td>
            </tr>
            @empty
            <tr><td colspan="3">Aucune donnée sur cette période.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Détail des transactions ({{ $transactions->count() }})</h2>
    <table class="data">
        <thead>
            <tr><th>Date</th><th>Catégorie</th><th>Libellé</th><th style="text-align:right;">Montant</th></tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $t->category->icon }} {{ $t->category->name }}</td>
                <td>{{ $t->label }}</td>
                <td class="amount {{ $t->type === 'income' ? 'is-income' : 'is-expense' }}">{{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount_xof, 0, ',', ' ') }} XOF</td>
            </tr>
            @empty
            <tr><td colspan="4">Aucune transaction sur cette période.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
