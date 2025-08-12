<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
    // Register middleware aliases
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);

    // Add middleware to 'web' group
    $middleware->appendToGroup('web', [
        \App\Http\Middleware\TenantScopeMiddleware::class,
    ]);

    // Add middleware to 'api' group
    $middleware->appendToGroup('api', [
        \App\Http\Middleware\TenantScopeMiddleware::class,
    ]);
})  
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
