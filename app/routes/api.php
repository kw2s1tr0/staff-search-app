<?php

use App\Http\Controllers\Api\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Api\Department\DepartmentController;
use App\Http\Controllers\Api\Employee\EmployeeController;
use App\Http\Controllers\Api\Position\PositionController;
use Illuminate\Support\Facades\Route;

// 認証情報を検証し、CLIがBearer認証に使うAPIトークンを発行する。
Route::post('auth/login', [AuthenticatedTokenController::class, 'store'])
    ->name('api.auth.login');

Route::middleware('auth:sanctum')->prefix('auth')->group(function (): void {
    // 提示されたトークンに対応する利用者を返す。
    Route::get('me', [AuthenticatedTokenController::class, 'show'])
        ->middleware('abilities:api:read')
        ->name('api.auth.me');

    // 提示中のトークンだけを失効し、別のCLI用トークンは維持する。
    Route::post('logout', [AuthenticatedTokenController::class, 'destroy'])
        ->name('api.auth.logout');
});

// 社員情報を参照するAPIは、読み取り権限を持つトークンだけに公開する。
Route::middleware(['auth:sanctum', 'abilities:api:read'])->group(function (): void {
    Route::apiResource('departments', DepartmentController::class)
        ->only(['index'])
        ->names('api.departments');

    Route::apiResource('employees', EmployeeController::class)
        ->only(['index'])
        ->names('api.employees');

    Route::apiResource('positions', PositionController::class)
        ->only(['index'])
        ->names('api.positions');
});
