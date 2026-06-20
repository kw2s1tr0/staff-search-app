<?php

namespace App\Repositories\Position\Record\Input;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

/**
 * 役職Repositoryへ渡す検索条件を保持する入力Record。
 */
final readonly class PositionSearchInputRecord
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
