<?php

namespace App\Http\Controllers\Web\Auth;

use App\Application\Auth\Login\Input\Builder\LoginInputBuilder;
use App\Application\Auth\Login\LoginService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン画面の表示、ログイン処理、ログアウト処理を受け持つ。
 */
class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly LoginInputBuilder $loginInputBuilder,
        private readonly LoginService $loginService,
    ) {}

    /**
     * 未認証ユーザーへログインフォームを表示する。
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 検証済みの認証情報でログインし、本来の遷移先へリダイレクトする。
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Request自体をServiceへ渡さず、認証に必要な値だけを入力DTOへ変換する。
        $validated = $request->validated();
        $ipAddress = $request->ip();
        $input = $this->loginInputBuilder->build($validated, $ipAddress);

        $this->loginService->execute($input);
        // ログイン前のセッションIDを破棄し、セッション固定攻撃を防ぐ。
        $request->session()->regenerate();

        $employeeIndexUrl = route('employees.index', absolute: false);

        return redirect()->intended($employeeIndexUrl);
    }

    /**
     * 認証状態とセッションを破棄し、安全な新しいCSRFトークンを発行する。
     */
    public function destroy(Request $request): RedirectResponse
    {
        // webガードで保持しているログイン状態を解除する。
        Auth::guard('web')->logout();

        // 既存セッションを再利用できないよう無効化し、CSRFトークンも更新する。
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
