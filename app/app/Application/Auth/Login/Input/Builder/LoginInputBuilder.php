<?php

namespace App\Application\Auth\Login\Input\Builder;

use App\Application\Auth\Login\Input\LoginInput;

/**
 * 検証済みのログインリクエストを、LoginService専用の入力へ変換する。
 */
final class LoginInputBuilder
{
    /**
     * 必須値を明示的に型変換し、省略可能な値には既定値を設定する。
     *
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated, ?string $ipAddress): LoginInput
    {
        // FormRequestで検証済みでも、後続処理が扱う型をここで確定させる。
        $email = (string) $validated['email'];
        $password = (string) $validated['password'];
        $remember = isset($validated['remember'])
            ? (bool) $validated['remember']
            : false;
        $resolvedIpAddress = $ipAddress ?? '';

        // ServiceがHTTPリクエストへ直接依存しないよう、必要な値だけを詰め替える。
        return new LoginInput(
            email: $email,
            password: $password,
            remember: $remember,
            ipAddress: $resolvedIpAddress,
        );
    }
}
