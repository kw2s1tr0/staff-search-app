<?php

namespace App\Application\Department\Search\Output;

final readonly class SearchOutput
{
    /**
     * @param  list<DepartmentOutput>  $departments
     */
    public function __construct(
        public array $departments,
    ) {}
}
