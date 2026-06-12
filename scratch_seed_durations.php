<?php

use Illuminate\Support\Facades\DB;

if (php_sapi_name() !== 'cli') {
    die('CLI only');
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$durations = [];
// Generate a nice range of durations
$presets = [31, 30, 15, 7, 10, 5, 3, 12, 14, 21, 28, 45, 60, 90];

foreach ($presets as $days) {
    $nights = max(1, $days - 1);
    $durations[] = [
        'nights' => $nights,
        'name' => sprintf('%02d Days', $days),
        'status' => in_array($days, [15]) ? 'Inactive' : 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

// Add more dummy ones to reach a realistic count like 42
for ($i = 1; $i <= 28; $i++) {
    if (in_array($i, $presets)) continue;
    $durations[] = [
        'nights' => $i,
        'name' => sprintf('%02d Days', $i + 1),
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

DB::table('durations')->truncate();
DB::table('durations')->insert($durations);

echo "Seed durations successfully! Total: " . count($durations) . "\n";
