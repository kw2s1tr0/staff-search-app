<?php

namespace App\Application\Employee\Search\Output;

use App\Enums\EmploymentStatus;

/**
 * HTTP層へ渡す社員1件分の検索結果DTO。
 */
final readonly class EmployeeOutput
{
    public function __construct(
        public int $id,
        public string $employeeNumber,
        public int $departmentId,
        public int $positionId,
        public string $familyName,
        public string $givenName,
        public string $familyNameKana,
        public string $givenNameKana,
        public string $email,
        public EmploymentStatus $employmentStatus,
        public string $createdAt,
        public string $updatedAt,
        public DepartmentOutput $department,
        public PositionOutput $position,
    ) {}
}
