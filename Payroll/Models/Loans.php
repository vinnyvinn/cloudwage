<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loans extends Model
{
    use SoftDeletes;

    const MODULE_ID = 22;
    
    protected $fillable = [
        'employee_id', 'date_processed', 'amount', 'amount_payable', 'type',
        'balance', 'duration', 'rate', 'installments', 'deadline', 'low_benefit', 'fringe_benefit'
    ];

    const PERMISSIONS = [
        'Create'    => 'loan.create',
        'Read'      => 'loan.read',
        'Update'    => 'loan.update',
        'Delete'    => 'loan.delete'
    ];

    protected $dates = ['date_processed', 'deadline'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayments::class, 'loan_id');
    }
}
