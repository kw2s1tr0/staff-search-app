<?php

namespace Tests\Feature;

use App\Enums\EmploymentStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeSearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_index_returns_search_results(): void
    {
        $development = Department::factory()->create(['code' => 'DEV', 'name' => 'Development']);
        $sales = Department::factory()->create(['code' => 'SALES', 'name' => 'Sales']);
        $engineer = Position::factory()->create(['code' => 'ENGINEER', 'name' => 'Engineer']);

        $matchedEmployee = Employee::factory()
            ->for($development)
            ->for($engineer)
            ->create([
                'employee_number' => 'EMP-00001',
                'family_name' => 'Yamada',
                'given_name' => 'Taro',
                'email' => 'yamada@example.com',
                'employment_status' => EmploymentStatus::Active,
            ]);

        Employee::factory()
            ->for($sales)
            ->create([
                'employee_number' => 'EMP-00002',
                'family_name' => 'Sato',
                'given_name' => 'Hanako',
                'email' => 'sato@example.com',
                'employment_status' => EmploymentStatus::Leave,
            ]);

        $response = $this->getJson('/employees?keyword=yamada&department_id='.$development->id);

        $response
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $matchedEmployee->id)
            ->assertJsonPath('0.department.id', $development->id)
            ->assertJsonPath('0.position.id', $engineer->id);
    }

    public function test_employee_show_returns_an_employee_detail(): void
    {
        $department = Department::factory()->create();
        $position = Position::factory()->create();
        $employee = Employee::factory()
            ->for($department)
            ->for($position)
            ->create();

        $response = $this->getJson('/employees/'.$employee->id);

        $response
            ->assertOk()
            ->assertJsonPath('id', $employee->id)
            ->assertJsonPath('department.id', $department->id)
            ->assertJsonPath('position.id', $position->id);
    }
}
