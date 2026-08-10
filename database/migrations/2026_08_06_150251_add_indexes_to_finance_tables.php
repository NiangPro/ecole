<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->index(['type', 'transaction_date'], 'finance_transactions_type_date_index');
        });

        Schema::table('finance_recurrings', function (Blueprint $table) {
            $table->index('next_due_date', 'finance_recurrings_next_due_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropIndex('finance_transactions_type_date_index');
        });

        Schema::table('finance_recurrings', function (Blueprint $table) {
            $table->dropIndex('finance_recurrings_next_due_date_index');
        });
    }
};
