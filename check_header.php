<?php
$html = file_get_contents('resources/views/components/header.blade.php');
preg_match_all('/data-lucide="([^"]+)"/', $html, $matches);
$icons = array_unique($matches[1]);
print_r($icons);
