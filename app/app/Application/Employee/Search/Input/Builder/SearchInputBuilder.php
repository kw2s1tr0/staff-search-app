<?php

namespace App\Application\Employee\Search\Input\Builder;

use App\Application\Employee\Search\Input\SearchInput;
use App\Enums\EmploymentStatus;

/**
 * HTTPリクエストの検証済み配列を、アプリケーション層で扱う入力値へ変換する。
 */
final class SearchInputBuilder
{
    /**
     * 文字列で届くIDや在籍状況を、後続処理が期待する型にそろえる。
     *
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
