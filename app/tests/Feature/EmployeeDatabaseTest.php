<?php

namespace Tests\Feature;

use App\Enums\EmploymentStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_schema_is_created_without_a_user_relationship(): void
    {
        $this->assertTrue(Schema::hasColumns('departments', [
            'id',
            'code',
            'name',
            'created_at',
            'updated_at',
        ]));
        $this->assertTrue(Schema::hasColumns('positions', [
            'id',
            'code',
            'name',
            'created_at',
            'updated_at',
        ]));
        $this->assertTrue(Schema::hasColumns('employees', [
            'id',
            'employee_number',
            'department_id',
            'position_id',
            'family_name',
            'given_name',
            'family_name_kana',
            'given_name_kana',
            'email',
            'employment_status',
            'created_at',
            'updated_at',
        ]));
        $this->assertFalse(Schema::hasColumn('employees', 'user_id'));
    }

    public function test_employee_belongs_to_a_position(): void
    {
        $position = Position::factory()->create();
        $employee = Employee::factory()->for($position)->create();

        $this->assertTrue($employee->position->is($position));
        $this->assertTrue($position->employees->contains($employee));
    }

    public function test_employee_requires_a_position(): void
    {
        $this->expectException(QueryException::class);

        Employee::factory()->create(['position_id' => null]);
    }

    public function test_employee_belongs_to_a_department(): void
    {
        $department = Department::factory()->create();
        $employee = Employee::factory()->for($department)->create();

        $this->assertTrue($employee->department->is($department));
        $this->assertTrue($department->employees->contains($employee));
    }

    public function test_employee_number_must_be_unique(): void
    {
        $employee = Employee::factory()->create();

        $this->expectException(QueryException::class);

        Employee::factory()->create([
            'employee_number' => $employee->employee_number,
            'email' => fake()->unique()->safeEmail(),
        ]);
    }

    public function test_employee_email_must_be_unique(): void
    {
        $employee = Employee::factory()->create();

        $this->expectException(QueryException::class);

        Employee::factory()->create([
            'employee_number' => fake()->unique()->bothify('EMP-#####'),
            'email' => $employee->email,
        ]);
    }

    public function test_department_code_must_be_unique(): void
    {
        $department = Department::factory()->create();

        $this->expectException(QueryException::class);

        Department::factory()->create([
            'code' => $department->code,
            'name' => fake()->unique()->words(2, true),
        ]);
    }

    public function test_department_name_must_be_unique(): void
    {
        $department = Department::factory()->create();

        $this->expectException(QueryException::class);

        Department::factory()->create([
            'code' => fake()->unique()->bothify('DEPT-###'),
            'name' => $department->name,
        ]);
    }

    public function test_position_code_must_be_unique(): void
    {
        $position = Position::factory()->create();

        $this->expectException(QueryException::class);

        Position::factory()->create([
            'code' => $position->code,
            'name' => fake()->unique()->jobTitle(),
        ]);
    }

    public function test_position_name_must_be_unique(): void
    {
        $position = Position::factory()->create();

        $this->expectException(QueryException::class);

        Position::factory()->create([
            'code' => fake()->unique()->bothify('POS-###'),
            'name' => $position->name,
        ]);
    }

    public function test_employee_requires_an_existing_department(): void
    {
        $this->expectException(QueryException::class);

        Employee::factory()->create(['department_id' => 999999]);
    }

    public function test_employee_rejects_an_unknown_position(): void
    {
        $this->expectException(QueryException::class);

        Employee::factory()->create(['position_id' => 999999]);
    }

    public function test_department_with_employees_cannot_be_deleted(): void
    {
        $department = Department::factory()->create();
        Employee::factory()->for($department)->create();

        $this->expectException(QueryException::class);

        $department->delete();
    }

    public function test_position_with_employees_cannot_be_deleted(): void
    {
        $position = Position::factory()->create();
        Employee::factory()->for($position)->create();

        $this->expectException(QueryException::class);

        $position->delete();
    }

    public function test_employment_status_is_cast_to_an_enum(): void
    {
        $active = Employee::factory()->create();
        $leave = Employee::factory()->leave()->create();
        $retired = Employee::factory()->retired()->create();

        $this->assertSame(EmploymentStatus::Active, $active->employment_status);
        $this->assertSame(EmploymentStatus::Leave, $leave->employment_status);
        $this->assertSame(EmploymentStatus::Retired, $retired->employment_status);
    }

    public function test_database_rejects_an_unknown_employment_status(): void
    {
        $department = Department::factory()->create();
        $position = Position::factory()->create();

        $this->expectException(QueryException::class);

        DB::table('employees')->insert([
            'employee_number' => 'EMP-INVALID',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'family_name' => 'Test',
            'given_name' => 'Employee',
            'family_name_kana' => 'テスト',
            'given_name_kana' => 'シャイン',
            'email' => 'invalid-status@example.com',
            'employment_status' => 'invalid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_database_seeder_creates_searchable_employee_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('departments', 3);
        $this->assertDatabaseCount('positions', 3);
        $this->assertDatabaseCount('employees', 15);
        $this->assertDatabaseHas('departments', ['code' => 'DEV', 'name' => '開発部']);
        $this->assertDatabaseHas('positions', ['code' => 'MEMBER', 'name' => '一般社員']);
        $this->assertDatabaseHas('employees', [
            'employee_number' => 'EMP-00001',
            'family_name' => '山田',
            'given_name' => '太郎',
            'family_name_kana' => 'ヤマダ',
            'given_name_kana' => 'タロウ',
            'email' => 'employee00001@example.com',
            'employment_status' => EmploymentStatus::Active->value,
        ]);
        $this->assertDatabaseHas('employees', [
            'employee_number' => 'EMP-00004',
            'employment_status' => EmploymentStatus::Leave->value,
        ]);
        $this->assertDatabaseHas('employees', [
            'employee_number' => 'EMP-00005',
            'employment_status' => EmploymentStatus::Retired->value,
        ]);
        $this->assertDatabaseMissing('employees', ['department_id' => null]);
        $this->assertDatabaseMissing('employees', ['position_id' => null]);
    }
}
