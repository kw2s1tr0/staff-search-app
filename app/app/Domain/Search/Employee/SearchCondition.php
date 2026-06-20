<?php

namespace App\Domain\Search\Employee;

use App\Enums\EmploymentStatus;

/**
 * 社員検索で使用できる条件を型付きでまとめたDomainオブジェクト。
 */
final readonly class SearchCondition
{
    public function __construct(
        public ?Keywords $keyword,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
