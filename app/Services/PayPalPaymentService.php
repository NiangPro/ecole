<?php

namespace App\Services;

use App\Models\SiteSetting;

class PayPalPaymentService
{
    /**
     * Obtenir les configurations PayPal
     */
    private static function getConfig(): array
    {
        $settings = SiteSetting::first();
        
        if (!$settings || !$settings->paypal_enabled) {
            throw new \Exception('PayPal n\'est pas activé dans les configurations');
        }
        
        return [
            'client_id' => $settings->paypal_client_id,
            'client_secret' => $settings->paypal_client_secret,
            'mode' => $settings->paypal_mode ?? 'sandbox',
        ];
    }

    /**
     * Créer une commande PayPal
     */
    public static function createOrder(float $amount, string $currency = 'XOF', string $description = 'Donation'): array
    {
        $config = self::getConfig();
        
        // Convertir XOF en USD pour PayPal (approximation)
        $usdAmount = $amount / 600; // 1 USD ≈ 600 XOF
        
        $baseUrl = $config['mode'] === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
        
        // Obtenir le token d'accès
        $accessToken = self::getAccessToken($config, $baseUrl);
        
        // Créer la commande
        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($usdAmount, 2, '.', ''),
                    ],
                    'description' => $description,
                ],
            ],
            'application_context' => [
                'brand_name' => 'NiangProgrammeur',
                'landing_page' => 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
                'return_url' => route('payment.paypal.return'),
                'cancel_url' => route('payment.paypal.cancel'),
            ],
        ];
        
        $ch = curl_init($baseUrl . '/v2/checkout/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
            CURLOPT_POSTFIELDS => json_encode($orderData),
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('Erreur cURL lors de la création de la commande PayPal: ' . $error);
        }
        
        if ($httpCode !== 201 && $httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['message'] ?? 'Erreur lors de la création de la commande PayPal';
            throw new \Exception($errorMessage . ' (Code: ' . $httpCode . ')');
        }
        
        return json_decode($response, true);
    }

    /**
     * Obtenir le token d'accès PayPal
     */
    private static function getAccessToken(array $config, string $baseUrl): string
    {
        $ch = curl_init($baseUrl . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_USERPWD => $config['client_id'] . ':' . $config['client_secret'],
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('Erreur cURL lors de l\'obtention du token PayPal: ' . $error);
        }
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['error_description'] ?? $errorData['error'] ?? 'Erreur lors de l\'obtention du token PayPal';
            throw new \Exception($errorMessage . ' (Code: ' . $httpCode . ')');
        }
        
        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            throw new \Exception('Token d\'accès PayPal non reçu');
        }
        
        return $data['access_token'];
    }

    /**
     * Capturer un paiement PayPal
     */
    public static function captureOrder(string $orderId): array
    {
        $config = self::getConfig();
        $baseUrl = $config['mode'] === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
        
        $accessToken = self::getAccessToken($config, $baseUrl);
        
        $ch = curl_init($baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('Erreur cURL lors de la capture PayPal: ' . $error);
        }
        
        if ($httpCode !== 201 && $httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['message'] ?? 'Erreur lors de la capture du paiement PayPal';
            throw new \Exception($errorMessage . ' (Code: ' . $httpCode . ')');
        }
        
        return json_decode($response, true);
    }
}

