<?php

namespace App\Repositories\Position\Record\Output;

/**
 * 役職Repositoryが返す複数件の検索結果をまとめるRecord。
 */
final readonly class PositionSearchOutputRecord
{
    /**
     * @param  list<PositionOutputRecord>  $positions
     */
    public function __construct(
        public array $positions,
    ) {}
}
