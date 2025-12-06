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
        Schema::create('security_audits', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50)->index();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium')->index();
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('route', 100)->nullable()->index();
            $table->string('method', 10)->nullable();
            $table->json('request_data')->nullable();
            $table->integer('response_code')->nullable();
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Index composite pour les requêtes fréquentes
            $table->index(['severity', 'created_at']);
            $table->index(['event_type', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audits');
    }
};
