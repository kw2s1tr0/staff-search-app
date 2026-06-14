<?php

namespace App\Domain\Search\Employee\Builder;

use App\Domain\Search\Employee\Keywords;
use App\Domain\Search\Employee\SearchCondition;
use App\Enums\EmploymentStatus;

/**
 * 画面やAPIから受け取った値を、社員検索のルールに沿った条件へ整える。
 */
final readonly class SearchConditionBuilder
{
    public function __construct(
        private ?string $keyword,
        private ?int $departmentId,
        private ?int $positionId,
        private ?EmploymentStatus $employmentStatus,
    ) {}

    /**
     * キーワードだけを正規化し、選択式の条件は型付け済みの値をそのまま引き継ぐ。
     */
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
        // 空白の種類や個数にかかわらず、入力を意味のある単語だけに分割する。
        $keyword = $this->keyword ?? '';
        $keyword = trim($keyword);

        $keywords = preg_split(
            '/\s+/u',
            $keyword,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if ($keywords === false || $keywords === []) {
            // 未入力や空白だけの入力は、キーワード条件なしとして扱う。
            return null;
        }

        return new Keywords($keywords);
    }
}
