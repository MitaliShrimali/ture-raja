<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$tables = DB::select('SHOW TABLES');
echo "Tables:\n";
foreach($tables as $t) {
    echo array_values((array)$t)[0] . "\n";
}

$contacts = DB::table('contacts')->get();
echo "\nContacts:\n";
foreach($contacts as $c) {
    echo "ID: {$c->id}, Name: {$c->name}, MSG: {$c->message}\n";
}

$userInquiries = DB::table('user_inquiries')->get();
echo "\nUser Inquiries:\n";
foreach($userInquiries as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, MSG: {$u->message}\n";
}
