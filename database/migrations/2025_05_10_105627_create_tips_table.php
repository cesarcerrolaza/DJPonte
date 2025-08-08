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
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dj_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('djsession_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('song_id')->nullable()->constrained()->onDelete('set null');
            $table->string('custom_title')->nullable();
            $table->string('custom_artist')->nullable();
            $table->integer('amount');
            $table->string('currency');
            $table->string('stripe_session_id')->nullable();
            $table->enum('status', ['pending','paid','failed'])->default('pending');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};
