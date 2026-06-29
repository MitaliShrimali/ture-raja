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
            if (!Schema::hasColumn('packages', 'old_price')) {
                $table->decimal('old_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('packages', 'group_size')) {
                $table->string('group_size')->nullable();
            }
            if (!Schema::hasColumn('packages', 'gallery')) {
                $table->json('gallery')->nullable();
            }
            if (!Schema::hasColumn('packages', 'brochure')) {
                $table->string('brochure')->nullable();
            }
            if (!Schema::hasColumn('packages', 'included')) {
                $table->json('included')->nullable();
            }
            if (!Schema::hasColumn('packages', 'excluded')) {
                $table->json('excluded')->nullable();
            }
            if (!Schema::hasColumn('packages', 'itinerary')) {
                $table->json('itinerary')->nullable();
            }
            if (!Schema::hasColumn('packages', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable();
            }
            if (!Schema::hasColumn('packages', 'theme')) {
                $table->string('theme')->nullable();
            }
            if (!Schema::hasColumn('packages', 'holiday_type')) {
                $table->string('holiday_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'old_price', 'group_size', 'gallery', 'brochure', 
                'included', 'excluded', 'itinerary', 'hotel_id', 
                'theme', 'holiday_type'
            ]);
        });
    }
};
