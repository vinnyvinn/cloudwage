<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayments extends Model
{
    const MODULE_ID = 21;
    
    public function loan()
    {
        $this->belongsTo(Loans::class);
    }
}

