<?php

namespace Tests\Unit\Domain\Search\Department\Builder;

use App\Domain\Search\Department\Builder\SearchConditionBuilder;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;
use PHPUnit\Framework\TestCase;

class SearchConditionBuilderTest extends TestCase
{
    public function test_it_builds_a_typed_order_condition(): void
    {
        $condition = (new SearchConditionBuilder(
            orderBy: DepartmentOrderBy::Code,
            orderDirection: OrderDirection::Desc,
        ))->build();

        $this->assertSame(DepartmentOrderBy::Code, $condition->orderBy);
        $this->assertSame(OrderDirection::Desc, $condition->orderDirection);
    }
}
