<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceExchangeRate;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;

class FinanceTransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:income,expense',
            'category' => 'nullable|exists:finance_categories,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
            'sort' => 'nullable|in:date_asc,date_desc,amount_asc,amount_desc',
        ]);

        $query = FinanceTransaction::with('category');

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->category) {
            $query->where('finance_category_id', $request->category);
        }
        if ($request->month) {
            $query->whereMonth('transaction_date', $request->month);
        }
        if ($request->year) {
            $query->whereYear('transaction_date', $request->year);
        }

        match ($request->get('sort', 'date_desc')) {
            'date_asc' => $query->orderBy('transaction_date', 'asc'),
            'amount_desc' => $query->orderByDesc('amount_xof'),
            'amount_asc' => $query->orderBy('amount_xof', 'asc'),
            default => $query->orderByDesc('transaction_date'),
        };

        $transactions = $query->paginate(25)->withQueryString();
        $categories = FinanceCategory::activeCached();

        return view('admin.finances.transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = FinanceCategory::activeCached()->groupBy('type');
        return view('admin.finances.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->applyExchangeRate($data);

        FinanceTransaction::create($data);
        return redirect()->route('admin.finances.transactions.index')
            ->with('success', 'Transaction ajoutée avec succès.');
    }

    public function edit(FinanceTransaction $transaction)
    {
        $categories = FinanceCategory::activeCached()->groupBy('type');
        return view('admin.finances.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, FinanceTransaction $transaction)
    {
        $data = $this->validated($request);
        $this->applyExchangeRate($data);

        $transaction->update($data);
        return redirect()->route('admin.finances.transactions.index')
            ->with('success', 'Transaction modifiée.');
    }

    public function destroy(FinanceTransaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Transaction supprimée.');
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:XOF,EUR,USD',
            'label' => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $this->applyExchangeRate($data);

        $transaction = FinanceTransaction::create($data)->load('category');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'transaction' => $transaction]);
        }

        return back()->with('success', '✅ Transaction ajoutée rapidement.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'category' => 'nullable|exists:finance_categories,id',
        ]);

        $transactions = FinanceTransaction::with('category')
            ->when($request->year, fn ($q) => $q->whereYear('transaction_date', $request->year))
            ->when($request->month, fn ($q) => $q->whereMonth('transaction_date', $request->month))
            ->when($request->category, fn ($q) => $q->where('finance_category_id', $request->category))
            ->orderByDesc('transaction_date')
            ->get();

        $filename = 'finances-'.now()->format('Y-m').'.csv';
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8'];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Date', 'Type', 'Catégorie', 'Libellé', 'Montant', 'Devise', 'Montant XOF', 'Notes'], ';');
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->transaction_date->format('d/m/Y'),
                    $t->type === 'income' ? 'Revenu' : 'Dépense',
                    $t->category->name,
                    $t->label,
                    number_format($t->amount, 0, ',', ' '),
                    $t->currency,
                    number_format($t->amount_xof, 0, ',', ' '),
                    $t->notes ?? '',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers + ['Content-Disposition' => "attachment; filename={$filename}"]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:XOF,EUR,USD',
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);
    }

    private function applyExchangeRate(array &$data): void
    {
        $data['exchange_rate'] = FinanceExchangeRate::getRate($data['currency']);
        $data['amount_xof'] = FinanceExchangeRate::convert($data['amount'], $data['currency']);
    }
}
