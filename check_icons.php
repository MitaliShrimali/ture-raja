<?php
$code = file_get_contents('lucide.js');
$icons = ['bed','binoculars','building','calendar','check','chef-hat','chevron-right','circle-alert','circle-check','circle-x','copy','external-link','file-text','globe','list','mail','map','map-pin','package','phone','phone-call','phone-forwarded','share-2','shield-check','sparkles','star','user','user-check','x'];
$missing = [];
foreach($icons as $icon) {
    $camel = str_replace(' ', '', ucwords(str_replace('-', ' ', $icon)));
    if (strpos($code, '"'.$camel.'"') === false && strpos($code, "'".$camel."'") === false && !preg_match("/".$camel."/i", $code)) {
        $missing[] = $icon;
    }
}
print_r($missing);
