<?php

namespace App\Application\Auth\Login;

use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use App\Application\Auth\Login\Input\LoginInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * 試行回数の制限を確認したうえで、Laravelの認証処理を実行する。
 */
final class LoginService
{
    /**
     * ログインを試行し、失敗理由をアプリケーション固有の例外で通知する。
     */
    public function execute(LoginInput $input): void
    {
        // 同じ利用者からの連続試行を数えるため、メールアドレスとIPで識別する。
        $throttleKey = $this->buildThrottleKey($input);

        // 上限到達後は認証を試さず、再試行可能になる時刻を呼び出し元へ伝える。
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $availableInSeconds = RateLimiter::availableIn($throttleKey);

            throw new LoginRateLimitedException($availableInSeconds);
        }

        $credentials = [
            'email' => $input->email,
            'password' => $input->password,
        ];

        // 認証失敗を記録し、Controllerではなく例外rendererに応答生成を任せる。
        if (! Auth::attempt($credentials, $input->remember)) {
            RateLimiter::hit($throttleKey);

            // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
            throw new AuthenticationFailedException;
        }

        // 成功後に過去の失敗回数を残さない。
        RateLimiter::clear($throttleKey);
    }

    /**
     * 大文字小文字や文字表現の違いを吸収した、試行回数管理用のキーを作る。
     */
    private function buildThrottleKey(LoginInput $input): string
    {
        $lowercaseEmail = Str::lower($input->email);
        $email = Str::transliterate($lowercaseEmail);

        return $email.'|'.$input->ipAddress;
    }
}
