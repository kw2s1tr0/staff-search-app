<?php

namespace App\Http\Requests\Api\Position\Index;

use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 役職検索APIで受け付ける並び順を検証する。
 */
class IndexRequest extends FormRequest
{
    /**
     * 両方とも省略可能とし、指定時は対応するEnumの値だけを許可する。
     *
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
