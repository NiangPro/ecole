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
        // Vérifier si la table existe déjà
        if (!Schema::hasTable('formation_adsense_units')) {
            Schema::create('formation_adsense_units', function (Blueprint $table) {
            $table->id();
            $table->string('formation_slug', 50); // Slug de la formation (ex: html5, css3, javascript) - Limité à 50 caractères
            $table->foreignId('adsense_unit_id')->constrained('adsense_units')->onDelete('cascade');
            $table->string('position', 20)->default('content'); // Position dans la page: header, content, sidebar, footer - Limité à 20 caractères
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('formation_slug');
            $table->index('status');
            // Index unique avec longueurs limitées pour éviter l'erreur "clé trop longue"
            $table->unique(['formation_slug', 'adsense_unit_id', 'position'], 'formation_ads_unique'); // Une unité par position par formation
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_adsense_units');
    }
};
