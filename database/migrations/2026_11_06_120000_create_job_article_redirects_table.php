<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Audit SEO-03 : redirections 301 d'anciens slugs d'articles (doublons fusionnés) vers
 * l'article conservé. Table vide à la création : les fusions sont décidées à la main.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_article_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug')->unique();
            $table->string('new_slug');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_article_redirects');
    }
};
