<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Relief extends Model
{
    const MODULE_ID = 31;
    
    protected $fillable = ['name', 'reliefable_id', 'reliefable_type', 'amount'];

    public function reliefable()
    {
        return $this->morphTo();
    }
}
