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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('dj_id')->constrained('users')->onDelete('cascade');         // Creador del sorteo
            $table->foreignId('djsession_id')->nullable()->constrained()->onDelete('set null'); // Opcional
            $table->nullableMorphs('winner'); // Puede ser un user o un social user

            // Información del premio
            $table->string('prize_name');
            $table->integer('prize_quantity')->default(1);
            $table->string('prize_image')->nullable();

            // Sorteo actual
            $table->boolean('is_current')->default(false);
            // Estado del sorteo
            $table->enum('status', ['draft', 'open', 'closed', 'terminated'])->default('draft');
            // Descripción
            $table->string('description')->nullable();
            $table->unsignedInteger('participants_count')->nullable(); // Se puede mantener actualizado vía eventos
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
