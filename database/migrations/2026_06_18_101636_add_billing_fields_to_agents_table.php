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
        Schema::table('agents', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->timestamp('plan_expires_at')->nullable();
            $table->timestamp('service_guaranteed_expires_at')->nullable();
            // Try to change default, but if doctrine/dbal isn't there it might fail.
            // We'll handle this in the code level for new agents.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['plan_id', 'plan_expires_at', 'service_guaranteed_expires_at']);
        });
    }
};
