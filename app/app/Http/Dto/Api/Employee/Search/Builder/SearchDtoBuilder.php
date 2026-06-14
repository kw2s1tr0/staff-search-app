<?php

namespace App\Http\Dto\Api\Employee\Search\Builder;

use App\Application\Employee\Search\Output\DepartmentOutput;
use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\PositionOutput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Http\Dto\Api\Employee\Search\EmployeeSearchDepartmentDto;
use App\Http\Dto\Api\Employee\Search\EmployeeSearchDto;
use App\Http\Dto\Api\Employee\Search\EmployeeSearchPositionDto;

/**
 * アプリケーション出力を、APIのレスポンス形式を表すDTOへ変換する。
 */
final class SearchDtoBuilder
{
    /**
     * APIの表現変更がアプリケーション層へ影響しないよう、HTTP層の境界で詰め替える。
     *
     * @return list<EmployeeSearchDto>
     */
    public function build(SearchOutput $output): array
    {
        $employees = array_map(
            fn (EmployeeOutput $employee): EmployeeSearchDto => $this->buildEmployee($employee),
            $output->employees,
        );

        return $employees;
    }

    private function buildEmployee(EmployeeOutput $employee): EmployeeSearchDto
    {
        $department = $this->buildDepartment($employee->department);
        $position = $this->buildPosition($employee->position);

        return new EmployeeSearchDto(
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
        DepartmentOutput $department
    ): EmployeeSearchDepartmentDto {
        return new EmployeeSearchDepartmentDto(
            id: $department->id,
            code: $department->code,
            name: $department->name,
            createdAt: $department->createdAt,
            updatedAt: $department->updatedAt,
        );
    }

    private function buildPosition(
        PositionOutput $position
    ): EmployeeSearchPositionDto {
        return new EmployeeSearchPositionDto(
            id: $position->id,
            code: $position->code,
            name: $position->name,
            createdAt: $position->createdAt,
            updatedAt: $position->updatedAt,
        );
    }
}
