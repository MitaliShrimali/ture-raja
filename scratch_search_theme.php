<?php
$welcome = file_get_contents('resources/views/welcome.blade.php');
$lines = explode("\n", $welcome);
foreach ($lines as $i => $line) {
    if (stripos($line, 'theme') !== false) {
        echo "welcome.blade.php Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}

$listing = file_get_contents('resources/views/listing.blade.php');
$lines = explode("\n", $listing);
foreach ($lines as $i => $line) {
    if (stripos($line, 'theme') !== false) {
        echo "listing.blade.php Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
