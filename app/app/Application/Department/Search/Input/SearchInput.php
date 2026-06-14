<?php

namespace App\Application\Department\Search\Input;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

final readonly class SearchInput
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
