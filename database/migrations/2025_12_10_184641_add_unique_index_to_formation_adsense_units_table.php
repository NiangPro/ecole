<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sur MySQL : réduire la taille des colonnes (limite d'octets des index)
        // et ne rien faire si l'index existe déjà. Ces opérations sont propres à MySQL.
        if (DB::getDriverName() === 'mysql') {
            $indexExists = DB::select("SHOW INDEX FROM formation_adsense_units WHERE Key_name = 'formation_ads_unique'");
            if (!empty($indexExists)) {
                return;
            }
            DB::statement("ALTER TABLE formation_adsense_units MODIFY formation_slug VARCHAR(50) NOT NULL");
            DB::statement("ALTER TABLE formation_adsense_units MODIFY position VARCHAR(20) NOT NULL DEFAULT 'content'");
        }

        // Créer l'index unique (compatible tous moteurs, idempotent)
        try {
            Schema::table('formation_adsense_units', function (Blueprint $table) {
                $table->unique(['formation_slug', 'adsense_unit_id', 'position'], 'formation_ads_unique');
            });
        } catch (\Throwable $e) {
            // Index déjà présent → ignorer
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('formation_adsense_units', function (Blueprint $table) {
                $table->dropUnique('formation_ads_unique');
            });
        } catch (\Throwable $e) {
            // Index déjà absent → ignorer
        }
    }
};
