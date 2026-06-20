<?php

namespace App\Application\Auth\Login\Exception;

use RuntimeException;

/**
 * 認証情報が一致しなかったことをHTTP層へ伝える例外。
 */
final class AuthenticationFailedException extends RuntimeException
{
    /**
     * 内部ログ用の固定メッセージを設定する。
     */
    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
