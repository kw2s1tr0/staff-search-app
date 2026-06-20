<?php

namespace App\Http\Dto\Web\Employee\Search;

use App\Enums\EmploymentStatus;

/**
 * 社員一覧の表示に必要な項目だけを保持するWeb画面専用DTO。
 */
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
