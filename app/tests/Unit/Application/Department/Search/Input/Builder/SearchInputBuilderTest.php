<?php

namespace Tests\Unit\Application\Department\Search\Input\Builder;

use App\Application\Department\Search\Input\Builder\SearchInputBuilder;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;
use PHPUnit\Framework\TestCase;

class SearchInputBuilderTest extends TestCase
{
    public function test_it_builds_the_default_order(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $input = (new SearchInputBuilder)->build([]);

        $this->assertSame(DepartmentOrderBy::Id, $input->orderBy);
        $this->assertSame(OrderDirection::Asc, $input->orderDirection);
    }

    public function test_it_builds_the_specified_order(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $input = (new SearchInputBuilder)->build([
            'order_by' => 'name',
            'order_direction' => 'desc',
        ]);

        $this->assertSame(DepartmentOrderBy::Name, $input->orderBy);
        $this->assertSame(OrderDirection::Desc, $input->orderDirection);
    }
}
