<?php

namespace App\Repositories\Department;

use App\Repositories\Department\Record\Input\DepartmentSearchInputRecord;
use App\Repositories\Department\Record\Output\DepartmentSearchOutputRecord;

interface DepartmentRepository
{
    public function search(DepartmentSearchInputRecord $input): DepartmentSearchOutputRecord;
}
