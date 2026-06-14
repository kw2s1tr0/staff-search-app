<?php

namespace App\Application\Auth\Login\Exception;

use RuntimeException;

final class AuthenticationFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
