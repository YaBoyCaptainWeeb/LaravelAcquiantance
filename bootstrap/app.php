<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            ], ($e instanceof ValidationException) ? 422 : 500);
        });

        $exceptions->report(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return;
            }

            $controller = null;
            $route = request()->route();
            if ($route) {
                $controller = $route->getController();
            }

            $context = [
                'endpoint' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'controller' => get_class($controller)
            ];


            Log::error(request()->getPathInfo() .  " : Произошла ошибка", $context);
        });
    })->create();
