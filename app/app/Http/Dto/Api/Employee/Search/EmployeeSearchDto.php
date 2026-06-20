<?php

namespace App\Http\Dto\Api\Employee\Search;

use App\Enums\EmploymentStatus;
use Carbon\CarbonImmutable;
use JsonSerializable;

/**
 * 社員1件分と関連情報のAPIレスポンス形式を定義するDTO。
 */
final readonly class EmployeeSearchDto implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $employeeNumber,
        public int $departmentId,
        public int $positionId,
        public string $familyName,
        public string $givenName,
        public string $familyNameKana,
        public string $givenNameKana,
        public string $email,
        public EmploymentStatus $employmentStatus,
        public string $createdAt,
        public string $updatedAt,
        public EmployeeSearchDepartmentDto $department,
        public EmployeeSearchPositionDto $position,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'employee_number' => $this->employeeNumber,
            'department_id' => $this->departmentId,
            'position_id' => $this->positionId,
            'family_name' => $this->familyName,
            'given_name' => $this->givenName,
            'family_name_kana' => $this->familyNameKana,
            'given_name_kana' => $this->givenNameKana,
            'email' => $this->email,
            'employment_status' => $this->employmentStatus->value,
            'created_at' => CarbonImmutable::parse($this->createdAt)->toJSON(),
            'updated_at' => CarbonImmutable::parse($this->updatedAt)->toJSON(),
            'department' => $this->department,
            'position' => $this->position,
        ];
    }
}
