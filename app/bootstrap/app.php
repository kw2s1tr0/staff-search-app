<?php

use App\Application\Auth\ApiToken\Issue\Exception\ApiAuthenticationFailedException;
use App\Application\Auth\ApiToken\Issue\Exception\ApiLoginRateLimitedException;
use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use App\Http\Exceptions\Api\Auth\AuthenticationExceptionRenderer as ApiAuthenticationExceptionRenderer;
use App\Http\Exceptions\Web\Auth\AuthenticationExceptionRenderer;
use App\Http\Middleware\AuthenticateWebRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

// Laravelアプリケーションの起点をプロジェクトルートに設定する。
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // ブラウザ用、API用、CLI用のRoute定義をそれぞれ読み込む。
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // 稼働監視サービスがアプリケーションの生存確認に使うURLを公開する。
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 未認証ユーザーはログイン画面へ、認証済みユーザーは社員一覧へ誘導する。
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/employees');

        // Route側で短い名前を指定できるよう、独自の認証Middlewareを登録する。
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'auth.web' => AuthenticateWebRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 認証処理の例外を、ログイン画面向けのHTTPレスポンスへ変換する。
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $renderer = new AuthenticationExceptionRenderer;

        $exceptions->render($renderer->renderAuthenticationFailed(...));
        $exceptions->render($renderer->renderLoginRateLimited(...));

        // APIの認証失敗はリダイレクトせず、CLIが扱えるJSONへ変換する。
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $apiRenderer = new ApiAuthenticationExceptionRenderer;

        $exceptions->render($apiRenderer->renderAuthenticationFailed(...));
        $exceptions->render($apiRenderer->renderLoginRateLimited(...));

        // 利用者の入力に起因する想定内の認証失敗は、障害ログへ記録しない。
        $exceptions->dontReport([
            AuthenticationFailedException::class,
            ApiAuthenticationFailedException::class,
            ApiLoginRateLimitedException::class,
            LoginRateLimitedException::class,
        ]);
    })
    // 上記のRoute、Middleware、例外設定を反映したApplicationを生成する。
    ->create();
