<?php

namespace App\Repositories\Department\Record\Input;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

/**
 * 部署Repositoryへ渡す検索条件を保持する入力Record。
 */
final readonly class DepartmentSearchInputRecord
{
    public function __construct(
        public DepartmentOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
