<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('amount_xof', 12, 2)->nullable();
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->string('label');
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->boolean('is_recurring_instance')->default(false);
            $table->foreignId('finance_recurring_id')->nullable()->constrained('finance_recurrings')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
