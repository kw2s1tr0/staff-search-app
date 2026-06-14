<?php

namespace App\Http\Requests\Api\Department\Index;

use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;
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
            'order_by' => ['nullable', Rule::enum(DepartmentOrderBy::class)],
            'order_direction' => ['nullable', Rule::enum(OrderDirection::class)],
        ];
    }
}
