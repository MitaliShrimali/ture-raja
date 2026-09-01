<?php
$files = [
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-edit.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace in Blade files
    $content = str_replace(
        "['key' => 'limit_hotel_options', 'label' => 'Hotel Options', 'type' => 'numeric']",
        "['key' => 'limit_hotel_options', 'label' => 'Hotel Options', 'type' => 'numeric_dropdown']",
        $content
    );
    
    file_put_contents($file, $content);
    echo "Updated $file\n";
}
