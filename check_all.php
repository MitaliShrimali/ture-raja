<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$allIcons = [];
foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/data-lucide="([^"]+)"/', $content, $matches);
        $allIcons = array_merge($allIcons, $matches[1]);
    }
}
$allIcons = array_unique($allIcons);

$code = file_get_contents('lucide.js');
$missing = [];
foreach($allIcons as $icon) {
    $camel = str_replace(' ', '', ucwords(str_replace('-', ' ', $icon)));
    if (strpos($code, '"'.$camel.'"') === false && strpos($code, "'".$camel."'") === false && !preg_match("/".$camel."/i", $code)) {
        $missing[] = $icon;
    }
}
print_r($missing);
