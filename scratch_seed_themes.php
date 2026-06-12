<?php

use Illuminate\Support\Facades\DB;

if (php_sapi_name() !== 'cli') {
    die('CLI only');
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$themes = [
    [
        'name' => 'Family/Group',
        'description' => 'Fun-filled trips for everyone',
        'image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Religious',
        'description' => 'Spiritual & sacred journeys',
        'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Honeymoon',
        'description' => 'Romantic & intimate escapes',
        'image' => 'https://images.unsplash.com/photo-1573152958734-1922c188fba3?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Solo',
        'description' => 'Self-discovery & independent journeys',
        'image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Adventure',
        'description' => 'Thrilling & action-packed expeditions',
        'image' => 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Cruise',
        'description' => 'Luxury voyages & ship adventures',
        'image' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'WaterPark',
        'description' => 'Wet & wild aquatic theme parks',
        'image' => 'https://images.unsplash.com/photo-1582650625119-3a31f8fa2699?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Pilgrimage',
        'description' => 'Sacred trails & religious devotion',
        'image' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&q=80&w=400&v=1',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ]
];

DB::table('themes')->truncate();
DB::table('themes')->insert($themes);

echo "Seed themes successfully! Total: " . count($themes) . "\n";
