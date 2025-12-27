<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paid_courses', function (Blueprint $table) {
            // Ajouter cover_type d'abord
            if (!Schema::hasColumn('paid_courses', 'cover_type')) {
                $table->enum('cover_type', ['internal', 'external'])->default('internal')->after('image');
            }
        });
        
        // Renommer image en cover_image en utilisant DB::statement
        if (Schema::hasColumn('paid_courses', 'image') && !Schema::hasColumn('paid_courses', 'cover_image')) {
            DB::statement('ALTER TABLE `paid_courses` CHANGE `image` `cover_image` VARCHAR(191) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renommer cover_image en image
        if (Schema::hasColumn('paid_courses', 'cover_image') && !Schema::hasColumn('paid_courses', 'image')) {
            DB::statement('ALTER TABLE `paid_courses` CHANGE `cover_image` `image` VARCHAR(191) NULL');
        }
        
        Schema::table('paid_courses', function (Blueprint $table) {
            if (Schema::hasColumn('paid_courses', 'cover_type')) {
                $table->dropColumn('cover_type');
            }
        });
    }
};
