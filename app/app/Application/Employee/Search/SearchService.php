<?php

namespace App\Application\Employee\Search;

use App\Application\Employee\Search\Builder\SearchOutputBuilder;
use App\Application\Employee\Search\Input\SearchInput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Domain\Search\Employee\Builder\SearchConditionBuilder;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;

final readonly class SearchService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private SearchOutputBuilder $searchOutputBuilder,
    ) {}

    public function execute(SearchInput $input): SearchOutput
    {
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
        $repositoryOutput = $this->employeeRepository->search($repositoryInput);
        $output = $this->searchOutputBuilder->build($repositoryOutput);

        return $output;
    }
}
