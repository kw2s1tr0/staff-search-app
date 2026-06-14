<?php

namespace App\Http\Exceptions\Web\Auth;

use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AuthenticationExceptionRenderer
{
    public function renderAuthenticationFailed(
        AuthenticationFailedException $exception,
        Request $request,
    ): RedirectResponse {
        return $this->redirectToLogin(
            request: $request,
            message: 'メールアドレスまたはパスワードが正しくありません。',
        );
    }

    public function renderLoginRateLimited(
        LoginRateLimitedException $exception,
        Request $request,
    ): RedirectResponse {
        $lockout = new Lockout($request);
        event($lockout);

        $seconds = $exception->availableInSeconds;
        $message = "ログイン試行回数が上限に達しました。{$seconds}秒後に再度お試しください。";

        return $this->redirectToLogin(
            request: $request,
            message: $message,
        );
    }

    private function redirectToLogin(Request $request, string $message): RedirectResponse
    {
        $oldInput = $request->only('email', 'remember');

        return redirect()
            ->route('login')
            ->withErrors(['email' => $message])
            ->withInput($oldInput);
    }
}
