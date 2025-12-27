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
        Schema::table('document_purchases', function (Blueprint $table) {
            $table->string('download_token', 64)->nullable()->unique()->after('customer_name');
            $table->timestamp('token_expires_at')->nullable()->after('download_token');
            $table->index('download_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_purchases', function (Blueprint $table) {
            $table->dropIndex(['download_token']);
            $table->dropColumn(['download_token', 'token_expires_at']);
        });
    }
};
