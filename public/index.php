<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

define('CUSTOM_PUBLIC_PATH', __DIR__);

// Determine environment paths
$isHostinger = file_exists(__DIR__ . '/../../tour-raja/bootstrap/app.php');

$maintenancePath = $isHostinger
    ? __DIR__ . '/../../tour-raja/storage/framework/maintenance.php'
    : __DIR__ . '/../storage/framework/maintenance.php';

$vendorPath = $isHostinger
    ? __DIR__ . '/../../tour-raja/vendor/autoload.php'
    : __DIR__ . '/../vendor/autoload.php';

$bootstrapPath = $isHostinger
    ? __DIR__ . '/../../tour-raja/bootstrap/app.php'
    : __DIR__ . '/../bootstrap/app.php';

// Determine if the application is in maintenance mode...
if (file_exists($maintenancePath)) {
    require $maintenancePath;
}

// Register the Composer autoloader...
require $vendorPath;

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $bootstrapPath;

$app->handleRequest(Request::capture());
