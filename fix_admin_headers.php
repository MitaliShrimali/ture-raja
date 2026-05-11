<?php
$files = glob('resources/views/admin/*.blade.php');

foreach($files as $f) {
    $c = file_get_contents($f);
    $original = $c;
    
    // Convert all <h1 class="..."> to <h2 class="..."> 
    // This perfectly matches the leads.blade.php size that the user prefers
    // because in app.css, h2 is styled as @apply text-3xl md:text-4xl lg:text-5xl;
    $c = preg_replace('/<h1([^>]*)>/i', '<h2$1>', $c);
    $c = preg_replace('/<\/h1>/i', '</h2>', $c);
    
    // Also remove any rogue text-2xl or text-3xl classes from these tags since app.css 
    // handles the sizing globally for h2
    $c = preg_replace('/(<h2[^>]*class=[\'"][^\'"]*)text-(2xl|3xl|4xl|5xl|6xl|7xl)([^\'"]*[\'"]>)/i', '$1$3', $c);
    // Cleanup any double spaces caused by the class removal
    $c = preg_replace('/class=" /', 'class="', $c);
    $c = preg_replace('/  +/', ' ', $c);

    if ($c !== $original) {
        file_put_contents($f, $c);
        echo "Updated headers to h2: " . basename($f) . "\n";
    }
}
echo "All done.\n";
