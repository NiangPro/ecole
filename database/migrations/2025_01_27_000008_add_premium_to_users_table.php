<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('email_verified_at');
            $table->timestamp('premium_until')->nullable()->after('is_premium');
            $table->foreignId('current_subscription_id')->nullable()->after('premium_until')->constrained('subscriptions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_subscription_id']);
            $table->dropColumn(['is_premium', 'premium_until', 'current_subscription_id']);
        });
    }
};



