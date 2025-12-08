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
        Schema::table('site_settings', function (Blueprint $table) {
            // Configuration Wave
            $table->string('wave_merchant_id')->nullable()->after('mail_from_name');
            $table->string('wave_country_code')->default('sn')->after('wave_merchant_id');
            $table->boolean('wave_enabled')->default(true)->after('wave_country_code');
            
            // Configuration PayPal
            $table->string('paypal_client_id')->nullable()->after('wave_enabled');
            $table->string('paypal_client_secret')->nullable()->after('paypal_client_id');
            $table->string('paypal_mode')->default('sandbox')->after('paypal_client_secret'); // sandbox ou live
            $table->boolean('paypal_enabled')->default(false)->after('paypal_mode');
            
            // Configuration Stripe
            $table->string('stripe_public_key')->nullable()->after('paypal_enabled');
            $table->string('stripe_secret_key')->nullable()->after('stripe_public_key');
            $table->string('stripe_webhook_secret')->nullable()->after('stripe_secret_key');
            $table->boolean('stripe_enabled')->default(false)->after('stripe_webhook_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'wave_merchant_id',
                'wave_country_code',
                'wave_enabled',
                'paypal_client_id',
                'paypal_client_secret',
                'paypal_mode',
                'paypal_enabled',
                'stripe_public_key',
                'stripe_secret_key',
                'stripe_webhook_secret',
                'stripe_enabled',
            ]);
        });
    }
};
