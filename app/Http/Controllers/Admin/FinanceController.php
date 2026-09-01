<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceNotification;
use App\Models\FinanceRecurring;
use App\Models\FinanceTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthIncome = FinanceTransaction::where('type', 'income')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount_xof');

        $monthExpense = FinanceTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount_xof');

        $balance = $monthIncome - $monthExpense;

        $totalIncome = FinanceTransaction::where('type', 'income')->sum('amount_xof');
        $totalExpense = FinanceTransaction::where('type', 'expense')->sum('amount_xof');
        $currentBalance = $totalIncome - $totalExpense;

        $yearIncome = FinanceTransaction::where('type', 'income')
            ->whereYear('transaction_date', $currentYear)->sum('amount_xof');
        $yearExpense = FinanceTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)->sum('amount_xof');

        $budgetCategories = FinanceCategory::where('type', 'expense')
            ->whereNotNull('monthly_budget')
            ->get();

        $spentByCategory = FinanceTransaction::selectRaw('finance_category_id, SUM(amount_xof) as total')
            ->whereIn('finance_category_id', $budgetCategories->pluck('id'))
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->groupBy('finance_category_id')
            ->pluck('total', 'finance_category_id');

        $budgetAlerts = $budgetCategories->filter(function ($cat) use ($spentByCategory) {
            $cat->current_month_amount = (float) ($spentByCategory[$cat->id] ?? 0);
            return $cat->current_month_amount > $cat->monthly_budget;
        });

        $upcomingRecurrings = FinanceRecurring::where('is_active', true)
            ->where('next_due_date', '<=', now()->addDays(30))
            ->orderBy('next_due_date')
            ->with('category')
            ->get();

        $unreadNotifications = FinanceNotification::where('is_read', false)
            ->with('recurring.category')
            ->orderBy('due_date')
            ->get();

        $recentTransactions = FinanceTransaction::with('category')
            ->orderByDesc('transaction_date')
            ->limit(10)
            ->get();

        $oldestTransactionDate = FinanceTransaction::min('transaction_date');
        $minChartYear = $oldestTransactionDate ? Carbon::parse($oldestTransactionDate)->year : $currentYear;

        return view('admin.finances.dashboard', compact(
            'monthIncome', 'monthExpense', 'balance',
            'currentBalance',
            'yearIncome', 'yearExpense',
            'budgetAlerts', 'upcomingRecurrings',
            'unreadNotifications', 'recentTransactions',
            'minChartYear'
        ));
    }

    /**
     * Charge en un seul appel les données du graphique annuel et des deux
     * donuts du dashboard (au lieu de 3 requêtes AJAX séparées au chargement).
     */
    public function dashboardData(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|min:2000|max:2100']);
        $year = $request->get('year', now()->year);

        return response()->json([
            'chart' => $this->buildChartData($year),
            'donutExpense' => $this->buildDonutData('expense', now()->month, now()->year),
            'donutIncome' => $this->buildDonutData('income', now()->month, now()->year),
        ]);
    }

    public function chartData(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|min:2000|max:2100']);
        $year = $request->get('year', now()->year);

        return response()->json($this->buildChartData($year));
    }

    public function donutData(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
            'type' => 'nullable|in:income,expense',
        ]);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $type = $request->get('type', 'expense');

        return response()->json($this->buildDonutData($type, $month, $year));
    }

    private function buildChartData(int $year): array
    {
        $rows = FinanceTransaction::selectRaw('MONTH(transaction_date) as month, type, SUM(amount_xof) as total')
            ->whereYear('transaction_date', $year)
            ->groupBy('month', 'type')
            ->get();

        $income = array_fill(1, 12, 0.0);
        $expense = array_fill(1, 12, 0.0);

        foreach ($rows as $row) {
            if ($row->type === 'income') {
                $income[(int) $row->month] = (float) $row->total;
            } else {
                $expense[(int) $row->month] = (float) $row->total;
            }
        }

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = [
                'month' => Carbon::createFromDate($year, $m, 1)->translatedFormat('M'),
                'income' => $income[$m],
                'expense' => $expense[$m],
                'balance' => $income[$m] - $expense[$m],
            ];
        }

        return $data;
    }

    private function buildDonutData(string $type, int $month, int $year): array
    {
        $sums = FinanceTransaction::selectRaw('finance_category_id, SUM(amount_xof) as total')
            ->where('type', $type)
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->groupBy('finance_category_id')
            ->pluck('total', 'finance_category_id');

        return FinanceCategory::where('type', $type)
            ->whereIn('id', $sums->keys())
            ->get()
            ->map(fn ($cat) => [
                'name' => $cat->name,
                'icon' => $cat->icon,
                'color' => $cat->color,
                'amount' => (float) $sums[$cat->id],
            ])
            ->filter(fn ($c) => $c['amount'] > 0)
            ->sortByDesc('amount')
            ->values()
            ->all();
    }

    public function reports(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'sort' => 'nullable|in:date_asc,date_desc,amount_asc,amount_desc',
            'category' => 'nullable|exists:finance_categories,id',
        ]);

        [$startDate, $endDate, $sortBy, $categoryId, $transactions, $totalIncome, $totalExpense, $byCategory, $categories]
            = $this->buildReportsData($request);

        return view('admin.finances.reports', compact(
            'transactions', 'startDate', 'endDate', 'sortBy', 'categoryId',
            'totalIncome', 'totalExpense', 'byCategory', 'categories'
        ));
    }

    public function exportReportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category' => 'nullable|exists:finance_categories,id',
        ]);

        [$startDate, $endDate, , , $transactions, $totalIncome, $totalExpense, $byCategory]
            = $this->buildReportsData($request, paginate: false);

        $pdf = Pdf::loadView('admin.finances.reports-pdf', compact(
            'transactions', 'startDate', 'endDate', 'totalIncome', 'totalExpense', 'byCategory'
        ));

        return $pdf->download('rapport-finances-'.$startDate.'-au-'.$endDate.'.pdf');
    }

    /**
     * Construit les données communes aux pages Rapports (HTML paginé) et
     * export PDF (liste complète non paginée) à partir des mêmes filtres.
     */
    private function buildReportsData(Request $request, bool $paginate = true): array
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $sortBy = $request->get('sort', 'date_desc');
        $categoryId = $request->get('category');

        $applyFilters = fn ($q) => $q->whereBetween('transaction_date', [$startDate, $endDate])
            ->when($categoryId, fn ($qq) => $qq->where('finance_category_id', $categoryId));

        $query = $applyFilters(FinanceTransaction::with('category'));

        match ($sortBy) {
            'date_asc' => $query->orderBy('transaction_date', 'asc'),
            'amount_desc' => $query->orderByDesc('amount_xof'),
            'amount_asc' => $query->orderBy('amount_xof', 'asc'),
            default => $query->orderByDesc('transaction_date'),
        };

        $transactions = $paginate ? $query->paginate(50)->withQueryString() : $query->get();

        $totals = $applyFilters(FinanceTransaction::query())
            ->selectRaw("SUM(CASE WHEN type='income' THEN amount_xof ELSE 0 END) as total_income, SUM(CASE WHEN type='expense' THEN amount_xof ELSE 0 END) as total_expense")
            ->first();
        $totalIncome = (float) ($totals->total_income ?? 0);
        $totalExpense = (float) ($totals->total_expense ?? 0);

        $byCategoryTotals = $applyFilters(FinanceTransaction::query())
            ->selectRaw('finance_category_id, SUM(amount_xof) as total')
            ->groupBy('finance_category_id')
            ->pluck('total', 'finance_category_id');

        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        $byCategory = $categories
            ->filter(fn ($cat) => ($byCategoryTotals[$cat->id] ?? 0) > 0)
            ->map(fn ($cat) => [
                'name' => $cat->name,
                'icon' => $cat->icon,
                'color' => $cat->color,
                'type' => $cat->type,
                'amount' => (float) $byCategoryTotals[$cat->id],
            ])
            ->sortByDesc('amount')
            ->values();

        return [$startDate, $endDate, $sortBy, $categoryId, $transactions, $totalIncome, $totalExpense, $byCategory, $categories];
    }
}
