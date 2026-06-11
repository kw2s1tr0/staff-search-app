<?php

namespace App\Application\Employee\Search\Output;

final readonly class SearchOutput
{
    /**
     * @param  list<EmployeeOutput>  $employees
     */
    public function __construct(
        public array $employees,
    ) {}
}
