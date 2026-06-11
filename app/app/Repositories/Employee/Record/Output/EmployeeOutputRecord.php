<?php

namespace App\Repositories\Employee\Record\Output;

use App\Enums\EmploymentStatus;

final readonly class EmployeeOutputRecord
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
        public EmployeeSearchDepartmentOutputRecord $department,
        public ?EmployeeSearchPositionOutputRecord $position,
    ) {}
}
