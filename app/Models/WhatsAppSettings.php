<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSettings extends Model
{
    protected $table = 'whatsapp_settings';

    protected $fillable = [
        'enabled',
        'api_provider',
        'api_key',
        'api_secret',
        'phone_number',
        'webhook_url',
        'message_template',
        'send_on_purchase',
        'send_on_payment_confirmed',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'send_on_purchase' => 'boolean',
        'send_on_payment_confirmed' => 'boolean',
    ];

    /**
     * Obtenir les paramètres WhatsApp (singleton)
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'enabled' => false,
            'send_on_purchase' => true,
            'send_on_payment_confirmed' => true,
        ]);
    }

    /**
     * Vérifier si WhatsApp est activé
     */
    public static function isEnabled(): bool
    {
        $settings = static::getSettings();
        return $settings->enabled && !empty($settings->api_key);
    }
}
