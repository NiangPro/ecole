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
        // Vérifier si l'index n'existe pas déjà
        $indexExists = DB::select("SHOW INDEX FROM formation_adsense_units WHERE Key_name = 'formation_ads_unique'");
        
        if (empty($indexExists)) {
            // Réduire la taille des colonnes pour permettre l'index unique
            // MySQL a une limite de 1000 octets pour les index
            // formation_slug (50) + adsense_unit_id (8) + position (20) = ~78 caractères max
            
            DB::statement("ALTER TABLE formation_adsense_units MODIFY formation_slug VARCHAR(50) NOT NULL");
            DB::statement("ALTER TABLE formation_adsense_units MODIFY position VARCHAR(20) NOT NULL DEFAULT 'content'");
            
            // Créer l'index unique
            Schema::table('formation_adsense_units', function (Blueprint $table) {
                $table->unique(['formation_slug', 'adsense_unit_id', 'position'], 'formation_ads_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vérifier si l'index existe avant de le supprimer
        $indexExists = DB::select("SHOW INDEX FROM formation_adsense_units WHERE Key_name = 'formation_ads_unique'");
        
        if (!empty($indexExists)) {
            Schema::table('formation_adsense_units', function (Blueprint $table) {
                $table->dropUnique('formation_ads_unique');
            });
        }
    }
};
