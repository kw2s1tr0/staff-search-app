<?php

use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

// アプリケーションのルートURLでは、Laravelの初期案内画面を表示する。
Route::get('/', function () {
    return view('welcome');
});

// ログイン済みユーザーには不要なRouteを、guest Middlewareで保護する。
Route::middleware('guest')->group(function (): void {
    // ログインフォームの表示と、入力された認証情報の送信先を分ける。
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

// 社員画面とログアウトは、独自のWeb認証を通過したユーザーだけに許可する。
Route::middleware('auth.web')->group(function (): void {
    // 通常アクセスでは検索フォーム、選択肢、検索結果を含む画面全体を返す。
    Route::get('employees', [EmployeeController::class, 'index'])
        ->name('employees.index');

    // htmxからの検索では、画面全体ではなく差し替え用の検索結果だけを返す。
    Route::get('employees/results', [EmployeeController::class, 'results'])
        ->name('employees.results');

    // 状態を変更するログアウト処理はGETではなくPOSTで受け付ける。
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
