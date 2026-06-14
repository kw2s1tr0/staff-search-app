<?php

namespace App\Repositories\Employee;

use App\Enums\EmploymentStatus;
use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;
use App\Repositories\Employee\Record\Output\EmployeeOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchDepartmentOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchPositionOutputRecord;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * 社員検索条件をSQLへ変換し、関連する部署・役職を含めて取得する。
 */
final class DatabaseEmployeeRepository implements EmployeeRepository
{
    /**
     * 検索条件を適用し、DBの行データをRepositoryの出力Recordへ変換する。
     */
    public function search(EmployeeSearchInputRecord $input): EmployeeSearchOutputRecord
    {
        // 同名カラムを区別できるよう、部署・役職のカラムには専用の別名を付ける。
        $query = DB::table('employees')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->join('positions', 'positions.id', '=', 'employees.position_id')
            ->select([
                'employees.id',
                'employees.employee_number',
                'employees.department_id',
                'employees.position_id',
                'employees.family_name',
                'employees.given_name',
                'employees.family_name_kana',
                'employees.given_name_kana',
                'employees.email',
                'employees.employment_status',
                'employees.created_at',
                'employees.updated_at',
                'departments.id as department_data_id',
                'departments.code as department_data_code',
                'departments.name as department_data_name',
                'departments.created_at as department_data_created_at',
                'departments.updated_at as department_data_updated_at',
                'positions.id as position_data_id',
                'positions.code as position_data_code',
                'positions.name as position_data_name',
                'positions.created_at as position_data_created_at',
                'positions.updated_at as position_data_updated_at',
            ]);

        $this->applyInput($query, $input);

        /** @var list<EmployeeOutputRecord> $employees */
        $employees = $query
            ->orderBy('employees.id')
            ->get()
            ->map(fn (stdClass $row): EmployeeOutputRecord => $this->toEmployeeOutputRecord($row))
            ->all();

        return new EmployeeSearchOutputRecord($employees);
    }

    private function applyInput(Builder $query, EmployeeSearchInputRecord $input): void
    {
        // nullの条件はSQLへ追加せず、指定された絞り込みだけを有効にする。
        if ($input->departmentId !== null) {
            $query->where('employees.department_id', $input->departmentId);
        }

        if ($input->positionId !== null) {
            $query->where('employees.position_id', $input->positionId);
        }

        if ($input->employmentStatus !== null) {
            $query->where('employees.employment_status', $input->employmentStatus->value);
        }

        if ($input->keywords === []) {
            return;
        }

        // キーワード同士と、1つのキーワードに対する検索項目同士をORで評価する。
        $query->where(function (Builder $keywordsQuery) use ($input): void {
            foreach ($input->keywords as $keyword) {
                $keywordsQuery->orWhere(function (Builder $keywordQuery) use ($keyword): void {
                    $likeKeyword = "%{$keyword}%";

                    $keywordQuery
                        ->where('employees.employee_number', $keyword)
                        ->orWhere('employees.email', $keyword)
                        ->orWhereRaw(
                            $this->concatenate('employees.family_name', 'employees.given_name').' like ?',
                            [$likeKeyword]
                        )
                        ->orWhereRaw(
                            $this->concatenate('employees.family_name_kana', 'employees.given_name_kana').' like ?',
                            [$likeKeyword]
                        )
                        ->orWhere('departments.name', 'like', $likeKeyword)
                        ->orWhere('positions.name', 'like', $likeKeyword);
                });
            }
        });
    }

    private function concatenate(string $firstColumn, string $secondColumn): string
    {
        // SQLiteとMySQLでは文字列連結の構文が異なるため、接続先に合わせて切り替える。
        if (DB::getDriverName() === 'sqlite') {
            return "{$firstColumn} || {$secondColumn}";
        }

        return "CONCAT({$firstColumn}, {$secondColumn})";
    }

    private function toEmployeeOutputRecord(stdClass $row): EmployeeOutputRecord
    {
        // Query BuilderはstdClassを返すため、境界で型を確定して後続処理を安全にする。
        return new EmployeeOutputRecord(
            id: (int) $row->id,
            employeeNumber: (string) $row->employee_number,
            departmentId: (int) $row->department_id,
            positionId: (int) $row->position_id,
            familyName: (string) $row->family_name,
            givenName: (string) $row->given_name,
            familyNameKana: (string) $row->family_name_kana,
            givenNameKana: (string) $row->given_name_kana,
            email: (string) $row->email,
            employmentStatus: EmploymentStatus::from((string) $row->employment_status),
            createdAt: (string) $row->created_at,
            updatedAt: (string) $row->updated_at,
            department: new EmployeeSearchDepartmentOutputRecord(
                id: (int) $row->department_data_id,
                code: (string) $row->department_data_code,
                name: (string) $row->department_data_name,
                createdAt: (string) $row->department_data_created_at,
                updatedAt: (string) $row->department_data_updated_at,
            ),
            position: $this->toPositionOutputRecord($row),
        );
    }

    private function toPositionOutputRecord(stdClass $row): EmployeeSearchPositionOutputRecord
    {
        return new EmployeeSearchPositionOutputRecord(
            id: (int) $row->position_data_id,
            code: (string) $row->position_data_code,
            name: (string) $row->position_data_name,
            createdAt: (string) $row->position_data_created_at,
            updatedAt: (string) $row->position_data_updated_at,
        );
    }
}
