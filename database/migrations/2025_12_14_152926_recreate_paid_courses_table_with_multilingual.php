<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ne pas supprimer la table car elle a des contraintes de clé étrangère
        // Vérifier et ajouter les colonnes manquantes seulement
        
        Schema::table('paid_courses', function (Blueprint $table) {
            // Ajouter les colonnes multilingues si elles n'existent pas déjà
            if (!Schema::hasColumn('paid_courses', 'title_fr')) {
                $table->string('title_fr')->nullable()->after('title');
            }
            if (!Schema::hasColumn('paid_courses', 'title_en')) {
                $table->string('title_en')->nullable()->after('title_fr');
            }
            if (!Schema::hasColumn('paid_courses', 'description_fr')) {
                $table->text('description_fr')->nullable()->after('description');
            }
            if (!Schema::hasColumn('paid_courses', 'description_en')) {
                $table->text('description_en')->nullable()->after('description_fr');
            }
            if (!Schema::hasColumn('paid_courses', 'content_fr')) {
                $table->text('content_fr')->nullable()->after('content');
            }
            if (!Schema::hasColumn('paid_courses', 'content_en')) {
                $table->text('content_en')->nullable()->after('content_fr');
            }
            if (!Schema::hasColumn('paid_courses', 'what_you_learn_fr')) {
                $table->json('what_you_learn_fr')->nullable()->after('what_you_learn');
            }
            if (!Schema::hasColumn('paid_courses', 'what_you_learn_en')) {
                $table->json('what_you_learn_en')->nullable()->after('what_you_learn_fr');
            }
            if (!Schema::hasColumn('paid_courses', 'requirements_fr')) {
                $table->json('requirements_fr')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('paid_courses', 'requirements_en')) {
                $table->json('requirements_en')->nullable()->after('requirements_fr');
            }
        });
    }

    public function down(): void
    {
        // Ne rien faire en cas de rollback pour éviter de casser les données
        // Cette migration ne supprime plus la table
    }
};
