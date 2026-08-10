<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_recurrings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->string('label');
            $table->text('notes')->nullable();

            // Type de récurrence
            $table->enum('recurrence_type', ['day_of_month', 'every_n_days']);
            // Si day_of_month : jour du mois (1-31) — Si every_n_days : nombre de jours entre chaque occurrence
            $table->integer('recurrence_value');

            $table->integer('reminder_days_before')->default(3);
            $table->date('next_due_date');
            $table->date('last_generated_date')->nullable();
            $table->boolean('auto_create_transaction')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_recurrings');
    }
};
