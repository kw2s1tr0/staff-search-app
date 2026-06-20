<?php

namespace App\Domain\Search\Department;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

/**
 * 部署検索で許可する並び順を表すDomainオブジェクト。
 */
final readonly class SearchCondition
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
