<?php

namespace Tests\Unit\Http\Dto\Api\Employee\Search\Builder;

use App\Application\Employee\Search\Output\DepartmentOutput;
use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\PositionOutput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Enums\EmploymentStatus;
use App\Http\Dto\Api\Employee\Search\Builder\SearchDtoBuilder;
use PHPUnit\Framework\TestCase;

class SearchDtoBuilderTest extends TestCase
{
    public function test_it_builds_json_serializable_dtos_from_service_output(): void
    {
        $department = new DepartmentOutput(
            id: 1,
            code: 'DEV',
            name: 'Development',
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
        );
        $position = new PositionOutput(
            id: 2,
            code: 'ENGINEER',
            name: 'Engineer',
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
        );
        $employee = new EmployeeOutput(
            id: 10,
            employeeNumber: 'EMP-00010',
            departmentId: 1,
            positionId: 2,
            familyName: 'Yamada',
            givenName: 'Taro',
            familyNameKana: 'ヤマダ',
            givenNameKana: 'タロウ',
            email: 'yamada@example.com',
            employmentStatus: EmploymentStatus::Active,
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
            department: $department,
            position: $position,
        );

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new SearchDtoBuilder;
        $output = new SearchOutput([$employee]);
        $dtos = $builder->build($output);
        $serialized = json_decode(
            json_encode($dtos, JSON_THROW_ON_ERROR),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $serializedEmployee = $serialized[0];

        $this->assertSame(10, $serializedEmployee['id']);
        $this->assertSame('EMP-00010', $serializedEmployee['employee_number']);
        $this->assertSame('active', $serializedEmployee['employment_status']);
        $this->assertSame('Development', $serializedEmployee['department']['name']);
        $this->assertSame('Engineer', $serializedEmployee['position']['name']);
        $this->assertSame('2026-01-01T00:00:00.000000Z', $serializedEmployee['created_at']);
    }
}
