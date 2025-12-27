<?php

namespace App\Services;

use App\Models\WhatsAppSettings;
use App\Models\DocumentPurchase;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Envoyer un message WhatsApp pour un achat de document
     */
    public static function sendDocumentPurchaseMessage(DocumentPurchase $purchase): bool
    {
        $settings = WhatsAppSettings::getSettings();
        
        // Vérifier si WhatsApp est activé
        if (!$settings->enabled) {
            return false;
        }
        
        // Vérifier si le téléphone est disponible
        if (!$purchase->customer_phone || !$purchase->country_code) {
            return false;
        }
        
        // Vérifier si WhatsApp est activé pour cet achat
        if (!$purchase->whatsapp_enabled) {
            return false;
        }
        
        // Construire le numéro complet
        $fullPhone = $purchase->country_code . preg_replace('/[^0-9]/', '', $purchase->customer_phone);
        
        // Générer le token si pas déjà fait
        if (!$purchase->download_token) {
            $purchase->generateDownloadToken();
        }
        
        // Construire le message
        $message = self::buildMessage($purchase, $settings);
        
        // Envoyer selon le provider
        try {
            switch ($settings->api_provider) {
                case 'twilio':
                    return self::sendViaTwilio($fullPhone, $message, $settings);
                case 'whatsapp_business':
                    return self::sendViaWhatsAppBusiness($fullPhone, $message, $settings);
                default:
                    // Pour l'instant, on log juste le message (à implémenter avec l'API choisie)
                    Log::info('WhatsApp message would be sent', [
                        'phone' => $fullPhone,
                        'message' => $message,
                        'provider' => $settings->api_provider
                    ]);
                    return true; // Retourner true pour ne pas bloquer le processus
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi WhatsApp: ' . $e->getMessage(), [
                'purchase_id' => $purchase->id,
                'phone' => $fullPhone
            ]);
            return false;
        }
    }
    
    /**
     * Construire le message WhatsApp
     */
    private static function buildMessage(DocumentPurchase $purchase, WhatsAppSettings $settings): string
    {
        $template = $settings->message_template;
        
        if ($template) {
            // Remplacer les variables dans le template
            $message = str_replace('{customer_name}', $purchase->customer_name ?? 'Client', $template);
            $message = str_replace('{document_title}', $purchase->document->title, $message);
            $message = str_replace('{download_link}', route('documents.download.token', ['token' => $purchase->download_token]), $message);
            $message = str_replace('{download_token}', $purchase->download_token, $message);
        } else {
            // Message par défaut
            $message = "Bonjour " . ($purchase->customer_name ?? 'Client') . ",\n\n";
            $message .= "Merci pour votre achat de : *" . $purchase->document->title . "*\n\n";
            $message .= "Lien de téléchargement :\n";
            $message .= route('documents.download.token', ['token' => $purchase->download_token]) . "\n\n";
            $message .= "Ce lien est valide pendant 30 jours.\n\n";
            $message .= "Cordialement,\nNiangProgrammeur";
        }
        
        return $message;
    }
    
    /**
     * Envoyer via Twilio
     */
    private static function sendViaTwilio(string $phone, string $message, WhatsAppSettings $settings): bool
    {
        // TODO: Implémenter l'envoi via Twilio API
        // Pour l'instant, on log juste
        Log::info('Twilio WhatsApp message', [
            'phone' => $phone,
            'message' => $message
        ]);
        return true;
    }
    
    /**
     * Envoyer via WhatsApp Business API
     */
    private static function sendViaWhatsAppBusiness(string $phone, string $message, WhatsAppSettings $settings): bool
    {
        // TODO: Implémenter l'envoi via WhatsApp Business API
        // Pour l'instant, on log juste
        Log::info('WhatsApp Business API message', [
            'phone' => $phone,
            'message' => $message
        ]);
        return true;
    }
}

