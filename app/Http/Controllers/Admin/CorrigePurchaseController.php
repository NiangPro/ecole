<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Models\CorrigePurchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorrigePurchaseController extends Controller
{
    use LocaleTrait;

    public function index(Request $request)
    {
        $this->ensureLocale();

        $query = CorrigePurchase::with(['epreuve', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $purchases = $query->orderByDesc('created_at')->paginate(20)->appends($request->only('status', 'q'));

        $stats = [
            'pending' => CorrigePurchase::where('status', 'pending')->count(),
            'completed' => CorrigePurchase::where('status', 'completed')->count(),
            'revenue' => (float) CorrigePurchase::where('status', 'completed')->sum('amount_paid'),
        ];

        return view('admin.epreuves.purchases', compact('purchases', 'stats'));
    }

    /**
     * Valider manuellement un achat : marque complété, génère le token et livre.
     */
    public function approve($id)
    {
        $purchase = CorrigePurchase::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            if ($purchase->payment_id) {
                Payment::where('id', $purchase->payment_id)->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
            $purchase->markCompletedAndDeliver();
        });

        return back()->with('success', 'Achat validé. Le corrigé a été livré à l\'acheteur.');
    }

    public function cancel($id)
    {
        $purchase = CorrigePurchase::findOrFail($id);

        if ($purchase->status === 'completed') {
            return back()->with('error', 'Impossible d\'annuler un achat déjà complété.');
        }

        DB::transaction(function () use ($purchase) {
            if ($purchase->payment_id) {
                Payment::where('id', $purchase->payment_id)->update(['status' => 'failed']);
            }
            $purchase->update(['status' => 'failed']);
        });

        return back()->with('success', 'Achat annulé.');
    }
}
