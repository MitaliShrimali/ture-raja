<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \DB::table('users')->where('email', 'admin@tourraja.com')->first();
if ($user) {
    echo json_encode($user, JSON_PRETTY_PRINT);
} else {
    echo "User not found\n";
}
