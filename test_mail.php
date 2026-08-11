<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Mail::raw('This is a test email to verify SMTP configuration.', function ($message) {
        $message->to('info@tour raja.com')
                ->subject('SMTP Test');
    });
    echo "SMTP Connection Successful\n";
} catch (\Exception $e) {
    echo "SMTP Error: " . $e->getMessage() . "\n";
}
