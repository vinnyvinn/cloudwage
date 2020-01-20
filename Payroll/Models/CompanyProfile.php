<?php

namespace Payroll\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::updated(function () {
            self::reCache();
        });
    }


    protected $fillable = [
        'logo', 'name', 'city', 'country', 'phone', 'mobile', 'email', 'website',
        'kra_pin', 'currency_id', 'nssf', 'nhif'
    ];

    const MODULE_ID = 6;

    const PERMISSIONS = [
        'Create'    => 'company.create',
        'Read'      => 'company.read',
        'Update'    => 'company.update',
        'Delete'    => 'company.delete'
    ];

    public static function getCacheKey()
    {
        return database() . 'PAYROLL_COMPANY';
    }

    public static function first()
    {
        if (! Cache::has(self::getCacheKey())) {
            self::reCache();
        }

        return Cache::get(self::getCacheKey());
    }

    private static function reCache()
    {
        Cache::forget(self::getCacheKey());
        Cache::rememberForever(self::getCacheKey(), function () {
            return CompanyProfile::all()->first();
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
