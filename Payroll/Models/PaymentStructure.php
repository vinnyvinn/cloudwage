<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStructure extends Model
{
    const MODULE_ID = 28;
    
    protected $fillable = ['name', 'description', 'unit'];

    const PERMISSIONS = [
        'Create'    => 'payment_structure.create',
        'Read'      => 'payment_structure.read',
        'Update'    => 'payment_structure.update',
        'Delete'    => 'payment_structure.delete'
    ];

    public function employeeTypes()
    {
        return $this->hasMany(EmployeeType::class);
    }

    public function payGrade()
    {
        return $this->hasMany(PayGrade::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
