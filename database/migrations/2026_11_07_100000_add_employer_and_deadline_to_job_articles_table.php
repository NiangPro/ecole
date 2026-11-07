<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Audit SEO-02 : vrai employeur et vraie date limite des offres, nécessaires à un JobPosting
 * conforme (Google for Jobs) et à la clôture des offres expirées. Colonnes facultatives :
 * les articles existants restent valides (sans JobPosting tant qu'ils ne sont pas complétés).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_articles', function (Blueprint $table) {
            $table->string('employer_name')->nullable()->after('location');
            $table->date('application_deadline')->nullable()->after('employer_name');
            $table->index('application_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('job_articles', function (Blueprint $table) {
            $table->dropIndex(['application_deadline']);
            $table->dropColumn(['employer_name', 'application_deadline']);
        });
    }
};
