<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

class EmployeeService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Employee>
     */
    public function search(array $filters): Collection
    {
        $keyword = $this->stringFilter($filters, 'keyword');
        $departmentId = $this->stringFilter($filters, 'department_id');
        $positionId = $this->stringFilter($filters, 'position_id');
        $employmentStatus = $this->stringFilter($filters, 'employment_status');

        return Employee::query()
            ->with(['department', 'position'])
            ->when($keyword !== null, function ($query) use ($keyword): void {
                $likeKeyword = '%'.$keyword.'%';

                $query->where(function ($query) use ($likeKeyword): void {
                    $query
                        ->where('employee_number', 'like', $likeKeyword)
                        ->orWhere('family_name', 'like', $likeKeyword)
                        ->orWhere('given_name', 'like', $likeKeyword)
                        ->orWhere('family_name_kana', 'like', $likeKeyword)
                        ->orWhere('given_name_kana', 'like', $likeKeyword)
                        ->orWhere('email', 'like', $likeKeyword);
                });
            })
            ->when($departmentId !== null, fn ($query): mixed => $query->where('department_id', $departmentId))
            ->when($positionId !== null, fn ($query): mixed => $query->where('position_id', $positionId))
            ->when($employmentStatus !== null, fn ($query): mixed => $query->where('employment_status', $employmentStatus))
            ->orderBy('employee_number')
            ->get();
    }

    public function get(Employee $employee): Employee
    {
        return $employee->load(['department', 'position']);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function stringFilter(array $filters, string $key): ?string
    {
        if (! isset($filters[$key]) || ! is_scalar($filters[$key])) {
            return null;
        }

        $value = trim((string) $filters[$key]);

        return $value === '' ? null : $value;
    }
}
