<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAgentProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agentId = session('agent_id');
        if ($agentId) {
            $agent = \DB::table('agents')->where('id', $agentId)->first();
            if ($agent) {
                // Calculate completion percentage
                $fields = ['name', 'phone', 'email', 'address', 'city', 'state', 'country', 'pincode', 'logo', 'about'];
                $filled = 0;
                foreach ($fields as $field) {
                    if (!empty($agent->$field)) {
                        $filled++;
                    }
                }
                $percentage = round(($filled / count($fields)) * 100);

                // Share percentage with all views
                view()->share('profileCompletionPercentage', $percentage);
                view()->share('agentProfileInfo', $agent);

                // Check route name
                $routeName = $request->route() ? $request->route()->getName() : null;
                
                // Allow API and logout routes globally
                if ($request->is('agent/logout*') || $request->is('api*')) {
                    return $next($request);
                }

                if ($percentage < 80) {
                    $allowedSettingsRoutes = ['agent.settings', 'agent.settings.update'];
                    if (!in_array($routeName, $allowedSettingsRoutes) && !$request->is('agent/settings*')) {
                        return redirect()->route('agent.settings')->with('warning', 'Please complete at least 80% of your profile details to unlock other features (Current completion: ' . $percentage . '%).');
                    }
                } else if (empty($agent->plan_id)) {
                    $allowedBillingRoutes = ['agent.payment', 'agent.checkout', 'agent.checkout.process'];
                    if (!in_array($routeName, $allowedBillingRoutes) && !$request->is('agent/payment*') && !$request->is('agent/checkout*')) {
                        return redirect()->route('agent.payment')->with('show_upgrade_modal', true)->with('warning', 'Please select a plan to access the full dashboard.');
                    }
                }
            }
        }

        return $next($request);
    }
}
