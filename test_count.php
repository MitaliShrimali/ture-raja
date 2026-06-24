<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\Package::where('category', 'international')->where('status', 'Active')->count();
echo "Active International: " . $count . "\n";
$all_int = \App\Models\Package::where('category', 'international')->count();
echo "All International: " . $all_int . "\n";
