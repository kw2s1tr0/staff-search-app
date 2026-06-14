<?php

namespace App\Application\Auth\Login\Exception;

use RuntimeException;

final class LoginRateLimitedException extends RuntimeException
{
    public function __construct(
        public readonly int $availableInSeconds,
    ) {
        parent::__construct('Login attempts are rate limited.');
    }
}
