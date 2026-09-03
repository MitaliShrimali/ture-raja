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
            if (!Schema::hasColumn('agents', 'notify_email')) {
                $table->boolean('notify_email')->default(1)->after('sac_hsn_code');
            }
            if (!Schema::hasColumn('agents', 'notify_sms')) {
                $table->boolean('notify_sms')->default(1)->after('notify_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (Schema::hasColumn('agents', 'notify_email')) {
                $table->dropColumn('notify_email');
            }
            if (Schema::hasColumn('agents', 'notify_sms')) {
                $table->dropColumn('notify_sms');
            }
        });
    }
};
