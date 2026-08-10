<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_recurring_id')->constrained('finance_recurrings')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->date('due_date');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_notifications');
    }
};
