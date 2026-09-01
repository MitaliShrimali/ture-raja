<?php
$file = 'c:/Users/tusha/Downloads/Tour_raja/resources/views/agent/pages/payment.blade.php';
$content = file_get_contents($file);

$replacements = [
    '<a href="{{ route(\'agent.checkout\', [\'type\' => \'boost\'' => '<a target="_blank" href="{{ route(\'agent.checkout\', [\'type\' => \'boost\'',
    '<a href="{{ route(\'agent.checkout\', [\'type\' => \'ad\'' => '<a target="_blank" href="{{ route(\'agent.checkout\', [\'type\' => \'ad\'',
    '<form action="{{ route(\'agent.checkout\') }}" method="GET">' => '<form target="_blank" action="{{ route(\'agent.checkout\') }}" method="GET">',
    '<a href="{{ route(\'agent.checkout\', [\'type\' => \'plan\'' => '<a target="_blank" href="{{ route(\'agent.checkout\', [\'type\' => \'plan\''
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Updated checkout links with target='_blank'\n";
