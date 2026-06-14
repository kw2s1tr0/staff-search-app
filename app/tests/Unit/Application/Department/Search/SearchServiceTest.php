<?php

namespace Tests\Unit\Application\Department\Search;

use App\Application\Department\Search\Input\SearchInput;
use App\Application\Department\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Department\Search\SearchService;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    public function test_it_searches_departments_with_the_built_condition(): void
    {
        $repository = $this->createMock(DepartmentRepository::class);
        $repository
            ->expects($this->once())
            ->method('search')
            ->with($this->callback(
                fn (DepartmentSearchInputRecord $input): bool => $input->orderBy === DepartmentOrderBy::Name
                    && $input->orderDirection === OrderDirection::Desc
            ))
            ->willReturn(new DepartmentSearchOutputRecord([]));

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $outputBuilder = new SearchOutputBuilder;
        $service = new SearchService($repository, $outputBuilder);
        $output = $service->execute(new SearchInput(
            orderBy: DepartmentOrderBy::Name,
            orderDirection: OrderDirection::Desc,
        ));

        $this->assertSame([], $output->departments);
    }
}
