<?php

namespace App\Application\Employee\Search;

use App\Application\Employee\Search\Input\SearchInput;
use App\Application\Employee\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Domain\Search\Employee\Builder\SearchConditionBuilder;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;

/**
 * 社員検索に必要な条件生成、データ取得、出力変換を順番に組み立てる。
 */
final readonly class SearchService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private SearchOutputBuilder $searchOutputBuilder,
    ) {}

    /**
     * HTTPやDBの詳細に依存せず、社員検索の一連の処理を実行する。
     */
    public function execute(SearchInput $input): SearchOutput
    {
        // 未加工のキーワードを、検索仕様に沿ったキーワード一覧へ正規化する。
        $conditionBuilder = new SearchConditionBuilder(
            keyword: $input->keyword,
            departmentId: $input->departmentId,
            positionId: $input->positionId,
            employmentStatus: $input->employmentStatus,
        );
        $condition = $conditionBuilder->build();
        $repositoryInput = new EmployeeSearchInputRecord(
            keywords: $condition->keyword->values ?? [],
            departmentId: $condition->departmentId,
            positionId: $condition->positionId,
            employmentStatus: $condition->employmentStatus,
        );

        // Repositoryの入出力をアプリケーション層の型へ変換し、DB固有の型を外へ漏らさない。
        $repositoryOutput = $this->employeeRepository->search($repositoryInput);
        $output = $this->searchOutputBuilder->build($repositoryOutput);

        return $output;
    }
}
