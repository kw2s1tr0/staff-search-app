<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

/**
 * 未認証のWebリクエストをログイン画面へ誘導する認証Middleware。
 */
class AuthenticateWebRequest extends Authenticate
{
    /**
     * 親Middlewareが認証失敗時に使用するリダイレクト先を返す。
     */
    protected function redirectTo(Request $request): string
    {
        return route('login');
    }
}
