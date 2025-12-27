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
        Schema::create('document_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->decimal('amount_paid', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])->default('pending');
            $table->timestamp('purchased_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('download_limit')->default(5);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('document_id');
            $table->index('payment_id');
            $table->index('status');
            $table->index('purchased_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_purchases');
    }
};
