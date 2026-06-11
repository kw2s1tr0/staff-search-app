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

    public function test_employee_index_rejects_invalid_search_conditions(): void
    {
        $response = $this->getJson('/employees?department_id=invalid&employment_status=unknown');

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['department_id', 'employment_status']);
    }

    public function test_employee_index_applies_all_search_conditions(): void
    {
        $development = Department::factory()->create();
        $sales = Department::factory()->create();
        $engineer = Position::factory()->create();

        $matchedEmployee = Employee::factory()
            ->for($development)
            ->for($engineer)
            ->create([
                'employee_number' => 'EMP-SEARCH-01',
                'family_name' => 'Yamada',
                'given_name' => 'Taro',
                'email' => 'taro@example.com',
                'employment_status' => EmploymentStatus::Active,
            ]);

        Employee::factory()
            ->for($development)
            ->for($engineer)
            ->create([
                'family_name' => 'Yamada',
                'given_name' => 'Hanako',
                'employment_status' => EmploymentStatus::Active,
            ]);

        Employee::factory()
            ->for($sales)
            ->for($engineer)
            ->create([
                'family_name' => 'Yamada',
                'given_name' => 'Taro',
                'employment_status' => EmploymentStatus::Active,
            ]);

        $response = $this->getJson('/employees?'.http_build_query([
            'keyword' => 'Yamada Taro',
            'department_id' => $development->id,
            'position_id' => $engineer->id,
            'employment_status' => EmploymentStatus::Active->value,
        ]));

        $response
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $matchedEmployee->id)
            ->assertJsonPath('0.employee_number', 'EMP-SEARCH-01')
            ->assertJsonPath('0.employment_status', EmploymentStatus::Active->value);
    }

    public function test_employee_index_searches_concatenated_names(): void
    {
        $employee = Employee::factory()->create([
            'family_name' => 'Yamada',
            'given_name' => 'Taro',
            'family_name_kana' => 'ヤマダ',
            'given_name_kana' => 'タロウ',
        ]);

        $this->getJson('/employees?keyword=YamadaTaro')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);

        $this->getJson('/employees?keyword=ヤマダタロウ')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);
    }

    public function test_employee_index_searches_department_and_position_names(): void
    {
        $department = Department::factory()->create(['name' => 'Product Development']);
        $position = Position::factory()->create(['name' => 'Lead Engineer']);
        $employee = Employee::factory()
            ->for($department)
            ->for($position)
            ->create();

        $this->getJson('/employees?keyword=Development')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);

        $this->getJson('/employees?keyword=Engineer')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);
    }

    public function test_employee_number_and_email_require_an_exact_match(): void
    {
        $employee = Employee::factory()->create([
            'employee_number' => 'EMP-EXACT-001',
            'family_name' => 'UniqueFamily',
            'given_name' => 'UniqueGiven',
            'email' => 'exact-match@example.com',
        ]);

        $this->getJson('/employees?keyword=EMP-EXACT')
            ->assertOk()
            ->assertJsonCount(0);

        $this->getJson('/employees?keyword=example.com')
            ->assertOk()
            ->assertJsonCount(0);

        $this->getJson('/employees?keyword=EMP-EXACT-001')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);

        $this->getJson('/employees?keyword=exact-match@example.com')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id);
    }

    public function test_employee_index_returns_an_employee_without_a_position(): void
    {
        $department = Department::factory()->create();
        $employee = Employee::factory()
            ->for($department)
            ->create(['position_id' => null]);

        $response = $this->getJson('/employees?department_id='.$department->id);

        $response
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $employee->id)
            ->assertJsonPath('0.position_id', null)
            ->assertJsonPath('0.position', null);
    }
}
