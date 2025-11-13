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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('image')->nullable()->after('ad_code');
            $table->enum('image_type', ['internal', 'external'])->default('external')->after('image');
            $table->string('link_url')->nullable()->after('image_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_type', 'link_url']);
        });
    }
};
