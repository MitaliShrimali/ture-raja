<?php
$file = 'c:/Users/tusha/Downloads/Tour_raja/app/Services/PlanPermissionService.php';
$content = file_get_contents($file);
$replacement = <<<EOF
const PERMISSIONS = [
    'feat_business_profile' => ['type' => 'boolean', 'label' => 'Business Profile'],
    'feat_domestic_packages' => ['type' => 'boolean', 'label' => 'Domestic Packages'],
    'feat_international_packages' => ['type' => 'boolean', 'label' => 'International Packages'],
    'feat_package_expiry' => ['type' => 'boolean', 'label' => 'Package Expiry'],
    'limit_package_photos' => ['type' => 'numeric', 'label' => 'Package Photos Limit (0 for unlimited)'],
    'feat_hotel_options' => ['type' => 'boolean', 'label' => 'Hotel Options'],
    'limit_gallery_images' => ['type' => 'numeric', 'label' => 'Gallery Images Limit (0 for unlimited)'],
    'feat_theme_options' => ['type' => 'boolean', 'label' => 'Holiday / Theme Options'],
    'feat_hide_package_price' => ['type' => 'boolean', 'label' => 'Hide Package Price'],
    'feat_website_on_profile' => ['type' => 'boolean', 'label' => 'Website on Profile'],
    'feat_email_on_profile' => ['type' => 'boolean', 'label' => 'Email on Profile'],
    'feat_whatsapp_on_profile' => ['type' => 'boolean', 'label' => 'WhatsApp on Profile'],
    'feat_package_boosting' => ['type' => 'boolean', 'label' => 'Package Boosting'],
    'feat_featured_destination' => ['type' => 'boolean', 'label' => 'Featured Destination'],
    'feat_trusted_seller' => ['type' => 'boolean', 'label' => 'Trusted Seller Badge'],
    'feat_reviews_ratings' => ['type' => 'boolean', 'label' => 'Reviews & Ratings'],
    'feat_profile_analytics' => ['type' => 'boolean', 'label' => 'Profile Analytics'],
    'limit_branches' => ['type' => 'numeric', 'label' => 'Multiple Branches Limit (0 for unlimited)'],
    'limit_packages' => ['type' => 'numeric', 'label' => 'Package Limit (0 for unlimited)']
];
EOF;

$content = preg_replace('/const PERMISSIONS = \[.*?\];/s', $replacement, $content);
file_put_contents($file, $content);
echo "Done";
