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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('formation_slug'); // Formation complétée
            $table->string('certificate_number')->unique(); // Numéro unique du certificat
            $table->date('completed_date'); // Date de complétion
            $table->integer('score')->nullable(); // Score obtenu (si applicable)
            $table->string('pdf_path')->nullable(); // Chemin vers le PDF généré
            $table->timestamp('generated_at')->nullable(); // Date de génération du PDF
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'un seul certificat par formation
            $table->unique(['user_id', 'formation_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
