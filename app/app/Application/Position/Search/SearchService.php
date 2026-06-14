<?php

namespace App\Application\Position\Search;

use App\Application\Position\Search\Input\SearchInput;
use App\Application\Position\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Position\Search\Output\SearchOutput;
use App\Domain\Search\Position\Builder\SearchConditionBuilder;
use App\Repositories\Position\PositionRepository;
use App\Repositories\Position\Record\Input\PositionSearchInputRecord;

final readonly class SearchService
{
    public function __construct(
        private PositionRepository $positionRepository,
        private SearchOutputBuilder $searchOutputBuilder,
    ) {}

    public function execute(SearchInput $input): SearchOutput
    {
        $conditionBuilder = new SearchConditionBuilder(
            orderBy: $input->orderBy,
            orderDirection: $input->orderDirection,
        );
        $condition = $conditionBuilder->build();
        $repositoryInput = new PositionSearchInputRecord(
            orderBy: $condition->orderBy,
            orderDirection: $condition->orderDirection,
        );

        $repositoryOutput = $this->positionRepository->search($repositoryInput);
        $output = $this->searchOutputBuilder->build($repositoryOutput);

        return $output;
    }
}
