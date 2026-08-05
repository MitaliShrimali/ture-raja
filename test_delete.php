<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = $app->make(\App\Http\Controllers\AdminController::class);
$req = new \Illuminate\Http\Request();
$req->setMethod('POST');

// Check what agent media we have
$media = \App\Models\AgentMedia::first();
if (!$media) {
    echo "No media to delete\n";
    exit;
}

$req->merge(['selected_ids' => [$media->id]]);

try {
    $resp = $controller->deleteMedia($req);
    echo "Success! Redirect Target: " . $resp->getTargetUrl() . "\n";
    
    // Check if it's still in DB
    $stillExists = \App\Models\AgentMedia::find($media->id);
    if ($stillExists) {
        echo "Error: Media STILL in DB!\n";
    } else {
        echo "Media successfully deleted from DB.\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
