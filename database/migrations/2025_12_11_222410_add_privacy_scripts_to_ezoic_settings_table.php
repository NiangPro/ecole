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
        if (Schema::hasTable('ezoic_settings')) {
            Schema::table('ezoic_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('ezoic_settings', 'privacy_scripts')) {
                    $table->text('privacy_scripts')->nullable()->after('site_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ezoic_settings')) {
            Schema::table('ezoic_settings', function (Blueprint $table) {
                if (Schema::hasColumn('ezoic_settings', 'privacy_scripts')) {
                    $table->dropColumn('privacy_scripts');
                }
            });
        }
    }
};
