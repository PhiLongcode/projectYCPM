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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'redirectIfNotAuthenticated' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
            // Bạn có thể thêm các middleware khác nếu cần
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
