<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('paid_course_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_details')->nullable(); // JSON
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('paid_course_id');
            $table->index('status');
            $table->unique(['user_id', 'paid_course_id']); // Un utilisateur ne peut acheter qu'une fois
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_purchases');
    }
};



