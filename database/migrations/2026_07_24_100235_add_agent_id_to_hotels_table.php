<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Add agent_id column (nullable so existing rows don't break during migration)
            $table->unsignedBigInteger('agent_id')->nullable()->after('id')->index();
        });

        // Delete all existing mock/seed data — every agent starts with a clean empty list
        DB::table('hotels')->whereNull('agent_id')->delete();
        DB::table('hotels')->where('agent_id', 0)->delete();
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropIndex(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
