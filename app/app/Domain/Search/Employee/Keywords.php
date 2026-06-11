<?php

namespace App\Domain\Search\Employee;

final readonly class Keywords
{
    /**
     * @param  list<string>  $values
     */
    public function __construct(
        public array $values,
    ) {}
}
