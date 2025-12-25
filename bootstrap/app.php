<?php

use App\Exceptions\ApiException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'api_key' => \App\Http\Middleware\AuthenticateApiKey::class,
            'log_api' => \App\Http\Middleware\LogApiRequests::class,
        ]);

        $middleware->appendToGroup('api', [
            \App\Http\Middleware\LogApiRequests::class,
        ]);
    })
    ->withProviders([
        \App\Providers\RouteServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->render(function (ApiException $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            Log::warning('API domain exception', [
                'code' => $e->errorCode(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => [
                    'code' => $e->errorCode(),
                    'message' => __($e->messageKey(), $e->params()),
                ],
            ], $e->status());
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            Log::error('Unhandled API exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => __('messages.unexpected_error'),
                ],
            ], 500);
        });
    })->create();
