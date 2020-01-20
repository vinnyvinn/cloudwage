<?php

namespace Payroll\Repositories;

use Cache;
use Payroll\Models\Deduction;

class DeductionRepository
{
    public static function getCacheKey()
    {
        return database() . 'PAYROLL_DEDUCTIONS';
    }

    public static function reCache()
    {
        Cache::forget(self::getCacheKey());
        Cache::rememberForever(self::getCacheKey(), function () {
            return Deduction::all();
        });
    }

    public static function checkCache()
    {
        if (! Cache::has(self::getCacheKey())) {
            self::reCache();
        }
    }

    public static function all()
    {
        self::checkCache();

        return Cache::get(self::getCacheKey());
    }
}
