<?php

namespace App\Repositories\Position;

use App\Repositories\Position\Record\Input\PositionSearchInputRecord;
use App\Repositories\Position\Record\Output\PositionOutputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * 役職検索をQuery Builderで実行するRepository実装。
 */
final class DatabasePositionRepository implements PositionRepository
{
    /**
     * 指定された並び順で役職を取得し、DBの行を型付きRecordへ変換する。
     */
    public function search(PositionSearchInputRecord $input): PositionSearchOutputRecord
    {
        // Enumの値だけをカラム名と方向に使うため、任意のSQL文字列は入り込まない。
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

        // 複数件の結果を1つのRecordにまとめてApplication層へ返す。
        return new PositionSearchOutputRecord($positions);
    }
}
