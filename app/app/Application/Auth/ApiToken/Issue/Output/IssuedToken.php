<?php

namespace App\Application\Auth\ApiToken\Issue\Output;

use Carbon\CarbonImmutable;

/**
 * 発行時に一度だけ返せる平文トークンと有効期限を運ぶDTO。
 */
final readonly class IssuedToken
{
    public function __construct(
        public string $plainTextToken,
        public CarbonImmutable $expiresAt,
    ) {}
}
