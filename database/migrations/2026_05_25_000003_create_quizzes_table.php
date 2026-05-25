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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('passing_score')->default(70);
            $table->integer('time_limit_minutes')->nullable();
            $table->boolean('shuffle_questions')->default(true);
            $table->boolean('show_correct_answers')->default(true);
            $table->integer('attempts_count')->default(0);
            $table->decimal('average_score', 5, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
            
            $table->index('formation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
