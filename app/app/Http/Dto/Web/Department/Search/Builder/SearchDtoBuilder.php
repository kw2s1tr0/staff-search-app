<?php

namespace App\Http\Dto\Web\Department\Search\Builder;

use App\Application\Department\Search\Output\DepartmentOutput;
use App\Application\Department\Search\Output\SearchOutput;
use App\Http\Dto\Web\Department\Search\DepartmentSearchDto;

/**
 * 部署検索結果から、選択肢表示に必要な値だけを取り出す。
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
                name: $department->name,
            ),
            $output->departments,
        );
    }
}
