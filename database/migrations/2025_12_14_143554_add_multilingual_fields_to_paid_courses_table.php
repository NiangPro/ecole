<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paid_courses', function (Blueprint $table) {
            // Champs multilingues pour le titre
            $table->string('title_fr')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_fr');
            
            // Champs multilingues pour la description
            $table->text('description_fr')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_fr');
            
            // Champs multilingues pour le contenu
            $table->text('content_fr')->nullable()->after('content');
            $table->text('content_en')->nullable()->after('content_fr');
        });
    }

    public function down(): void
    {
        Schema::table('paid_courses', function (Blueprint $table) {
            $table->dropColumn(['title_fr', 'title_en', 'description_fr', 'description_en', 'content_fr', 'content_en']);
        });
    }
};
