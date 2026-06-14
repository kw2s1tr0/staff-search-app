<?php

namespace App\Application\Department\Search\Output\Builder;

use App\Application\Department\Search\Output\DepartmentOutput;
use App\Application\Department\Search\Output\SearchOutput;
use App\Repositories\Department\Record\Output\DepartmentOutputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;

final class SearchOutputBuilder
{
    public function build(DepartmentSearchOutputRecord $record): SearchOutput
    {
        $departments = array_map(
            fn (DepartmentOutputRecord $department): DepartmentOutput => $this->buildDepartment($department),
            $record->departments,
        );

        return new SearchOutput($departments);
    }

    private function buildDepartment(DepartmentOutputRecord $department): DepartmentOutput
    {
        return new DepartmentOutput(
            id: $department->id,
            code: $department->code,
            name: $department->name,
            createdAt: $department->createdAt,
            updatedAt: $department->updatedAt,
        );
    }
}
