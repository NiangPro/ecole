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
        Schema::table('course_chapters', function (Blueprint $table) {
            $table->string('title_fr')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_fr');
            $table->text('description_fr')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_fr');
            $table->text('content_fr')->nullable()->after('content');
            $table->text('content_en')->nullable()->after('content_fr');
        });

        // Migrer les données existantes vers les champs français
        \App\Models\CourseChapter::withoutEvents(function () {
            \App\Models\CourseChapter::all()->each(function ($chapter) {
                if ($chapter->title && !$chapter->title_fr) {
                    $chapter->title_fr = $chapter->title;
                }
                if ($chapter->description && !$chapter->description_fr) {
                    $chapter->description_fr = $chapter->description;
                }
                if ($chapter->content && !$chapter->content_fr) {
                    $chapter->content_fr = $chapter->content;
                }
                $chapter->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_chapters', function (Blueprint $table) {
            $table->dropColumn([
                'title_fr', 'title_en',
                'description_fr', 'description_en',
                'content_fr', 'content_en'
            ]);
        });
    }
};
