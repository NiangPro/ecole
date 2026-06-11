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
        Schema::create('epreuve_matieres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // Classe d'icône ou emoji
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('epreuves', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // epreuve | corrige | epreuve_corrige | examen_blanc | devoir | composition
            $table->string('type', 30)->default('epreuve');
            // cfee | bfem | bac | bts | cap (null si document de classe hors examen)
            $table->string('exam', 10)->nullable();
            // ci | cp | ce1 | ce2 | cm1 | cm2 | 6eme...3eme | seconde | premiere | terminale
            $table->string('level', 15)->nullable();
            $table->foreignId('matiere_id')->nullable()->constrained('epreuve_matieres')->nullOnDelete();
            $table->string('serie', 30)->nullable(); // S, S1, S2, L, L1, L2, L'A, T...
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('corrige_file_path')->nullable(); // PDF corrigé séparé (type epreuve_corrige)
            $table->unsignedInteger('downloads_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();

            $table->index(['status', 'exam', 'matiere_id']);
            $table->index(['status', 'level', 'matiere_id']);
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epreuves');
        Schema::dropIfExists('epreuve_matieres');
    }
};
