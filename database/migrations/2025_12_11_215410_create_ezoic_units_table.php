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
        if (!Schema::hasTable('ezoic_units')) {
            Schema::create('ezoic_units', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Nom de l'unité (ex: "Sidebar - 300x250")
                $table->text('description')->nullable(); // Description de l'emplacement
                $table->string('ad_id'); // ID de l'annonce Ezoic (ex: "ezoic-pub-1234567890-12345")
                $table->string('ad_format')->default('auto'); // Format: auto, horizontal, vertical, rectangle
                $table->string('position'); // Position: header, sidebar, content, footer, in-article
                $table->string('location')->nullable(); // Location spécifique (ex: "homepage", "article", "formation")
                $table->string('size')->nullable(); // Taille: 300x250, 728x90, etc.
                $table->boolean('responsive')->default(true); // Responsive ou non
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->integer('order')->default(0); // Ordre d'affichage
                $table->text('custom_code')->nullable(); // Code personnalisé si nécessaire
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ezoic_units');
    }
};
