<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('show_on_wall')->default(true); // Afficher sur le mur des donateurs
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('completed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};



