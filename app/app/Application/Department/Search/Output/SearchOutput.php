<?php

namespace App\Application\Department\Search\Output;

/**
 * 部署検索で取得した複数件の結果をまとめるDTO。
 */
final readonly class SearchOutput
{
    /**
     * @param  list<DepartmentOutput>  $departments
     */
    public function __construct(
        public array $departments,
    ) {}
}
