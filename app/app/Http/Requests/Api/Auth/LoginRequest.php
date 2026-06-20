<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * APIトークン発行に使う認証情報とトークン名を検証する。
 */
class LoginRequest extends FormRequest
{
    /**
     * 認証処理へ渡す前に、必須項目と入力形式をHTTP層で確認する。
     * token_nameは「どのCLIで使うトークンか」を後から識別するための名前。
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'token_name' => ['required', 'string', 'max:255'],
        ];
    }
}
