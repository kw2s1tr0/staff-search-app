<?php

namespace App\Providers;

use App\Repositories\Department\DatabaseDepartmentRepository;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Employee\DatabaseEmployeeRepository;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Position\DatabasePositionRepository;
use App\Repositories\Position\PositionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // アプリケーション層がDB実装を直接参照せず、interfaceへ依存できるようにする。
        $this->app->bind(DepartmentRepository::class, DatabaseDepartmentRepository::class);
        $this->app->bind(EmployeeRepository::class, DatabaseEmployeeRepository::class);
        $this->app->bind(PositionRepository::class, DatabasePositionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
