<?php

namespace App\Application\Position\Search\Output;

final readonly class SearchOutput
{
    /**
     * @param  list<PositionOutput>  $positions
     */
    public function __construct(
        public array $positions,
    ) {}
}
