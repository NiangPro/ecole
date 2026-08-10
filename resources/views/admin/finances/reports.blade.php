@extends('admin.layout')

@section('title', 'Rapports Finances - Admin')

@section('content')
@include('admin.finances._theme')
<div class="finance-module text-slate-100">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-white">📊 Rapports par période</h1>
        <a href="{{ route('admin.finances.dashboard') }}"
           class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
            ← Dashboard
        </a>
    </div>

    <form method="GET" class="stat-card !p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-slate-400 mb-1">Du</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                   class="border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1">Au</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                   class="border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1">Catégorie</label>
            <select name="category" class="border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 text-sm">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string) $categoryId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1">Tri</label>
            <select name="sort" class="border border-slate-600 bg-slate-900 text-slate-100 rounded-lg px-3 py-2 text-sm">
                <option value="date_desc" {{ $sortBy == 'date_desc' ? 'selected' : '' }}>Date ↓</option>
                <option value="date_asc" {{ $sortBy == 'date_asc' ? 'selected' : '' }}>Date ↑</option>
                <option value="amount_desc" {{ $sortBy == 'amount_desc' ? 'selected' : '' }}>Montant ↓</option>
                <option value="amount_asc" {{ $sortBy == 'amount_asc' ? 'selected' : '' }}>Montant ↑</option>
            </select>
        </div>
        <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Appliquer</button>
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('admin.finances.transactions.export', array_filter(['year' => \Carbon\Carbon::parse($startDate)->year, 'category' => $categoryId])) }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                ⬇ Export CSV
            </a>
            <a href="{{ route('admin.finances.reports.export-pdf', array_filter(['start_date' => $startDate, 'end_date' => $endDate, 'category' => $categoryId])) }}"
               class="bg-slate-700 hover:bg-slate-600 text-slate-100 px-4 py-2 rounded-lg text-sm font-medium">
                ⬇ Export PDF
            </a>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <p class="text-sm text-green-400 font-medium mb-1">Revenus de la période</p>
            <p class="text-2xl font-bold text-green-400">{{ number_format($totalIncome, 0, ',', ' ') }} XOF</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-red-400 font-medium mb-1">Dépenses de la période</p>
            <p class="text-2xl font-bold text-red-400">{{ number_format($totalExpense, 0, ',', ' ') }} XOF</p>
        </div>
        @php $periodBalance = $totalIncome - $totalExpense; @endphp
        <div class="stat-card">
            <p class="text-sm font-medium mb-1 {{ $periodBalance >= 0 ? 'text-cyan-400' : 'text-orange-400' }}">Solde de la période</p>
            <p class="text-2xl font-bold {{ $periodBalance >= 0 ? 'text-cyan-400' : 'text-orange-400' }}">
                {{ $periodBalance >= 0 ? '+' : '' }}{{ number_format($periodBalance, 0, ',', ' ') }} XOF
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 stat-card !p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-700">
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Catégorie</th>
                        <th class="px-4 py-3">Libellé</th>
                        <th class="px-4 py-3 text-right">Montant</th>
                        <th class="px-4 py-3 text-right">≈ XOF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-slate-800 last:border-0">
                        <td class="px-4 py-3 text-slate-400">{{ $t->transaction_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $t->category->icon }} {{ $t->category->name }}</td>
                        <td class="px-4 py-3 text-slate-200">{{ $t->label }}</td>
                        <td class="px-4 py-3 text-right {{ $t->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', ' ') }} {{ $t->currency }}
                        </td>
                        <td class="px-4 py-3 text-right text-slate-400">{{ number_format($t->amount_xof, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucune transaction sur cette période.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $transactions->links() }}</div>
        </div>

        <div class="stat-card">
            <h2 class="font-semibold text-slate-200 mb-3">Répartition par catégorie</h2>
            @forelse($byCategory as $cat)
            <div class="flex justify-between items-center py-1.5 text-sm">
                <span style="color: {{ $cat['color'] }}">{{ $cat['icon'] }} {{ $cat['name'] }}</span>
                <span class="{{ $cat['type'] === 'income' ? 'text-green-400' : 'text-red-400' }} font-medium">
                    {{ number_format($cat['amount'], 0, ',', ' ') }} XOF
                </span>
            </div>
            @empty
            <p class="text-slate-500 text-sm">Aucune donnée.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
