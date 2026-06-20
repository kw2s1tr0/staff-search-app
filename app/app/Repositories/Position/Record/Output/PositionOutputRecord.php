<?php

namespace App\Repositories\Position\Record\Output;

/**
 * DBから取得した役職1件分をRepository境界で型付けするRecord。
 */
final readonly class PositionOutputRecord
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
