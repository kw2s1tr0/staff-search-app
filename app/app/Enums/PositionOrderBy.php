<?php

namespace App\Enums;

/**
 * 役職検索で並び替えに使用できるDBカラムを限定する。
 */
enum PositionOrderBy: string
{
    case Id = 'id';
    case Code = 'code';
    case Name = 'name';
}
