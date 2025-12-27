<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Models\WhatsAppSettings;
use Illuminate\Http\Request;

class WhatsAppSettingsController extends Controller
{
    use LocaleTrait;

    /**
     * Afficher les paramètres WhatsApp
     */
    public function index()
    {
        $this->ensureLocale();
        
        $settings = WhatsAppSettings::getSettings();
        
        return view('admin.whatsapp-settings.index', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres WhatsApp
     */
    public function update(Request $request)
    {
        $this->ensureLocale();
        
        $request->validate([
            'enabled' => 'boolean',
            'api_provider' => 'nullable|string|in:twilio,whatsapp_business',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'webhook_url' => 'nullable|url|max:500',
            'message_template' => 'nullable|string|max:2000',
            'send_on_purchase' => 'boolean',
            'send_on_payment_confirmed' => 'boolean',
        ]);
        
        $settings = WhatsAppSettings::getSettings();
        
        $settings->update([
            'enabled' => $request->has('enabled') ? (bool)$request->enabled : false,
            'api_provider' => $request->api_provider,
            'api_key' => $request->api_key,
            'api_secret' => $request->api_secret,
            'phone_number' => $request->phone_number,
            'webhook_url' => $request->webhook_url,
            'message_template' => $request->message_template,
            'send_on_purchase' => $request->has('send_on_purchase') ? (bool)$request->send_on_purchase : false,
            'send_on_payment_confirmed' => $request->has('send_on_payment_confirmed') ? (bool)$request->send_on_payment_confirmed : false,
        ]);
        
        return redirect()->route('admin.documents.whatsapp-settings.index')
            ->with('success', 'Paramètres WhatsApp mis à jour avec succès.');
    }
}
