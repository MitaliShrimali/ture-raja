<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = request();
$req->merge(['selected_ids' => [14]]);
$ctrl = app()->make(App\Http\Controllers\AdminController::class);
try {
    $res = $ctrl->deleteMedia($req);
    echo "Status Code: " . $res->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
