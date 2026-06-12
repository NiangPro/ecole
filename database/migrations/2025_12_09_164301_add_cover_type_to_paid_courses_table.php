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
        
        // Renommer image en cover_image (compatible MySQL et SQLite)
        if (Schema::hasColumn('paid_courses', 'image') && !Schema::hasColumn('paid_courses', 'cover_image')) {
            Schema::table('paid_courses', function (Blueprint $table) {
                $table->renameColumn('image', 'cover_image');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renommer cover_image en image (compatible MySQL et SQLite)
        if (Schema::hasColumn('paid_courses', 'cover_image') && !Schema::hasColumn('paid_courses', 'image')) {
            Schema::table('paid_courses', function (Blueprint $table) {
                $table->renameColumn('cover_image', 'image');
            });
        }
        
        Schema::table('paid_courses', function (Blueprint $table) {
            if (Schema::hasColumn('paid_courses', 'cover_type')) {
                $table->dropColumn('cover_type');
            }
        });
    }
};
