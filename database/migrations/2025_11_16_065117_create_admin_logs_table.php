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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // 'create', 'update', 'delete', 'approve', 'reject', etc.
            $table->string('model_type')->nullable(); // 'App\Models\JobArticle', 'App\Models\Comment', etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID de l'objet concerné
            $table->text('description')->nullable(); // Description de l'action
            $table->json('old_values')->nullable(); // Valeurs avant modification
            $table->json('new_values')->nullable(); // Valeurs après modification
            $table->string('ip_address', 45)->nullable(); // IP de l'admin
            $table->text('user_agent')->nullable(); // User agent
            $table->timestamps();
            
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
