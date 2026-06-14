<?php

namespace App\Application\Department\Search\Input\Builder;

use App\Application\Department\Search\Input\SearchInput;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

final class SearchInputBuilder
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated): SearchInput
    {
        $orderBy = isset($validated['order_by'])
            ? DepartmentOrderBy::from($validated['order_by'])
            : DepartmentOrderBy::Id;
        $orderDirection = isset($validated['order_direction'])
            ? OrderDirection::from($validated['order_direction'])
            : OrderDirection::Asc;

        return new SearchInput(
            orderBy: $orderBy,
            orderDirection: $orderDirection,
        );
    }
}
