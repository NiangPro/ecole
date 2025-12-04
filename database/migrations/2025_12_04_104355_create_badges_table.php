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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Code unique du badge (ex: 'first_formation', '10_exercises')
            $table->string('name'); // Nom du badge
            $table->text('description')->nullable(); // Description du badge
            $table->string('icon')->default('fa-trophy'); // Icône FontAwesome
            $table->string('color')->default('#06b6d4'); // Couleur du badge
            $table->enum('type', ['formation', 'exercise', 'quiz', 'special', 'streak']); // Type de badge
            $table->integer('requirement_value')->nullable(); // Valeur requise (ex: 10 pour 10 exercices)
            $table->string('requirement_type')->nullable(); // Type de condition (ex: 'count', 'percentage', 'streak')
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Badge actif ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
