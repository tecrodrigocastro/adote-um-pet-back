<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

// Laravel's bundled config/database.php still references the PDO::MYSQL_ATTR_SSL_CA
// constant deprecated in PHP 8.5; silence until upstream ships a fix.
error_reporting(E_ALL & ~E_DEPRECATED);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            if ($e instanceof ValidationException || $e instanceof AuthenticationException) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            return response()->json([
                'success' => false,
                'message' => $status === 500 && ! config('app.debug')
                    ? 'Erro interno do servidor.'
                    : $e->getMessage(),
                'data' => null,
            ], $status);
        });
    })->create();
