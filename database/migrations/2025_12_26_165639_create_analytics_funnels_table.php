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
        Schema::create('analytics_funnels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('steps'); // [{url: '/page1', name: 'Page 1'}, ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('analytics_funnel_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funnel_id')->constrained('analytics_funnels')->onDelete('cascade');
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('step_reached'); // Étape atteinte dans le funnel
            $table->boolean('completed')->default(false); // A-t-il complété le funnel
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_to_complete')->nullable(); // En secondes
            $table->timestamps();

            $table->index(['funnel_id', 'created_at']);
            $table->index(['completed', 'created_at']);
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_funnel_conversions');
        Schema::dropIfExists('analytics_funnels');
    }
};
