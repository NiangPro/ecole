<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('administrative_documents')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            if (Schema::hasColumn('administrative_documents', 'seo_title')) {
                DB::statement('ALTER TABLE `administrative_documents` MODIFY `seo_title` TEXT NULL');
            }
            if (Schema::hasColumn('administrative_documents', 'seo_keywords')) {
                DB::statement('ALTER TABLE `administrative_documents` MODIFY `seo_keywords` TEXT NULL');
            }
            if (Schema::hasColumn('administrative_documents', 'approx_delay')) {
                DB::statement('ALTER TABLE `administrative_documents` MODIFY `approx_delay` TEXT NULL');
            }
            return;
        }

        if ($driver === 'pgsql') {
            if (Schema::hasColumn('administrative_documents', 'seo_title')) {
                DB::statement('ALTER TABLE administrative_documents ALTER COLUMN seo_title TYPE TEXT');
            }
            if (Schema::hasColumn('administrative_documents', 'seo_keywords')) {
                DB::statement('ALTER TABLE administrative_documents ALTER COLUMN seo_keywords TYPE TEXT');
            }
            if (Schema::hasColumn('administrative_documents', 'approx_delay')) {
                DB::statement('ALTER TABLE administrative_documents ALTER COLUMN approx_delay TYPE TEXT');
            }
            return;
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('administrative_documents')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `administrative_documents` MODIFY `seo_title` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `administrative_documents` MODIFY `seo_keywords` VARCHAR(512) NULL');
            DB::statement('ALTER TABLE `administrative_documents` MODIFY `approx_delay` VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE administrative_documents ALTER COLUMN seo_title TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE administrative_documents ALTER COLUMN seo_keywords TYPE VARCHAR(512)');
            DB::statement('ALTER TABLE administrative_documents ALTER COLUMN approx_delay TYPE VARCHAR(255)');
            return;
        }
    }
};

