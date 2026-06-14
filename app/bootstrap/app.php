<?php

use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use App\Http\Exceptions\Web\Auth\AuthenticationExceptionRenderer;
use App\Http\Middleware\AuthenticateWebRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/employees');
        $middleware->alias([
            'auth.web' => AuthenticateWebRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $renderer = new AuthenticationExceptionRenderer;

        $exceptions->render($renderer->renderAuthenticationFailed(...));
        $exceptions->render($renderer->renderLoginRateLimited(...));
        $exceptions->dontReport([
            AuthenticationFailedException::class,
            LoginRateLimitedException::class,
        ]);
    })->create();
