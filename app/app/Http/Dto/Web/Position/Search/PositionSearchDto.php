<?php

namespace App\Http\Dto\Web\Position\Search;

final readonly class PositionSearchDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
