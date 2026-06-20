<?php

namespace App\Http\Dto\Web\Position\Search;

/**
 * 役職選択肢の表示に必要なIDと名称を保持するDTO。
 */
final readonly class PositionSearchDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
