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
        // Mettre à jour tous les enregistrements existants avec download_limit = 5
        DB::table('document_purchases')
            ->where('download_limit', 5)
            ->update(['download_limit' => 2]);
        
        // Modifier la valeur par défaut pour MySQL/MariaDB
        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE document_purchases MODIFY COLUMN download_limit INT DEFAULT 2');
        }
        // Pour SQLite, on ne peut pas modifier la valeur par défaut d'une colonne existante
        // La valeur par défaut sera appliquée lors de la création de nouveaux enregistrements
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE document_purchases MODIFY COLUMN download_limit INT DEFAULT 5');
        }
    }
};
