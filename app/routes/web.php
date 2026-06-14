<?php

use App\Http\Controllers\Web\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('employees', [EmployeeController::class, 'index'])
    ->name('employees.index');
