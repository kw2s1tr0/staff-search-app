<?php

namespace App\Repositories\Employee\Record\Output;

final readonly class EmployeeSearchDepartmentOutputRecord
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
