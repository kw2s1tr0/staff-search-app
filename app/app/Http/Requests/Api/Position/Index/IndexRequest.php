<?php

namespace App\Http\Requests\Api\Position\Index;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
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
            'order_by' => ['nullable', Rule::enum(PositionOrderBy::class)],
            'order_direction' => ['nullable', Rule::enum(OrderDirection::class)],
        ];
    }
}
