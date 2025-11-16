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
        Schema::create('formation_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('formation_slug'); // html5, css3, javascript, etc.
            $table->string('section_id')->nullable(); // ID de la section actuelle
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('completed_sections')->nullable(); // Liste des sections complétées
            $table->integer('time_spent_minutes')->default(0); // Temps passé en minutes
            $table->timestamps();
            
            $table->unique(['user_id', 'formation_slug']);
            $table->index('formation_slug');
            $table->index('progress_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_progress');
    }
};
