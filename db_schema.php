<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $t = array_values((array)$table)[0];
    echo 'TABLE: ' . $t . "\n";
    $cols = \DB::select('DESCRIBE ' . $t);
    foreach ($cols as $col) {
        echo '  ' . $col->Field . ' (' . $col->Type . ')' . "\n";
    }
}
