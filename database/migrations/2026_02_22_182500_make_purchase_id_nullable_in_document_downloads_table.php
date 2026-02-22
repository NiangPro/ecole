<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Permet d'enregistrer les téléchargements gratuits (sans achat).
     */
    public function up(): void
    {
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
        });

        Schema::table('document_downloads', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->nullable()->change();
        });

        Schema::table('document_downloads', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('document_purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
        });

        Schema::table('document_downloads', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->nullable(false)->change();
        });

        Schema::table('document_downloads', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('document_purchases')->onDelete('cascade');
        });
    }
};
