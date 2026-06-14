<?php

namespace App\Repositories\Position\Record\Output;

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
