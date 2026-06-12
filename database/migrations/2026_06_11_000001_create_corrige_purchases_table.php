<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corrige_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epreuve_id')->constrained('epreuves')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Identité de l'acheteur : e-mail OU téléphone (au moins un des deux)
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->boolean('whatsapp_enabled')->default(false);
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('currency', 5)->default('XOF');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('purchased_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->string('download_token', 64)->nullable()->index();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();

            $table->index(['customer_email', 'epreuve_id']);
            $table->index(['customer_phone', 'epreuve_id']);
            $table->index(['status', 'epreuve_id']);
        });

        // Prix global d'un corrigé (FCFA), réglable en admin
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'corrige_price')) {
                $table->decimal('corrige_price', 10, 2)->default(500);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrige_purchases');
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'corrige_price')) {
                $table->dropColumn('corrige_price');
            }
        });
    }
};
