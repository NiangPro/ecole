<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $query = FinanceCategory::orderBy('type')->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $categories = $query->paginate(24)->withQueryString();

        $spentByCategory = FinanceTransaction::selectRaw('finance_category_id, SUM(amount_xof) as total')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->groupBy('finance_category_id')
            ->pluck('total', 'finance_category_id');

        $categories->getCollection()->each(function ($cat) use ($spentByCategory) {
            $cat->current_month_amount = (float) ($spentByCategory[$cat->id] ?? 0);
        });

        return view('admin.finances.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.finances.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_default'] = false;
        $data['is_active'] = true;

        FinanceCategory::create($data);
        FinanceCategory::forgetActiveCache();

        return redirect()->route('admin.finances.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(FinanceCategory $category)
    {
        return view('admin.finances.categories.edit', compact('category'));
    }

    public function update(Request $request, FinanceCategory $category)
    {
        $category->update($this->validated($request));
        FinanceCategory::forgetActiveCache();

        return redirect()->route('admin.finances.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(FinanceCategory $category)
    {
        if ($category->is_default) {
            return back()->with('error', 'Les catégories par défaut ne peuvent pas être supprimées.');
        }

        if ($category->transactions()->exists() || $category->recurrings()->exists()) {
            return back()->with('error', 'Impossible de supprimer une catégorie utilisée par des transactions ou des récurrents.');
        }

        $category->delete();
        FinanceCategory::forgetActiveCache();

        return back()->with('success', 'Catégorie supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'monthly_budget' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
    }
}
