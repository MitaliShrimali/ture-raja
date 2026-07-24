<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transit_music', function (Blueprint $table) {
            $table->id();
            $table->string('transit_name');       // e.g. "Train Package"
            $table->string('music_name');         // Display name for the music
            $table->string('music_file');         // e.g. /uploads/transit_music/train.mp3
            $table->string('status')->default('Active'); // Active, Inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transit_music');
    }
};
