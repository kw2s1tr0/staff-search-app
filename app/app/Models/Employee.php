<?php

namespace App\Models;

use App\Enums\EmploymentStatus;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'employee_number',
    'department_id',
    'position_id',
    'family_name',
    'given_name',
    'family_name_kana',
    'given_name_kana',
    'email',
    'employment_status',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<Position, $this>
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'employment_status' => EmploymentStatus::class,
        ];
    }
}
