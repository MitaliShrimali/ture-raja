<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\Illuminate\Support\Facades\DB::table('cms_pages')
    ->where('slug', 'privacy-policy')
    ->update(['status' => 'Published']);
echo "Updated to Published.\n";
