<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    DB::table('user_inquiries')->insert([
        'name' => 'Test',
        'email' => 't@t.com',
        'message' => 'Test MSG',
        'status' => 'Pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Inserted into user_inquiries.\n";
} catch (\Exception $e) {
    echo "Error user_inquiries: " . $e->getMessage() . "\n";
}

try {
    DB::table('contacts')->insert([
        'name' => 'Test',
        'email' => 't@t.com',
        'message' => 'Test MSG',
        'status' => 'Pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Inserted into contacts.\n";
} catch (\Exception $e) {
    echo "Error contacts: " . $e->getMessage() . "\n";
}
