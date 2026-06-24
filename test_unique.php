<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/discover', 'GET', ['category' => ['international'], 'sort' => 'ALL']);

$controller = new \App\Http\Controllers\ListingController();
$response = $controller->index($request);
$data = $response->getData();
$packages = $data['packages'];

echo "Packages returned to view: " . $packages->count() . "\n";
