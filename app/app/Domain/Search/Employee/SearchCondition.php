<?php

namespace App\Domain\Search\Employee;

use App\Enums\EmploymentStatus;

final readonly class SearchCondition
{
    public function __construct(
        public ?Keywords $keyword,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
