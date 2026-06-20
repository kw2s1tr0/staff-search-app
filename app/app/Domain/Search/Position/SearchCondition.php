<?php

namespace App\Domain\Search\Position;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

/**
 * 役職検索で許可する並び順を表すDomainオブジェクト。
 */
final readonly class SearchCondition
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
