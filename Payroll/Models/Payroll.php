<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    const MODULE_ID = 29;
    const ENABLE_DAYS_ATTENDANCE = 'ENABLE DAYS WORKED MODULE';
    const NUMBER_OF_DAYS = 'MINIMUM DAYS WORKED PER MONTH';
    
    protected $fillable = [
        'employee_id', 'payroll_date', 'basic_pay', 'deductions',
        'allowances', 'advances', 'loans', 'gross_pay', 'net_pay', 'overtime'
    ];

    const PERMISSIONS = [
        'Create'    => 'payroll.create',
        'Read'      => 'payroll.read',
        'Update'    => 'payroll.update',
        'Delete'    => 'payroll.delete'
    ];

    protected $dates = ['payroll_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTaxableAllowanceAmount()
    {
        $totalDeducts = 0;
        foreach (json_decode($this->allowances) as $allowance) {
            $totalDeducts += $allowance->amount;
        }

        return $totalDeducts;
    }
    
}
