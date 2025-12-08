<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('affiliate_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Pourcentage
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->decimal('paid_earnings', 10, 2)->default(0);
            $table->decimal('pending_earnings', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('affiliate_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};



