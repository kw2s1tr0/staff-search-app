<?php

namespace App\Repositories\Employee\Record\Output;

final readonly class EmployeeSearchOutputRecord
{
    /**
     * @param  list<EmployeeOutputRecord>  $employees
     */
    public function __construct(
        public array $employees,
    ) {}
}
