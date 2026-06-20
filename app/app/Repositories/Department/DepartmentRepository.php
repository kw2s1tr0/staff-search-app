<?php

namespace App\Repositories\Department;

use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;

/**
 * Application層がDB実装へ直接依存せずに部署を検索するための契約。
 */
interface DepartmentRepository
{
    /**
     * 検索入力を受け取り、部署の一覧をRepository Recordで返す。
     */
    public function search(DepartmentSearchInputRecord $input): DepartmentSearchOutputRecord;
}
