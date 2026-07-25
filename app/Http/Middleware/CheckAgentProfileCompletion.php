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
                $fields = ['name', 'phone', 'email', 'address', 'city', 'state', 'country', 'pincode', 'logo', 'about', 'business_card'];
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
                $allowedRoutes = ['agent.settings', 'agent.settings.update', 'agent.logout'];

                if ($percentage < 80 && !in_array($routeName, $allowedRoutes) && !$request->is('agent/settings*') && !$request->is('agent/logout*') && !$request->is('api*')) {
                    return redirect()->route('agent.settings')->with('warning', 'Please complete at least 80% of your profile details to unlock other features (Current completion: ' . $percentage . '%).');
                }
            }
        }

        return $next($request);
    }
}
