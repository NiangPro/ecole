<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_from', 3); // EUR | USD
            $table->string('currency_to', 3)->default('XOF');
            $table->decimal('rate', 12, 4);
            $table->date('rate_date');
            $table->boolean('is_current')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_exchange_rates');
    }
};
