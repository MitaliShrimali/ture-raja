<?php

namespace App\Services;

use App\Models\PlanPermission;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class PlanPermissionService
{
    /**
     * Map of all available plan permissions
     */
    const PERMISSIONS = [
    'feat_business_profile' => ['type' => 'boolean', 'label' => 'Business Profile'],
    'feat_domestic_packages' => ['type' => 'boolean', 'label' => 'Domestic Packages'],
    'feat_international_packages' => ['type' => 'boolean', 'label' => 'International Packages'],
    'feat_package_expiry' => ['type' => 'boolean', 'label' => 'Package Expiry'],
    'limit_package_photos' => ['type' => 'numeric', 'label' => 'Package Photos Limit (0 for unlimited)'],
    'limit_hotel_options' => ['type' => 'numeric', 'label' => 'Hotel Options Limit (0 for unlimited)'],
    'feat_add_gallery' => ['type' => 'boolean', 'label' => 'Add Gallery'],
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

    /**
     * Get permission value for a specific user and key
     */
    public function getPermission($user, string $key)
    {
        if (!array_key_exists($key, self::PERMISSIONS)) {
            return false;
        }

        $planId = $user->plan_id; // Agents have plan_id
        if (!$planId) {
            return self::PERMISSIONS[$key]['type'] === 'numeric' ? 0 : false;
        }

        $permission = PlanPermission::where('plan_id', $planId)
            ->where('permission_key', $key)
            ->first();

        if (!$permission) {
            return self::PERMISSIONS[$key]['type'] === 'numeric' ? 0 : false;
        }

        return self::PERMISSIONS[$key]['type'] === 'numeric' ? $permission->limit_value : $permission->boolean_value;
    }

    /**
     * Check if a user has a specific boolean permission
     */
    public function hasPermission($user, string $key): bool
    {
        $value = $this->getPermission($user, $key);
        return $value === true;
    }

    /**
     * Check if a user has reached their limit for a specific numeric permission
     */
    public function hasReachedLimit($user, string $key, int $currentCount): bool
    {
        $limit = $this->getPermission($user, $key);
        
        // Null or 0 might mean unlimited depending on implementation, 
        // let's say null is unlimited, but 0 is literally 0.
        // The prompt says "0 for unlimited" in labels, let's treat 0 as unlimited.
        if ($limit === 0 || $limit === null) {
            return false;
        }

        return $currentCount >= $limit;
    }
}
