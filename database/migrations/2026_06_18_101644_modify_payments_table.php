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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('id');
            $table->string('invoice_number')->nullable()->after('amount');
            $table->string('type')->default('plan_upgrade')->after('plan_type'); // plan_upgrade, ad_payment, service_guarantee, feature_package
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['agent_id', 'invoice_number', 'type']);
        });
    }
};
