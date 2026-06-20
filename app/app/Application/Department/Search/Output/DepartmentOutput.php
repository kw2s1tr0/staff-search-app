<?php

namespace App\Application\Department\Search\Output;

/**
 * HTTP層へ渡す部署1件分の検索結果DTO。
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
