<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionPayments extends Model
{
    protected $fillable = ['deduction_id', 'employee_id', 'deduction_number', 'amount', 'for_month'];

    const MODULE_ID = 9;

    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
