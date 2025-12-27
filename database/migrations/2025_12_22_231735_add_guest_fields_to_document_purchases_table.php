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
        Schema::table('document_purchases', function (Blueprint $table) {
            // Rendre user_id nullable pour permettre les achats anonymes
            $table->foreignId('user_id')->nullable()->change();
            
            // Ajouter les champs pour les clients anonymes
            $table->string('customer_email')->nullable()->after('user_id');
            $table->string('customer_name')->nullable()->after('customer_email');
            
            // Index pour les recherches par email
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_purchases', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['customer_email']);
            
            // Supprimer les colonnes
            $table->dropColumn(['customer_email', 'customer_name']);
            
            // Remettre user_id non-nullable (attention: peut échouer si des données null existent)
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
