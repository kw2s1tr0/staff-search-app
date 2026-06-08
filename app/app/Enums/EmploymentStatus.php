<?php

namespace App\Enums;

enum EmploymentStatus: string
{
    case Active = 'active';
    case Leave = 'leave';
    case Retired = 'retired';
}
