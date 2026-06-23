<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$p = \App\Models\Package::where("title", "like", "%maharastra%")->first();
if ($p) {
    echo "Price: " . $p->price . "\n";
    echo "Old Price: " . $p->old_price . "\n";
} else {
    echo "Not found";
}
