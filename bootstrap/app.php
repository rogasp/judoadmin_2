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

        // Hantera QueryException och andra relaterade undantag
        $exceptions->renderable(function (Illuminate\Database\QueryException $e, $request) {
            if (tenant() && !tenant('ready')) {
                return response()->view('errors.building', [], 503);
            }
        });

        // Fånga alla andra undantag och logga dem
        $exceptions->reportable(function (Throwable $e) {
            Log::error('Unhandled Exception: ', ['exception' => $e]);
        });

    })->create();
