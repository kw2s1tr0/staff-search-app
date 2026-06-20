<?php

namespace App\Application\Position\Search\Output;

/**
 * 役職検索で取得した複数件の結果をまとめるDTO。
 */
final readonly class SearchOutput
{
    /**
     * @param  list<PositionOutput>  $positions
     */
    public function __construct(
        public array $positions,
    ) {}
}
