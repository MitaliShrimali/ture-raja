<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$pages = [
    ['title' => 'Anti-fraud policy', 'slug' => 'anti-fraud-policy'],
    ['title' => 'Listing accuracy policy', 'slug' => 'listing-accuracy-policy'],
    ['title' => 'Intellectual property', 'slug' => 'intellectual-property'],
    ['title' => 'Connect purchase terms', 'slug' => 'connect-purchase-terms']
];

foreach ($pages as $p) {
    if (!DB::table('cms_pages')->where('slug', $p['slug'])->exists()) {
        DB::table('cms_pages')->insert([
            'title' => $p['title'],
            'slug' => $p['slug'],
            'content' => '<p>' . $p['title'] . ' content goes here.</p>',
            'status' => 'Published',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Added " . $p['title'] . "\n";
    } else {
        echo "Already exists: " . $p['title'] . "\n";
    }
}
echo "Done.\n";
