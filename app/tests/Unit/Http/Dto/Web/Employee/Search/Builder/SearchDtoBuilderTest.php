<?php

namespace Tests\Unit\Http\Dto\Web\Employee\Search\Builder;

use App\Application\Employee\Search\Output\DepartmentOutput;
use App\Application\Employee\Search\Output\EmployeeOutput;
use App\Application\Employee\Search\Output\PositionOutput;
use App\Application\Employee\Search\Output\SearchOutput;
use App\Enums\EmploymentStatus;
use App\Http\Dto\Web\Employee\Search\Builder\SearchDtoBuilder;
use PHPUnit\Framework\TestCase;

class SearchDtoBuilderTest extends TestCase
{
    public function test_it_builds_template_dtos_with_display_fields(): void
    {
        $employee = new EmployeeOutput(
            id: 10,
            employeeNumber: 'EMP-00010',
            departmentId: 1,
            positionId: 2,
            familyName: 'Yamada',
            givenName: 'Taro',
            familyNameKana: 'ヤマダ',
            givenNameKana: 'タロウ',
            email: 'yamada@example.com',
            employmentStatus: EmploymentStatus::Active,
            createdAt: '2026-01-01 00:00:00',
            updatedAt: '2026-01-02 00:00:00',
            department: new DepartmentOutput(
                id: 1,
                code: 'DEV',
                name: 'Development',
                createdAt: '2026-01-01 00:00:00',
                updatedAt: '2026-01-02 00:00:00',
            ),
            position: new PositionOutput(
                id: 2,
                code: 'ENGINEER',
                name: 'Engineer',
                createdAt: '2026-01-01 00:00:00',
                updatedAt: '2026-01-02 00:00:00',
            ),
        );

        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new SearchDtoBuilder;
        $dtos = $builder->build(new SearchOutput([$employee]));
        $dto = $dtos[0];

        $this->assertSame('EMP-00010', $dto->employeeNumber);
        $this->assertSame('Yamada', $dto->familyName);
        $this->assertSame('Taro', $dto->givenName);
        $this->assertSame('yamada@example.com', $dto->email);
        $this->assertSame(EmploymentStatus::Active, $dto->employmentStatus);
        $this->assertSame('Development', $dto->departmentName);
        $this->assertSame('Engineer', $dto->positionName);
        $this->assertObjectNotHasProperty('createdAt', $dto);
    }
}
