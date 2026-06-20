<?php

namespace App\Repositories\Department;

use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;
use App\Repositories\Department\Record\Output\DepartmentOutputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * 部署検索をQuery Builderで実行するRepository実装。
 */
final class DatabaseDepartmentRepository implements DepartmentRepository
{
    /**
     * 指定された並び順で部署を取得し、DBの行を型付きRecordへ変換する。
     */
    public function search(DepartmentSearchInputRecord $input): DepartmentSearchOutputRecord
    {
        // Enumの値だけをカラム名と方向に使うため、任意のSQL文字列は入り込まない。
        /** @var list<DepartmentOutputRecord> $departments */
        $departments = DB::table('departments')
            ->orderBy($input->orderBy->value, $input->orderDirection->value)
            ->get()
            ->map(fn (stdClass $row): DepartmentOutputRecord => new DepartmentOutputRecord(
                id: (int) $row->id,
                code: (string) $row->code,
                name: (string) $row->name,
                createdAt: (string) $row->created_at,
                updatedAt: (string) $row->updated_at,
            ))
            ->all();

        // 複数件の結果を1つのRecordにまとめてApplication層へ返す。
        return new DepartmentSearchOutputRecord($departments);
    }
}
