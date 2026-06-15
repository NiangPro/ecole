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
        Schema::table('epreuves', function (Blueprint $table) {
            // null = gratuit ; > 0 = payant (FCFA, entier)
            $table->unsignedInteger('price')->nullable()->after('corrige_file_path');
            // null = utilise le prix global (SiteSetting corrige_price) ; > 0 = prix spécifique
            $table->unsignedInteger('corrige_price')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->dropColumn(['price', 'corrige_price']);
        });
    }
};
