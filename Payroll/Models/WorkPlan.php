<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Payroll\Models\Shift;

class WorkPlan extends Model
{
    protected $fillable = ['name', 'shift_id', 'days_of_week'];

    const MODULE_ID = 37;

    const PERMISSIONS = [
        'Create'    => 'work_plan.create',
        'Read'      => 'work_plan.read',
        'Update'    => 'work_plan.update',
        'Delete'    => 'work_plan.delete'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function assignment()
    {
        return $this->hasMany(EmployeeWorkPlanAssignment::class);
    }
}
