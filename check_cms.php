<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pages = \Illuminate\Support\Facades\DB::table('cms_pages')->get();
foreach($pages as $p) {
    echo $p->slug . " | status: " . $p->status . "\n";
}
