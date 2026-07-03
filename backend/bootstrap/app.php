<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    });

// Bind view factory before creation to prevent ReflectionException
$app->bind('view', function () {
    return new \Illuminate\View\Factory(
        new \Illuminate\View\Engines\EngineResolver(),
        new \Illuminate\View\FileViewFinder(
            new \Illuminate\Filesystem\Filesystem(),
            [resource_path('views')]
        ),
        new \Illuminate\Events\Dispatcher()
    );
});

$container = $app->create();

return $container;
