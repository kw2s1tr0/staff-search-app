<?php

namespace App\Domain\Search\Department\Builder;

use App\Domain\Search\Department\SearchCondition;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

/**
 * 部署検索の入力値から、Domain層の検索条件を組み立てる。
 */
final readonly class SearchConditionBuilder
{
    public function __construct(
        private DepartmentOrderBy $orderBy,
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
