<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'editorial_itinerary')) {
                $table->longText('editorial_itinerary')->nullable();
            }
            if (!Schema::hasColumn('packages', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable()->after('editorial_itinerary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumnIfExists('editorial_itinerary');
            $table->dropColumnIfExists('hotel_id');
        });
    }
};
