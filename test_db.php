<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Admins:\n";
print_r(\DB::table('admins')->pluck('email')->toArray());
echo "\nUsers:\n";
print_r(\DB::table('users')->pluck('email')->toArray());
