<?php
$files = glob('resources/views/admin/*.blade.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    if(preg_match('/<p[^>]*>.*?Admin \/.*?<\/p>/is', $c, $m)) {
        echo basename($f) . ': ' . trim(preg_replace('/\s+/', ' ', strip_tags($m[0]))) . "\n";
    } else {
        // let's look for any <p> near <h1>
        if (preg_match('/<p[^>]*>.*?<\/p>\s*<h1/is', $c, $m)) {
            echo basename($f) . ': HAS P BEFORE H1: ' . trim(preg_replace('/\s+/', ' ', strip_tags($m[0]))) . "\n";
        } else {
            echo basename($f) . ': NO BREADCRUMB' . "\n";
        }
    }
}
