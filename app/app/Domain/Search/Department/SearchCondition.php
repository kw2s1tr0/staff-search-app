<?php

namespace App\Domain\Search\Department;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

final readonly class SearchCondition
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
