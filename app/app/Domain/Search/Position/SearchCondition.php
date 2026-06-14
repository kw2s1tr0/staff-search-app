<?php

namespace App\Domain\Search\Position;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

final readonly class SearchCondition
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
