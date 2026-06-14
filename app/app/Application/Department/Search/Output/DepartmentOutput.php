<?php

namespace App\Application\Department\Search\Output;

final readonly class DepartmentOutput
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
