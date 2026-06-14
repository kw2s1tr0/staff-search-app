<?php

namespace App\Application\Position\Search\Input\Builder;

use App\Application\Position\Search\Input\SearchInput;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;

final class SearchInputBuilder
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated): SearchInput
    {
        $orderBy = isset($validated['order_by'])
            ? PositionOrderBy::from($validated['order_by'])
            : PositionOrderBy::Id;
        $orderDirection = isset($validated['order_direction'])
            ? OrderDirection::from($validated['order_direction'])
            : OrderDirection::Asc;

        return new SearchInput(
            orderBy: $orderBy,
            orderDirection: $orderDirection,
        );
    }
}
