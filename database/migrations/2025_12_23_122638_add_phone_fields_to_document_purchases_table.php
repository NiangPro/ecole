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
        Schema::table('document_purchases', function (Blueprint $table) {
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('country_code', 5)->nullable()->after('customer_phone');
            $table->boolean('whatsapp_enabled')->default(false)->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_purchases', function (Blueprint $table) {
            $table->dropColumn(['customer_phone', 'country_code', 'whatsapp_enabled']);
        });
    }
};
