<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class DaysWorked extends Model
{
    protected $fillable = ['employee_id', 'for_month', 'days_worked'];

    protected $dates = ['for_month'];

    const PERMISSIONS = [
        'Create'    => 'days_worked.create',
        'Read'      => 'days_worked.read',
        'Update'    => 'days_worked.update',
        'Delete'    => 'days_worked.delete'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
