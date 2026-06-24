<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$packages = DB::table('packages')->get();

foreach ($packages as $p) {
    $cat = trim($p->category);
    if (str_starts_with($cat, '[') || str_starts_with($cat, '{')) {
        DB::table('packages')->where('id', $p->id)->update([
            'categories_list' => $cat,
            'category' => 'domestic'
        ]);
        echo "Updated package ID: " . $p->id . "\n";
    }
}
echo "Migration script complete.\n";
