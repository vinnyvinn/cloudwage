<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    const MODULE_ID = 38;
    const EXPECTED_LEAVE_DAYS = 'EXPECTED LEAVE DAYS';

    protected $fillable = ['employee_id', 'start_date', 'end_date', 'days', 'status'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function getMonth($employee)
    {
        return Leave::where('employee_id', $employee)->where('status', Leave::APPROVED)->get();
    }

    public function employeeLeaves()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }
}
