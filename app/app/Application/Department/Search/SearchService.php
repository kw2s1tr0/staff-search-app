<?php

namespace App\Application\Department\Search;

use App\Application\Department\Search\Input\SearchInput;
use App\Application\Department\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Department\Search\Output\SearchOutput;
use App\Domain\Search\Department\Builder\SearchConditionBuilder;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;

/**
 * 部署の検索条件生成、データ取得、出力変換を順番に実行する。
 */
final readonly class SearchService
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
        private SearchOutputBuilder $searchOutputBuilder,
    ) {}

    /**
     * 型付きの入力をRepositoryへ渡し、HTTP層で使える検索結果を返す。
     */
    public function execute(SearchInput $input): SearchOutput
    {
        // Domain層を通して、検索条件を業務上有効な型へ確定する。
        $conditionBuilder = new SearchConditionBuilder(
            orderBy: $input->orderBy,
            orderDirection: $input->orderDirection,
        );
        $condition = $conditionBuilder->build();

        // Domainの条件を、Repositoryが受け取るRecordへ詰め替える。
        $repositoryInput = new DepartmentSearchInputRecord(
            orderBy: $condition->orderBy,
            orderDirection: $condition->orderDirection,
        );

        // DB検索の結果をApplication DTOへ変換して呼び出し元へ返す。
        $repositoryOutput = $this->departmentRepository->search($repositoryInput);
        $output = $this->searchOutputBuilder->build($repositoryOutput);

        return $output;
    }
}
