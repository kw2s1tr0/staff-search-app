<?php

namespace App\Application\Department\Search\Input;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

/**
 * 部署検索Serviceへ渡す並び順を保持する入力DTO。
 */
final readonly class SearchInput
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
