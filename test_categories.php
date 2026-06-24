<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$cats = \App\Models\Package::pluck('category')->unique();
echo "Unique Categories in DB: \n";
foreach($cats as $c) {
    echo "- " . $c . "\n";
}
