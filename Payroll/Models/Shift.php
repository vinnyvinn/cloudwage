<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    const MODULE_ID = 34;
    
    protected $fillable = [
        'name', 'shift_start', 'shift_end', 'shift_hours', 'time_allowance', 'is_overnight', 'breaks'
    ];

    const PERMISSIONS = [
        'Create'    => 'shift.create',
        'Read'      => 'shift.read',
        'Update'    => 'shift.update',
        'Delete'    => 'shift.delete'
    ];

    public function workplans()
    {
        return $this->hasMany(WorkPlan::class);
    }
}
