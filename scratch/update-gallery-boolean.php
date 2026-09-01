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
        "'limit_gallery_images' => ['type' => 'numeric', 'label' => 'Gallery Images Limit (0 for unlimited)']",
        "'feat_add_gallery' => ['type' => 'boolean', 'label' => 'Add Gallery']",
        $content
    );
    
    // In Blade files
    $content = str_replace(
        "['key' => 'limit_gallery_images', 'label' => 'Add Gallery', 'type' => 'numeric_dropdown']",
        "['key' => 'feat_add_gallery', 'label' => 'Add Gallery', 'type' => 'boolean']",
        $content
    );
    
    // Fallback for payment.blade.php if it uses 'type' => 'numeric' instead of 'numeric_dropdown'
    $content = str_replace(
        "['key' => 'limit_gallery_images', 'label' => 'Add Gallery', 'type' => 'numeric']",
        "['key' => 'feat_add_gallery', 'label' => 'Add Gallery', 'type' => 'boolean']",
        $content
    );
    
    file_put_contents($file, $content);
    echo "Updated $file\n";
}
