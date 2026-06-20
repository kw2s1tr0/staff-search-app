<?php

namespace App\Http\Dto\Api\Department\Search\Builder;

use App\Application\Department\Search\Output\DepartmentOutput;
use App\Application\Department\Search\Output\SearchOutput;
use App\Http\Dto\Api\Department\Search\DepartmentSearchDto;

/**
 * Application層の部署検索結果を、APIレスポンス用DTOへ変換する。
 */
final class SearchDtoBuilder
{
    /**
     * @return list<DepartmentSearchDto>
     */
    public function build(SearchOutput $output): array
    {
        return array_map(
            fn (DepartmentOutput $department): DepartmentSearchDto => new DepartmentSearchDto(
                id: $department->id,
                code: $department->code,
                name: $department->name,
                createdAt: $department->createdAt,
                updatedAt: $department->updatedAt,
            ),
            $output->departments,
        );
    }
}
