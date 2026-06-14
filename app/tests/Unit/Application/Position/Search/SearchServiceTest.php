<?php

namespace Tests\Unit\Application\Position\Search;

use App\Application\Position\Search\Input\SearchInput;
use App\Application\Position\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Position\Search\SearchService;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
use App\Repositories\Position\PositionRepository;
use App\Repositories\Position\Record\Input\PositionSearchInputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    public function test_it_searches_positions_with_the_built_condition(): void
    {
        $repository = $this->createMock(PositionRepository::class);
        $repository
            ->expects($this->once())
            ->method('search')
            ->with($this->callback(
                fn (PositionSearchInputRecord $input): bool => $input->orderBy === PositionOrderBy::Code
                    && $input->orderDirection === OrderDirection::Asc
            ))
            ->willReturn(new PositionSearchOutputRecord([]));

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $outputBuilder = new SearchOutputBuilder;
        $service = new SearchService($repository, $outputBuilder);
        $output = $service->execute(new SearchInput(
            orderBy: PositionOrderBy::Code,
            orderDirection: OrderDirection::Asc,
        ));

        $this->assertSame([], $output->positions);
    }
}
