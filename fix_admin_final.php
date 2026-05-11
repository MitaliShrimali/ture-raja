<?php
$files = glob('resources/views/admin/*.blade.php');

$stdClass = 'text-xs font-bold text-primary uppercase tracking-widest mb-1';

foreach($files as $f) {
    $c = file_get_contents($f);
    $original = $c;
    
    // 1. Convert h1 to h2
    $c = preg_replace('/<h1([^>]*)>/i', '<h2$1>', $c);
    $c = preg_replace('/<\/h1>/i', '</h2>', $c);
    
    // 2. Remove text- sizing classes from h2 tags so they inherit the global app.css h2 size
    // We only replace inside h2 tags!
    $c = preg_replace_callback('/(<h2[^>]*class=[\'"])([^\'"]*)([\'"][^>]*>)/i', function($matches) {
        $classes = $matches[2];
        $classes = preg_replace('/\btext-(2xl|3xl|4xl|5xl|6xl|7xl)\b/i', '', $classes);
        // clean up double spaces inside the class attribute ONLY
        $classes = preg_replace('/\s+/', ' ', $classes);
        $classes = trim($classes);
        return $matches[1] . $classes . $matches[3];
    }, $c);
    
    // 3. Fix breadcrumbs
    // Pattern A: standard <p> tag breadcrumbs
    $c = preg_replace('/<p\s+class=[\'"][^\'"]*[\'"]([^>]*)>(.*?Admin\s*\/.*?)<\/p>/is', '<p class="' . $stdClass . '"$1>$2</p>', $c);
    
    // Pattern B: leads.blade.php specific breadcrumb
    $c = preg_replace('/<div class="flex items-center gap-2 text-\[10px\].*?Dashboard.*?\/.*?Lead Management.*?<\/div>/is', '<p class="' . $stdClass . '">Admin / Leads</p>', $c);

    if ($c !== $original) {
        file_put_contents($f, $c);
        echo "Updated: " . basename($f) . "\n";
    }
}
echo "All done.\n";
