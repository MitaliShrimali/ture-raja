<?php

use Illuminate\Support\Facades\DB;

// Ensure this script can only run via artisan or terminal
if (php_sapi_name() !== 'cli') {
    die('CLI only');
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transits = [
    [
        'name' => 'Helicopter Package',
        'description' => 'Premium Sky Tours',
        'selected_icon' => 'helicopter',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Individual Package',
        'description' => 'Solo Backpacking',
        'selected_icon' => 'user',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Int. tour by Car',
        'description' => 'Cross-border Luxury Sedan',
        'selected_icon' => 'car',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Tracking',
        'description' => 'Guided Mountain Trails',
        'selected_icon' => 'footprints',
        'status' => 'Inactive',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Land Package',
        'description' => 'Comprehensive Ground Transport',
        'selected_icon' => 'map-pin',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Cruise Package',
        'description' => 'Maritime Leisure Journeys',
        'selected_icon' => 'ship',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Bullet Ride',
        'description' => 'High-Octane Motorbikig',
        'selected_icon' => 'bike',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Bus Package',
        'description' => 'Regional Coach Fleet',
        'selected_icon' => 'bus',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Train Package',
        'description' => 'Scenic Rail Expeditions',
        'selected_icon' => 'train',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Flight Package',
        'description' => 'International Air Charter',
        'selected_icon' => 'plane',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ]
];

DB::table('transits')->truncate();
DB::table('transits')->insert($transits);

echo "Seed transits successfully!\n";
