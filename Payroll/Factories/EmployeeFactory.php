<?php

namespace Payroll\Factories;

use Payroll\Models\Employee;

class EmployeeFactory
{
    public static function archived()
    {
        // Employee::chunk(200, function () {});
        return Employee::onlyTrashed()->with('user')->get();
    }
}
