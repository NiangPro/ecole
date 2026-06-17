<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // VARCHAR(255) est trop court pour les User-Agent modernes (Google bots = ~200+ chars)
        foreach (['statistics', 'analytics_events', 'push_subscriptions'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_agent')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('user_agent', 500)->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['statistics', 'analytics_events', 'push_subscriptions'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_agent')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('user_agent', 255)->nullable()->change();
                });
            }
        }
    }
};
