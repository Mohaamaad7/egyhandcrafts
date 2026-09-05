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
        Schema::create('craftsmen_stories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('craftsman_name', 255);
            $table->string('craftsman_role', 255);
            $table->string('photo', 255)->nullable();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('youtube_url', 500)->nullable();
            $table->string('audio_file', 255)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('craftsmen_stories');
    }
};
