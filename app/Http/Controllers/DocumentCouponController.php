<?php

namespace App\Http\Controllers;

use App\Models\DocumentCoupon;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentCouponController extends Controller
{
    /**
     * Valider un code promo (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'document_id' => 'nullable|exists:documents,id',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $coupon = DocumentCoupon::where('code', $request->code)
            ->active()
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Code promo invalide ou expiré.',
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Ce code promo n\'est plus valide.',
            ]);
        }

        // Si un document spécifique est fourni, vérifier la compatibilité
        if ($request->document_id) {
            $document = Document::findOrFail($request->document_id);
            
            if (!$coupon->canBeUsedFor($document)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Ce code promo ne peut pas être utilisé pour ce document.',
                ]);
            }
        }

        // Si un montant est fourni, vérifier le minimum
        if ($request->amount && $coupon->minimum_amount && $request->amount < $coupon->minimum_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Le montant minimum pour utiliser ce code est de ' . number_format($coupon->minimum_amount, 0, ',', ' ') . ' FCFA.',
            ]);
        }

        // Calculer la réduction
        $discount = 0;
        if ($request->amount) {
            $discount = $coupon->calculateDiscount($request->amount);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Code promo valide !',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount,
                'formatted_discount' => $coupon->type === 'percentage' 
                    ? $coupon->value . '%' 
                    : number_format($coupon->value, 0, ',', ' ') . ' FCFA',
            ],
        ]);
    }

    /**
     * Appliquer un code promo au panier
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = DocumentCoupon::where('code', $request->code)
            ->active()
            ->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Code promo invalide ou expiré.');
        }

        // Stocker le code dans la session
        session(['applied_coupon' => $coupon->id]);

        return back()->with('success', 'Code promo appliqué avec succès !');
    }

    /**
     * Retirer le code promo appliqué
     */
    public function remove()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Code promo retiré.');
    }
}
