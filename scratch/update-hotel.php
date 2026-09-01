<?php
$files = [
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-edit.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/agent/pages/payment.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/app/Services/PlanPermissionService.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // In PlanPermissionService
    $content = str_replace(
        "'feat_hotel_options' => ['type' => 'boolean', 'label' => 'Hotel Options']",
        "'limit_hotel_options' => ['type' => 'numeric', 'label' => 'Hotel Options Limit (0 for unlimited)']",
        $content
    );
    
    // In Blade files
    $content = str_replace(
        "['key' => 'feat_hotel_options', 'label' => 'Hotel Options', 'type' => 'boolean']",
        "['key' => 'limit_hotel_options', 'label' => 'Hotel Options', 'type' => 'numeric']",
        $content
    );
    
    file_put_contents($file, $content);
    echo "Updated $file\n";
}
