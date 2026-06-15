<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (!Schema::hasColumn('agents', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('agents', 'agency_name')) {
                $table->string('agency_name')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['password', 'agency_name']);
        });
    }
};
