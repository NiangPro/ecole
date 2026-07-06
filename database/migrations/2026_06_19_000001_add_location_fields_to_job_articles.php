<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter les colonnes de localisation à la table job_articles
        // pour supporter les offres d'emploi avec localisation
        Schema::table('job_articles', function (Blueprint $table) {
            if (!Schema::hasColumn('job_articles', 'location')) {
                $table->string('location')->nullable()->after('content')
                    ->comment('Localisation (Dakar, Télétravail, Sénégal, etc.)');
            }
            if (!Schema::hasColumn('job_articles', 'expert_content_added')) {
                $table->boolean('expert_content_added')->default(false)->after('location')
                    ->comment('Flag pour éviter d\'ajouter plusieurs fois le contenu expert');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_articles', function (Blueprint $table) {
            $table->dropColumn(['location', 'expert_content_added']);
        });
    }
};
