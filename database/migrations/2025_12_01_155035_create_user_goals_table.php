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
        Schema::create('user_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('goal_type'); // 'formation', 'exercise', 'quiz', 'time', 'score'
            $table->string('title'); // Titre de l'objectif
            $table->text('description')->nullable();
            $table->integer('target_value'); // Valeur cible
            $table->integer('current_value')->default(0); // Valeur actuelle
            $table->string('unit')->nullable(); // Unité (%, minutes, points, etc.)
            $table->date('deadline')->nullable(); // Date limite
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('goal_type');
            $table->index('completed');
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_goals');
    }
};
