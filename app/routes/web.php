<?php

use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth.web')->group(function (): void {
    Route::get('employees', [EmployeeController::class, 'index'])
        ->name('employees.index');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
