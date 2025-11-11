<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->date('visit_date');
            $table->timestamps();
            
            $table->index('visit_date');
            $table->index('page_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
