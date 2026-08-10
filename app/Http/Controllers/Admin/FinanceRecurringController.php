<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceRecurring;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceRecurringController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|exists:finance_categories,id',
            'status' => 'nullable|in:active,inactive',
            'sort' => 'nullable|in:due_asc,due_desc,amount_desc,amount_asc',
        ]);

        $query = FinanceRecurring::with('category');

        if ($request->filled('search')) {
            $query->where('label', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('category')) {
            $query->where('finance_category_id', $request->category);
        }
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        match ($request->get('sort', 'due_asc')) {
            'due_desc' => $query->orderByDesc('next_due_date'),
            'amount_desc' => $query->orderByDesc('amount'),
            'amount_asc' => $query->orderBy('amount'),
            default => $query->orderBy('next_due_date'),
        };

        $recurrings = $query->paginate(20)->withQueryString();
        $categories = FinanceCategory::activeCached();

        return view('admin.finances.recurring.index', compact('recurrings', 'categories'));
    }

    public function create()
    {
        $categories = FinanceCategory::activeCached()->groupBy('type');
        return view('admin.finances.recurring.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = true;
        $data['auto_create_transaction'] = $request->boolean('auto_create_transaction');

        $temp = new FinanceRecurring($data);
        $data['next_due_date'] = $temp->calculateNextDueDate(Carbon::parse($data['start_date']))->toDateString();

        FinanceRecurring::create($data);
        return redirect()->route('admin.finances.recurring.index')
            ->with('success', 'Récurrent créé avec succès.');
    }

    public function edit(FinanceRecurring $recurring)
    {
        $categories = FinanceCategory::activeCached()->groupBy('type');
        return view('admin.finances.recurring.edit', compact('recurring', 'categories'));
    }

    public function update(Request $request, FinanceRecurring $recurring)
    {
        $data = $this->validated($request);
        $data['auto_create_transaction'] = $request->boolean('auto_create_transaction');

        if ($data['recurrence_type'] !== $recurring->recurrence_type ||
            (int) $data['recurrence_value'] !== (int) $recurring->recurrence_value) {
            $temp = new FinanceRecurring($data);
            $data['next_due_date'] = $temp->calculateNextDueDate()->toDateString();
        }

        $recurring->update($data);
        return redirect()->route('admin.finances.recurring.index')
            ->with('success', 'Récurrent mis à jour.');
    }

    public function destroy(FinanceRecurring $recurring)
    {
        $recurring->delete();
        return back()->with('success', 'Récurrent supprimé.');
    }

    public function toggle(FinanceRecurring $recurring)
    {
        $recurring->update(['is_active' => !$recurring->is_active]);
        return back()->with('success', $recurring->is_active ? 'Activé.' : 'Désactivé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'label' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'recurrence_type' => 'required|in:day_of_month,every_n_days',
            'recurrence_value' => 'required|integer|min:1|max:365',
            'reminder_days_before' => 'required|integer|min:0|max:30',
            'auto_create_transaction' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);
    }
}
