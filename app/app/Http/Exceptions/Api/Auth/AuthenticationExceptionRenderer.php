<?php

namespace App\Http\Exceptions\Api\Auth;

use App\Application\Auth\ApiToken\Issue\Exception\ApiAuthenticationFailedException;
use App\Application\Auth\ApiToken\Issue\Exception\ApiLoginRateLimitedException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * APIログインの想定内エラーをJSONレスポンスへ変換する。
 */
final class AuthenticationExceptionRenderer
{
    /**
     * 認証失敗を、画面リダイレクトではなくCLI向けの401 JSONへ変換する。
     */
    public function renderAuthenticationFailed(
        ApiAuthenticationFailedException $exception,
        Request $request,
    ): JsonResponse {
        return response()->json([
            'message' => 'メールアドレスまたはパスワードが正しくありません。',
        ], 401);
    }

    /**
     * 試行上限到達を429で返し、再試行可能になる秒数も通知する。
     */
    public function renderLoginRateLimited(
        ApiLoginRateLimitedException $exception,
        Request $request,
    ): JsonResponse {
        $lockout = new Lockout($request);
        // Laravel標準イベントを発火し、将来ログ監視などから検知できるようにする。
        event($lockout);

        $seconds = $exception->availableInSeconds;
        $message = "ログイン試行回数が上限に達しました。{$seconds}秒後に再度お試しください。";

        return response()->json([
            'message' => $message,
            'retry_after' => $seconds,
        ], 429, [
            // CLIが待機時間をHTTPヘッダーからも判断できるようにする。
            'Retry-After' => (string) $seconds,
        ]);
    }
}
