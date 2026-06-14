<?php

namespace App\Application\Department\Search;

use App\Application\Department\Search\Input\SearchInput;
use App\Application\Department\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Department\Search\Output\SearchOutput;
use App\Domain\Search\Department\Builder\SearchConditionBuilder;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;

final readonly class SearchService
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
        private SearchOutputBuilder $searchOutputBuilder,
    ) {}

    public function execute(SearchInput $input): SearchOutput
    {
        $conditionBuilder = new SearchConditionBuilder(
            orderBy: $input->orderBy,
            orderDirection: $input->orderDirection,
        );
        $condition = $conditionBuilder->build();
        $repositoryInput = new DepartmentSearchInputRecord(
            orderBy: $condition->orderBy,
            orderDirection: $condition->orderDirection,
        );

        $repositoryOutput = $this->departmentRepository->search($repositoryInput);
        $output = $this->searchOutputBuilder->build($repositoryOutput);

        return $output;
    }
}
