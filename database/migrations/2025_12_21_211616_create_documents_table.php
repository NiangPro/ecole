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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('restrict');
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size');
            $table->string('file_type');
            $table->string('file_extension');
            $table->string('cover_image')->nullable();
            $table->enum('cover_type', ['internal', 'external'])->default('internal');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('download_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->foreignId('author_id')->constrained('users')->onDelete('restrict');
            $table->json('tags')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category_id');
            $table->index('author_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_active');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
