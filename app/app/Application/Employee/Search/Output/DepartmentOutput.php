<?php

namespace App\Application\Employee\Search\Output;

/**
 * 社員検索結果に付属する部署情報のDTO。
 */
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
