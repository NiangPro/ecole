<?php

namespace App\Services;

use App\Models\SiteSetting;

class StripePaymentService
{
    /**
     * Obtenir les configurations Stripe
     */
    private static function getConfig(): array
    {
        $settings = SiteSetting::first();
        
        if (!$settings || !$settings->stripe_enabled) {
            throw new \Exception('Stripe n\'est pas activé dans les configurations');
        }
        
        return [
            'public_key' => $settings->stripe_public_key,
            'secret_key' => $settings->stripe_secret_key,
            'webhook_secret' => $settings->stripe_webhook_secret,
        ];
    }

    /**
     * Créer une session de paiement Stripe
     */
    public static function createCheckoutSession(float $amount, string $currency = 'XOF', string $description = 'Donation', array $metadata = []): array
    {
        $config = self::getConfig();
        
        // Taux de change XOF vers EUR (approximation)
        // 1 EUR ≈ 655 XOF
        $exchangeRate = 655;
        
        // Convertir XOF en EUR
        $eurAmount = $amount / $exchangeRate;
        
        // Stripe utilise les centimes d'euro (minimum 50 centimes = 0.50 EUR)
        $eurAmountInCents = (int) round($eurAmount * 100);
        
        // Vérifier le minimum Stripe (50 centimes)
        if ($eurAmountInCents < 50) {
            throw new \Exception('Le montant minimum pour Stripe est de ' . ceil(50 * $exchangeRate / 100) . ' FCFA (équivalent à 0.50 EUR)');
        }
        
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $description,
                        ],
                        'unit_amount' => $eurAmountInCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.stripe.cancel'),
            'metadata' => array_merge($metadata, [
                'amount_xof' => $amount,
                'currency_original' => $currency,
            ]),
        ];
        
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_USERPWD => $config['secret_key'] . ':',
            CURLOPT_POSTFIELDS => http_build_query($sessionData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('Erreur cURL lors de la création de la session Stripe: ' . $error);
        }
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['error']['message'] ?? 'Erreur lors de la création de la session Stripe';
            throw new \Exception($errorMessage . ' (Code: ' . $httpCode . ')');
        }
        
        return json_decode($response, true);
    }

    /**
     * Récupérer une session Stripe
     */
    public static function retrieveSession(string $sessionId): array
    {
        $config = self::getConfig();
        
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . $sessionId);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $config['secret_key'] . ':',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('Erreur cURL lors de la récupération de la session Stripe: ' . $error);
        }
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['error']['message'] ?? 'Erreur lors de la récupération de la session Stripe';
            throw new \Exception($errorMessage . ' (Code: ' . $httpCode . ')');
        }
        
        return json_decode($response, true);
    }

    /**
     * Obtenir la clé publique pour le frontend
     */
    public static function getPublicKey(): string
    {
        $config = self::getConfig();
        return $config['public_key'];
    }
}

