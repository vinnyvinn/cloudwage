<?php

namespace Payroll\Repositories;

use Cache;
use Payroll\Models\Payroll;
use Payroll\Models\Policy;

class PolicyRepository
{
    public static function getCacheKey()
    {
        return database() . 'PAYROLL_POLICIES';
    }

    public static function reCache()
    {
        Cache::forget(self::getCacheKey());
        Cache::rememberForever(self::getCacheKey(), function () {
            return Policy::all();
        });
    }

    public static function checkCache()
    {
        if (! Cache::has(self::getCacheKey())) {
            self::reCache();
        }
    }

    public static function get($module, $policy)
    {
        self::checkCache();

        return Cache::get(self::getCacheKey())
            ->whereLoose('module_id', $module)
            ->whereLoose('policy', $policy)
            ->first()->value;
    }
}
