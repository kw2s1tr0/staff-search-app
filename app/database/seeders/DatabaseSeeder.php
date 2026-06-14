<?php

namespace Database\Seeders;

use App\Enums\EmploymentStatus;
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

        $departments = collect([
            ['code' => 'SALES', 'name' => '営業部'],
            ['code' => 'DEV', 'name' => '開発部'],
            ['code' => 'HR', 'name' => '人事部'],
        ])->mapWithKeys(function (array $attributes): array {
            $department = Department::factory()->create($attributes);

            return [$department->code => $department];
        });
        $positions = collect([
            ['code' => 'MANAGER', 'name' => 'マネージャー'],
            ['code' => 'LEADER', 'name' => 'リーダー'],
            ['code' => 'MEMBER', 'name' => '一般社員'],
        ])->mapWithKeys(function (array $attributes): array {
            $position = Position::factory()->create($attributes);

            return [$position->code => $position];
        });

        $employees = [
            ['00001', 'DEV', 'MANAGER', '山田', '太郎', 'ヤマダ', 'タロウ', EmploymentStatus::Active],
            ['00002', 'DEV', 'LEADER', '佐藤', '花子', 'サトウ', 'ハナコ', EmploymentStatus::Active],
            ['00003', 'DEV', 'MEMBER', '鈴木', '健太', 'スズキ', 'ケンタ', EmploymentStatus::Active],
            ['00004', 'DEV', 'MEMBER', '高橋', '美咲', 'タカハシ', 'ミサキ', EmploymentStatus::Leave],
            ['00005', 'DEV', 'MEMBER', '田中', '一郎', 'タナカ', 'イチロウ', EmploymentStatus::Retired],
            ['00006', 'SALES', 'MANAGER', '伊藤', '直子', 'イトウ', 'ナオコ', EmploymentStatus::Active],
            ['00007', 'SALES', 'LEADER', '渡辺', '大輔', 'ワタナベ', 'ダイスケ', EmploymentStatus::Active],
            ['00008', 'SALES', 'MEMBER', '中村', '彩', 'ナカムラ', 'アヤ', EmploymentStatus::Active],
            ['00009', 'SALES', 'MEMBER', '小林', '翔太', 'コバヤシ', 'ショウタ', EmploymentStatus::Leave],
            ['00010', 'SALES', 'MEMBER', '加藤', '由美', 'カトウ', 'ユミ', EmploymentStatus::Retired],
            ['00011', 'HR', 'MANAGER', '吉田', '誠', 'ヨシダ', 'マコト', EmploymentStatus::Active],
            ['00012', 'HR', 'LEADER', '山本', '恵', 'ヤマモト', 'メグミ', EmploymentStatus::Active],
            ['00013', 'HR', 'MEMBER', '松本', '亮', 'マツモト', 'リョウ', EmploymentStatus::Active],
            ['00014', 'HR', 'MEMBER', '井上', '香織', 'イノウエ', 'カオリ', EmploymentStatus::Leave],
            ['00015', 'HR', 'MEMBER', '木村', '拓也', 'キムラ', 'タクヤ', EmploymentStatus::Retired],
        ];

        foreach (
            $employees as [
                $number,
                $departmentCode,
                $positionCode,
                $familyName,
                $givenName,
                $familyNameKana,
                $givenNameKana,
                $employmentStatus,
            ]
        ) {
            Employee::factory()->create([
                'employee_number' => "EMP-{$number}",
                'department_id' => $departments[$departmentCode]->id,
                'position_id' => $positions[$positionCode]->id,
                'family_name' => $familyName,
                'given_name' => $givenName,
                'family_name_kana' => $familyNameKana,
                'given_name_kana' => $givenNameKana,
                'email' => "employee{$number}@example.com",
                'employment_status' => $employmentStatus,
            ]);
        }
    }
}
