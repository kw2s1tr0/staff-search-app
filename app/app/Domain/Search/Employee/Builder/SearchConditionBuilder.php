<?php

namespace App\Domain\Search\Employee\Builder;

use App\Domain\Search\Employee\Keywords;
use App\Domain\Search\Employee\SearchCondition;
use App\Enums\EmploymentStatus;

final readonly class SearchConditionBuilder
{
    public function __construct(
        private ?string $keyword,
        private ?int $departmentId,
        private ?int $positionId,
        private ?EmploymentStatus $employmentStatus,
    ) {}

    public function build(): SearchCondition
    {
        $keywords = $this->buildKeywords();

        return new SearchCondition(
            keyword: $keywords,
            departmentId: $this->departmentId,
            positionId: $this->positionId,
            employmentStatus: $this->employmentStatus,
        );
    }

    private function buildKeywords(): ?Keywords
    {
        $keyword = $this->keyword ?? '';
        $keyword = trim($keyword);

        $keywords = preg_split(
            '/\s+/u',
            $keyword,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if ($keywords === false || $keywords === []) {
            return null;
        }

        return new Keywords($keywords);
    }
}
