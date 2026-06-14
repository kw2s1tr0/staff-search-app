<?php

namespace Tests\Unit\Application\Employee\Search;

use App\Application\Employee\Search\Input\SearchInput;
use App\Application\Employee\Search\Output\Builder\SearchOutputBuilder;
use App\Application\Employee\Search\SearchService;
use App\Enums\EmploymentStatus;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    public function test_it_searches_employees_with_the_built_condition(): void
    {
        $input = new SearchInput(
            keyword: 'Yamada Taro',
            departmentId: 1,
            positionId: 2,
            employmentStatus: EmploymentStatus::Active,
        );
        $repositoryOutput = new EmployeeSearchOutputRecord([]);
        $repository = $this->createMock(EmployeeRepository::class);
        $repository
            ->expects($this->once())
            ->method('search')
            ->with($this->callback(
                fn (EmployeeSearchInputRecord $input): bool => $input->keywords === ['Yamada', 'Taro']
                    && $input->departmentId === 1
                    && $input->positionId === 2
                    && $input->employmentStatus === EmploymentStatus::Active
            ))
            ->willReturn($repositoryOutput);

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $outputBuilder = new SearchOutputBuilder;
        $service = new SearchService($repository, $outputBuilder);
        $output = $service->execute($input);

        $this->assertSame([], $output->employees);
    }
}
