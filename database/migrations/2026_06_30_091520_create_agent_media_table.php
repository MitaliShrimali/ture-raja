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
        Schema::create('agent_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->string('type'); // 'folder' or 'image'
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent folder ID
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->integer('size')->default(0); // in bytes
            $table->string('mime_type')->nullable();
            $table->timestamps();

            // Setup basic indexes
            $table->index('agent_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_media');
    }
};
