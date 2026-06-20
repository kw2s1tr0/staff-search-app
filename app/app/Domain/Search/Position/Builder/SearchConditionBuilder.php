<?php

namespace App\Domain\Search\Position\Builder;

use App\Domain\Search\Position\SearchCondition;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

/**
 * 役職検索の入力値から、Domain層の検索条件を組み立てる。
 */
final readonly class SearchConditionBuilder
{
    public function __construct(
        private PositionOrderBy $orderBy,
        private OrderDirection $orderDirection,
    ) {}

    /**
     * 型付け済みの並び順を、不変な検索条件としてまとめる。
     */
    public function build(): SearchCondition
    {
        return new SearchCondition(
            orderBy: $this->orderBy,
            orderDirection: $this->orderDirection,
        );
    }
}
