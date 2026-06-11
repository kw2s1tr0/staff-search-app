<?php

namespace App\Application\Employee\Search\Input;

use App\Enums\EmploymentStatus;

final readonly class SearchInput
{
    public function __construct(
        public ?string $keyword,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
