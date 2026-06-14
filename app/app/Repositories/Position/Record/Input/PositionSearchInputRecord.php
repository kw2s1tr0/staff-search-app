<?php

namespace App\Repositories\Position\Record\Input;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

final readonly class PositionSearchInputRecord
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
