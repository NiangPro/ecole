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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable(); // Pour les commentaires anonymes
            $table->string('email')->nullable(); // Pour les commentaires anonymes
            $table->morphs('commentable'); // Polymorphe : peut commenter JobArticle ou Formation
            $table->text('content');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Pour les réponses
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->integer('likes')->default(0);
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            
            // morphs() crée déjà l'index commentable_type_commentable_id
            $table->index('status');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
