<?php

namespace App\Repositories\Position;

use App\Repositories\Position\Record\Input\PositionSearchInputRecord;
use App\Repositories\Position\Record\Output\PositionSearchOutputRecord;

/**
 * Application層がDB実装へ直接依存せずに役職を検索するための契約。
 */
interface PositionRepository
{
    /**
     * 検索入力を受け取り、役職の一覧をRepository Recordで返す。
     */
    public function search(PositionSearchInputRecord $input): PositionSearchOutputRecord;
}
