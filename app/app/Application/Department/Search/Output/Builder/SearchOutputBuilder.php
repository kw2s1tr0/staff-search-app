<?php

namespace App\Application\Department\Search\Output\Builder;

use App\Application\Department\Search\Output\DepartmentOutput;
use App\Application\Department\Search\Output\SearchOutput;
use App\Repositories\Department\Record\Output\DepartmentOutputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;

/**
 * Repositoryの部署Recordを、アプリケーション層の出力DTOへ変換する。
 */
final class SearchOutputBuilder
{
    /**
     * 検索結果に含まれるすべての部署を、HTTP層へ渡せる型へ詰め替える。
     */
    public function build(DepartmentSearchOutputRecord $record): SearchOutput
    {
        // 配列の各Recordへ同じ変換を適用する。
        $departments = array_map(
            fn (DepartmentOutputRecord $department): DepartmentOutput => $this->buildDepartment($department),
            $record->departments,
        );

        return new SearchOutput($departments);
    }

    /**
     * 部署1件分のRepository RecordをApplication DTOへ変換する。
     */
    private function buildDepartment(DepartmentOutputRecord $department): DepartmentOutput
    {
        return new DepartmentOutput(
            id: $department->id,
            code: $department->code,
            name: $department->name,
            createdAt: $department->createdAt,
            updatedAt: $department->updatedAt,
        );
    }
}
