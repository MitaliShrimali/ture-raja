<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/discover', 'GET', ['category' => ['international']]);
$controller = new \App\Http\Controllers\ListingController();
$response = $controller->index($request);

$view = clone $response;
$data = $view->getData();
echo "Total filtered packages before pagination: " . $data['packages']->total() . "\n";
