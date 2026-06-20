<?php

namespace App\Application\Auth\ApiToken\Issue\Exception;

use RuntimeException;

/**
 * APIログイン試行が上限に達したことを通知する。
 */
final class ApiLoginRateLimitedException extends RuntimeException
{
    public function __construct(
        public readonly int $availableInSeconds,
    ) {
        parent::__construct('API login attempts are rate limited.');
    }
}
