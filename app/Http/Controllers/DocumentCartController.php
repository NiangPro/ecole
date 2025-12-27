<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCart;
use App\Models\DocumentPurchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Concerns\LocaleTrait;

class DocumentCartController extends Controller
{
    use LocaleTrait;

    /**
     * Afficher le panier
     */
    public function index()
    {
        $this->ensureLocale();
        
        $cartItems = DocumentCart::getCurrentCart();
        $total = DocumentCart::getTotal($cartItems);
        
        return view('documents.cart', compact('cartItems', 'total'));
    }

    /**
     * Ajouter un document au panier
     */
    public function add(Request $request, $documentId)
    {
        $this->ensureLocale();
        
        $document = Document::published()->active()->findOrFail($documentId);
        
        // Vérifier si l'utilisateur a déjà acheté ce document
        if (Auth::check()) {
            $existingPurchase = DocumentPurchase::where('user_id', Auth::id())
                ->where('document_id', $document->id)
                ->where('status', 'completed')
                ->exists();
            
            if ($existingPurchase) {
                return redirect()->back()
                    ->with('info', 'Vous avez déjà acheté ce document.');
            }
        }
        
        $price = $document->hasDiscount() ? $document->discount_price : $document->price;
        
        // Vérifier si le document est déjà dans le panier
        if (Auth::check()) {
            $existingCartItem = DocumentCart::forUser(Auth::id())
                ->where('document_id', $document->id)
                ->first();
        } else {
            $existingCartItem = DocumentCart::forSession(session()->getId())
                ->where('document_id', $document->id)
                ->first();
        }
        
        if ($existingCartItem) {
            return redirect()->back()
                ->with('info', 'Ce document est déjà dans votre panier.');
        }
        
        // Ajouter au panier
        DocumentCart::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'session_id' => Auth::check() ? null : session()->getId(),
            'document_id' => $document->id,
            'quantity' => 1,
            'price' => $price,
        ]);
        
        return redirect()->back()
            ->with('success', 'Document ajouté au panier avec succès.');
    }

    /**
     * Mettre à jour la quantité d'un item
     */
    public function update(Request $request, $cartItemId)
    {
        $this->ensureLocale();
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);
        
        $cartItem = DocumentCart::findOrFail($cartItemId);
        
        // Vérifier que l'item appartient à l'utilisateur ou à la session
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            if ($cartItem->session_id !== session()->getId()) {
                abort(403);
            }
        }
        
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);
        
        // Si c'est une requête AJAX, retourner JSON
        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Recharger l'item pour obtenir le nouveau subtotal
            $cartItem->refresh();
            $cartItems = DocumentCart::getCurrentCart();
            $total = DocumentCart::getTotal($cartItems);
            
            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour avec succès.',
                'item_subtotal' => number_format($cartItem->subtotal, 0, ',', ' ') . ' FCFA',
                'total' => number_format($total, 0, ',', ' ') . ' FCFA',
                'total_raw' => $total,
            ]);
        }
        
        return redirect()->route('documents.cart')
            ->with('success', 'Panier mis à jour avec succès.');
    }

    /**
     * Retirer un document du panier
     */
    public function remove($cartItemId)
    {
        $this->ensureLocale();
        
        $cartItem = DocumentCart::findOrFail($cartItemId);
        
        // Vérifier que l'item appartient à l'utilisateur ou à la session
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            if ($cartItem->session_id !== session()->getId()) {
                abort(403);
            }
        }
        
        $cartItem->delete();
        
        return redirect()->route('documents.cart')
            ->with('success', 'Document retiré du panier.');
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        $this->ensureLocale();
        
        DocumentCart::clear();
        
        return redirect()->route('documents.cart')
            ->with('success', 'Panier vidé avec succès.');
    }

    /**
     * API: Obtenir le total du panier (AJAX)
     */
    public function getTotal()
    {
        $cartItems = DocumentCart::getCurrentCart();
        $total = DocumentCart::getTotal($cartItems);
        $count = $cartItems->count();
        
        return response()->json([
            'total' => $total,
            'count' => $count,
            'formatted_total' => number_format($total, 0, ',', ' ') . ' FCFA',
        ]);
    }

    /**
     * Afficher la page de sélection de méthode de paiement
     */
    public function checkoutPayment()
    {
        $this->ensureLocale();
        
        $cartItems = DocumentCart::getCurrentCart();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('documents.cart')
                ->with('error', 'Votre panier est vide.');
        }
        
        // Vérifier que tous les documents sont toujours disponibles
        foreach ($cartItems as $item) {
            if (!$item->document || !$item->document->is_active || $item->document->status !== 'published') {
                return redirect()->route('documents.cart')
                    ->with('error', 'Un ou plusieurs documents ne sont plus disponibles.');
            }
        }
        
        $total = DocumentCart::getTotal($cartItems);
        $isGuest = !Auth::check();
        
        // Vérifier si un coupon est appliqué
        $appliedCoupon = null;
        $couponId = session('applied_coupon');
        if ($couponId) {
            $appliedCoupon = \App\Models\DocumentCoupon::where('id', $couponId)
                ->active()
                ->first();
        }
        
        return view('documents.checkout', compact('cartItems', 'total', 'isGuest', 'appliedCoupon'));
    }

    /**
     * Traiter le checkout et créer les paiements
     */
    public function processCheckout(Request $request)
    {
        $this->ensureLocale();
        
        // Validation selon si l'utilisateur est connecté ou non
        if (Auth::check()) {
            $request->validate([
                'payment_method' => 'required|in:wave,paypal',
                'customer_phone' => 'nullable|string|max:20',
                'country_code' => 'nullable|string|max:5',
            ]);
        } else {
            $request->validate([
                'payment_method' => 'required|in:wave,paypal',
                'customer_email' => 'required|email|max:255',
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'country_code' => 'nullable|string|max:5',
            ]);
        }
        
        $user = Auth::user();
        $cartItems = DocumentCart::getCurrentCart();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('documents.cart')
                ->with('error', 'Votre panier est vide.');
        }
        
        // Vérifier que tous les documents sont toujours disponibles
        foreach ($cartItems as $item) {
            if (!$item->document || !$item->document->is_active || $item->document->status !== 'published') {
                return redirect()->route('documents.cart')
                    ->with('error', 'Un ou plusieurs documents ne sont plus disponibles.');
            }
            
            // Vérifier si l'utilisateur a déjà acheté ce document (seulement si connecté)
            if ($user) {
                $existingPurchase = DocumentPurchase::where('user_id', $user->id)
                    ->where('document_id', $item->document_id)
                    ->where('status', 'completed')
                    ->exists();
                
                if ($existingPurchase) {
                    // Retirer du panier si déjà acheté
                    $item->delete();
                }
            } else {
                // Pour les visiteurs anonymes, vérifier par email
                $existingPurchase = DocumentPurchase::where('customer_email', $request->customer_email)
                    ->where('document_id', $item->document_id)
                    ->where('status', 'completed')
                    ->exists();
                
                if ($existingPurchase) {
                    $item->delete();
                }
            }
        }
        
        // Recharger les items après nettoyage
        $cartItems = DocumentCart::getCurrentCart();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('documents.cart')
                ->with('info', 'Tous les documents de votre panier ont déjà été achetés.');
        }
        
        // Appliquer code promo si fourni
        $coupon = null;
        $totalDiscount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\DocumentCoupon::where('code', $request->coupon_code)
                ->active()
                ->first();
            
            if ($coupon && $coupon->isValid()) {
                // Vérifier que le coupon peut être utilisé pour au moins un document
                $canUseCoupon = false;
                foreach ($cartItems as $item) {
                    if ($coupon->canBeUsedFor($item->document)) {
                        $canUseCoupon = true;
                        break;
                    }
                }
                
                if ($canUseCoupon) {
                    session(['applied_coupon' => $coupon->id]);
                } else {
                    $coupon = null;
                }
            } else {
                $coupon = null;
            }
        } else {
            // Vérifier si un coupon est en session
            $couponId = session('applied_coupon');
            if ($couponId) {
                $coupon = \App\Models\DocumentCoupon::where('id', $couponId)
                    ->active()
                    ->first();
            }
        }
        
        // Appliquer le coupon une seule fois si valide
        $couponApplied = false;
        if ($coupon && $coupon->isValid()) {
            // Vérifier que le coupon peut être utilisé pour au moins un document
            foreach ($cartItems as $item) {
                if ($coupon->canBeUsedFor($item->document)) {
                    $couponApplied = true;
                    break;
                }
            }
            if ($couponApplied) {
                $coupon->apply(); // Incrémenter usage_count une seule fois
            }
        }
        
        // Créer un DocumentPurchase et un Payment pour chaque document
        $payments = [];
        
        foreach ($cartItems as $item) {
            $price = $item->price * $item->quantity;
            
            // Appliquer réduction coupon si applicable
            if ($couponApplied && $coupon && $coupon->canBeUsedFor($item->document)) {
                $discount = $coupon->calculateDiscount($price);
                $price = max(0, $price - $discount); // Ne pas avoir de prix négatif
                $totalDiscount += $discount;
            }
            
            // Créer l'achat
            $purchaseData = [
                'user_id' => $user ? $user->id : null,
                'document_id' => $item->document_id,
                'amount_paid' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'download_limit' => 2,
            ];
            
            // Ajouter les informations client si visiteur anonyme
            if (!$user) {
                $purchaseData['customer_email'] = $request->customer_email;
                $purchaseData['customer_name'] = $request->customer_name;
            }
            
            // Ajouter le téléphone si fourni (pour tous les utilisateurs)
            if ($request->filled('customer_phone') && $request->filled('country_code')) {
                $purchaseData['customer_phone'] = $request->customer_phone;
                $purchaseData['country_code'] = $request->country_code;
                
                // Vérifier si WhatsApp est activé
                $whatsappSettings = \App\Models\WhatsAppSettings::getSettings();
                if ($whatsappSettings->enabled && $whatsappSettings->send_on_purchase) {
                    $purchaseData['whatsapp_enabled'] = true;
                }
            }
            
            $purchase = DocumentPurchase::create($purchaseData);
            
            // Générer le token de téléchargement immédiatement
            $purchase->generateDownloadToken();
            
            // Créer le paiement
            $payment = Payment::create([
                'user_id' => $user ? $user->id : null,
                'paymentable_type' => DocumentPurchase::class,
                'paymentable_id' => $purchase->id,
                'amount' => $price,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_gateway' => $request->payment_method,
                'transaction_id' => 'DOC-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(12)),
                'payment_reference' => 'REF-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10)),
            ]);
            
            // Stocker les informations client dans les détails du paiement
            $paymentDetails = [];
            if (!$user) {
                $paymentDetails['customer_email'] = $request->customer_email;
                $paymentDetails['customer_name'] = $request->customer_name;
            }
            if ($request->filled('customer_phone') && $request->filled('country_code')) {
                $paymentDetails['customer_phone'] = $request->customer_phone;
                $paymentDetails['country_code'] = $request->country_code;
            }
            if (!empty($paymentDetails)) {
                $payment->update(['payment_details' => $paymentDetails]);
            }
            
            // Lier le paiement à l'achat
            $purchase->update(['payment_id' => $payment->id]);
            
            $payments[] = $payment;
        }
        
        // Retirer le coupon de la session après utilisation
        if ($couponApplied) {
            session()->forget('applied_coupon');
        }
        
        // Calculer le montant total
        $totalAmount = array_sum(array_map(function($p) { return $p->amount; }, $payments));
        $firstPayment = $payments[0];
        
        // Si Wave est sélectionné, générer le lien Wave
        if ($request->payment_method === 'wave') {
            $description = 'Achat de documents';
            if (count($payments) === 1) {
                $purchase = $firstPayment->paymentable;
                if ($purchase instanceof DocumentPurchase) {
                    $description = 'Achat: ' . $purchase->document->title;
                }
            }
            
            $waveLink = \App\Services\WavePaymentService::generatePaymentLink(
                (float) $totalAmount,
                $firstPayment->payment_reference,
                $description
            );
            
            // Sauvegarder le lien dans les détails du premier paiement
            $firstPayment->update([
                'payment_details' => array_merge(
                    $firstPayment->payment_details ?? [],
                    ['wave_link' => $waveLink]
                )
            ]);
            
            // Si requête AJAX, retourner JSON avec le lien Wave
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'wave_link' => $waveLink,
                    'payment_id' => $firstPayment->id,
                    'amount' => $totalAmount
                ]);
            }
        }
        
        // Si PayPal est sélectionné, générer le lien PayPal
        if ($request->payment_method === 'paypal') {
            try {
                $description = 'Achat de documents';
                if (count($payments) === 1) {
                    $purchase = $firstPayment->paymentable;
                    if ($purchase instanceof DocumentPurchase) {
                        $description = 'Achat: ' . $purchase->document->title;
                    }
                }
                
                // Créer la commande PayPal
                $order = \App\Services\PayPalPaymentService::createOrder(
                    (float) $totalAmount,
                    'XOF',
                    $description
                );
                
                // Sauvegarder l'ID de commande dans les détails du paiement
                $firstPayment->update([
                    'payment_details' => array_merge(
                        $firstPayment->payment_details ?? [],
                        ['paypal_order_id' => $order['id']]
                    )
                ]);
                
                // Récupérer l'URL d'approbation PayPal
                $approvalUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'];
                
                // Si requête AJAX, retourner JSON avec le lien PayPal
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'paypal_link' => $approvalUrl,
                        'payment_id' => $firstPayment->id,
                        'amount' => $totalAmount
                    ]);
                }
            } catch (\Exception $e) {
                // Si requête AJAX, retourner l'erreur en JSON
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la génération du lien PayPal : ' . $e->getMessage()
                    ], 400);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['payment_method' => 'Erreur lors de la création du paiement PayPal : ' . $e->getMessage()]);
            }
        }
        
        // Si un seul paiement, rediriger vers la confirmation
        if (count($payments) === 1) {
            return redirect()->route('payment.confirm', $payments[0]->id)
                ->with('success', 'Votre achat a été créé. Veuillez compléter le paiement.');
        }
        
        // Si plusieurs paiements, rediriger vers une page de résumé
        // Pour l'instant, on redirige vers le premier paiement
        return redirect()->route('payment.confirm', $payments[0]->id)
            ->with('success', 'Vos achats ont été créés. Veuillez compléter les paiements.');
    }

    /**
     * Page de succès pour les visiteurs anonymes après paiement
     */
    public function guestSuccess($paymentId)
    {
        $this->ensureLocale();
        
        $payment = Payment::findOrFail($paymentId);
        $purchase = $payment->paymentable;
        
        // Vérifier que c'est bien un DocumentPurchase et qu'il est complété
        if (!$purchase instanceof DocumentPurchase || $purchase->status !== 'completed') {
            return redirect()->route('documents.index')
                ->with('error', 'Achat introuvable ou non complété.');
        }
        
        return view('documents.guest-success', compact('payment', 'purchase'));
    }
}

