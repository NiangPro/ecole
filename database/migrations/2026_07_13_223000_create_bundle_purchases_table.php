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
        Schema::create('bundle_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('document_bundles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('currency', 5)->default('XOF');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('purchased_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->string('download_token', 64)->nullable()->index();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();

            $table->index(['customer_email', 'bundle_id']);
            $table->index(['customer_phone', 'bundle_id']);
            $table->index(['status', 'bundle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_purchases');
    }
};
