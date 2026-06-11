<?php

namespace App\Application\Employee\Search\Output;

use App\Enums\EmploymentStatus;

final readonly class EmployeeOutput
{
    public function __construct(
        public int $id,
        public string $employeeNumber,
        public int $departmentId,
        public ?int $positionId,
        public string $familyName,
        public string $givenName,
        public string $familyNameKana,
        public string $givenNameKana,
        public string $email,
        public EmploymentStatus $employmentStatus,
        public string $createdAt,
        public string $updatedAt,
        public DepartmentOutput $department,
        public ?PositionOutput $position,
    ) {}
}
