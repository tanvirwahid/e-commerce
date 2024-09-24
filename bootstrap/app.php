<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\RefreshToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

function getUnauthenticatedResponse()
{
    return response()->json([
        'message' => 'Not Logged in'
    ], 401);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
          $middleware->alias([
            'jwt.auth' => Authenticate::class,
            'jwt.refresh' => RefreshToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            return getUnauthenticatedResponse();
        });
    })->create();
