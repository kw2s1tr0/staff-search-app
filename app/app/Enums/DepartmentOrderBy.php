<?php

namespace App\Enums;

/**
 * 部署検索で並び替えに使用できるDBカラムを限定する。
 */
enum DepartmentOrderBy: string
{
    case Id = 'id';
    case Code = 'code';
    case Name = 'name';
}
