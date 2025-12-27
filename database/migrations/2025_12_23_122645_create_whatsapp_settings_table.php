<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('api_provider')->nullable()->comment('twilio, whatsapp_business, etc.');
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('phone_number')->nullable()->comment('Numéro WhatsApp Business');
            $table->text('webhook_url')->nullable();
            $table->text('message_template')->nullable()->comment('Template du message WhatsApp');
            $table->boolean('send_on_purchase')->default(true)->comment('Envoyer automatiquement à l\'achat');
            $table->boolean('send_on_payment_confirmed')->default(true)->comment('Envoyer quand le paiement est confirmé');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
    }
};
