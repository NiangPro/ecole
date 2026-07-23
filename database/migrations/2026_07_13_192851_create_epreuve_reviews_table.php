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
        Schema::create('epreuve_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epreuve_id')->constrained('epreuves')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable(); // Pour les utilisateurs anonymes
            $table->string('user_email')->nullable(); // Pour les utilisateurs anonymes
            $table->integer('rating')->default(5); // 1-5 étoiles
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false); // Modération
            $table->timestamps();

            $table->index('epreuve_id');
            $table->index('user_id');
            $table->index('is_approved');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epreuve_reviews');
    }
};
