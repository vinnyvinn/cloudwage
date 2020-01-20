<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $fillable = ['payment_structure_id', 'name', 'description'];

    const MODULE_ID = 18;

    const PERMISSIONS = [
        'Create'    => 'employee_type.create',
        'Read'      => 'employee_type.read',
        'Update'    => 'employee_type.update',
        'Delete'    => 'employee_type.delete'
    ];

    public function paymentStructure()
    {
        return $this->belongsTo(PaymentStructure::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }
}
