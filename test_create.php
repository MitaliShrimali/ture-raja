<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pkg = new \App\Models\Package();
$pkg->title = 'Test International Package from Script';
$pkg->category = 'international';
$pkg->status = 'Active';
$pkg->price = 1000;
$pkg->save();
echo "Package created with category international. ID: " . $pkg->id . "\n";
