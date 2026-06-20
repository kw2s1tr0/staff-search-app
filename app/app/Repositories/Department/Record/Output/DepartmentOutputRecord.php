<?php

namespace App\Repositories\Department\Record\Output;

/**
 * DBから取得した部署1件分をRepository境界で型付けするRecord。
 */
final readonly class DepartmentOutputRecord
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
