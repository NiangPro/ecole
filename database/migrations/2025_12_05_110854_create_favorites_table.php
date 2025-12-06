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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('favoritable_type', 50); // 'formation' ou 'article'
            $table->string('favoritable_slug', 100); // slug de la formation ou de l'article
            $table->string('favoritable_name'); // nom pour affichage
            $table->timestamps();
            
            // Empêcher les doublons
            $table->unique(['user_id', 'favoritable_type', 'favoritable_slug']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
