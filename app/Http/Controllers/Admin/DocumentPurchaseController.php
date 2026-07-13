<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Mail\DocumentPurchaseConfirmation;
use App\Models\DocumentPurchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DocumentPurchaseController extends Controller
{
    use LocaleTrait;

    public function index(Request $request)
    {
        $this->ensureLocale();
        
        $query = DocumentPurchase::with(['user', 'document', 'payment']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $purchases = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.documents.purchases.index', compact('purchases'));
    }

    public function show($id)
    {
        $this->ensureLocale();
        $purchase = DocumentPurchase::with(['user', 'document', 'payment', 'downloads'])
            ->findOrFail($id);
        
        return view('admin.documents.purchases.show', compact('purchase'));
    }

    public function approve($id)
    {
        $this->ensureLocale();
        $purchase = DocumentPurchase::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            // Mettre à jour le paiement si existe
            if ($purchase->payment_id) {
                Payment::where('id', $purchase->payment_id)->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }

            // Mettre à jour l'achat
            $purchase->update([
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            // Incrémenter le compteur de ventes du document
            $purchase->document->incrementSales();
        });

        return redirect()->back()
            ->with('success', 'Achat approuvé avec succès');
    }

    public function sendEmail(Request $request, $id)
    {
        $this->ensureLocale();

        $purchase = DocumentPurchase::with(['user', 'document'])->findOrFail($id);

        if ($purchase->status !== 'completed' || !$purchase->download_token) {
            return redirect()->back()
                ->with('error', "Ce document ne peut pas être partagé par email (achat non complété ou lien de téléchargement indisponible).");
        }

        $request->validate([
            'email' => 'nullable|email',
        ]);

        $email = $request->filled('email')
            ? $request->input('email')
            : ($purchase->customer_email ?? ($purchase->user ? $purchase->user->email : null));

        if (!$email) {
            return redirect()->back()
                ->with('error', "Aucune adresse email disponible pour cet achat.");
        }

        Mail::to($email)->send(new DocumentPurchaseConfirmation($purchase));

        return redirect()->back()
            ->with('success', "Document envoyé par email à {$email}.");
    }

    public function updateDownloadLimit(Request $request, $id)
    {
        $this->ensureLocale();
        $purchase = DocumentPurchase::findOrFail($id);

        $request->validate([
            'download_limit' => 'required|integer|min:0|max:100',
        ]);

        $purchase->update(['download_limit' => $request->input('download_limit')]);

        return redirect()->back()
            ->with('success', "Limite de téléchargement mise à jour ({$purchase->download_limit}).");
    }

    public function cancel($id)
    {
        $this->ensureLocale();
        $purchase = DocumentPurchase::findOrFail($id);

        if ($purchase->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Impossible d\'annuler un achat déjà complété.');
        }

        DB::transaction(function () use ($purchase) {
            // Mettre à jour le paiement si existe
            if ($purchase->payment_id) {
                Payment::where('id', $purchase->payment_id)->update([
                    'status' => 'cancelled',
                ]);
            }

            // Mettre à jour l'achat
            $purchase->update([
                'status' => 'cancelled',
            ]);
        });

        return redirect()->back()
            ->with('success', 'Achat annulé avec succès');
    }
}
