<?php

namespace App\Domain\Search\Department\Builder;

use App\Domain\Search\Department\SearchCondition;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

final readonly class SearchConditionBuilder
{
    public function __construct(
        private DepartmentOrderBy $orderBy,
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
