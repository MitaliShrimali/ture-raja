<?php
$code = file_get_contents('lucide.js');
$icons = ['chevron-down', 'layout-grid', 'list', 'mail', 'map-pin', 'message-circle', 'package', 'phone', 'search', 'sliders-horizontal', 'user', 'verified', 'x'];
$missing = [];
foreach($icons as $icon) {
    $camel = str_replace(' ', '', ucwords(str_replace('-', ' ', $icon)));
    if (strpos($code, '"'.$camel.'"') === false && strpos($code, "'".$camel."'") === false && !preg_match("/".$camel."/i", $code)) {
        $missing[] = $icon;
    }
}
print_r($missing);
