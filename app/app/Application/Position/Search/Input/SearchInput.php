<?php

namespace App\Application\Position\Search\Input;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

/**
 * 役職検索Serviceへ渡す並び順を保持する入力DTO。
 */
final readonly class SearchInput
{
    public function __construct(
        public PositionOrderBy $orderBy,
        public OrderDirection $orderDirection,
    ) {}
}
