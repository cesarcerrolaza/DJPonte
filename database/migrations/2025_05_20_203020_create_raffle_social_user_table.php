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
        Schema::create('raffle_social_user', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('raffle_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_user_id')->constrained()->onDelete('cascade');

            $table->unique(['raffle_id', 'social_user_id']); // Un usuario solo puede participar una vez por sorteo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffle_social_user');
    }
};
