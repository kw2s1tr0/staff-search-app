<?php

namespace App\Repositories\Department;

use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;
use App\Repositories\Department\Record\Output\DepartmentOutputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;
use Illuminate\Support\Facades\DB;
use stdClass;

final class DatabaseDepartmentRepository implements DepartmentRepository
{
    public function search(DepartmentSearchInputRecord $input): DepartmentSearchOutputRecord
    {
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

        return new DepartmentSearchOutputRecord($departments);
    }
}
