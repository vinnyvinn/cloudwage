<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [];

    const MODULE_ID = 7;

    public function allowance()
    {
        return $this->hasMany(Allowance::class);
    }

    public function companyProfile()
    {
        return $this->hasMany(CompanyProfile::class);
    }

    public function paygrade()
    {
        return $this->hasMany(PayGrade::class);
    }

}
