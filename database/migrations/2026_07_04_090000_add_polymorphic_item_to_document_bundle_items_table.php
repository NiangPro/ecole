<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_bundle_items', function (Blueprint $table) {
            $table->string('item_type')->nullable()->after('bundle_id');
            $table->unsignedBigInteger('item_id')->nullable()->after('item_type');
        });

        DB::table('document_bundle_items')->update([
            'item_type' => \App\Models\Document::class,
            'item_id' => DB::raw('document_id'),
        ]);

        Schema::table('document_bundle_items', function (Blueprint $table) {
            $table->dropUnique(['bundle_id', 'document_id']);
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');

            $table->unique(['bundle_id', 'item_type', 'item_id'], 'bundle_item_unique');
            $table->index(['item_type', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_bundle_items', function (Blueprint $table) {
            $table->dropUnique('bundle_item_unique');
            $table->dropIndex(['item_type', 'item_id']);
            $table->foreignId('document_id')->nullable()->after('bundle_id')->constrained('documents')->onDelete('cascade');
        });

        DB::table('document_bundle_items')
            ->where('item_type', \App\Models\Document::class)
            ->update(['document_id' => DB::raw('item_id')]);

        Schema::table('document_bundle_items', function (Blueprint $table) {
            $table->unique(['bundle_id', 'document_id']);
            $table->dropColumn(['item_type', 'item_id']);
        });
    }
};
