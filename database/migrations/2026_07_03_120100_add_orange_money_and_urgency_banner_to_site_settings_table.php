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
            // Configuration Orange Money
            $table->string('orange_money_number')->nullable()->after('wave_qr_code');
            $table->boolean('orange_money_enabled')->default(false)->after('orange_money_number');
            $table->text('orange_money_instructions')->nullable()->after('orange_money_enabled');

            // Bannière d'urgence (calendrier examens)
            $table->boolean('urgency_banner_enabled')->default(false)->after('corrige_price');
            $table->string('urgency_banner_text')->nullable()->after('urgency_banner_enabled');
            $table->dateTime('urgency_banner_target_date')->nullable()->after('urgency_banner_text');
            $table->string('urgency_banner_link')->nullable()->after('urgency_banner_target_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'orange_money_number',
                'orange_money_enabled',
                'orange_money_instructions',
                'urgency_banner_enabled',
                'urgency_banner_text',
                'urgency_banner_target_date',
                'urgency_banner_link',
            ]);
        });
    }
};
