<?php

namespace App\Application\Employee\Search\Output\Builder;

use App\Application\Employee\Search\Output\DepartmentOutput;
use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\PositionOutput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Repositories\Employee\Record\Output\EmployeeOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchDepartmentOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchPositionOutputRecord;

/**
 * Repositoryの検索結果を、HTTP層へ渡すアプリケーション出力へ変換する。
 */
final class SearchOutputBuilder
{
    /**
     * Repository固有のRecordを外へ漏らさないよう、アプリケーション用の型へ詰め替える。
     */
    public function build(EmployeeSearchOutputRecord $record): SearchOutput
    {
        $employees = array_map(
            fn (EmployeeOutputRecord $employee): EmployeeOutput => $this->buildEmployee($employee),
            $record->employees,
        );

        return new SearchOutput($employees);
    }

    private function buildEmployee(EmployeeOutputRecord $employee): EmployeeOutput
    {
        $department = $this->buildDepartment($employee->department);
        $position = $this->buildPosition($employee->position);

        return new EmployeeOutput(
            id: $employee->id,
            employeeNumber: $employee->employeeNumber,
            departmentId: $employee->departmentId,
            positionId: $employee->positionId,
            familyName: $employee->familyName,
            givenName: $employee->givenName,
            familyNameKana: $employee->familyNameKana,
            givenNameKana: $employee->givenNameKana,
            email: $employee->email,
            employmentStatus: $employee->employmentStatus,
            createdAt: $employee->createdAt,
            updatedAt: $employee->updatedAt,
            department: $department,
            position: $position,
        );
    }

    private function buildDepartment(
        EmployeeSearchDepartmentOutputRecord $department
    ): DepartmentOutput {
        return new DepartmentOutput(
            id: $department->id,
            code: $department->code,
            name: $department->name,
            createdAt: $department->createdAt,
            updatedAt: $department->updatedAt,
        );
    }

    private function buildPosition(
        EmployeeSearchPositionOutputRecord $position
    ): PositionOutput {
        return new PositionOutput(
            id: $position->id,
            code: $position->code,
            name: $position->name,
            createdAt: $position->createdAt,
            updatedAt: $position->updatedAt,
        );
    }
}
