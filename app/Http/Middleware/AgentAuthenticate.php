<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentAuthenticate
{
    /**
     * Handle an incoming request.
     * Agents authenticate via session('agent_id'), NOT via Laravel's Auth system.
     * Using the built-in 'auth' middleware on agent routes causes an infinite
     * redirect loop because Auth::check() returns false even after agent login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('agent_id')) {
            return redirect()->route('agent.login')
                ->with('error', 'Please login to access your agent dashboard.');
        }

        return $next($request);
    }
}
