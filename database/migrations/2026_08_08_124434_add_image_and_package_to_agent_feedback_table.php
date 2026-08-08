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
        Schema::table('agent_feedback', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('message');
            $table->unsignedBigInteger('package_id')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_feedback', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'package_id']);
        });
    }
};
