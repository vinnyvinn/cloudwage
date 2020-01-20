<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    const MODULE_ID = 23;

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
