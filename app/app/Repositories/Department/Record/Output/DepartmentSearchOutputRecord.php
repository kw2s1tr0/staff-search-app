<?php

namespace App\Repositories\Department\Record\Output;

final readonly class DepartmentSearchOutputRecord
{
    /**
     * @param  list<DepartmentOutputRecord>  $departments
     */
    public function __construct(
        public array $departments,
    ) {}
}
