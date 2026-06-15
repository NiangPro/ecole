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
        Schema::table('epreuves', function (Blueprint $table) {
            // null = année unique (= year) ; renseigné = plage "year — year_end" (annales)
            $table->unsignedSmallInteger('year_end')->nullable()->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->dropColumn('year_end');
        });
    }
};
