<?php

use App\Http\Controllers\Api\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('employees', EmployeeController::class)
    ->only(['index']);
