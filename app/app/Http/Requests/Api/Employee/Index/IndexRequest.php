<?php

namespace App\Http\Requests\Api\Employee\Index;

use App\Enums\EmploymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string'],
            'department_id' => ['nullable', 'integer'],
            'position_id' => ['nullable', 'integer'],
            'employment_status' => ['nullable', Rule::enum(EmploymentStatus::class)],
        ];
    }
}
