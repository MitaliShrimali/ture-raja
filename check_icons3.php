<?php
$code = file_get_contents('lucide.js');
$icons = ['arrow-left', 'arrow-right', 'book-open', 'building', 'calendar', 'calendar-days', 'chef-hat', 'chevron-down', 'chevron-right', 'circle-check', 'circle-x', 'clock', 'coins', 'compass', 'eye', 'eye-off', 'facebook', 'file-text', 'folder', 'heart', 'home', 'image', 'image-off', 'info', 'instagram', 'log-out', 'map-pin', 'menu', 'palette', 'pencil', 'phone-call', 'plus', 'search', 'shield', 'shield-check', 'sparkles', 'star', 'sun', 'tag', 'trash-2', 'twitter', 'upload-cloud', 'user', 'user-check', 'users', 'utensils', 'wallet', 'x'];
$missing = [];
foreach($icons as $icon) {
    $camel = str_replace(' ', '', ucwords(str_replace('-', ' ', $icon)));
    if (strpos($code, '"'.$camel.'"') === false && strpos($code, "'".$camel."'") === false && !preg_match("/".$camel."/i", $code)) {
        $missing[] = $icon;
    }
}
print_r($missing);
