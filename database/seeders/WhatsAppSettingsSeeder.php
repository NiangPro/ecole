<?php

namespace Database\Seeders;

use App\Models\WhatsAppSettings;
use Illuminate\Database\Seeder;

class WhatsAppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les paramètres WhatsApp par défaut s'ils n'existent pas
        if (WhatsAppSettings::count() === 0) {
            WhatsAppSettings::create([
                'enabled' => false,
                'api_provider' => null,
                'api_key' => null,
                'api_secret' => null,
                'phone_number' => null,
                'webhook_url' => null,
                'message_template' => "Bonjour {customer_name},\n\nMerci pour votre achat de : *{document_title}*\n\nLien de téléchargement :\n{download_link}\n\nCe lien est valide pendant 30 jours.\n\nCordialement,\nNiangProgrammeur",
                'send_on_purchase' => true,
                'send_on_payment_confirmed' => true,
            ]);
        }
    }
}
