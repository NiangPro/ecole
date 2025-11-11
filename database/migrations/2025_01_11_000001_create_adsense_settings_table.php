<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adsense_settings', function (Blueprint $table) {
            $table->id();
            $table->string('publisher_id')->nullable();
            $table->text('adsense_code')->nullable();
            $table->boolean('header_banner')->default(true);
            $table->boolean('sidebar_banner')->default(true);
            $table->boolean('footer_banner')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adsense_settings');
    }
};
