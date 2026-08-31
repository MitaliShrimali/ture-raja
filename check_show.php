<?php
$html = file_get_contents('resources/views/packages/show.blade.php');
preg_match_all('/data-lucide="([^"]+)"/', $html, $matches);
$icons = array_unique($matches[1]);
print_r($icons);

$code = file_get_contents('lucide.js');
$missing = [];
foreach($icons as $icon) {
    $camel = str_replace(' ', '', ucwords(str_replace('-', ' ', $icon)));
    if (strpos($code, '"'.$camel.'"') === false && strpos($code, "'".$camel."'") === false && !preg_match("/".$camel."/i", $code)) {
        $missing[] = $icon;
    }
}
echo "MISSING:\n";
print_r($missing);
