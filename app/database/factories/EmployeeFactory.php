<?php

namespace Database\Factories;

use App\Enums\EmploymentStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->bothify('EMP-#####'),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'family_name' => fake()->lastName(),
            'given_name' => fake()->firstName(),
            'family_name_kana' => fake()->randomElement(['サトウ', 'スズキ', 'タカハシ', 'タナカ']),
            'given_name_kana' => fake()->randomElement(['タロウ', 'ハナコ', 'ケンタ', 'ユキ']),
            'email' => fake()->unique()->safeEmail(),
            'employment_status' => EmploymentStatus::Active->value,
        ];
    }

    /**
     * Indicate that the employee is on leave.
     */
    public function leave(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => EmploymentStatus::Leave->value,
        ]);
    }

    /**
     * Indicate that the employee has retired.
     */
    public function retired(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => EmploymentStatus::Retired->value,
        ]);
    }
}
