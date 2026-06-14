<?php

namespace Tests\Feature;

use App\Enums\EmploymentStatus;
use App\Http\Dto\Web\Department\Search\DepartmentSearchDto;
use App\Http\Dto\Web\Employee\Search\EmployeeSearchDto;
use App\Http\Dto\Web\Position\Search\PositionSearchDto;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeSearchTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_employee_index_returns_the_search_template(): void
    {
        $this->withoutVite();

        $department = Department::factory()->create(['name' => 'Development']);
        $position = Position::factory()->create(['name' => 'Engineer']);
        $employee = Employee::factory()
            ->for($department)
            ->for($position)
            ->create([
                'employee_number' => 'EMP-00001',
                'family_name' => 'Yamada',
                'given_name' => 'Taro',
                'employment_status' => EmploymentStatus::Active,
            ]);

        $response = $this->get('/employees');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertViewIs('employees.index')
            ->assertViewHas('employees', function (array $employees) use ($employee): bool {
                return count($employees) === 1
                    && $employees[0] instanceof EmployeeSearchDto
                    && $employees[0]->employeeNumber === $employee->employee_number
                    && $employees[0]->email === $employee->email
                    && $employees[0]->departmentName === 'Development'
                    && $employees[0]->positionName === 'Engineer';
            })
            ->assertViewHas('departments', function (array $departments) use ($department): bool {
                return count($departments) === 1
                    && $departments[0] instanceof DepartmentSearchDto
                    && $departments[0]->id === $department->id;
            })
            ->assertViewHas('positions', function (array $positions) use ($position): bool {
                return count($positions) === 1
                    && $positions[0] instanceof PositionSearchDto
                    && $positions[0]->id === $position->id;
            })
            ->assertSeeText('社員検索')
            ->assertSeeText('Development')
            ->assertSeeText('Engineer')
            ->assertSeeText($employee->email)
            ->assertSeeText('1 件')
            ->assertSeeText('在籍')
            ->assertSee('hx-get="'.route('employees.results').'"', false)
            ->assertSee('hx-target="#employee-search-results"', false)
            ->assertSee('hx-swap="outerHTML"', false)
            ->assertSee('type="button"', false)
            ->assertSee('data-employee-search-clear', false);
    }

    public function test_employee_index_applies_search_conditions(): void
    {
        $this->withoutVite();

        $matchedEmployee = Employee::factory()->create([
            'family_name' => 'Yamada',
            'employment_status' => EmploymentStatus::Active,
        ]);
        Employee::factory()->create([
            'family_name' => 'Sato',
            'employment_status' => EmploymentStatus::Leave,
        ]);

        $response = $this->get('/employees?'.http_build_query([
            'keyword' => 'Yamada',
            'employment_status' => EmploymentStatus::Active->value,
        ]));

        $response
            ->assertOk()
            ->assertViewHas('employees', function (array $employees) use ($matchedEmployee): bool {
                return count($employees) === 1
                    && $employees[0]->employeeNumber === $matchedEmployee->employee_number;
            })
            ->assertSee('value="Yamada"', false)
            ->assertSee('value="active" selected', false);
    }

    public function test_employee_index_searches_multiple_keywords_with_or_condition(): void
    {
        $this->withoutVite();

        $taro = Employee::factory()->create([
            'family_name' => '山田',
            'given_name' => '太郎',
        ]);
        $ichiro = Employee::factory()->create([
            'family_name' => '田中',
            'given_name' => '一郎',
        ]);
        Employee::factory()->create([
            'family_name' => '佐藤',
            'given_name' => '花子',
        ]);

        $response = $this->get('/employees?'.http_build_query([
            'keyword' => '太郎　一郎',
        ]));

        $response
            ->assertOk()
            ->assertViewHas('employees', function (array $employees) use ($taro, $ichiro): bool {
                $employeeNumbers = array_map(
                    fn (EmployeeSearchDto $employee): string => $employee->employeeNumber,
                    $employees,
                );

                return $employeeNumbers === [
                    $taro->employee_number,
                    $ichiro->employee_number,
                ];
            })
            ->assertSeeText('2 件');
    }

    public function test_employee_index_keeps_selected_department_and_position(): void
    {
        $this->withoutVite();

        $department = Department::factory()->create(['name' => 'Development']);
        $position = Position::factory()->create(['name' => 'Engineer']);
        Employee::factory()
            ->for($department)
            ->for($position)
            ->create();

        $response = $this->get('/employees?'.http_build_query([
            'department_id' => $department->id,
            'position_id' => $position->id,
        ]));

        $response->assertOk();

        $content = $response->getContent();

        $this->assertMatchesRegularExpression(
            '/<option\s+value="'.$department->id.'"\s+selected\s*>\s*Development/',
            $content,
        );
        $this->assertMatchesRegularExpression(
            '/<option\s+value="'.$position->id.'"\s+selected\s*>\s*Engineer/',
            $content,
        );
    }

    public function test_employee_index_displays_an_empty_state_when_no_employee_matches(): void
    {
        $this->withoutVite();

        Employee::factory()->create([
            'family_name' => 'Yamada',
        ]);

        $response = $this->get('/employees?keyword=NoMatchingEmployee');

        $response
            ->assertOk()
            ->assertSeeText('0 件')
            ->assertSeeText('該当する社員が見つかりませんでした');
    }

    public function test_employee_index_redirects_with_invalid_search_conditions(): void
    {
        $response = $this->get('/employees?department_id=invalid&employment_status=unknown');

        $response
            ->assertRedirect()
            ->assertSessionHasErrors(['department_id', 'employment_status']);
    }

    public function test_employee_results_returns_the_matching_partial_for_htmx(): void
    {
        $this->withoutVite();

        $matchedEmployee = Employee::factory()->create([
            'family_name' => 'Yamada',
            'employment_status' => EmploymentStatus::Active,
        ]);
        $otherEmployee = Employee::factory()->create([
            'family_name' => 'Sato',
            'employment_status' => EmploymentStatus::Leave,
        ]);

        $query = http_build_query([
            'keyword' => 'Yamada',
            'department_id' => $matchedEmployee->department_id,
            'position_id' => $matchedEmployee->position_id,
            'employment_status' => EmploymentStatus::Active->value,
        ]);
        $response = $this->get('/employees/results?'.$query, [
            'HX-Request' => 'true',
        ]);

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertHeader('HX-Replace-Url', '/employees?'.$query)
            ->assertViewIs('employees.partials.search-response')
            ->assertViewHas('employees', function (array $employees) use ($matchedEmployee): bool {
                return count($employees) === 1
                    && $employees[0]->employeeNumber === $matchedEmployee->employee_number;
            })
            ->assertSeeText($matchedEmployee->email)
            ->assertDontSeeText($otherEmployee->email)
            ->assertSee('id="employee-search-results"', false)
            ->assertSee('hx-swap-oob="outerHTML"', false)
            ->assertDontSee('<html', false)
            ->assertDontSee('id="employee-search-form"', false);
    }

    public function test_employee_results_returns_an_empty_partial_and_clears_the_search_url(): void
    {
        $this->withoutVite();

        $response = $this->get('/employees/results', [
            'HX-Request' => 'true',
        ]);

        $response
            ->assertOk()
            ->assertHeader('HX-Replace-Url', '/employees')
            ->assertSeeText('0 件')
            ->assertSeeText('該当する社員が見つかりませんでした');
    }
}
