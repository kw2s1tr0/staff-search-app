<?php

namespace Tests\Unit\Application\Employee\Search\Output\Builder;

use App\Application\Employee\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Employee\Search\Output\DepartmentOutput;
use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\PositionOutput;
use App\Enums\EmploymentStatus;
use App\Repositories\Employee\Record\Output\EmployeeOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchDepartmentOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchPositionOutputRecord;
use PHPUnit\Framework\TestCase;

class SearchOutputBuilderTest extends TestCase
{
    public function test_it_builds_application_output_from_repository_output(): void
    {
        $departmentRecord = new EmployeeSearchDepartmentOutputRecord(
            id: 1,
            code: 'DEV',
            name: 'Development',
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
        );
        $positionRecord = new EmployeeSearchPositionOutputRecord(
            id: 2,
            code: 'ENGINEER',
            name: 'Engineer',
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
        );
        $employeeRecord = $this->createEmployeeRecord($departmentRecord, $positionRecord);
        $repositoryOutput = new EmployeeSearchOutputRecord([$employeeRecord]);

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new SearchOutputBuilder;
        $output = $builder->build($repositoryOutput);

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
        $expectedEmployee = new EmployeeOutput(
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

        $this->assertEquals([$expectedEmployee], $output->employees);
        $this->assertNotSame($employeeRecord, $output->employees[0]);
    }

    private function createEmployeeRecord(
        EmployeeSearchDepartmentOutputRecord $department,
        EmployeeSearchPositionOutputRecord $position,
    ): EmployeeOutputRecord {
        return new EmployeeOutputRecord(
            id: 10,
            employeeNumber: 'EMP-00010',
            departmentId: 1,
            positionId: $position->id,
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
    }
}
