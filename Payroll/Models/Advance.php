<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    protected $fillable = ['employee_id', 'amount', 'desc', 'for_month'];

    protected $dates = ['for_month'];

    protected $policy;

    const PERMISSIONS = [
        'Create'    => 'advance.create',
        'Read'      => 'advance.read',
        'Update'    => 'advance.update',
        'Delete'    => 'advance.delete'
    ];

    const
        MODULE_ID = 1,
        ALLOW_OTHER_MONTHS = 'ADVANCE FOR FUTURE',
        ALLOW_MULTIPLE_ADVANCES = 'MULTIPLE ADVANCES',
        ALLOW_MORE_THAN_BASIC = 'ADVANCE MORE THAN BASIC',
        CHANGE_UNPAID_TO_LOAN = 'UNPAID ADVANCE TO LOAN',
        GROSS_PAY_PERCENTAGE = 'GROSS PERCENTAGE AS ADVANCE',
        STATUS_PAID = 'Paid',
        STATUS_UNPAID = 'Unpaid',
        STATUS_LOAN = 'Changed to Loan';

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payments()
    {
        return $this->hasMany(AdvancePayments::class);
    }

    public function getPolicy($policy)
    {
        if (is_null($this->policy)) {
            $this->policy = Policy::whereModuleId(Advance::MODULE_ID)->get();
        }

        return $this->policy->where('policy', $policy)->first();
    }

    public function checkPolicies()
    {
//        $previousAdvances = Advance::where('employee_id', $this->employee_id)->get()->count();
        $previousAdvances = 0;
        $employee = Employee::findOrFail($this->employee_id);

        if ($this->getPolicy(Advance::ALLOW_MULTIPLE_ADVANCES)->value == 'false' && $previousAdvances > 0) {
            flash('Sorry, an employee can only be given one advance', 'error');
            return false;
        }

        if ($this->getPolicy(Advance::ALLOW_MORE_THAN_BASIC)->value == 'false') {
            $basicSalary = $employee->contract->first()->current_basic_salary;
            if ($basicSalary < $this->amount) {
                flash('Sorry, an employee cannot get an advance that is more than his basic pay', 'error');

                return false;
            }
        }

        return true;
    }


}
