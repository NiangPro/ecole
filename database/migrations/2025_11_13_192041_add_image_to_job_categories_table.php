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
        Schema::table('job_categories', function (Blueprint $table) {
            // Éviter l'erreur si la colonne existe déjà (cas de ré-exécution des migrations)
            if (!Schema::hasColumn('job_categories', 'image')) {
                $table->string('image')->nullable()->after('icon');
            }

            if (!Schema::hasColumn('job_categories', 'image_type')) {
                $table->enum('image_type', ['internal', 'external'])->default('internal')->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            //
        });
    }
};
