<?php

namespace App\Repositories\Employee;

use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;

/**
 * Application層がDB実装へ直接依存せずに社員を検索するための契約。
 */
interface EmployeeRepository
{
    /**
     * 検索入力を受け取り、関連情報を含む社員一覧をRepository Recordで返す。
     */
    public function search(EmployeeSearchInputRecord $input): EmployeeSearchOutputRecord;
}
