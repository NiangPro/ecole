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
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('file_type')->default('epreuve'); // epreuve, corrige, certificate, etc.
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('identifier_hash')->index(); // Hash IP + User ID
            $table->boolean('is_suspicious')->default(false)->index();
            $table->string('reason')->nullable(); // Raison du blocage
            $table->boolean('blocked')->default(false)->index();
            $table->timestamps();

            // Index composés pour les requêtes communes
            $table->index(['identifier_hash', 'created_at']);
            $table->index(['is_suspicious', 'created_at']);
            $table->index(['blocked', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};
