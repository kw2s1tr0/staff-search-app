<?php

use App\Http\Controllers\Api\Department\DepartmentController;
use App\Http\Controllers\Api\Employee\EmployeeController;
use App\Http\Controllers\Api\Position\PositionController;
use Illuminate\Support\Facades\Route;

// 部署は参照専用なので、RESTful Routeのうち一覧取得だけを公開する。
Route::apiResource('departments', DepartmentController::class)
    ->only(['index'])
    ->names('api.departments');

// 社員検索APIをGET /api/employeesとして公開する。
Route::apiResource('employees', EmployeeController::class)
    ->only(['index'])
    ->names('api.employees');

// 役職は参照専用なので、RESTful Routeのうち一覧取得だけを公開する。
Route::apiResource('positions', PositionController::class)
    ->only(['index'])
    ->names('api.positions');
