<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'set-current-hotel' => \App\Http\Middleware\SetCurrentHotel::class,
            'check-permission' => \App\Http\Middleware\CheckPermission::class,
            'audit-log' => \App\Http\Middleware\AuditLog::class,
            'check-admin-access' => \App\Http\Middleware\CheckAdminAccess::class,
        ]);

        $middleware->redirectTo(
            function (Request $request) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    return route('admin.login');
                }
                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
