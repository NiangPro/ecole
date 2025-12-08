<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan_type')->default('premium'); // premium, pro, enterprise
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF'); // XOF pour FCFA
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('next_billing_date')->nullable();
            $table->string('payment_method')->nullable(); // stripe, paypal, mobile_money, bank_transfer
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('plan_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};



