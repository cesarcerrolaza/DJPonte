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
        Schema::create('song_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('song_id')->unsigned()->nullable();
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('set null');
            $table->foreignId('djsession_id')->constrained()->onDelete('cascade');
            $table->string('custom_title')->nullable();
            $table->string('custom_artist')->nullable();
            $table->float('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_requests');
    }
};
