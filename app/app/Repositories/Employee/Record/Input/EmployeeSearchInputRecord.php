<?php

namespace App\Repositories\Employee\Record\Input;

use App\Enums\EmploymentStatus;

/**
 * 社員Repositoryへ渡す、正規化済みの検索条件を保持する入力Record。
 */
final readonly class EmployeeSearchInputRecord
{
    /**
     * @param  list<string>  $keywords
     */
    public function __construct(
        public array $keywords,
        public ?int $departmentId,
        public ?int $positionId,
        public ?EmploymentStatus $employmentStatus,
    ) {}
}
