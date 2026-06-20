<?php

namespace App\Application\Employee\Search\Input;

use App\Enums\EmploymentStatus;

/**
 * 社員検索Serviceへ渡す、型付け済みの検索条件DTO。
 */
final readonly class SearchInput
{
    public function __construct(
        public ?string $keyword,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
