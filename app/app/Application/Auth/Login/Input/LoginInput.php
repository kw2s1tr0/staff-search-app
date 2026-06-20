<?php

namespace App\Application\Auth\Login\Input;

/**
 * ログイン処理に必要な入力値をひとまとめにして運ぶDTO。
 */
final readonly class LoginInput
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember,
        public string $ipAddress,
    ) {}
}
