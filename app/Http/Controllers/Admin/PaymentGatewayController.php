<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentGatewayController extends Controller
{
    /**
     * Afficher la page de configuration des moyens de paiement
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $settings = SiteSetting::first();
        
        // Si aucun paramètre n'existe, créer un objet vide
        if (!$settings) {
            $settings = new SiteSetting();
        }
        
        return view('admin.payment-gateways', compact('settings'));
    }

    /**
     * Mettre à jour les configurations des moyens de paiement
     */
    public function update(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            // Wave
            'wave_merchant_id' => 'nullable|string|max:255',
            'wave_country_code' => 'nullable|string|size:2',
            'wave_enabled' => 'boolean',
            'wave_qr_code' => 'nullable|string|max:500',
            
            // PayPal
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_client_secret' => 'nullable|string|max:255',
            'paypal_mode' => 'nullable|in:sandbox,live',
            'paypal_enabled' => 'boolean',
            
            // Stripe
            'stripe_public_key' => 'nullable|string|max:255',
            'stripe_secret_key' => 'nullable|string|max:255',
            'stripe_webhook_secret' => 'nullable|string|max:255',
            'stripe_enabled' => 'boolean',
        ]);
        
        $settings = SiteSetting::first();
        
        if (!$settings) {
            $settings = new SiteSetting();
        }
        
        // Récupérer les données
        $data = $request->only([
            'wave_merchant_id',
            'wave_country_code',
            'wave_enabled',
            'wave_qr_code',
            'paypal_client_id',
            'paypal_client_secret',
            'paypal_mode',
            'paypal_enabled',
            'stripe_public_key',
            'stripe_secret_key',
            'stripe_webhook_secret',
            'stripe_enabled',
        ]);
        
        // Gérer les checkboxes (si non cochées, elles ne sont pas envoyées)
        $data['wave_enabled'] = $request->has('wave_enabled');
        $data['paypal_enabled'] = $request->has('paypal_enabled');
        $data['stripe_enabled'] = $request->has('stripe_enabled');
        
        // Si le secret PayPal est vide, ne pas le mettre à jour (garder l'ancien)
        if (empty($data['paypal_client_secret'])) {
            unset($data['paypal_client_secret']);
        }
        
        // Si le secret Stripe est vide, ne pas le mettre à jour (garder l'ancien)
        if (empty($data['stripe_secret_key'])) {
            unset($data['stripe_secret_key']);
        }
        
        // Si le webhook secret Stripe est vide, ne pas le mettre à jour (garder l'ancien)
        if (empty($data['stripe_webhook_secret'])) {
            unset($data['stripe_webhook_secret']);
        }
        
        $settings->fill($data);
        $settings->save();
        
        // Invalider le cache
        SiteSetting::clearCache();
        
        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Configurations des moyens de paiement mises à jour avec succès !');
    }
}

