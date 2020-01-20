<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'employee_type_id', 'start_date', 'end_date', 'pay_grade_id',
        'currency_id', 'current_basic_salary', 'times_renewed'
    ];

    const PERMISSIONS = [
        'Create'    => 'employee_contract.create',
        'Read'      => 'employee_contract.read',
        'Update'    => 'employee_contract.update',
        'Delete'    => 'employee_contract.delete'
    ];

    protected $dates = ['start_date', 'end_date'];

    const MODULE_ID = 14;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    public function payGrade()
    {
        return $this->belongsTo(PayGrade::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
