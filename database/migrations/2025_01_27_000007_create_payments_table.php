<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('paymentable_type'); // Subscription, CoursePurchase, Donation
            $table->unsignedBigInteger('paymentable_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('payment_method'); // stripe, paypal, mobile_money, bank_transfer, cash
            $table->string('payment_gateway')->nullable(); // stripe, paypal, orange_money, mtn_money, etc.
            $table->string('transaction_id')->nullable()->unique();
            $table->string('payment_reference')->nullable();
            $table->text('payment_details')->nullable(); // JSON
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index(['paymentable_type', 'paymentable_id']);
            $table->index('status');
            $table->index('transaction_id');
            $table->index('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};



