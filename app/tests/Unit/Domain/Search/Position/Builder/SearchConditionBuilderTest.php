<?php

namespace Tests\Unit\Domain\Search\Position\Builder;

use App\Domain\Search\Position\Builder\SearchConditionBuilder;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
use PHPUnit\Framework\TestCase;

class SearchConditionBuilderTest extends TestCase
{
    public function test_it_builds_a_typed_order_condition(): void
    {
        $condition = (new SearchConditionBuilder(
            orderBy: PositionOrderBy::Name,
            orderDirection: OrderDirection::Asc,
        ))->build();

        $this->assertSame(PositionOrderBy::Name, $condition->orderBy);
        $this->assertSame(OrderDirection::Asc, $condition->orderDirection);
    }
}
