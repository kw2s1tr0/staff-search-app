<?php

namespace App\Application\Employee\Search\Output;

/**
 * 社員検索で取得した複数件の結果をまとめるDTO。
 */
final readonly class SearchOutput
{
    /**
     * @param  list<EmployeeOutput>  $employees
     */
    public function __construct(
        public array $employees,
    ) {}
}
