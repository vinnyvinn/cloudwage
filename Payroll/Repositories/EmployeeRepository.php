<?php

namespace Payroll\Repositories;

use Cache;
use Payroll\Models\Employee;

class EmployeeRepository
{
    public static function getCacheKey()
    {
        return database() . 'PAYROLL_EMPLOYEES';
    }

    public static function reCache()
    {
        Cache::forget(self::getCacheKey());
        Cache::forget(self::getCacheKey());
        Cache::rememberForever(self::getCacheKey(), function () {
            return Employee::with([
                'contract', 'allowances.allowance', 'deductions.deduction.slabs', 'paymentStructure',
                'daysWorked', 'hoursWorked', 'unitsMade', 'advances'
            ])->get();
        });
    }

    public static function checkCache()
    {
        if (! Cache::has(self::getCacheKey())) {
            self::reCache();
        }
    }

    public static function getBaseDetails($employeeId)
    {
        self::checkCache();

        if (is_array($employeeId)) {
            return Cache::get(self::getCacheKey())
                ->whereInLoose('id', $employeeId);
        }

        return Cache::get(self::getCacheKey())
            ->whereLoose('id', $employeeId);
    }
}
