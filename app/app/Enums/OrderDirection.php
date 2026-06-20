<?php

namespace App\Enums;

/**
 * 検索結果で使用できる昇順・降順を限定する。
 */
enum OrderDirection: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
