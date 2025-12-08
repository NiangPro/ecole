<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paid_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('duration_hours')->nullable(); // Durée estimée en heures
            $table->integer('students_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0); // Note sur 5
            $table->integer('reviews_count')->default(0);
            $table->text('what_you_learn')->nullable(); // JSON array
            $table->text('requirements')->nullable(); // JSON array
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paid_courses');
    }
};



