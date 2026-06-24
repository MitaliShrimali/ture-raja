<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/discover', 'GET', ['category' => ['international']]);

// emulate the method exactly
$dbPackages = \App\Models\Package::where('status', 'Active')->get()->toArray();
$packages = collect($dbPackages);

$catTypes = array_map('strtolower', (array) $request->category);
$filtered = $packages->filter(function($pkg) use ($catTypes) {
    $pkg = (array) $pkg;
    return in_array(strtolower($pkg['category'] ?? ''), $catTypes);
});
echo "After category filter: " . $filtered->count() . "\n";

