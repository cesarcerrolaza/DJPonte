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
        Schema::create('djsessions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('venue');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->bigInteger('user_id')->unsigned()->nullable();
                    $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->unsignedInteger('song_request_timeout')->default(30);
            $table->unsignedInteger('current_users')->default(0);
            $table->unsignedInteger('peak_users')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('djsessions');
    }
};
