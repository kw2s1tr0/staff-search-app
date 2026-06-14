<?php

namespace App\Application\Position\Search\Output\Builder;

use App\Application\Position\Search\Output\PositionOutput;
use App\Application\Position\Search\Output\SearchOutput;
use App\Repositories\Position\Record\Output\PositionOutputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;

final class SearchOutputBuilder
{
    public function build(PositionSearchOutputRecord $record): SearchOutput
    {
        $positions = array_map(
            fn (PositionOutputRecord $position): PositionOutput => $this->buildPosition($position),
            $record->positions,
        );

        return new SearchOutput($positions);
    }

    private function buildPosition(PositionOutputRecord $position): PositionOutput
    {
        return new PositionOutput(
            id: $position->id,
            code: $position->code,
            name: $position->name,
            createdAt: $position->createdAt,
            updatedAt: $position->updatedAt,
        );
    }
}
