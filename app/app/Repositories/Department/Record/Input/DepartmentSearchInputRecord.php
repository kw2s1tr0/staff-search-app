<?php

namespace App\Repositories\Department\Record\Input;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

final readonly class DepartmentSearchInputRecord
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
