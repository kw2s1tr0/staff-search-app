<?php

namespace App\Http\Dto\Web\Department\Search\Builder;

use App\Application\Department\Search\Output\DepartmentOutput;
use App\Application\Department\Search\Output\SearchOutput;
use App\Http\Dto\Web\Department\Search\DepartmentSearchDto;

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
