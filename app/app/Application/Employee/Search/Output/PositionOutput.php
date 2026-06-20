<?php

namespace App\Application\Employee\Search\Output;

/**
 * 社員検索結果に付属する役職情報のDTO。
 */
final readonly class PositionOutput
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
