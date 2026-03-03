<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('administrative_documents', function (Blueprint $table) {
            $table->string('cover_image', 2048)->nullable()->after('tips');
            $table->enum('cover_type', ['internal', 'external'])->default('internal')->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('administrative_documents', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'cover_type']);
        });
    }
};
