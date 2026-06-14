<?php

namespace App\Application\Auth\Login\Input;

final readonly class LoginInput
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
        public string $ipAddress,
    ) {}
}
