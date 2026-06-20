<?php

namespace App\Application\Position\Search\Output;

/**
 * HTTP層へ渡す役職1件分の検索結果DTO。
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
