<?php

namespace App\Domain\Search\Position\Builder;

use App\Domain\Search\Position\SearchCondition;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

final readonly class SearchConditionBuilder
{
    public function __construct(
        private PositionOrderBy $orderBy,
        private OrderDirection $orderDirection,
    ) {}

    public function build(): SearchCondition
    {
        return new SearchCondition(
            orderBy: $this->orderBy,
            orderDirection: $this->orderDirection,
        );
    }
}
