<?php

namespace Tests\Unit\Application\Employee\Search\Builder;

use App\Application\Employee\Search\Builder\SearchInputBuilder;
use App\Enums\EmploymentStatus;
use PHPUnit\Framework\TestCase;

class SearchInputBuilderTest extends TestCase
{
    public function test_it_builds_input_from_validated_values(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new SearchInputBuilder;
        $input = $builder->build([
            'keyword' => 'Yamada',
            'department_id' => 1,
            'position_id' => 2,
            'employment_status' => EmploymentStatus::Active->value,
        ]);

        $this->assertSame('Yamada', $input->keyword);
        $this->assertSame(1, $input->departmentId);
        $this->assertSame(2, $input->positionId);
        $this->assertSame(EmploymentStatus::Active, $input->employmentStatus);
    }

    public function test_it_builds_input_with_unset_optional_values(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new SearchInputBuilder;
        $input = $builder->build([]);

        $this->assertNull($input->keyword);
        $this->assertNull($input->departmentId);
        $this->assertNull($input->positionId);
        $this->assertNull($input->employmentStatus);
    }
}
