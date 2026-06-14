<?php

namespace App\Repositories\Position\Record\Output;

final readonly class PositionSearchOutputRecord
{
    /**
     * @param  list<PositionOutputRecord>  $positions
     */
    public function __construct(
        public array $positions,
    ) {}
}
