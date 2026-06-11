<?php

namespace Tests\Unit\Domain\Search\Employee\Builder;

use App\Domain\Search\Employee\Builder\SearchConditionBuilder;
use App\Enums\EmploymentStatus;
use PHPUnit\Framework\TestCase;

class SearchConditionBuilderTest extends TestCase
{
    public function test_it_builds_a_search_condition_with_default_values(): void
    {
        $builder = new SearchConditionBuilder(
            keyword: null,
            departmentId: null,
            positionId: null,
            employmentStatus: null,
        );
        $condition = $builder->build();

        $this->assertNull($condition->keyword);
        $this->assertNull($condition->departmentId);
        $this->assertNull($condition->positionId);
        $this->assertNull($condition->employmentStatus);
    }

    public function test_it_builds_a_search_condition_with_specified_values(): void
    {
        $builder = new SearchConditionBuilder(
            keyword: 'yamada taro',
            departmentId: 1,
            positionId: 2,
            employmentStatus: EmploymentStatus::Active,
        );
        $condition = $builder->build();

        $this->assertSame(['yamada', 'taro'], $condition->keyword?->values);
        $this->assertSame(1, $condition->departmentId);
        $this->assertSame(2, $condition->positionId);
        $this->assertSame(EmploymentStatus::Active, $condition->employmentStatus);
    }

    public function test_it_treats_blank_keyword_as_unset(): void
    {
        $builder = new SearchConditionBuilder(
            keyword: '   ',
            departmentId: null,
            positionId: null,
            employmentStatus: null,
        );
        $condition = $builder->build();

        $this->assertNull($condition->keyword);
    }
}
