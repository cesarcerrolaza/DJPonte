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
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('djsession_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('social_account_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // 'instagram', 'tiktok', etc.
            $table->string('media_id')->comment('El ID del post en la plataforma social.');
                   
            // Es el post que está actualmente recibiendo peticiones
            $table->boolean('is_active')->default(false);
            
            // Mostrar una vista previa sin volver a llamar a la API
            $table->text('caption')->nullable(); // La descripción del post
            $table->text('media_url')->nullable();
            $table->text('permalink')->nullable();
            
            $table->timestamps();
            
            $table->unique(['social_account_id', 'media_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
