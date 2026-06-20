<?php

namespace App\Http\Dto\Web\Department\Search;

/**
 * 部署選択肢の表示に必要なIDと名称を保持するDTO。
 */
final readonly class DepartmentSearchDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
