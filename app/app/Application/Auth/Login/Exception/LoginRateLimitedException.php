<?php

namespace App\Application\Auth\Login\Exception;

use RuntimeException;

/**
 * ログイン試行回数が上限に達したことと、再試行までの秒数を伝える例外。
 */
final class LoginRateLimitedException extends RuntimeException
{
    /**
     * 画面のエラーメッセージで使う待機秒数を保持する。
     */
    public function __construct(
        public readonly int $availableInSeconds,
    ) {
        parent::__construct('Login attempts are rate limited.');
    }
}
