<?php

namespace App\Application\Department\Search\Input\Builder;

use App\Application\Department\Search\Input\SearchInput;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;

/**
 * 部署検索APIの検証済み配列を、型付きの検索入力へ変換する。
 */
final class SearchInputBuilder
{
    /**
     * 並び順が省略された場合は、IDの昇順を既定値として補う。
     *
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated): SearchInput
    {
        // 文字列のリクエスト値をEnumへ変換し、利用可能な値を型でも限定する。
        $orderBy = isset($validated['order_by'])
            ? DepartmentOrderBy::from($validated['order_by'])
            : DepartmentOrderBy::Id;
        $orderDirection = isset($validated['order_direction'])
            ? OrderDirection::from($validated['order_direction'])
            : OrderDirection::Asc;

        // HTTP層の配列をServiceへ持ち込まず、用途が明確なDTOを返す。
        return new SearchInput(
            orderBy: $orderBy,
            orderDirection: $orderDirection,
        );
    }
}
