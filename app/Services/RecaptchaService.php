<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private $secretKey;
    private $siteKey;

    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->siteKey = config('services.recaptcha.site_key');
    }

    /**
     * Vérifier le token reCAPTCHA
     *
     * @param string $token
     * @param string $ip
     * @return bool
     */
    public function verify($token, $ip = null)
    {
        if (empty($this->secretKey) || empty($token)) {
            // Si reCAPTCHA n'est pas configuré, autoriser (mode développement)
            return true;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip ?? request()->ip(),
            ]);

            $result = $response->json();

            if ($result['success'] === true && isset($result['score'])) {
                // reCAPTCHA v3 : score entre 0.0 (bot) et 1.0 (humain)
                // Accepter si score >= 0.5
                return $result['score'] >= 0.5;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            // En cas d'erreur, autoriser (pour éviter de bloquer les vrais utilisateurs)
            return true;
        }
    }

    /**
     * Obtenir la clé publique (site key)
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey ?? '';
    }
}

