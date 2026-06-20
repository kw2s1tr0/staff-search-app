<?php

namespace App\Application\Auth\ApiToken\Issue;

use App\Application\Auth\ApiToken\Issue\Exception\ApiAuthenticationFailedException;
use App\Application\Auth\ApiToken\Issue\Exception\ApiLoginRateLimitedException;
use App\Application\Auth\ApiToken\Issue\Input\IssueTokenInput;
use App\Application\Auth\ApiToken\Issue\Output\IssuedToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * 認証情報を確認し、読み取り専用のSanctumトークンを発行する。
 */
final class IssueTokenService
{
    // 同じメールアドレスとIPから5回失敗したら、一時的にログインを止める。
    private const int MAX_ATTEMPTS = 5;

    // APIトークンは発行から30日後に利用できなくなる。
    private const int EXPIRATION_DAYS = 30;

    /**
     * 認証情報が正しければ、API読み取り用トークンを1本発行する。
     */
    public function execute(IssueTokenInput $input): IssuedToken
    {
        // メールアドレスだけでなくIPも含め、別の利用元を同じ制限に巻き込まないようにする。
        $throttleKey = $this->buildThrottleKey($input);

        // 上限到達中はDB検索やパスワード照合を行わず、再試行までの秒数を返す。
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $availableInSeconds = RateLimiter::availableIn($throttleKey);

            throw new ApiLoginRateLimitedException($availableInSeconds);
        }

        $user = User::query()
            ->where('email', $input->email)
            ->first();

        // Hash::checkを使い、DBに保存されたハッシュと受け取った平文パスワードを安全に比較する。
        // ユーザー不在とパスワード不一致を同じ例外にして、登録メールを推測されにくくする。
        if ($user === null || ! Hash::check($input->password, $user->password)) {
            // 失敗したときだけ回数を加算する。既定では60秒後にこの記録が失効する。
            RateLimiter::hit($throttleKey);

            // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
            throw new ApiAuthenticationFailedException;
        }

        // 正しい認証情報でログインできたら、過去の失敗回数をリセットする。
        RateLimiter::clear($throttleKey);

        $expiresAt = CarbonImmutable::now()->addDays(self::EXPIRATION_DAYS);
        // 今回のAPIは参照専用なので、読み取り権限だけをトークンへ付ける。
        $abilities = ['api:read'];
        // Sanctumは平文トークンをこの瞬間だけ返し、DBにはSHA-256ハッシュを保存する。
        $accessToken = $user->createToken($input->tokenName, $abilities, $expiresAt);

        return new IssuedToken(
            plainTextToken: $accessToken->plainTextToken,
            expiresAt: $expiresAt,
        );
    }

    private function buildThrottleKey(IssueTokenInput $input): string
    {
        // 大文字小文字や文字表現の違いで試行回数制限を回避されないよう正規化する。
        $lowercaseEmail = Str::lower($input->email);
        $email = Str::transliterate($lowercaseEmail);

        return 'api-login|'.$email.'|'.$input->ipAddress;
    }
}
