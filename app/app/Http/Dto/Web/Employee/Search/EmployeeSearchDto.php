<?php

namespace App\Http\Dto\Web\Employee\Search;

use App\Enums\EmploymentStatus;

final readonly class EmployeeSearchDto
{
    public function __construct(
        public string $employeeNumber,
        public string $familyName,
        public string $givenName,
        public string $email,
        public EmploymentStatus $employmentStatus,
        public string $departmentName,
        public string $positionName,
    ) {}
}
