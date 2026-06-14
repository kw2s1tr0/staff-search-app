<?php

use App\Http\Controllers\Api\Department\DepartmentController;
use App\Http\Controllers\Api\Employee\EmployeeController;
use App\Http\Controllers\Api\Position\PositionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('departments', DepartmentController::class)
    ->only(['index'])
    ->names('api.departments');

Route::apiResource('employees', EmployeeController::class)
    ->only(['index'])
    ->names('api.employees');

Route::apiResource('positions', PositionController::class)
    ->only(['index'])
    ->names('api.positions');
