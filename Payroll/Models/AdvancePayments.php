<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePayments extends Model
{
    protected $fillable = ['advance_id', 'employee_id', 'amount', 'comment'];

    const MODULE_ID = 2;

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
