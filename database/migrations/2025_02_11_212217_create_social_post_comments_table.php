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
        Schema::create('social_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_post_id')->constrained()->onDelete('cascade');
            $table->bigInteger('social_user_id')->unsigned()->nullable();
            $table->foreign('social_user_id')->references('id')->on('social_users')->onDelete('set null');
            $table->string('media_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_post_comments');
    }
};
