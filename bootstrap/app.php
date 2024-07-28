<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web/routes.php',
        commands: __DIR__.'/../routes/console/routs.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => AuthMiddleware::class,
        ]);
        $middleware->group('universal', []);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException $e, $request) {
            return response()->view('errors.building', [], 503);
        });
        // Optionellt: Lägg till en renderable för att visa ett standardfelmeddelande för alla andra undantag
        $exceptions->renderable(function (Throwable $e, $request) {
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        });

    })->create();
