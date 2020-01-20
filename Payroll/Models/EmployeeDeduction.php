<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    protected $fillable = ['employee_id', 'deduction_id', 'deduction_number', 'amount'];

    const MODULE_ID = 15;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }

    public function slabs()
    {
        return $this->hasManyThrough(DeductionSlab::class, Deduction::class, 'id', 'deduction_id');
    }
}
