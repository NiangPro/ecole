<?php

namespace App\Services;

/**
 * Orange Money Sénégal n'a pas d'API marchand disponible sans contrat commercial,
 * contrairement à Wave qui expose un lien de paiement statique. Le flux retenu ici
 * est donc "numéro marchand + instructions", avec confirmation manuelle côté acheteur
 * puis validation admin — le même mécanisme déjà utilisé pour Wave dans ce projet.
 */
class OrangeMoneyPaymentService
{
    public static function getNumber(): ?string
    {
        return \App\Models\SiteSetting::get('orange_money_number');
    }

    public static function getInstructions(): string
    {
        return \App\Models\SiteSetting::get(
            'orange_money_instructions',
            'Envoyez le montant via Orange Money au numéro indiqué, puis cliquez sur "J\'ai payé" pour confirmer.'
        );
    }

    public static function isEnabled(): bool
    {
        return (bool) \App\Models\SiteSetting::get('orange_money_enabled', false);
    }

    /**
     * Construire les informations à afficher à l'acheteur pour un paiement Orange Money.
     */
    public static function buildPaymentPayload(float $amount, ?string $reference = null, ?string $description = null): array
    {
        return [
            'number' => self::getNumber(),
            'amount' => (int) $amount,
            'reference' => $reference,
            'description' => $description,
            'instructions' => self::getInstructions(),
        ];
    }
}
