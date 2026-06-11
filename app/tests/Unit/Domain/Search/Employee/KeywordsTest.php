<?php

namespace Tests\Unit\Domain\Search\Employee;

use App\Domain\Search\Employee\Keywords;
use PHPUnit\Framework\TestCase;

class KeywordsTest extends TestCase
{
    public function test_it_holds_keyword_values(): void
    {
        $keywords = new Keywords(['yamada', 'taro']);

        $this->assertSame(['yamada', 'taro'], $keywords->values);
    }
}
