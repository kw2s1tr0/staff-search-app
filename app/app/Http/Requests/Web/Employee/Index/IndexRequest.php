<?php

namespace App\Http\Requests\Web\Employee\Index;

use App\Enums\EmploymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 社員検索画面で受け付ける検索条件を検証する。
 */
class IndexRequest extends FormRequest
{
    /**
     * 条件はすべて任意とし、指定された値だけを検索に使用する。
     *
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
