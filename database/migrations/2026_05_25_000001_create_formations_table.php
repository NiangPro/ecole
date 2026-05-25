<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('chapters_count')->default(0);
            $table->integer('duration_hours')->nullable();
            $table->decimal('rating', 3, 2)->default(4.5);
            $table->integer('reviews_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('enrollments_count')->default(0);
            $table->string('level')->default('Beginner'); // Beginner, Intermediate, Advanced
            $table->string('category')->nullable(); // Web Dev, Mobile, Data Science, etc.
            $table->text('what_you_learn')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
