<?php

use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('employees', EmployeeController::class)
    ->only(['index', 'show']);
