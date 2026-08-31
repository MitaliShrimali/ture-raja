<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        if (preg_match('/(?<!:)data-lucide="(facebook|twitter|instagram|linkedin|music-off)"/', $content, $m)) {
            echo $file->getPathname() . " -> " . $m[1] . "\n";
        }
    }
}
