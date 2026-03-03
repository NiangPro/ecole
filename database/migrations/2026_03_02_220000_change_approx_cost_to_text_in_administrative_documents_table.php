<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('administrative_documents') || !Schema::hasColumn('administrative_documents', 'approx_cost')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `administrative_documents` MODIFY `approx_cost` TEXT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE administrative_documents ALTER COLUMN approx_cost TYPE TEXT');
            return;
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('administrative_documents') || !Schema::hasColumn('administrative_documents', 'approx_cost')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `administrative_documents` MODIFY `approx_cost` VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE administrative_documents ALTER COLUMN approx_cost TYPE VARCHAR(255)');
            return;
        }
    }
};

