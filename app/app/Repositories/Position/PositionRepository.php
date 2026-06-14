<?php

namespace App\Repositories\Position;

use App\Repositories\Position\Record\Input\PositionSearchInputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;

interface PositionRepository
{
    public function search(PositionSearchInputRecord $input): PositionSearchOutputRecord;
}
