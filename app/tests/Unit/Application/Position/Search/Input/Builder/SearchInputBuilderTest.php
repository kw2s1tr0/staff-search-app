<?php

namespace Tests\Unit\Application\Position\Search\Input\Builder;

use App\Application\Position\Search\Input\Builder\SearchInputBuilder;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
use PHPUnit\Framework\TestCase;

class SearchInputBuilderTest extends TestCase
{
    public function test_it_builds_the_default_order(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $input = (new SearchInputBuilder)->build([]);

        $this->assertSame(PositionOrderBy::Id, $input->orderBy);
        $this->assertSame(OrderDirection::Asc, $input->orderDirection);
    }

    public function test_it_builds_the_specified_order(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $input = (new SearchInputBuilder)->build([
            'order_by' => 'code',
            'order_direction' => 'desc',
        ]);

        $this->assertSame(PositionOrderBy::Code, $input->orderBy);
        $this->assertSame(OrderDirection::Desc, $input->orderDirection);
    }
}
