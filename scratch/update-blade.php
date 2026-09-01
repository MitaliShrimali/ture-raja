<?php
$file = 'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php';
$content = file_get_contents($file);

$html = file_get_contents('c:/Users/tusha/Downloads/Tour_raja/scratch/plan-creation-features.html');

// Replace from <div class="space-y-6"> up to <!-- Actions -->
$content = preg_replace('/<div class="space-y-6">.*?<!-- Actions -->/s', $html . "\n\n            <!-- Actions -->", $content);

file_put_contents($file, $content);
echo "Done";
