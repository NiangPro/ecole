<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('referral_type'); // subscription, course_purchase, donation
            $table->unsignedBigInteger('referral_id'); // ID de l'élément référencé
            $table->decimal('amount', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('affiliate_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['referral_type', 'referral_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_referrals');
    }
};



