<?php

namespace App\Application\Auth\ApiToken\Issue\Input;

/**
 * APIトークン発行に必要な認証情報と識別名を運ぶDTO。
 */
final readonly class IssueTokenInput
{
    public function __construct(
        public string $email,
        public string $password,
        public string $tokenName,
        public string $ipAddress,
    ) {}
}
