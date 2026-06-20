<?php

namespace App\Application\Auth\ApiToken\Issue\Input\Builder;

use App\Application\Auth\ApiToken\Issue\Input\IssueTokenInput;

/**
 * 検証済みのAPIログイン入力を、トークン発行用DTOへ変換する。
 */
final class IssueTokenInputBuilder
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated, ?string $ipAddress): IssueTokenInput
    {
        // FormRequest通過後の配列から、Serviceが必要とする型へ明示的に変換する。
        $email = (string) $validated['email'];
        $password = (string) $validated['password'];
        $tokenName = (string) $validated['token_name'];
        // テストなどでIPを取得できない場合も、Serviceには必ず文字列を渡す。
        $resolvedIpAddress = $ipAddress ?? '';

        // HTTPのRequestを渡さず、認証処理に必要な値だけをDTOへ詰める。
        return new IssueTokenInput(
            email: $email,
            password: $password,
            tokenName: $tokenName,
            ipAddress: $resolvedIpAddress,
        );
    }
}
