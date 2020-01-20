<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class PayGrade extends Model
{
    protected $fillable = [
        'name', 'currency_id', 'payment_structure_id', 'basic_salary',
        'annual_increment', 'default_allowances', 'default_deductions'
    ];

    const PERMISSIONS = [
        'Create'    => 'pay_grade.create',
        'Read'      => 'pay_grade.read',
        'Update'    => 'pay_grade.update',
        'Delete'    => 'pay_grade.delete'
    ];

    const MODULE_ID = 26;
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function paymentStructure()
    {
        return $this->belongsTo(PaymentStructure::class);
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }
}
