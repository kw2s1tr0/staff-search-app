<?php

namespace App\Http\Exceptions\Web\Auth;

use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * LoginServiceの例外を、ログイン画面向けのリダイレクトレスポンスへ変換する。
 */
final class AuthenticationExceptionRenderer
{
    /**
     * 認証失敗の内部事情を隠し、利用者向けの共通メッセージを表示する。
     */
    public function renderAuthenticationFailed(
        AuthenticationFailedException $exception,
        Request $request,
    ): RedirectResponse {
        return $this->redirectToLogin(
            request: $request,
            message: 'メールアドレスまたはパスワードが正しくありません。',
        );
    }

    /**
     * ロックアウトイベントを通知し、再試行までの秒数を表示する。
     */
    public function renderLoginRateLimited(
        LoginRateLimitedException $exception,
        Request $request,
    ): RedirectResponse {
        // Laravel標準のイベントを発火し、監視やリスナーから検知可能にする。
        $lockout = new Lockout($request);
        event($lockout);

        $seconds = $exception->availableInSeconds;
        $message = "ログイン試行回数が上限に達しました。{$seconds}秒後に再度お試しください。";

        return $this->redirectToLogin(
            request: $request,
            message: $message,
        );
    }

    /**
     * パスワードを除く入力とエラーをセッションへ保存してログイン画面へ戻す。
     */
    private function redirectToLogin(Request $request, string $message): RedirectResponse
    {
        $oldInput = $request->only('email', 'remember');

        return redirect()
            ->route('login')
            ->withErrors(['email' => $message])
            ->withInput($oldInput);
    }
}
