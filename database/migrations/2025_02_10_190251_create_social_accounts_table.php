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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // Instagram, TikTok, etc.
            $table->string('account_id'); // ID del usuario en la red social
            $table->string('username');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable(); // NUEVO
            $table->timestamps();

            $table->unique(['platform', 'account_id']); // Un usuario no puede tener múltiples cuentas del mismo tipo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
