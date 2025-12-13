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
        if (!Schema::hasTable('ezoic_settings')) {
            Schema::create('ezoic_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_id')->nullable(); // Ezoic Site ID
                $table->text('ezoic_code')->nullable(); // Code Ezoic (Head)
                $table->text('ezoic_body_code')->nullable(); // Code Ezoic (Body - début)
                $table->text('ezoic_footer_code')->nullable(); // Code Ezoic (Footer)
                $table->boolean('header_banner')->default(true);
                $table->boolean('sidebar_banner')->default(true);
                $table->boolean('footer_banner')->default(false);
                $table->boolean('in_content')->default(false);
                $table->boolean('auto_ads')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ezoic_settings');
    }
};
