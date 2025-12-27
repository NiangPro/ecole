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
        Schema::create('ab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('test_type'); // 'page', 'element', 'feature'
            $table->string('target_url'); // URL où le test est actif
            $table->json('variants'); // [{name: 'A', content: '...'}, {name: 'B', content: '...'}]
            $table->string('goal_event'); // Événement à mesurer (ex: 'purchase', 'signup')
            $table->integer('traffic_percentage')->default(100); // Pourcentage de trafic à tester
            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ab_test_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('variant'); // 'A', 'B', etc.
            $table->boolean('converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index(['test_id', 'variant']);
            $table->index(['test_id', 'converted']);
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_test_assignments');
        Schema::dropIfExists('ab_tests');
    }
};
