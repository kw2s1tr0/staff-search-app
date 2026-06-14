<?php

namespace App\Http\Dto\Web\Position\Search\Builder;

use App\Application\Position\Search\Output\PositionOutput;
use App\Application\Position\Search\Output\SearchOutput;
use App\Http\Dto\Web\Position\Search\PositionSearchDto;

final class SearchDtoBuilder
{
    /**
     * @return list<PositionSearchDto>
     */
    public function build(SearchOutput $output): array
    {
        return array_map(
            fn (PositionOutput $position): PositionSearchDto => new PositionSearchDto(
                id: $position->id,
                name: $position->name,
            ),
            $output->positions,
        );
    }
}
