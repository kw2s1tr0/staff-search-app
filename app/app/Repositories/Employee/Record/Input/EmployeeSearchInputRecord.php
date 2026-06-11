<?php

namespace App\Repositories\Employee\Record\Input;

use App\Enums\EmploymentStatus;

final readonly class EmployeeSearchInputRecord
{
    /**
     * @param  list<string>  $keywords
     */
    public function __construct(
        public array $keywords,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
