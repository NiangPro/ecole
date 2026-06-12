<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_description',
        'logo',
        'contact_email',
        'contact_phone',
        'contact_address',
        'facebook_url',
        'facebook_app_id',
        'tiktok_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'github_url',
        'google_analytics_id',
        'bing_api_key',
        'show_achievements_section',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        // Configuration des moyens de paiement
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
        'maintenance_mode',
        'maintenance_message',
        'maintenance_ends_at',
        'corrige_price',
    ];

    protected $casts = [
        'maintenance_mode'   => 'boolean',
        'maintenance_ends_at' => 'datetime',
        'wave_enabled'       => 'boolean',
        'paypal_enabled'     => 'boolean',
        'stripe_enabled'     => 'boolean',
        'corrige_price'      => 'decimal:2',
    ];

    public static function get($key, $default = null)
    {
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return self::first();
        });
        return $settings ? ($settings->$key ?? $default) : $default;
    }
    
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('site_settings');
    }

    /**
     * Un logo personnalisé a été téléversé.
     */
    public static function hasCustomLogo(): bool
    {
        try {
            return (bool) self::get('logo');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * URL du logo du site : le logo téléversé si présent, sinon le logo par défaut.
     * Résilient (utilisé jusque dans les pages d'erreur) : ne lève jamais d'exception.
     */
    public static function logoUrl(): string
    {
        try {
            $logo = self::get('logo');
            if ($logo) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($logo);
            }
        } catch (\Throwable $e) {
            // Base indisponible (ex. page d'erreur) → on retombe sur le logo par défaut
        }

        return asset('images/logo.png');
    }
    
    /**
     * Surcharge de la méthode save pour invalider le cache automatiquement
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);
        
        // Invalider le cache après chaque sauvegarde réussie
        if ($result) {
            // Utiliser forget au lieu de clearCache pour éviter les problèmes récursifs
            \Illuminate\Support\Facades\Cache::forget('site_settings');
        }
        
        return $result;
    }
}
