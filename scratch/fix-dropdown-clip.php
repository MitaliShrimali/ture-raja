<?php
$files = [
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-edit.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    // Replace overflow-hidden with overflow-visible on the plan configuration card
    $content = str_replace('border-border-soft overflow-hidden shadow-sm', 'border-border-soft shadow-sm', $content);
    file_put_contents($file, $content);
}

echo "Done";
