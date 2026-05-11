<?php
$files = glob('resources/views/admin/*.blade.php');

foreach($files as $f) {
    $c = file_get_contents($f);
    $original = $c;
    
    // 1. Fix Headers (h1 and h2)
    // Replace text-3xl, text-4xl, etc with text-2xl
    $c = preg_replace('/(<h[12][^>]*class=[\'"][^\'"]*)text-(3xl|4xl)([^\'"]*[\'"]>)/i', '$1text-2xl$3', $c);
    
    // 2. Fix Breadcrumbs
    // Standard class
    $stdClass = 'text-xs font-bold text-primary uppercase tracking-widest mb-1';
    
    // Pattern A: <p> tags with Admin / ...
    if (preg_match('/<p\s+class=[\'"][^\'"]*[\'"]([^>]*)>(.*?Admin\s*\/.*?)<\/p>/i', $c)) {
        $c = preg_replace('/<p\s+class=[\'"][^\'"]*[\'"]([^>]*)>(.*?Admin\s*\/.*?)<\/p>/i', '<p class="' . $stdClass . '"$1>$2</p>', $c);
    } 
    // Pattern B: Leads page specific breadcrumb
    else if (preg_match('/<div[^>]*>.*?Dashboard.*?\/.*?Lead Management.*?<\/div>/is', $c)) {
        $c = preg_replace('/<div[^>]*>.*?Dashboard.*?\/.*?Lead Management.*?<\/div>/is', '<p class="' . $stdClass . '">Admin / Leads</p>', $c);
    }
    
    if ($c !== $original) {
        file_put_contents($f, $c);
        echo "Updated: " . basename($f) . "\n";
    }
}
echo "All done.\n";
