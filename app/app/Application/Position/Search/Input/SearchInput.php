<?php

namespace App\Application\Position\Search\Input;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

final readonly class SearchInput
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
