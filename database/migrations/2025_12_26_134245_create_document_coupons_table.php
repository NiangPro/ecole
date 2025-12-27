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
        Schema::create('document_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // Pourcentage ou montant fixe
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Montant minimum pour utiliser
            $table->foreignId('document_id')->nullable()->constrained('documents')->onDelete('cascade'); // Null = tous les documents
            $table->foreignId('category_id')->nullable()->constrained('document_categories')->onDelete('cascade'); // Null = toutes les catégories
            $table->integer('usage_limit')->nullable(); // Limite d'utilisation totale
            $table->integer('usage_count')->default(0);
            $table->integer('user_limit')->default(1); // Limite par utilisateur
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('code');
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_coupons');
    }
};
