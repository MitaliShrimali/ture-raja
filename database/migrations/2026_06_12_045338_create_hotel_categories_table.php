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
        Schema::create('hotel_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->default('bed');
            $table->boolean('status')->default(true); // true = Active, false = Inactive
            $table->timestamps();
        });

        // Insert initial categories matching the mockup
        $initialCategories = [
            ['name' => 'Apartment', 'description' => 'Serviced apartments for family stays', 'icon' => 'building-2', 'status' => true],
            ['name' => 'Home Stay', 'description' => 'Cozy local home stays', 'icon' => 'home', 'status' => true],
            ['name' => '7 Star', 'description' => 'Ultra luxury high-end hotels', 'icon' => 'award', 'status' => true],
            ['name' => '6 Star', 'description' => 'Premium luxury hotels', 'icon' => 'award', 'status' => true],
            ['name' => '5 Star', 'description' => 'Luxury 5-star properties', 'icon' => 'bed', 'status' => true],
            ['name' => '4 Star', 'description' => 'Comfortable 4-star properties', 'icon' => 'bed', 'status' => true],
            ['name' => '3 Star', 'description' => 'Budget friendly 3-star hotels', 'icon' => 'bed', 'status' => true],
            ['name' => '2 Star', 'description' => 'Basic 2-star properties', 'icon' => 'bed', 'status' => true],
            ['name' => 'Guest House', 'description' => 'Cozy guest houses', 'icon' => 'home', 'status' => true],
            ['name' => 'Dharmshala', 'description' => 'Simple religious lodging options', 'icon' => 'landmark', 'status' => true],
        ];

        DB::table('hotel_categories')->insert($initialCategories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_categories');
    }
};
