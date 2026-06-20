<?php

namespace App\Repositories\Department\Record\Output;

/**
 * 部署Repositoryが返す複数件の検索結果をまとめるRecord。
 */
final readonly class DepartmentSearchOutputRecord
{
    /**
     * @param  list<DepartmentOutputRecord>  $departments
     */
    public function __construct(
        public array $departments,
    ) {}
}
