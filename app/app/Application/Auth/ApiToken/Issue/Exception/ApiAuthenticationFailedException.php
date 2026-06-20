<?php

namespace App\Application\Auth\ApiToken\Issue\Exception;

use RuntimeException;

/**
 * APIトークン発行時に認証情報が一致しなかったことを通知する。
 */
final class ApiAuthenticationFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('API authentication failed.');
    }
}
