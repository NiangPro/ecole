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
        if (!Schema::hasTable('formation_ezoic_units')) {
            Schema::create('formation_ezoic_units', function (Blueprint $table) {
                $table->id();
                $table->string('formation_slug', 50); // Slug de la formation ou 'all' pour toutes
                $table->unsignedBigInteger('ezoic_unit_id'); // ID de l'unité Ezoic
                $table->string('position', 20); // Position: header, content, sidebar, footer
                $table->integer('order')->default(0); // Ordre d'affichage
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
                
                // Index pour améliorer les performances
                $table->index(['formation_slug', 'position']);
                $table->index('ezoic_unit_id');
                
                // Clé étrangère
                $table->foreign('ezoic_unit_id')->references('id')->on('ezoic_units')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_ezoic_units');
    }
};
