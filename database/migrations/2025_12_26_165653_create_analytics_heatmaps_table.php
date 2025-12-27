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
        Schema::create('analytics_heatmaps', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('x_position'); // Position X du clic
            $table->integer('y_position'); // Position Y du clic
            $table->integer('viewport_width'); // Largeur de la fenêtre
            $table->integer('viewport_height'); // Hauteur de la fenêtre
            $table->string('element_selector')->nullable(); // Sélecteur CSS de l'élément
            $table->string('element_type')->nullable(); // 'button', 'link', 'image', etc.
            $table->string('interaction_type')->default('click'); // 'click', 'hover', 'scroll'
            $table->integer('scroll_depth')->nullable(); // Pourcentage de scroll
            $table->timestamps();

            $table->index(['page_url', 'created_at']);
            $table->index(['interaction_type', 'created_at']);
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_heatmaps');
    }
};
