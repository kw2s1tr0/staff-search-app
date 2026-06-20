<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ログインフォームから送信された認証情報を検証する。
 */
class LoginRequest extends FormRequest
{
    /**
     * ログイン画面は未認証ユーザー向けなので、全リクエストを許可する。
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * メールアドレスとパスワードを必須とし、ログイン保持は真偽値だけ許可する。
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
