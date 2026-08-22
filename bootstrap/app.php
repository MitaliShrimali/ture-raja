<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        
        $middleware->validateCsrfTokens(except: [
            'agent/*', // Bypass CSRF temporarily
        ]);

        // Register custom agent session-based auth middleware
        $middleware->alias([
            'agent.auth' => \App\Http\Middleware\AgentAuthenticate::class,
            'agent.profile_complete' => \App\Http\Middleware\CheckAgentProfileCompletion::class,
            'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
        ]);

        // For any remaining routes still using the built-in 'auth' middleware,
        // make sure guests are sent to the right panel's login page
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            // Never redirect login/signup/submit pages — that causes an infinite loop
            if ($request->is('login') || $request->is('signup') || $request->is('login/submit') || $request->is('signup/submit')) {
                return null;
            }
            if ($request->is('admin/login') || $request->is('admin/signup') || $request->is('admin/login/submit')) {
                return null;
            }
            if ($request->is('agent/login') || $request->is('agent/signup') || $request->is('agent/login/submit') || $request->is('agent/signup/submit')) {
                return null;
            }

            // Redirect based on panel
            if ($request->is('admin') || $request->is('admin/*')) {
                return url('/admin/login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

$app->usePublicPath(
    defined('CUSTOM_PUBLIC_PATH') 
        ? CUSTOM_PUBLIC_PATH 
        : __DIR__.'/../public'
);

return $app;
