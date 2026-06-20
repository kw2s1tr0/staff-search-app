<?php

namespace App\Domain\Search\Employee;

/**
 * 空白分割と空要素除去を終えた、社員検索用キーワードの集合。
 */
final readonly class Keywords
{
    /**
     * @param  list<string>  $values
     */
    public function __construct(
        public array $values,
    ) {}
}
