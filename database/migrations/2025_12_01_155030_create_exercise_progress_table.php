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
        Schema::create('exercise_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('exercise_id'); // ID de l'exercice
            $table->string('language'); // Langage de programmation (php, python, java, etc.)
            $table->boolean('completed')->default(false);
            $table->integer('score')->default(0); // Score obtenu
            $table->integer('time_spent_seconds')->default(0); // Temps passé en secondes
            $table->text('code_submitted')->nullable(); // Code soumis
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'exercise_id']);
            $table->index('user_id');
            $table->index('language');
            $table->index('completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_progress');
    }
};
