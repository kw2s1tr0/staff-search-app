<?php

namespace App\Repositories\Employee;

use App\Repositories\Employee\Record\Input\EmployeeSearchInputRecord;
use App\Repositories\Employee\Record\Output\EmployeeSearchOutputRecord;

interface EmployeeRepository
{
    public function search(EmployeeSearchInputRecord $input): EmployeeSearchOutputRecord;
}
