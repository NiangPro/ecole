<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    /**
     * Afficher la liste des donations
     */
    public function index(Request $request)
    {
        $query = Donation::with('user')->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Donation::count(),
            'completed' => Donation::where('status', 'completed')->count(),
            'pending' => Donation::where('status', 'pending')->count(),
            'failed' => Donation::where('status', 'failed')->count(),
            'total_amount' => Donation::where('status', 'completed')->sum('amount'),
            'pending_amount' => Donation::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.monetization.donations', compact('donations', 'stats'));
    }

    /**
     * Afficher les détails d'une donation
     */
    public function show($id)
    {
        $donation = Donation::with(['user', 'payment'])->findOrFail($id);
        return view('admin.monetization.donation-show', compact('donation'));
    }

    /**
     * Créer une nouvelle donation (manuelle)
     */
    public function create()
    {
        return view('admin.monetization.donation-create');
    }

    /**
     * Enregistrer une nouvelle donation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|string|in:wave,mobile_money,bank_transfer,stripe,paypal,cash,other',
            'payment_reference' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'show_on_wall' => 'boolean',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $donation = Donation::create([
            'user_id' => $request->user_id ?? null,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'message' => $request->message,
            'is_anonymous' => $request->has('is_anonymous'),
            'show_on_wall' => $request->has('show_on_wall'),
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        // Créer le paiement associé si la donation est complétée
        if ($request->status === 'completed') {
            Payment::create([
                'user_id' => $donation->user_id,
                'paymentable_type' => Donation::class,
                'paymentable_id' => $donation->id,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => 'completed',
                'payment_method' => $donation->payment_method,
                'payment_gateway' => $donation->payment_method,
                'transaction_id' => 'DON-' . strtoupper(uniqid()),
                'payment_reference' => $donation->payment_reference ?? 'MAN-' . $donation->id,
                'paid_at' => now(),
            ]);
        }

        return redirect()->route('admin.monetization.donations')
            ->with('success', 'Donation créée avec succès');
    }

    /**
     * Éditer une donation
     */
    public function edit($id)
    {
        $donation = Donation::with('payment')->findOrFail($id);
        return view('admin.monetization.donation-edit', compact('donation'));
    }

    /**
     * Mettre à jour une donation
     */
    public function update(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|string|in:wave,mobile_money,bank_transfer,stripe,paypal,cash,other',
            'payment_reference' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'show_on_wall' => 'boolean',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldStatus = $donation->status;
        $newStatus = $request->status;

        $donation->update([
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => $newStatus,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'message' => $request->message,
            'is_anonymous' => $request->has('is_anonymous'),
            'show_on_wall' => $request->has('show_on_wall'),
            'completed_at' => $newStatus === 'completed' ? ($donation->completed_at ?? now()) : null,
        ]);

        // Gérer le paiement associé
        $payment = $donation->payment;
        
        if ($newStatus === 'completed' && !$payment) {
            // Créer le paiement si la donation passe à "completed"
            Payment::create([
                'user_id' => $donation->user_id,
                'paymentable_type' => Donation::class,
                'paymentable_id' => $donation->id,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => 'completed',
                'payment_method' => $donation->payment_method,
                'payment_gateway' => $donation->payment_method,
                'transaction_id' => 'DON-' . strtoupper(uniqid()),
                'payment_reference' => $donation->payment_reference ?? 'MAN-' . $donation->id,
                'paid_at' => now(),
            ]);
        } elseif ($payment) {
            // Mettre à jour le statut du paiement
            $payment->update([
                'status' => $newStatus === 'completed' ? 'completed' : ($newStatus === 'failed' ? 'failed' : 'pending'),
                'amount' => $donation->amount,
                'paid_at' => $newStatus === 'completed' ? ($payment->paid_at ?? now()) : null,
            ]);
        }

        return redirect()->route('admin.monetization.donations')
            ->with('success', 'Donation mise à jour avec succès');
    }

    /**
     * Supprimer une donation
     */
    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);

        // Supprimer le paiement associé
        if ($donation->payment) {
            $donation->payment->delete();
        }

        $donation->delete();

        return redirect()->route('admin.monetization.donations')
            ->with('success', 'Donation supprimée avec succès');
    }

    /**
     * Marquer une donation comme complétée
     */
    public function markCompleted($id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'completed') {
            return redirect()->back()
                ->with('info', 'Cette donation est déjà complétée');
        }

        DB::transaction(function () use ($donation) {
            $donation->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Créer ou mettre à jour le paiement
            $payment = $donation->payment;
            if (!$payment) {
                Payment::create([
                    'user_id' => $donation->user_id,
                    'paymentable_type' => Donation::class,
                    'paymentable_id' => $donation->id,
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => 'completed',
                    'payment_method' => $donation->payment_method,
                    'payment_gateway' => $donation->payment_method,
                    'transaction_id' => 'DON-' . strtoupper(uniqid()),
                    'payment_reference' => $donation->payment_reference ?? 'MAN-' . $donation->id,
                    'paid_at' => now(),
                ]);
            } else {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        });

        return redirect()->back()
            ->with('success', 'Donation marquée comme complétée');
    }

    /**
     * Marquer une donation comme échouée
     */
    public function markFailed($id, Request $request)
    {
        $donation = Donation::findOrFail($id);

        $donation->update([
            'status' => 'failed',
        ]);

        // Mettre à jour le paiement si existe
        if ($donation->payment) {
            $donation->payment->update([
                'status' => 'failed',
                'failure_reason' => $request->get('reason', 'Paiement échoué'),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Donation marquée comme échouée');
    }

    /**
     * Exporter les donations en CSV
     */
    public function export(Request $request)
    {
        $query = Donation::with('user')->orderBy('created_at', 'desc');

        // Appliquer les mêmes filtres que l'index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->get();

        $filename = 'donations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Donateur',
                'Email',
                'Montant',
                'Devise',
                'Statut',
                'Méthode de Paiement',
                'Référence',
                'Anonyme',
                'Afficher sur le mur',
                'Message',
                'Date de création',
                'Date de complétion'
            ]);

            // Données
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id,
                    $donation->donor_name,
                    $donation->donor_email ?? '',
                    $donation->amount,
                    $donation->currency,
                    $donation->status,
                    $donation->payment_method,
                    $donation->payment_reference ?? '',
                    $donation->is_anonymous ? 'Oui' : 'Non',
                    $donation->show_on_wall ? 'Oui' : 'Non',
                    $donation->message ?? '',
                    $donation->created_at->format('Y-m-d H:i:s'),
                    $donation->completed_at ? $donation->completed_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistiques des donations
     */
    public function statistics()
    {
        $stats = [
            'total_donations' => Donation::count(),
            'completed_donations' => Donation::where('status', 'completed')->count(),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'failed_donations' => Donation::where('status', 'failed')->count(),
            'total_amount' => Donation::where('status', 'completed')->sum('amount'),
            'pending_amount' => Donation::where('status', 'pending')->sum('amount'),
            'average_donation' => Donation::where('status', 'completed')->avg('amount'),
            'anonymous_count' => Donation::where('is_anonymous', true)->where('status', 'completed')->count(),
            'public_count' => Donation::where('is_anonymous', false)->where('status', 'completed')->count(),
        ];

        // Donations par mois (12 derniers mois)
        $donationsByMonth = Donation::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 10 donateurs
        $topDonors = Donation::where('status', 'completed')
            ->where('is_anonymous', false)
            ->select('donor_name', 'donor_email', DB::raw('SUM(amount) as total_donated'), DB::raw('COUNT(*) as donation_count'))
            ->groupBy('donor_name', 'donor_email')
            ->orderBy('total_donated', 'desc')
            ->take(10)
            ->get();

        return view('admin.monetization.donation-statistics', compact('stats', 'donationsByMonth', 'topDonors'));
    }
}

