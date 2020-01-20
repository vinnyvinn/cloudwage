<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    protected $fillable = ['employee_id', 'allowance_id', 'currency_id', 'amount'];

    const MODULE_ID = 13;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
