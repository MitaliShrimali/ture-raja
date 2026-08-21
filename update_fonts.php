<?php
$f = 'resources/views/agent/pages/settings.blade.php';
$c = file_get_contents($f);
$c = str_replace('text-[9px] font-bold text-gray-400 uppercase', 'text-[11px] font-bold text-gray-400 uppercase', $c);
$c = str_replace('text-[10px] font-bold text-gray-400 uppercase', 'text-[12px] font-bold text-gray-400 uppercase', $c);
file_put_contents($f, $c);
echo "Updated font sizes.\n";
