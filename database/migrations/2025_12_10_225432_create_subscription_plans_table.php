<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du plan (ex: Premium, Pro, Enterprise)
            $table->string('slug')->unique(); // Slug unique pour le plan
            $table->text('description')->nullable(); // Description du plan
            $table->decimal('price', 10, 2); // Prix du plan
            $table->string('currency', 3)->default('XOF'); // Devise
            $table->enum('billing_period', ['monthly', 'yearly'])->default('monthly'); // Période de facturation
            $table->integer('duration_days'); // Durée en jours (30 pour mensuel, 365 pour annuel)
            $table->text('features')->nullable(); // JSON array des fonctionnalités
            $table->boolean('is_active')->default(true); // Plan actif ou non
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->string('badge')->nullable(); // Badge (ex: "Populaire", "Meilleur")
            $table->boolean('is_featured')->default(false); // Plan mis en avant
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
