<?php

namespace App\Http\Dto\Web\Employee\Search\Builder;

use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Http\Dto\Web\Employee\Search\EmployeeSearchDto;

/**
 * アプリケーション出力から、社員一覧画面で使う項目だけを取り出す。
 */
final class SearchDtoBuilder
{
    /**
     * Viewが検索処理の内部構造へ依存しないよう、画面専用DTOへ変換する。
     *
     * @return list<EmployeeSearchDto>
     */
    public function build(SearchOutput $output): array
    {
        return array_map(
            fn (EmployeeOutput $employee): EmployeeSearchDto => new EmployeeSearchDto(
                employeeNumber: $employee->employeeNumber,
                familyName: $employee->familyName,
                givenName: $employee->givenName,
                email: $employee->email,
                employmentStatus: $employee->employmentStatus,
                departmentName: $employee->department->name,
                positionName: $employee->position->name,
            ),
            $output->employees,
        );
    }
}
