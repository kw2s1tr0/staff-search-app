<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->employeeService->search($request->query())
        );
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json(
            $this->employeeService->get($employee)
        );
    }
}
