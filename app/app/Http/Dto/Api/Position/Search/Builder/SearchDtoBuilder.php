<?php

namespace App\Http\Dto\Api\Position\Search\Builder;

use App\Application\Position\Search\Output\PositionOutput;
use App\Application\Position\Search\Output\SearchOutput;
use App\Http\Dto\Api\Position\Search\PositionSearchDto;

/**
 * Application層の役職検索結果を、APIレスポンス用DTOへ変換する。
 */
final class SearchDtoBuilder
{
    /**
     * @return list<PositionSearchDto>
     */
    public function build(SearchOutput $output): array
    {
        return array_map(
            fn (PositionOutput $position): PositionSearchDto => new PositionSearchDto(
                id: $position->id,
                code: $position->code,
                name: $position->name,
                createdAt: $position->createdAt,
                updatedAt: $position->updatedAt,
            ),
            $output->positions,
        );
    }
}
