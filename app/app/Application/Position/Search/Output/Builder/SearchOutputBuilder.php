<?php

namespace App\Application\Position\Search\Output\Builder;

use App\Application\Position\Search\Output\PositionOutput;
use App\Application\Position\Search\Output\SearchOutput;
use App\Repositories\Position\Record\Output\PositionOutputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;

/**
 * Repositoryの役職Recordを、アプリケーション層の出力DTOへ変換する。
 */
final class SearchOutputBuilder
{
    /**
     * 検索結果に含まれるすべての役職を、HTTP層へ渡せる型へ詰め替える。
     */
    public function build(PositionSearchOutputRecord $record): SearchOutput
    {
        // 配列の各Recordへ同じ変換を適用する。
        $positions = array_map(
            fn (PositionOutputRecord $position): PositionOutput => $this->buildPosition($position),
            $record->positions,
        );

        return new SearchOutput($positions);
    }

    /**
     * 役職1件分のRepository RecordをApplication DTOへ変換する。
     */
    private function buildPosition(PositionOutputRecord $position): PositionOutput
    {
        return new PositionOutput(
            id: $position->id,
            code: $position->code,
            name: $position->name,
            createdAt: $position->createdAt,
            updatedAt: $position->updatedAt,
        );
    }
}
