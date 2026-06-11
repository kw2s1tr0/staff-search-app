<?php

namespace App\Application\Employee\Search\Builder;

use App\Application\Employee\Search\Input\SearchInput;
use App\Enums\EmploymentStatus;

final class SearchInputBuilder
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated): SearchInput
    {
        $keyword = isset($validated['keyword'])
            ? (string) $validated['keyword']
            : null;
        $departmentId = isset($validated['department_id'])
            ? (int) $validated['department_id']
            : null;
        $positionId = isset($validated['position_id'])
            ? (int) $validated['position_id']
            : null;
        $employmentStatus = isset($validated['employment_status'])
            ? EmploymentStatus::from($validated['employment_status'])
            : null;

        return new SearchInput(
            keyword: $keyword,
            departmentId: $departmentId,
            positionId: $positionId,
            employmentStatus: $employmentStatus,
        );
    }
}
