<?php

namespace App\Services;

class WavePaymentService
{
    /**
     * Obtenir l'ID du marchand Wave depuis les paramètres
     */
    private static function getMerchantId(): string
    {
        $settings = \App\Models\SiteSetting::first();
        return $settings && $settings->wave_merchant_id ? $settings->wave_merchant_id : 'M_sn_XKgk0Xq-zzy4';
    }
    
    /**
     * Obtenir le code pays Wave depuis les paramètres
     */
    private static function getCountryCode(): string
    {
        $settings = \App\Models\SiteSetting::first();
        return $settings && $settings->wave_country_code ? $settings->wave_country_code : 'sn';
    }

    /**
     * Générer un lien de paiement Wave
     * 
     * @param float $amount Montant en FCFA
     * @param string|null $reference Référence optionnelle
     * @param string|null $description Description optionnelle
     * @return string URL de paiement Wave
     */
    public static function generatePaymentLink(float $amount, ?string $reference = null, ?string $description = null): string
    {
        $merchantId = self::getMerchantId();
        $countryCode = self::getCountryCode();
        $baseUrl = "https://pay.wave.com/m/" . $merchantId . "/c/" . $countryCode . "/";
        
        $params = [
            'amount' => (int) $amount, // Wave attend un entier
        ];
        
        if ($reference) {
            $params['reference'] = $reference;
        }
        
        if ($description) {
            $params['description'] = $description;
        }
        
        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * Générer un lien Wave pour une donation
     * 
     * @param float $amount Montant en FCFA
     * @param string|null $donorName Nom du donateur
     * @param string|null $message Message du donateur
     * @return string URL de paiement Wave
     */
    public static function generateDonationLink(float $amount, ?string $donorName = null, ?string $message = null): string
    {
        $reference = 'DON-' . strtoupper(uniqid());
        $description = 'Donation NiangProgrammeur';
        
        if ($donorName) {
            $description .= ' - ' . $donorName;
        }
        
        if ($message) {
            $description .= ' - ' . substr($message, 0, 50);
        }
        
        return self::generatePaymentLink($amount, $reference, $description);
    }

    /**
     * Valider le montant minimum pour Wave (1000 FCFA)
     */
    public static function validateAmount(float $amount): bool
    {
        return $amount >= 1000;
    }
}

