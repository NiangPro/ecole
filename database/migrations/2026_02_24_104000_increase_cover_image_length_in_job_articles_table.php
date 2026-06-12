<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Les URLs externes (ex. Blogger) peuvent dépasser 255 caractères.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE job_articles MODIFY cover_image VARCHAR(2048) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE job_articles MODIFY cover_image VARCHAR(255) NULL');
        }
    }
};
