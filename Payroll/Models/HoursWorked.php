<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class HoursWorked extends Model
{
    protected $fillable = ['employee_id', 'for_month', 'hours_worked'];

    protected $dates = ['for_month'];

    const PERMISSIONS = [
        'Create'    => 'hours_worked.create',
        'Read'      => 'hours_worked.read',
        'Update'    => 'hours_worked.update',
        'Delete'    => 'hours_worked.delete'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
