<?php

use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use OwenIt\Auditing\AuditingServiceProvider;
use Spatie\Permission\Contracts\Permission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission'=> [
                AuditingServiceProvider::class,
                PermissionMiddleware::class
            ]

        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
