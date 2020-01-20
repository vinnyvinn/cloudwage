<?php

namespace Payroll\Repositories;

use Cache;
use Payroll\Models\Holiday;
use Payroll\Models\Payroll;
use Payroll\Models\Policy;

class HolidayRepository
{
    public static function getCacheKey()
    {
        return database() . 'PAYROLL_HOLIDAYS';
    }

    public static function reCache()
    {
        Cache::forget(self::getCacheKey());
        Cache::rememberForever(self::getCacheKey(), function () {
            return Holiday::all();
        });
    }

    public static function checkCache()
    {
        if (! Cache::has(self::getCacheKey())) {
            self::reCache();
        }
    }

    public static function getForMonth($month)
    {
        self::checkCache();

        return Cache::get(self::getCacheKey())
            ->whereLoose('holiday_month', $month);
    }
}
