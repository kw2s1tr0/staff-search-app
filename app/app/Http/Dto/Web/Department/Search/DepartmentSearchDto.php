<?php

namespace App\Http\Dto\Web\Department\Search;

final readonly class DepartmentSearchDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
