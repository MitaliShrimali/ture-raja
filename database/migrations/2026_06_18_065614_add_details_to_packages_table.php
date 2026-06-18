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
        Schema::table('packages', function (Blueprint $table) {
            $table->json('hotels')->nullable();
            $table->json('amenities')->nullable();
            $table->json('meals')->nullable();
            $table->json('transfers')->nullable();
            $table->json('keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['hotels', 'amenities', 'meals', 'transfers', 'keywords']);
        });
    }
};
