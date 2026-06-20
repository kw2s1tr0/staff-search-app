<?php

namespace App\Repositories\Employee\Record\Output;

/**
 * 社員Repositoryが返す複数件の検索結果をまとめるRecord。
 */
final readonly class EmployeeSearchOutputRecord
{
    /**
     * @param  list<EmployeeOutputRecord>  $employees
     */
    public function __construct(
        public array $employees,
    ) {}
}
