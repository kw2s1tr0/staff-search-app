<?php

namespace App\Enums;

/**
 * 社員が取り得る在籍状態を、DB値と同じ文字列で表す。
 */
enum EmploymentStatus: string
{
    case Active = 'active';
    case Leave = 'leave';
    case Retired = 'retired';
}
