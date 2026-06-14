<?php

namespace App\Repositories\Position;

use App\Repositories\Position\Record\Input\PositionSearchInputRecord;
use App\Repositories\Position\Record\Output\PositionOutputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;
use Illuminate\Support\Facades\DB;
use stdClass;

final class DatabasePositionRepository implements PositionRepository
{
    public function search(PositionSearchInputRecord $input): PositionSearchOutputRecord
    {
        /** @var list<PositionOutputRecord> $positions */
        $positions = DB::table('positions')
            ->orderBy($input->orderBy->value, $input->orderDirection->value)
            ->get()
            ->map(fn (stdClass $row): PositionOutputRecord => new PositionOutputRecord(
                id: (int) $row->id,
                code: (string) $row->code,
                name: (string) $row->name,
                createdAt: (string) $row->created_at,
                updatedAt: (string) $row->updated_at,
            ))
            ->all();

        return new PositionSearchOutputRecord($positions);
    }
}
