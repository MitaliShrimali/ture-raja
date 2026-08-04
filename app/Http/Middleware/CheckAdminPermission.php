<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = Auth::user();

        // Must be logged in
        if (!$user) {
            return redirect('/admin/login');
        }

        // Super Admin gets access to everything
        if (strtoupper($user->role) === 'SUPER ADMIN') {
            return $next($request);
        }

        // Allow common routes for all authenticated admins
        $allowedRoutes = ['admin/dashboard', 'admin/profile', 'admin/profile/update', 'admin/profile/change-password', 'admin/logout'];
        if (in_array($request->path(), $allowedRoutes)) {
            return $next($request);
        }

        // If no explicit permission is passed, infer it from the route URI
        if (!$permission) {
             $path = $request->path(); // e.g. "admin/packages/create"
             $segments = explode('/', $path);
             
             if (isset($segments[1])) {
                 $module = $segments[1];
                 $action = 'view'; // default action

                 // Determine action based on URI segments and HTTP Method
                 $method = $request->method();
                 
                 // If the request has an ID, it could be edit or update or delete
                 if (in_array('create', $segments) || $method === 'POST') {
                     $action = 'create';
                 }
                 if (in_array('edit', $segments) || in_array('update', $segments) || $method === 'PUT' || $method === 'PATCH') {
                     $action = 'edit';
                 }
                 if (in_array('delete', $segments) || in_array('destroy', $segments) || $method === 'DELETE') {
                     $action = 'delete';
                 }

                 // Special overrides
                 if ($module === 'packages') {
                     if (in_array('pending', $segments) || in_array('approve', $segments)) $action = 'approve';
                     if (in_array('reject', $segments) || in_array('decline', $segments)) $action = 'reject';
                     if (in_array('domestic', $segments)) $action = 'view_domestic';
                     if (in_array('international', $segments)) $action = 'view_international';
                 }
                 if ($module === 'gallery' && $action === 'create') {
                     $action = 'upload';
                 }
                 if ($module === 'notifications' && $action === 'create') {
                     $action = 'send';
                 }
                 if ($module === 'payments' && in_array('export', $segments)) {
                     $action = 'export';
                 }
                 if ($module === 'payment-pricing') {
                     $module = 'payments';
                     $action = 'pricing';
                 }

                 // Module name mapping
                 $moduleMap = [
                     'users' => 'admin_users',
                     'registered-agents' => 'paid_users',
                     'agents' => 'paid_users',
                     'packages' => 'packages',
                     'gallery' => 'gallery',
                     'plans' => 'plans',
                     'payments' => 'payments',
                     'notifications' => 'notifications',
                     'careers' => 'careers',
                     // Ads mappings
                     'ads' => 'ads',
                     'banners' => 'ads',
                     'offer-stickers' => 'ads',
                     'reviews' => 'ads',
                     'leads' => 'ads',
                     'contact' => 'ads',
                     'subscribers' => 'ads',
                     // Settings mappings
                     'settings' => 'settings',
                     'holiday-types' => 'settings',
                     'activities' => 'settings',
                     'transits' => 'settings',
                     'durations' => 'settings',
                     'cms' => 'notifications', // The user requested CMS manage under notifications
                 ];

                 $mappedModule = $moduleMap[$module] ?? $module;

                 // Further refine action for grouped modules
                 if ($mappedModule === 'ads') {
                     if ($module === 'ads' || $module === 'campaigns') $action = 'campaigns';
                     if ($module === 'banners') $action = 'banners';
                     if ($module === 'offer-stickers') $action = 'offer_stickers';
                     if ($module === 'reviews') $action = 'client_reviews';
                     if ($module === 'leads') $action = 'lead_records';
                     if ($module === 'contact') $action = 'contact_inquiries';
                     if ($module === 'subscribers') $action = 'subscribers';
                 }
                 if ($mappedModule === 'settings') {
                     $action = 'general';
                     if ($module === 'holiday-types') $action = 'holiday_type';
                     if ($module === 'activities') $action = 'activity';
                     if ($module === 'transits') $action = 'transit';
                     if ($module === 'durations') $action = 'duration';
                     if ($module === 'amenities') $action = 'preference';
                     if (in_array($module, ['country', 'state', 'city'])) $action = 'geography';
                 }

                 $permission = $mappedModule . '.' . $action;
             }
        }

        // Check using our new model method
        if ($permission && !$user->hasAdminPermission($permission)) {
            // If it's an AJAX request, return JSON forbidden
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'You do not have permission to perform this action.'], 403);
            }

            // Otherwise, redirect back with error message
            return redirect('/admin/dashboard')->with('error', 'Access Denied: You do not have permission to access that section.');
        }

        return $next($request);
    }
}
