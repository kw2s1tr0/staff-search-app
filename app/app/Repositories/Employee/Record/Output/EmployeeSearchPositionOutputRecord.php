<?php

namespace App\Repositories\Employee\Record\Output;

/**
 * 社員検索結果に含める役職1件分のRepository Record。
 */
final readonly class EmployeeSearchPositionOutputRecord
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
