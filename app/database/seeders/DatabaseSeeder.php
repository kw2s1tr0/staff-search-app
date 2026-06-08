<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $departments = [
            ['code' => 'SALES', 'name' => 'Sales'],
            ['code' => 'DEV', 'name' => 'Development'],
            ['code' => 'HR', 'name' => 'Human Resources'],
        ];
        $positions = collect([
            ['code' => 'MANAGER', 'name' => 'Manager'],
            ['code' => 'LEADER', 'name' => 'Team Leader'],
            ['code' => 'MEMBER', 'name' => 'Member'],
        ])->map(fn (array $attributes) => Position::factory()->create($attributes));

        foreach ($departments as $departmentAttributes) {
            $department = Department::factory()->create($departmentAttributes);

            Employee::factory()
                ->count(5)
                ->for($department)
                ->state(fn () => [
                    'position_id' => $positions->random()->id,
                ])
                ->create();
        }
    }
}
