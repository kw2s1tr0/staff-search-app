<?php

namespace App\Http\Dto\Api\Employee\Search;

use Carbon\CarbonImmutable;
use JsonSerializable;

/**
 * 社員検索APIのレスポンスに含める役職情報DTO。
 */
final readonly class EmployeeSearchPositionDto implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'created_at' => CarbonImmutable::parse($this->createdAt)->toJSON(),
            'updated_at' => CarbonImmutable::parse($this->updatedAt)->toJSON(),
        ];
    }
}
