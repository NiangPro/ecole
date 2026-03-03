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
        Schema::create('administrative_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // Identité, Voyage, Éducation, Business, etc.
            $table->text('summary')->nullable(); // Description courte
            $table->text('purpose')->nullable(); // À quoi sert ce document
            $table->text('target_audience')->nullable(); // Qui peut le demander
            $table->json('required_documents')->nullable(); // Liste structurée des pièces à fournir
            $table->json('where_to_apply')->nullable(); // Lieux / administrations
            $table->string('approx_cost')->nullable(); // Texte libre (ex: "Gratuit", "5000 FCFA")
            $table->string('approx_delay')->nullable(); // Texte libre (ex: "7 à 21 jours")
            $table->text('tips')->nullable(); // Conseils, erreurs à éviter
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrative_documents');
    }
};

